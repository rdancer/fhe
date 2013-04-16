#!/bin/bash
# Copyright (c) 2009 The Native Client Authors. All rights reserved.
# Use of this source code is governed by a BSD-style license that be
# found in the LICENSE file.
#

#@ photo_tool.sh
#@
#@ Usage:  photo_tool.sh <mode>
#@
#@ This script grabs a copy of the Carpe JavaScript sliders and applies a few
#@ patches use by the Photo Native Client demo.
#@
#@
set -o nounset
set -o errexit

readonly SAVE_PWD=$(pwd)

readonly DIR_TMP=${DIR_TMP:-/tmp/nacl}
readonly DIR_INSTALL=${DIR_INSTALL:-/tmp/nacl}

readonly URL_CARPE_JS=http://build.chromium.org/mirror/nacl/slider.js
readonly URL_CARPE_CSS=http://build.chromium.org/mirror/nacl/default.css
#readonly URL_CARPE_JS=http://carpe.ambiprospect.com/slider/scripts/slider.js
#readonly URL_CARPE_CSS=http://carpe.ambiprospect.com/slider/styles/default.css

readonly PATCH_CARPE=${SAVE_PWD}/carpe.patch

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


Dos2Unix() {
  if which dos2unix ; then
    dos2unix $1
  else
    mv $1 $1.$$.tmp
    tr -d '\015' < $1.$$.tmp > $1
    rm $1.$$.tmp
  fi
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


DownloadAndPatch() {
  Banner "Downloading and patching Carpe Design Slider"
  Download ${URL_CARPE_JS} slider.js
  Download ${URL_CARPE_CSS} default.css
  # add a newline to the end of default.css so patch can be applied
  Dos2Unix default.css
  Dos2Unix slider.js
  echo >> default.css
  patch -p1 < ${PATCH_CARPE}
}


DownloadAndPatch ${DIR_TMP}
exit 0

