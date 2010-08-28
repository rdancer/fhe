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

mpz_t *randomNumber(unsigned long long int numberOfBits);
mpz_t *encryptOneBit(bool plainTextBit);
bool decryptOneBit(mpz_t *encryptedBit);
void initialize(unsigned long int mySecurityParameter);

#endif // FHE_H
