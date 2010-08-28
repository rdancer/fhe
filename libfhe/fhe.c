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
 * Generate a numberOfBits-bit long random number
 *
 * @return random number
 */
mpz_t *randomNumber(unsigned long long int numberOfBits) {
#if defined(__linux__)
    FILE *randomFile;
    int c;
    unsigned int bitmask;
    unsigned long int i;
    INIT_MPZ_T(randomNumber);

    if ((randomFile = fopen("/dev/random", "r")) == NULL) {
        perror("Error opening /dev/random");
	exit(EXIT_FAILURE);
    }

    /* The whole bytes */
    for (bitmask = 0xff, i = 0; i < numberOfBits; i += 8) {
        if ((c = fgetc(randomFile)) == EOF) {
	    perror("Error reading /dev/random");
	    exit(EXIT_FAILURE);
        }
        if (numberOfBits - i < 8) {
            // The last few bits will have a bitmask
            bitmask = 0xff >> (8 - (numberOfBits - i));
        }
        mpz_mul_ui(*randomNumber, *randomNumber, bitmask + 1);
        mpz_add_ui(*randomNumber, *randomNumber, c & bitmask);
    }

    return randomNumber;
#else // __linux__
# error This function is only implemented for Linux
#endif // __linux__
}

mpz_t *modulo(mpz_t *divident, mpz_t *divisor) {
    INIT_MPZ_T(remainder);

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
    mpz_mul_ui(*encryptedBit, *randomNumber(bitsN - 1), 2);
    /* add parity */
    mpz_add_ui(*encryptedBit, *encryptedBit, plainTextBit);
    /* add pq */
    INIT_MPZ_T(pq);
    mpz_mul(*pq, *privateKey, *randomNumber(bitsQ));
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

    privateKey = randomNumber(bitsP);
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

    DESTROY_MPZ_T(bitValue0);
    DESTROY_MPZ_T(bitValue1);

    return 0;
}
