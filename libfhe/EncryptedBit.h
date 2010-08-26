/* EncryptedBit.h -- One single encrypted bit under Epsilon */

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

#ifndef ENCRYPTED_BIT_H
#define ENCRYPTED_BIT_H

#include "Epsilon.h"

/**
 * This class instance corresponds to a single bit encrypted under Epsilon.
 */
class EncryptedBit
	: private mpz_class {
    public:
	EncryptedBit(PrivateKey privateKey, bool plainTextBit);
	bool Decrypt(PrivateKey privateKey);
	EncryptedBit Add(EncryptedBit other);
	EncryptedBit Sub(EncryptedBit other);
	EncryptedBit Mult(EncryptedBit other);
};
#endif // ENCRYPTED_BIT_H
