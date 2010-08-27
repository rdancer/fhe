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

#include "fhe.h"


FHE::FHE(unsigned long int securityParameter)
{
    this->securityParameter = securityParameter;
}

/**
 * Take an integer and encrypt it as a bit array
 *
 * @return Array of encrypted bits
 */
mpz_class *FHE::encrypt(unsigned long int integer)
{
    // XXX not actually working
    return new mpz_class;
}

/**
 * Take an array of encrypted bits and return the decrypted integer
 *
 * @return Plaintext integer
 */
unsigned long int *FHE::decrypt(mpz_class *cypherText)
{
    // XXX not actually working
    return (unsigned long int)0;
}
