#!/bin/bash
# Copyright (c) 2009 The Native Client Authors. All rights reserved.
# Use of this source code is governed by a BSD-style license that be
# found in the LICENSE file.
#

# nacl-clean-all-bitsize.sh
#
# usage:  nacl-clean-all-bitsize.sh [32|64]
#
# This script removes all packages installed for Native Client
# for the indicated bitsize (either "32" or "64").
# Use nacl-install-all-bitsize.sh to re-install all packages for
# this bitsize.
#
# TODO: if files other than those made by nacl-install-all.sh are
# placed in ${NACL_SDK_USR}, this script will need to exclude those
# files from deletion.
#

# need to define NACL_PACKAGES_BITSIZE before pulling in common.sh
bitsize=${1:-"32"}
if [ "${bitsize}" = "32" ] ; then
  export NACL_PACKAGES_BITSIZE="32"
elif [ "${bitsize}" = "64" ] ; then
  export NACL_PACKAGES_BITSIZE="64"
else
  echo "The bitsize to build must be '32' or '64', you listed: ${bitsize}." 1>&2
  exit 1
fi

# need to define these before pulling in common.sh
readonly PACKAGE_NAME=
readonly URL=

source scripts/common.sh

# remove all tarballs
Remove ${NACL_PACKAGES_TARBALLS}
# remove all downloaded, extracted, patched sources in the repository
Remove ${NACL_PACKAGES_REPOSITORY}
# remove all published binaries
Remove ${NACL_PACKAGES_PUBLISH}
# remove all installed headers, libraries, man pages, etc. in sdk usr
Remove ${NACL_SDK_USR}
# re-populate with empty directories
DefaultPreInstallStep
# remove specs file that adds include & lib paths to nacl-gcc
Remove ${NACL_TOOLCHAIN_ROOT}/lib/gcc/nacl/4.2.2/specs
# remove the installed.txt file that lists which packages are installed
Remove ${NACL_PACKAGES}/installed.txt
exit 0
