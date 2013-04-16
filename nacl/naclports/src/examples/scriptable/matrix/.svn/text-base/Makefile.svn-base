# Copyright 2010, The Native Client SDK Authors. All rights reserved.
# Use of this source code is governed by a BSD-style license that can
# be found in the LICENSE file.

# Makefile for the Matrix example.

.PHONY:  all clean

NACLPORTS_ROOT = ../../..

CCFILES = matrix_comp.cc \
	  npp_gate.cc \
	  loader.cc \
	  scripting_bridge.cc \
	  scriptable.cc

OBJECTS_X86_32 = $(CCFILES:%.cc=%_x86_32.o)
OBJECTS_X86_64 = $(CCFILES:%.cc=%_x86_64.o)


CFLAGS = -Wall -Wno-long-long -pthread -DXP_UNIX -Werror
INCLUDES = -I$(NACL_SDK_ROOT) -I$(NACLPORTS_ROOT)

LDFLAGS = $(ARCH_FLAGS) \
          -lgoogle_nacl_npruntime \
          -lpthread \
          -lsrpc \
          -lgoogle_nacl_imc

OPT_FLAGS = -O2

# The matrix executable depends on boost for math/ublas libraries 
BOOST_UBLAS_32 = \
  $(NACL_SDK_ROOT)/$(NACL_TOOLCHAIN_DIR)/nacl/usr/include/boost/numeric/ublas/matrix.hpp
BOOST_UBLAS_64 = \
  $(NACL_SDK_ROOT)/$(NACL_TOOLCHAIN_DIR)/nacl64/usr/include/boost/numeric/ublas/matrix.hpp

PACKAGES = $(BOOST_UBLAS_32) $(BOOST_UBLAS_64)

all: all_matrix

# common.mk has rules to build .o files from .cc files.
-include $(NACLPORTS_ROOT)/common_build_scripts/nacl_build.mk

all_matrix: check_variables $(PACKAGES) matrix_x86_32.nexe matrix_x86_64.nexe


matrix_x86_32.nexe: $(OBJECTS_X86_32)
	$(CPP) $^ $(LDFLAGS) -m32 -o $@

matrix_x86_64.nexe: $(OBJECTS_X86_64)
	$(CPP) $^ $(LDFLAGS) -m64 -o $@

clean:
	-$(RM) $(OBJECTS_X86_32) $(OBJECTS_X86_64) \
	    matrix_x86_32.nexe matrix_x86_64.nexe

# Build the required packages.
$(BOOST_UBLAS_32):
	(cd $(NACLPORTS_ROOT)/packages/scripts/boost_1_43_0; \
	export NACL_PACKAGES_BITSIZE=32; \
	./nacl-boost_1_43_0.sh)

$(BOOST_UBLAS_64):
	(cd $(NACLPORTS_ROOT)/packages/scripts/boost_1_43_0; \
	export NACL_PACKAGES_BITSIZE=64; \
	./nacl-boost_1_43_0.sh)

