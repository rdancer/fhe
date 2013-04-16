#!/bin/bash
# Copyright (c) 2009 The Native Client Authors. All rights reserved.
# Use of this source code is governed by a BSD-style license that be
# found in the LICENSE file.
#

# nacl-install-all.sh
#
# usage:  nacl-install-all.sh
#
# This script builds all packages listed below for Native Client for
# both 32-bit and 64-bit.
# Packages with no dependencies should be listed first.
#

code_dir=`dirname $0`
if ! "${code_dir}"/nacl-install-all-bitsize.sh 32 ; then
  echo "Error building for 32-bits." 1>&2
  exit 1
fi
if ! "${code_dir}"/nacl-install-all-bitsize.sh 64 ; then
  echo "Error building for 64-bits." 1>&2
  exit 1
fi
exit 0
