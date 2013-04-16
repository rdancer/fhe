#!/bin/bash
# Copyright (c) 2009 The Native Client Authors. All rights reserved.
# Use of this source code is governed by a BSD-style license that be
# found in the LICENSE file.
#

# nacl-esidl-0.1.5.sh
#
# usage:  nacl-esidl-0.1.5.sh
#
# this script downloads and builds esidl Web IDL compiler and C++ DOM API for Native Client.
#

readonly URL=http://es-operating-system.googlecode.com/svn/trunk/esidl
readonly PACKAGE_NAME=esidl-0.1.5

source ../common.sh

PATH=${NACL_BIN_PATH}:${PATH}

DefaultPreInstallStep

Banner "Fetching ${PACKAGE_NAME}"
ChangeDir ${NACL_PACKAGES_REPOSITORY}
svn checkout --force ${URL} ${PACKAGE_NAME}

# build and install esidl Web IDL compiler
ChangeDir ${NACL_PACKAGES_REPOSITORY}/${PACKAGE_NAME}
Remove ${PACKAGE_NAME}-build-local
MakeDir ${PACKAGE_NAME}-build-local
cd ${PACKAGE_NAME}-build-local
Banner "Configuring ${PACKAGE_NAME} for esidl Web IDL compiler"
../configure --prefix=${NACL_SDK_BASE} --disable-java --disable-cplusplus --disable-npapi
make -j4
make install
cd ..

# build and install C++ DOM API for Native Client
Remove ${PACKAGE_NAME}-build-nacl
MakeDir ${PACKAGE_NAME}-build-nacl
cd ${PACKAGE_NAME}-build-nacl
Banner "Configuring ${PACKAGE_NAME} for C++ DOM API for Native Client"
CXXFLAGS='-fno-rtti -fno-exceptions' ../configure --prefix=${NACL_SDK_USR} --htmldir=${NACL_NATIVE_CLIENT_SDK}/examples/${PACKAGE_NAME} --disable-java --host=nacl --target=nacl
make -j4
make install
cd ..

exit 0

