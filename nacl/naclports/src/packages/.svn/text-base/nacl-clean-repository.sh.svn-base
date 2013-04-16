#!/bin/bash
# Copyright (c) 2009 The Native Client Authors. All rights reserved.
# Use of this source code is governed by a BSD-style license that be
# found in the LICENSE file.
#

# nacl-clean-repository.sh
#
# usage:  nacl-clean-repository.sh
#
# This script remove all packages from the repository and
# deletes all object files for both 32-bit and 64-bit.
# Once packages are built and installed on Native Client, the respository
# can be removed if you no longer need the untarred sources.  This script
# does not remove the include headers or libs required for developement.
#

# need to define these before pulling in common.sh
readonly PACKAGE_NAME=
readonly URL=

code_dir=`dirname $0`
if ! "${code_dir}"/nacl-clean-repository-bitsize.sh 32 ; then
  echo "Error cleaning for 32-bits." 1>&2
  exit 1
fi
if ! "${code_dir}"/nacl-clean-repository-bitsize.sh 64 ; then
  echo "Error cleaning for 64-bits." 1>&2
  exit 1
fi
exit 0
