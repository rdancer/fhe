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

#include <vector>

#include "fhe.h"

using namespace std;

FHE::FHE(unsigned long int securityParameter)
{
    this->securityParameter = securityParameter;

    /* Create and store the private key */
    this->privateKey = new PrivateKey(this->securityParameter);
}

/**
 * Take an integer and encrypt it as a bit array
 *
 * @return Array of encrypted bits
 */
vector<EncryptedBit *> FHE::encrypt(uint32_t integer)
{
    vector<EncryptedBit *> result[32];

    // Loop through the bits of typeof(integer)
    for (int shift = 0; shift < 32; shift++) {
        result[shift] = new EncryptedBit(this->privateKey, (integer >> shift) & 0x1);
    }

    // XXX not actually working
    return result;
}

/**
 * Take an array of encrypted bits and return the decrypted integer
 *
 * @return Plaintext integer
 */
uint32_t *FHE::decrypt(mpz_class *cypherText)
{
    // XXX not actually working
    return (unsigned long int)0;
}
