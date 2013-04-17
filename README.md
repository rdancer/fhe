# Fully Homomorphic Encryption


## Overview

This is a simple client-server calculator which uses the Fully Homomorphic
Encryption scheme described in *Gentry (2008)*.  The scheme allows the server
to performs arbitrary arithmetic operations without ever knowing what the
values of the operands are.

If you are only looking for the `libfhe` library itself, check out the `lib`
branch of this repository.  See the wiki pages at
<https://github.com/rdancer/fhe/wiki/_pages> for more detailed information on
this project.

> Note: This project was the first publicly available implementation of the
> encryption system described in Gentry (2008).  You are more than welcome to
> fork it or contribute patches, however, please consider using e.g. the
> <https://hcrypt.com/> project instead.
>
> In particular, this demo is unfinished, and, because when it was developed,
> [*NaCl*][nacl] was a new technology, with changing API, some work may be
> required to make it work in contemporary versions of Google Chrome.

 [nacl]: https://developers.google.com/native-client


## Project Structure

This project is split between four interdependent modules:

1. The `libfhe` library, written in C
2. A *Native Client (NaCl)* plugin, written in C++; uses the `libfhe` library;
   compiles into several binary files: one per each supported architecture.
3. A directory with files to be served by a normal HTTP server such as Apache.
   Written in HTML, JavaScript, CSS, etc. The compiled *NaCl* binaries must be
   copied here.
4. Custom XML-RPC server; uses the `libfhe` library

Each individual sub-project contains a `Makefile`, which automates most of the
work.  However, some manual copying and configuration must still be performed;
some of the required steps may not be documented.


### Files

    libfhe/
      The `libfhe` library source.  Corresponds to the contents of he `lib`
      branch of this repository.
    
    nacl/sdk/
      Please download the *NaCl* SDK and unpack it into this directory
    
    nacl/sdk/examples/fhe_calculator/
      Source of the *NaCl* plugin
    
    www/
      Files to be served by the web server.  The *NaCl* compiled files must
      be copied here manually.
    
    xml-rpc/
      Source code of the FHE evaluator XML-RPC server
    
    contrib/
      Third-party libraries and code
    
    Fully Homomorphic Encryption: Calculator Demo.png
      Screenshot of how the calculator looks when working
    
    README.md
      This file


## Normal Operation

1. A *Native-Client-capable* **web browser** (such as Google Chrome *[Note: the
   project may need to be updated to work with contemporary browsers]*)
   connects to the **web server**, the client downloads the *NaCl* binary
   appropriate for the CPU architecture it is running on.  The UI is
   initialised.
2. User enters an arithmetic formula and presses the *Calculate* button
3. **Web browser** pre-processes the formula, and sends it (encrypted) to the
   **XML-RPC server**
4. **XML-RPC server**, computes the result, and sends it (still encrypted) back
   to the **web browser**
5. **Web browser** decrypts the result, shows the result to the user, and
   updates the UI accordingly

The `libfhe` library handles the encryption, decryption, and operations on the
encrypted numbers.  The **XML-RPC server**, while being able to perform
operations on the numbers, will have no knowledge of their value.


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

Gentry, Craig: *Computing Arbitrary Functions of Encrypted Data* (2008), URL:
<http://crypto.stanford.edu/craig/easy-fhe.pdf>
