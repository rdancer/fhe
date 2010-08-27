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


#include <iostream>

#include "privatekey.h"

/**
 * Generate the private key:  Given the security parameter λ, the number of
 * bits are derived as per §3.2: “N = λ, P = λ², Q = λ⁵”
 */
PrivateKey::PrivateKey(unsigned long int numberOfBits) {
#if defined(__linux__)
    mpz_class value;
    FILE *randomFile;
    int c;
    unsigned int bitmask;
    unsigned long int i;

    if ((randomFile = fopen("/dev/random", "r")) == NULL) {
        // XXX Throw something that will give helpful console message
        throw new std::runtime_error("Error opening /dev/random");
    }

    /* mpz_class instances must be initiated before use */
    //mpz_init(value);
    value = 0;

    /* The whole bytes */
    for (bitmask = 0xff, i = 0; i < numberOfBits; i += 8) {
        if ((c = fgetc(randomFile)) == EOF) {
            // XXX Throw something that will give helpful console message
            throw new std::runtime_error("Error reading /dev/random");
        }
        if (numberOfBits - i < 8) {
            // The last few bits will have a bitmask
            bitmask = 0xff >> (8 - (numberOfBits - i));
        }
        value *= bitmask + 1;
        value += c & bitmask;
    }

    this->value = value;

#else // __linux__
# error This function is only implemented for Linux
#endif // __linux__
}

/**
 * Constructor to use when initializing the key, and already have the key value, for example
 * when we'd stored it during the previous session
 */
PrivateKey::PrivateKey(mpz_class value) {
    throw "Unimplemented";
}

///**
// * The default constructor -- just use the default number of bits for the key length
// */
//PrivateKey::PrivateKey() {
//    PrivateKey(this->defaultNumberOfBits);
//}
//



mpz_class PrivateKey::getValue() {
    return this->value;
}

void PrivateKey::setValue(mpz_class newValue) {
    this->value = newValue;
}
