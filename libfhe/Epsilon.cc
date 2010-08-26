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

#include <iostream>
#include <stdio.h>
#include "Epsilon.h"

/**
 * Generate a numberOfBits-bit long random number
 *
 * @return random number
 */
mpz_class Epsilon::Random(unsigned long long int numberOfBits) {
#if defined(__linux__)
    mpz_class value;
    FILE* randomFile = fopen("/dev/random", "r");
    int c;
    unsigned long long int i;

    /* mpz_class instances must be initiated before use */
    //mpz_init(value);
    value = 0;

    /* The whole bytes */
    for (i = 0; i < numberOfBits; i << 8) {
	value *= 0x100;
	value += c;
    }

    /* The last (incomplete) byte */
    c = getc(randomFile);
    if (numberOfBits - i > 0) {
	unsigned char bitMask = 0xff >> (8 - (numberOfBits - i));
	value *= 0x100;
	value += c && bitMask;
    }
#else // __linux__
# error This function is only implemented for Linux
#endif // __linux__
}

/**
 * Program entry point
 */
int main(int argc, char **argv) {
    std::cout << "Hello, world!";
    return 0;
}
