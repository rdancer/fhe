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

#ifndef FHE_H
#define FHE_H

#include "FHE_global.h"
#include "privatekey.h"

class FHESHARED_EXPORT FHE {
public:
   FHE(unsigned long int securityParameter);
   mpz_class *encrypt(unsigned long int integer);
   unsigned long int *decrypt(mpz_class *cypherText);
private:
   mpz_class securityParameter;
   mpz_class privateKey;
   mpz_class publicKey;
};

#endif // FHE_H
