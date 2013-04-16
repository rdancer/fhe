#!/bin/bash
# Copyright (c) 2009 The Native Client Authors. All rights reserved.
# Use of this source code is governed by a BSD-style license that be
# found in the LICENSE file.
#

# nacl-clean-all.sh
#
# usage:  nacl-clean-all.sh
#
# This script removes all packages installed for Native Client and
# deletes all object files for both 32-bit and 64-bit.
# Use nacl-install-all.sh to re-install all packages.
#
# TODO: if files other than those made by nacl-install-all.sh are
# placed in ${NACL_SDK_USR}, this script will need to exclude those
# files from deletion.
#

# need to define these before pulling in common.sh
readonly PACKAGE_NAME=
readonly URL=

code_dir=`dirname $0`
if ! "${code_dir}"/nacl-clean-all-bitsize.sh 32 ; then
  echo "Error cleaning for 32-bits." 1>&2
  exit 1
fi
if ! "${code_dir}"/nacl-clean-all-bitsize.sh 64 ; then
  echo "Error cleaning for 64-bits." 1>&2
  exit 1
fi
exit 0
