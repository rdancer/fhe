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

mpz_t *privateKey;
unsigned long int securityParameter;

#define bitsN /* λ  */ securityParameter
#define bitsP /* λ² */ securityParameter * securityParameter
#define bitsQ /* λ⁵ */ securityParameter \
	* securityParameter \
	* securityParameter \
	* securityParameter \
	* securityParameter


#define INIT_MPZ_T(x) \
    mpz_t *x; \
    do { \
	if ((x = malloc(sizeof(void *))) == NULL) { \
	    perror("malloc"); \
	    exit(EXIT_FAILURE); \
	} \
	mpz_init(*x); \
    } while (0)

#define DESTROY_MPZ_T(x) \
    do { \
	if (x != NULL) { \
	   mpz_clear(*x); \
	   free(x); \
	} \
    } while (0)


/**
 * Generate a numberOfBits-bit long random integer.  Note: The most-significant
 * bit will not be random: it will always be 1.  The number of random bits
 * therefore is numberOfBits - 1.
 *
 * @return random number
 */
mpz_t *randomInteger(unsigned long long int numberOfBits) {
#if defined(__linux__)
    FILE *randomFile;
    int c;
    unsigned int bitmask;
    long int i;
    INIT_MPZ_T(randomInteger);

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
bool decryptOneBit(mpz_t *encryptedBit) {
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
mpz_t *encryptOneBit(bool plainTextBit) {
    INIT_MPZ_T(encryptedBit);

    /* noise: 2r */
    assert(bitsN > 0);
    mpz_mul_ui(*encryptedBit, *randomInteger(bitsN - 1), 2);
    /* add parity */
    mpz_add_ui(*encryptedBit, *encryptedBit, plainTextBit);
    /* add pq */
    INIT_MPZ_T(pq);
    mpz_mul(*pq, *privateKey, *randomInteger(bitsQ));
    mpz_add(*encryptedBit, *encryptedBit, *pq);
    DESTROY_MPZ_T(pq);

    return encryptedBit;
}

/**
 * Initialize the security scheme.
 *
 * @param securityParameter lambda, from this the bit-widths of the various
 * parts of the security scheme derive.
 */
void initialize(unsigned long int mySecurityParameter) {
    securityParameter = mySecurityParameter;

    privateKey = randomInteger(bitsP);
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
mpz_t *xorBits(mpz_t *bit1, mpz_t *bit2) {
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
mpz_t *andBits(mpz_t *bit1, mpz_t *bit2) {
    INIT_MPZ_T(result);

    mpz_mul(*result, *bit1, *bit2);

    return result;
}


/**
 * Program entry point -- used to test the library
 */
int main(int argc, char **argv) {

    // Get rid of compiler warning about unused parameters
    argc = argc;
    argv = argv;

    initialize(argc > 1 ? atoi(argv[1]): 2);

    INIT_MPZ_T(bitValue0);
    INIT_MPZ_T(bitValue1);

    bitValue1 = encryptOneBit(1);
    bitValue0 = encryptOneBit(0);

    (void)gmp_printf("Private key: 0x%Zx\n", privateKey);
    (void)gmp_printf("Encrypted bit (1): 0x%Zx\n", *bitValue1);
    (void)    printf("Decrypted bit (1): %d\n",
	    (int)decryptOneBit(bitValue1));
    (void)gmp_printf("Encrypted bit (0): 0x%Zx\n", *bitValue0);
    (void)    printf("Decrypted bit (0): %d\n",
	    (int)decryptOneBit(bitValue0));

    /*
     * Arithmetics
     */

    /* XOR */

    (void)gmp_printf("\n0 ⊕ 0 = %d\n",
	    (int)decryptOneBit(xorBits(bitValue0, bitValue0)));
    (void)gmp_printf("0 ⊕ 1 = %d\n",
	    (int)decryptOneBit(xorBits(bitValue0, bitValue1)));
    (void)gmp_printf("1 ⊕ 0 = %d\n",
	    (int)decryptOneBit(xorBits(bitValue1, bitValue0)));
    (void)gmp_printf("1 ⊕ 1 = %d\n",
	    (int)decryptOneBit(xorBits(bitValue1, bitValue1)));

    /* Sub() */ 

    (void)gmp_printf("\n0 × 0 = %d\n",
	    (int)decryptOneBit(andBits(bitValue0, bitValue0)));
    (void)gmp_printf("0 × 1 = %d\n",
	    (int)decryptOneBit(andBits(bitValue0, bitValue1)));
    (void)gmp_printf("1 × 0 = %d\n",
	    (int)decryptOneBit(andBits(bitValue1, bitValue0)));
    (void)gmp_printf("1 × 1 = %d\n",
	    (int)decryptOneBit(andBits(bitValue1, bitValue1)));

    DESTROY_MPZ_T(bitValue0);
    DESTROY_MPZ_T(bitValue1);

    return 0;
}
