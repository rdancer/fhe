# Copyright 2010, The Native Client SDK Authors. All rights reserved.
# Use of this source code is governed by a BSD-style license that can
# be found in the LICENSE file.

# Makefile for the Duality (IMC) example.

NACLPORTS_ROOT ?= ../../..

CCFILES = say_hello.cc \
	  print_number.cc \
	  npp_gate.cc \
	  loader.cc \
	  scripting_bridge.cc \
	  scriptable.cc

OBJECTS_X86_32 = $(CCFILES:%.cc=%_x86_32.o)
OBJECTS_X86_64 = $(CCFILES:%.cc=%_x86_64.o)

CFLAGS = -Wall -Wno-long-long -pthread -DXP_UNIX -Werror
INCLUDES = -I$(NACL_SDK_ROOT) -I$(NACLPORTS_ROOT)
LDFLAGS = -lgoogle_nacl_npruntime \
          -lpthread \
          -lsrpc \
          -lgoogle_nacl_imc \
$(ARCH_FLAGS)
OPT_FLAGS = -O2

# The check_variables target is in nacl_build.mk.
all: check_variables duality_x86_32.nexe duality_x86_64.nexe

# common.mk has rules to build .o files from .cc files.
-include $(NACLPORTS_ROOT)/common_build_scripts/nacl_build.mk

duality_x86_32.nexe: $(OBJECTS_X86_32)
	$(CPP) $^ $(LDFLAGS) -m32 -o $@

duality_x86_64.nexe: $(OBJECTS_X86_64)
	$(CPP) $^ $(LDFLAGS) -m64 -o $@

clean:
	-$(RM) $(OBJECTS_X86_32) $(OBJECTS_X86_64) \
	    duality_x86_32.nexe duality_x86_64.nexe
