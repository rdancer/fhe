#!/bin/bash
# Copyright (c) 2009 The Native Client Authors. All rights reserved.
# Use of this source code is governed by a BSD-style license that be
# found in the LICENSE file.
#

# nacl-clean-repository-bitsize.sh
#
# usage:  nacl-clean-repository-bitsize.sh [32|64]
#
# This script remove all packages from the repository as specified
# by the indicated bitsize (either "32" or "64").
# Once packages are built and installed on Native Client, the respository
# can be removed if you no longer need the untarred sources.  This script
# does not remove the include headers or libs required for developement.
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

# remove all downloaded, extracted, patched sources in the repository
Remove ${NACL_PACKAGES_REPOSITORY}
# re-populate with empty directories
DefaultPreInstallStep
exit 0
