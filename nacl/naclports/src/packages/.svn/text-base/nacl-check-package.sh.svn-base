#!/bin/bash
# Copyright (c) 2009 The Native Client Authors. All rights reserved.
# Use of this source code is governed by a BSD-style license that be
# found in the LICENSE file.
#

# nacl-check-package.sh
#
# usage:  nacl-check-package.sh <package name>
#
# This script will echo true if the named package is installed, false
# otherwise.
#

# need to define these before pulling in common.sh
readonly PACKAGE_NAME=$1
readonly URL=

source scripts/common.sh

if IsInstalled; then
  echo true
else
  echo false
fi
exit 0
