# Copyright 2010, The Native Client SDK Authors. All rights reserved.
# Use of this source code is governed by a BSD-style license that can
# be found in the LICENSE file.

# Makefile for the FHE Calculator

.PHONY: all clean

CCFILES = fhe_calculator_module.cc fhe_calculator.cc npn_bridge.cc npp_gate.cc evaluate.cc

OBJECTS_X86_32 = $(CCFILES:%.cc=%_x86_32.o)
OBJECTS_X86_64 = $(CCFILES:%.cc=%_x86_64.o)

NACL_SDK_ROOT = ../..

CFLAGS = -Wall -Wno-long-long -pthread -DXP_UNIX -Werror
INCLUDES =
LDFLAGS = -lgoogle_nacl_imc \
          -lgoogle_nacl_npruntime \
          -lpthread \
          -lsrpc \
          $(ARCH_FLAGS)
OPT_FLAGS = -O2

all: check_variables fhe_calculator_x86_32.nexe fhe_calculator_x86_64.nexe \
    fhe_calculator_x86_32.nexe fhe_calculator_x86_64.nexe

# common.mk has rules to build .o files from .cc files.
-include ../common.mk

fhe_calculator_x86_32.nexe: fhe_calculator_x86_32.nexe
	ln -s fhe_calculator_x86_32.nexe fhe_calculator_x86_32.nexe
fhe_calculator_x86_64.nexe: fhe_calculator_x86_64.nexe
	ln -s fhe_calculator_x86_64.nexe fhe_calculator_x86_64.nexe

fhe_calculator_x86_32.nexe: $(OBJECTS_X86_32)
	$(CPP) $^ $(LDFLAGS) -m32 -o $@

fhe_calculator_x86_64.nexe: $(OBJECTS_X86_64)
	$(CPP) $^ $(LDFLAGS) -m64 -o $@

evaluate: eval.cc evaluate.cc evaluate.h
	g++ -o $@ -lm $^

clean:
	-$(RM) $(OBJECTS_X86_32) $(OBJECTS_X86_64) \
	    fhe_calculator_x86_32.nexe fhe_calculator_x86_64.nexe \
	    fhe_calculator_x86_32.nexe fhe_calculator_x86_64.nexe \
	    evaluate

# This target is used by the SDK build system to produce a pre-built version
# of the .nexe.  You do not need to call this target.
install_prebuilt: fhe_calculator_x86_32.nexe fhe_calculator_x86_64.nexe
	-$(RM) $(OBJECTS_X86_32) $(OBJECTS_X86_64)
