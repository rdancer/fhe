# Fully Homomorphic Encryption Library Routines


## What Does This Library Do

This library implements the Fully Homomorphic Encryption schemes described in
Gentry (2008).

When the `fhe` executable is run, a battery of tests is performed, which can
serve as gentle demonstration of the library capabilities.  Refer to the
following Wiki page for further explanation:
<https://github.com/rdancer/fhe/wiki/FHE-in-action>.

> Note: This was the first publicly available implementation of the
> encryption system described in Gentry (2008).  You are more than welcome to
> fork it or contribute patches, however, please consider using for example
> the <https://hcrypt.com/> project instead.


## Files

    Makefile
        This project's make file
    
    fhe.c
        The library source code
    
    fhe.h
        Header file and C API for the library
    
    README.md
        This file


## Normal Operation

The C API functions can be seen in fhe.h; the normal workflow would be, using a
client-server model:

1. **Client** encrypts an integer using `fhe_encrypt_integer()`

  ... client sends request to server  ...

2. **Server** performs arithmetic operations using `fhe_add_integers()` and
   `fhe_multiply_integers()`

  ... server sends result to client ...

3. **Client** decrypts the result using `fhe_decrypt_integer()`


## Legal Stuff

Copyright 2010, 2013 Jan Minar <rdancer@rdancer.org>

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



## References

Gentry, Craig: *Computing Arbitrary Functions of Encrypted Data (2008)*, URL:
<http://crypto.stanford.edu/craig/easy-fhe.pdf>
