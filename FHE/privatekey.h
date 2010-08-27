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

#ifndef PRIVATEKEY_H
#define PRIVATEKEY_H

#include "fhe.h"


/**
 * This class implements the private key of Epsilon
 */
class PrivateKey : public mpz_class
{
public:
//    PrivateKey();
    PrivateKey(mpz_class value);
    PrivateKey(unsigned long int numberOfBits);
    mpz_class getValue();
    void setValue(mpz_class newValue);

private:
    mpz_class value;
};

#endif // PRIVATEKEY_H
