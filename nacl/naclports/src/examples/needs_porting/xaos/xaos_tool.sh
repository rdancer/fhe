#!/bin/bash
# Copyright (c) 2008 The Native Client Authors. All rights reserved.
# Use of this source code is governed by a BSD-style license that be
# found in the LICENSE file.
#

#@ xaos_tool.sh
#@
#@ usage:  xaos_tool.sh <mode>
#@
#@ this script bootstraps a nacl module for the xaos fractal rendering
#@ engine off the web including the gsl package it depends on.
#@
#@ This has to be run from a bash shell, assumes you have web access
#@ and that you have the typical Linux/Cygwin tools installed
#@
set -o nounset
set -o errexit

readonly SAVE_PWD=$(pwd)

readonly DIR_TMP=${DIR_TMP:-/tmp/nacl}
readonly DIR_INSTALL=${DIR_INSTALL:-/tmp/nacl}
readonly OS_NAME=$(uname -s)
if [ $OS_NAME = "Darwin" ]; then
  readonly OS_SUBDIR="mac"
elif [ $OS_NAME = "Linux" ]; then
  readonly OS_SUBDIR="linux"
else
  readonly OS_SUBDIR="windows"
fi

#readonly NACL_SDK_BASE=/usr/local/nacl-sdk/
readonly NACL_SDK_BASE=\
${SAVE_PWD}/../../../native_client/src/third_party/nacl_sdk/$OS_SUBDIR/sdk/nacl-sdk/

readonly URL_XAOS=http://build.chromium.org/mirror/nacl/XaoS-3.4.tar.gz
readonly URL_GSL=http://build.chromium.org/mirror/nacl/gsl-1.9.tar.gz
#readonly URL_XAOS=http://downloads.sourceforge.net/xaos/XaoS-3.4.tar.gz
#readonly URL_GSL=http://ftp.gnu.org/pub/gnu/gsl/gsl-1.9.tar.gz

readonly PATCH_GSL=${SAVE_PWD}/gsl-1.9.patch
readonly PATCH_XAOS=${SAVE_PWD}/XaoS-3.4.patch


# TODO: the dimensions need a little bit of more work in the xaos patch
readonly NACL_DIM_W=800
readonly NACL_DIM_H=600
readonly NACLCC=${NACL_SDK_BASE}/bin/nacl-gcc
readonly NACLAR=${NACL_SDK_BASE}/bin/nacl-ar
readonly NACLRANLIB=${NACL_SDK_BASE}/bin/nacl-ranlib
readonly DIR_AV_LIB=${NACL_SDK_BASE}/nacl/lib
readonly DIR_AV_INCLUDE=${NACL_SDK_BASE}/nacl/include


######################################################################
# Helper functions
######################################################################

Banner() {
  echo "######################################################################"
  echo $*
  echo "######################################################################"
}


Usage() {
  egrep "^#@" $0 | cut --bytes=3-
}


ReadKey() {
  read
}


Download() {
  if which wget ; then
    wget $1 -O $2
  elif which curl ; then
    curl --location --url $1 -o $2
  else
     Banner "Problem encountered"
     echo "Please install curl or wget and rerun this script"
     echo "or manually download $1 to $2"
     echo
     echo "press any key when done"
     ReadKey
  fi

  if [ ! -s $2 ] ; then
    echo "ERROR: could not find $2"
    exit -1
  fi
}


# params (tmp, url, patch)
DownloadXaos() {
  Banner "downloading xaos"
  cd $1
  Download $2 xaos.tgz
  rm -rf XaoS-3.4
  tar zxf xaos.tgz
  Banner "untaring, patching and autoconfing xaos"
  patch -p0 < $3
  cd XaoS-3.4
  autoconf
}

# params (tmp, install)
BuildXaos() {
  Banner "configuring xaos"
  cd $1
  export PATH="$2/bin:${PATH}"
  export CC=${NACLCC}
  export AR=${NACLAR}
  export RANLIB=${NACLRANLIB}
  export LDFLAGS="-static"
  export CFLAGS=""
  export CFLAGS="${CFLAGS} -DNACL_DIM_H=${NACL_DIM_H}"
  export CFLAGS="${CFLAGS} -DNACL_DIM_W=${NACL_DIM_W}"
  export CFLAGS="${CFLAGS} -I${DIR_AV_INCLUDE}"
  export LIBS="-L${DIR_AV_LIB} -lav -lsrpc -lpthread"
  rm -rf xaos-build
  # sadly xaos seem wants to be built in-place
  cp -r  XaoS-3.4 xaos-build
  cd xaos-build
  ../XaoS-3.4/configure\
      --with-png=no\
      --host=nacl\
      --with-x11-driver=no

  Banner "building and installing xaos"
  make
}

######################################################################
# Parse the mode and extract the needed arguments
######################################################################
if [ $# -eq 0  ] ; then
  echo "no mode specified"
  Usage
  exit -1
fi

readonly MODE=$1
shift

#@
#@ help
#@
#@   Prints help for all modes.
if [ ${MODE} = 'help' ] ; then
  Usage
  exit 0
fi

#@
#@ download_xoas
#@
#@
if [ ${MODE} = 'download_xaos' ] ; then
  mkdir -p ${DIR_TMP}
  DownloadXaos ${DIR_TMP}  ${URL_XAOS}  ${PATCH_XAOS}
  exit 0
fi

#@
#@ build_xaos
#@
#@
if [ ${MODE} = 'build_xaos' ] ; then
  BuildXaos  ${DIR_TMP} ${DIR_INSTALL}
  exit 0
fi

#@
#@ all
#@
#@
if [ ${MODE} = 'all' ] ; then
  mkdir -p ${DIR_TMP}
  DownloadXaos ${DIR_TMP}  ${URL_XAOS}  ${PATCH_XAOS}
  BuildXaos  ${DIR_TMP} ${DIR_INSTALL}

  Banner "Copying relevant files into this directory"
  cp ${DIR_TMP}/xaos-build/bin/xaos ${SAVE_PWD}/xaos.nexe
  cp ${DIR_TMP}/xaos-build/help/xaos.hlp ${SAVE_PWD}/

  Banner "To view the demo"

  echo "either point your browser at"
  echo
  echo "http://localhost:5103/tests/xaos/xaos.html"
  echo
  echo "after running tools/httpd.py while in the native_client directory"
  echo "or run"
  echo
  echo "../../scons-out/opt-linux-x86-32/staging/sel_ldr ./xaos.nexe"
  exit 0
fi


######################################################################
# Mode is not handled
######################################################################

echo "unknown mode: ${MODE}"
exit -1


