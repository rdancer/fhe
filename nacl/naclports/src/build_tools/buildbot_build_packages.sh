#!/bin/bash

SCRIPT_DIR="$(cd $(dirname $0) && pwd)"
cd ${SCRIPT_DIR}/../packages

export NACL_SDK_ROOT="${SCRIPT_DIR}/../"

./nacl-install-all.sh
