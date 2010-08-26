/* PrivateKey.h -- Private key under Epsilon */

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

#ifndef PRIVATE_KEY_H
#define PRIVATE_KEY_H

#include <gmp.h>
#include <gmpxx.h>
//#include "Epsilon.h"

/**
 * This class implements the private key of Epsilon
 */
class PrivateKey
{
    public:
	PrivateKey(unsigned long int securityParameter);
};
#endif // PRIVATE_KEY_H
