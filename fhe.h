/* Epsilon.h -- Fully homorphic encryption Gentry (2008) §3.2 */

/*
 * Copyright © 2010 Jan Minář <rdancer@rdancer.org>
 * 
 * This program is free software; you can redistribute it and/or modify
 * it under the terms of the GNU General Public License version 2 (two),
 * as published by the Free Software Foundation.
 * 
 * This program is distributed in the hope that it will be useful,
 * but WITHOUT ANY WARRANTY; without even the implied warranty of
 * MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
 * GNU General Public License for more details.
 * 
 * You should have received a copy of the GNU General Public License along
 * with this program; if not, write to the Free Software Foundation, Inc.,
 * 51 Franklin Street, Fifth Floor, Boston, MA 02110-1301 USA.
 */

/* Only include this file once */
#ifndef FHE_H
#define FHE_H

#include <gmp.h>
#include <stdbool.h>
#include <stdint.h>

#define RANDOM_FILE "/dev/urandom" // XXX unsecure -- not random, but fast

/* TODO Do not hard-code bit width */
#define FHE_INTEGER_BIT_WIDTH 32
typedef int32_t fhe_integer;


/*
 * Function macros and constants
 */

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


mpz_t *fhe_new_random_integer(unsigned long long int numberOfBits);
mpz_t *fhe_encrypt_one_bit(bool plainTextBit);
bool fhe_decrypt_one_bit(mpz_t *encryptedBit);
void fhe_initialize(unsigned long int mySecurityParameter);
mpz_t *fhe_xor_bits(mpz_t *bit1, mpz_t *bit2);
mpz_t *fhe_and_bits(mpz_t *bit1, mpz_t *bit2);
mpz_t **fhe_encrypt_integer(fhe_integer integer);
fhe_integer fhe_decrypt_integer(mpz_t **encryptedInteger);

#endif // FHE_H
