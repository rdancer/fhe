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

#include <stdlib.h>
#include <iostream>
#include "privatekey.h"

int main(int argc, char **argv) {

    // Get rid of compiler warning about unused parameters
    argc = argc;
    argv = argv;

    PrivateKey privateKey(argc > 1 ? atoi(argv[1]): 1);

    std::cout << "Private key: 0x"
            << privateKey.getValue().get_str(0x10) << std::endl;

    return 0;
}

