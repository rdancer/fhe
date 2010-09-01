/* Epsilon.cc -- Fully homorphic encryption Gentry (2008) §3.2 */

/*
Copyright © 2010 Jan Minář <rdancer@rdancer.org>

This program is free software; you can redistribute it and/or modify
it under the terms of the GNU General Public License version 2 (two),
as published by the Free Software Foundation.

This program is distributed in the hope that it will be useful,
but WITHOUT ANY WARRANTY; without even the implied warranty of
MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
GNU General Public License for more details.

You should have received a copy of the GNU General Public License along
with this program; if not, write to the Free Software Foundation, Inc.,
51 Franklin Street, Fifth Floor, Boston, MA 02110-1301 USA.
*/

#include <stdio.h>
#include <stdlib.h>
#include <errno.h>
#include <assert.h>

#include "fhe.h"

/*
 * From <http://en.wikipedia.org/w/index.php?title=Adder_(electronics)&oldid=381607326#Full_adder>
 * retrieved on 2010-09-01
 */
#define FULL_ADDER(bit1, bit2, carryIn) \
	sum = fhe_xor_bits(fhe_xor_bits(bit1, bit2), carryIn); \
	carryOut = fhe_xor_bits( \
	    fhe_and_bits(bit1, bit2), \
	    fhe_and_bits(carryIn, fhe_xor_bits(bit1, bit2)) \
	);


static inline void *checkMalloc(size_t size) {
    void *ptr = NULL;

    if ((ptr = malloc(size)) == NULL) {
	perror("malloc");
	exit(EXIT_FAILURE);
    }

    return ptr;
}

mpz_t *privateKey;
unsigned long int securityParameter;


/**
 * Add two signed integers together
 *
 * TODO: Determine the bit-length dynamically
 * TODO: Do negative numbers work correctly?
 *
 * @param integer1 First addend
 * @param integer1 Second addend
 * @return The sum of the two addends
 */
mpz_t **fhe_add_integers(mpz_t **integer1, mpz_t **integer2) {
    mpz_t **sum;
    INIT_MPZ_T(carry);

    sum = checkMalloc(sizeof(void *[FHE_INTEGER_BIT_WIDTH]));

    for (int i = 0; i < FHE_INTEGER_BIT_WIDTH; i++) {
	assert(integer1[i] != NULL && integer2[i] != NULL);

	/*
	 * Full adder adapted from (retrieved on 2010-09-01):
	 * <http://en.wikipedia.org/w/index.php?title=Adder_(electronics)&oldid=381607326#Full_adder>
	 */
	sum[i] = fhe_xor_bits(fhe_xor_bits(integer1[i], integer2[i]), carry);
	carry = fhe_xor_bits(
	    fhe_and_bits(integer1[i], integer2[i]),
	    fhe_and_bits(carry, fhe_xor_bits(integer1[i], integer2[i]))
	);
    }

    DESTROY_MPZ_T(carry);

    return sum;
}

/**
 * Encrypt an arbitrary integer under the scheme.
 *
 * @param integer An integer
 * @return Array of encrypted bits
 */
mpz_t **fhe_encrypt_integer(fhe_integer integer) {
    mpz_t **encryptedInteger;

    encryptedInteger = checkMalloc(sizeof(void *[FHE_INTEGER_BIT_WIDTH]));
    for (int i = 0; i < FHE_INTEGER_BIT_WIDTH; i++) {
	encryptedInteger[i] = fhe_encrypt_one_bit((integer >> i) & 0x1);
    }


    return encryptedInteger;
}

/**
 * Decrypt an integer.
 *
 * @param encryptedInteger Array of encrypted bits
 * @return An integer
 */
fhe_integer fhe_decrypt_integer(mpz_t **encryptedInteger) {
    fhe_integer integer = 0;	// must initialize to 0

    for (int i = 0; i < FHE_INTEGER_BIT_WIDTH; i++) {
	integer += fhe_decrypt_one_bit(encryptedInteger[i]) << i;
    }

    return integer;
}

/**
 * Generate a numberOfBits-bit long random integer.  Note: The most-significant
 * bit will not be random: it will always be 1.  The number of random bits
 * therefore is numberOfBits - 1.
 *
 * @return random number
 */
mpz_t *fhe_new_random_integer(unsigned long long int numberOfBits) {
#if defined(__linux__)
    FILE *randomFile;
    int c;
    unsigned int bitmask;
    long int i;
    INIT_MPZ_T(randomInteger);

    // TODO Maybe just open once per program run?
    if ((randomFile = fopen(RANDOM_FILE , "r")) == NULL) {
        perror("Error opening " RANDOM_FILE);
	exit(EXIT_FAILURE);
    }

    /* The whole bytes */
    for (bitmask = 0xff, i = numberOfBits; i > 0; i -= 8) {
        if ((c = fgetc(randomFile)) == EOF) {
	    perror("Error reading " RANDOM_FILE);
	    exit(EXIT_FAILURE);
        }

	/* Ensure the most-significant bit of the random integer is always 1 */
	if (i == numberOfBits) {
	    assert(i > 0);
	    c |= 0x1 << ((i < 8 ? i : 8) - 1);
	}

        if (i < 8) {
            // The last few bits will have a bitmask
            bitmask = 0xff >> (8 - i);
        }
        mpz_mul_ui(*randomInteger, *randomInteger, bitmask + 1);
        mpz_add_ui(*randomInteger, *randomInteger, c & bitmask);
    }

    if (fclose(randomFile) == EOF) {
        perror("Error closing " RANDOM_FILE);
	// Does not have adverse effect on program run
    }

    return randomInteger;
#else // __linux__
# error This function is only implemented for Linux
#endif // __linux__
}

/**
 * This function implements the special modulo operation found in §3.2:
 * “(c mod p) is the integer c' in (-p/2, p/2> such that p divides c − c')”
 */
mpz_t *modulo(mpz_t *divident, mpz_t *divisor) {
    INIT_MPZ_T(remainder);

    assert(mpz_cmp_ui(*divisor, 0) != 0);
    mpz_mod(*remainder, *divident, *divisor);

    /* Adjust (divident/2, divident) -> (-divident/2, 0) */
    INIT_MPZ_T(divident_half);
    mpz_fdiv_q_ui(*divident_half, *divident, 2);
    if (mpz_cmp(*remainder, *divident_half) > 0) {
	mpz_sub(*remainder, *remainder, *divident);
    }
    DESTROY_MPZ_T(divident_half);

    return remainder;
}
/**
 * Decrypt cryptotext of a single bit
 *
 * @param encryptedBit Single bit, encrypted
 * @return Decrypted bit
 */
bool fhe_decrypt_one_bit(mpz_t *encryptedBit) {
    bool result;
    INIT_MPZ_T(modulus);

    assert(mpz_cmp_ui(*privateKey, 0) != 0);
    mpz_mod(*modulus, *encryptedBit, *privateKey);
    result = mpz_tstbit(*modulus, 0);
    DESTROY_MPZ_T(modulus);
    return result;
}

/**
 * Take a one-bit number, and encrypt it under this scheme
 *
 * @param plainTextBit One-bit number to be encrypted
 * @return Cryptotext under this scheme
 */
mpz_t *fhe_encrypt_one_bit(bool plainTextBit) {
    INIT_MPZ_T(encryptedBit);

    /* noise: 2r */
    assert(bitsN > 0);
    mpz_mul_ui(*encryptedBit, *fhe_new_random_integer(bitsN - 1), 2);
    /* add parity */
    mpz_add_ui(*encryptedBit, *encryptedBit, plainTextBit);
    /* add pq */
    INIT_MPZ_T(pq);
    mpz_mul(*pq, *privateKey, *fhe_new_random_integer(bitsQ));
    mpz_add(*encryptedBit, *encryptedBit, *pq);
    DESTROY_MPZ_T(pq);

    return encryptedBit;
}

/**
 * Initialize the security scheme.
 *
 * @param mySecurityParameter lambda, from this the bit-widths of the various
 * parts of the security scheme derive.
 */
void fhe_initialize(unsigned long int mySecurityParameter) {
    securityParameter = mySecurityParameter;

    /* Private key is a bitsP-bit wide even integer */
    privateKey = fhe_new_random_integer(bitsP - 1);
    mpz_mul_ui(*privateKey, *privateKey, 2);
}


/****************************************************************************
 **                        Arithmetical Operations                         **
 ****************************************************************************/

/**
 * Exclusive or — boolean exclusive disjunction.  This function is called Add()
 * in Gentry (2008).  Note: The Sub() function in Gentry (2008) gives the
 * exactly same results for single bits.
 *
 * @param bit1 Single encrypted bit
 * @param bit2 Single encrypted bit
 * @return _Exclusive_or_ of the parameters
 */
mpz_t *fhe_xor_bits(mpz_t *bit1, mpz_t *bit2) {
    INIT_MPZ_T(result);
    
    mpz_add(*result, *bit1, *bit2);

    return result;
}

/**
 * And — boolean multiplication. This function is called Mult() in Gentry
 * (2008) §3.2.
 *
 * @param bit1 Single encrypted bit
 * @param bit2 Single encrypted bit
 * @return Logical _and_ of the parameters
 */
mpz_t *fhe_and_bits(mpz_t *bit1, mpz_t *bit2) {
    INIT_MPZ_T(result);

    mpz_mul(*result, *bit1, *bit2);

    return result;
}


/**
 * Program entry point -- used to test the library
 */
int main(int argc, char **argv) {

    int retval = 0, ok;

    // Get rid of compiler warning about unused parameters
    argc = argc;
    argv = argv;

    fhe_initialize(argc > 1 ? atoi(argv[1]): 2);

    INIT_MPZ_T(bitValue0);
    INIT_MPZ_T(bitValue1);

    bitValue1 = fhe_encrypt_one_bit(1);
    bitValue0 = fhe_encrypt_one_bit(0);

    do {
	bool result;

	(void)gmp_printf("Private key: 0x%Zx\n", privateKey);
	(void)gmp_printf("Encrypted bit (1): 0x%Zx\n", *bitValue1);

	result = fhe_decrypt_one_bit(bitValue1);
	ok = (result == 1);
	(void)    printf("Decrypted bit (1): %d %s\n",
		(int)result,
		ok ? "OK" : "FAIL");
	assert(ok);

	(void)gmp_printf("Encrypted bit (0): 0x%Zx\n", *bitValue0);

	result = fhe_decrypt_one_bit(bitValue0);
	ok = (result == 0);
	(void)    printf("Decrypted bit (0): %d %s\n",
		(int)result,
		ok ? "OK" : "FAIL");
	assert(ok);
    } while (0);

    /*
     * Boolean arithmetics
     */

    /* XOR */

    do {
	bool result;

	result = fhe_decrypt_one_bit(fhe_xor_bits(bitValue0, bitValue0));
	retval |= !(ok = (result == (0 ^ 0)));
	(void)gmp_printf("\n0 ⊕ 0 = %d %s\n",
		result,
		ok ? "OK" : "FAIL");
	assert(ok);

	result = (int)fhe_decrypt_one_bit(fhe_xor_bits(bitValue0, bitValue1));
	retval |= !(ok = (result == (0 ^ 1)));
	(void)gmp_printf("0 ⊕ 1 = %d %s\n",
		result,
		ok ? "OK" : "FAIL");
	assert(ok);

	result = (int)fhe_decrypt_one_bit(fhe_xor_bits(bitValue1, bitValue0));
	retval |= !(ok = (result == (1 ^ 0)));
	(void)gmp_printf("1 ⊕ 0 = %d %s\n",
		result,
		ok ? "OK" : "FAIL");
	assert(ok);

	result = (int)fhe_decrypt_one_bit(fhe_xor_bits(bitValue1, bitValue1));
	retval |= !(ok = (result == (1 ^ 1)));
	(void)gmp_printf("1 ⊕ 1 = %d %s\n",
		result,
		ok ? "OK" : "FAIL");
	assert(ok);


	/* Sub() */ 

	result = fhe_decrypt_one_bit(fhe_and_bits(bitValue0, bitValue0));
	retval |= !(ok = (result == (0 & 0)));
	(void)gmp_printf("\n0 × 0 = %d %s\n",
		result,
		ok ? "OK" : "FAIL");
	assert(ok);

	result = (int)fhe_decrypt_one_bit(fhe_and_bits(bitValue0, bitValue1));
	retval |= !(ok = (result == (0 & 1)));
	(void)gmp_printf("0 × 1 = %d %s\n",
		result,
		ok ? "OK" : "FAIL");
	assert(ok);

	result = (int)fhe_decrypt_one_bit(fhe_and_bits(bitValue1, bitValue0));
	retval |= !(ok = (result == (1 & 0)));
	(void)gmp_printf("1 × 0 = %d %s\n",
		result,
		ok ? "OK" : "FAIL");
	assert(ok);

	result = (int)fhe_decrypt_one_bit(fhe_and_bits(bitValue1, bitValue1));
	retval |= !(ok = (result == (1 & 1)));
	(void)gmp_printf("1 × 1 = %d %s\n",
		result,
		ok ? "OK" : "FAIL");
	assert(ok);
    } while (0);

    DESTROY_MPZ_T(bitValue0);
    DESTROY_MPZ_T(bitValue1);

    /* Encrypt and decrypt an integer */

    do {
	fhe_integer integer = 0x12345678;
	fhe_integer result = fhe_decrypt_integer(fhe_encrypt_integer(integer));

	retval |= !(ok = (result == integer));

	(void)gmp_printf("\nEncrypt-decrypt integer: 0x%x = 0x%x\n",
		integer,
		result,
		ok ? "OK" : "FAIL");
	assert(ok);
    } while (0);


    /*
     * Integral arithmetics
     */

    /* Addition: hard-coded numbers */

    (void)printf("\n");
    for (int i = 0; i < 16; i++) {
	fhe_integer result, addend = 0x11111111 * i;
	

	result = fhe_decrypt_integer(
	    fhe_add_integers(
		fhe_encrypt_integer(addend),
		fhe_encrypt_integer(addend)
		)
	);
	retval |= !(ok = (result == addend + addend));

	(void)gmp_printf("0x%08x + 0x%08x = 0x%08x %s\n",
		addend,
		addend,
		result,
		ok ? "OK" : "FAIL");
	assert(ok);
    }

    /* Addition: random numbers  */
    (void)printf("\n");
    for (int i = 0; i < 16; i++) {
	fhe_integer result, addend1, addend2;
	addend1 = mpz_get_si(*fhe_new_random_integer(FHE_INTEGER_BIT_WIDTH));
	addend2 = mpz_get_si(*fhe_new_random_integer(FHE_INTEGER_BIT_WIDTH));
	

	result = fhe_decrypt_integer(
	    fhe_add_integers(
		fhe_encrypt_integer(addend1),
		fhe_encrypt_integer(addend2)
		)
	);
	retval |= !(ok = (result == addend1 + addend2));

	(void)gmp_printf("0x%08x + 0x%08x = 0x%08x %s\n",
		addend1,
		addend2,
		result,
		ok ? "OK" : "FAIL");
	assert(ok);
    }


    // Will only ever return 0 because of the assert()s above
    return retval;
}
