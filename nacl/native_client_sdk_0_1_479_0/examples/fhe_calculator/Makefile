# Makefile --  Makefile for the FHE Calculator

# Copyright 2010, The Native Client SDK Authors. All rights reserved.
# Copyright © 2010 Jan Minář <rdancer@rdancer.org>
#
# This program is free software; you can redistribute it and/or modify
# it under the terms of the GNU General Public License version 2 (two),
# as published by the Free Software Foundation.
#
# This program is distributed in the hope that it will be useful,
# but WITHOUT ANY WARRANTY; without even the implied warranty of
# MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
# GNU General Public License for more details.
#
# You should have received a copy of the GNU General Public License along
# with this program; if not, write to the Free Software Foundation, Inc.,
# 51 Franklin Street, Fifth Floor, Boston, MA 02110-1301 USA.


.PHONY: all clean

CCFILES = fhe_calculator_module.cc fhe_calculator.cc npn_bridge.cc npp_gate.cc evaluate.cc libfhe.cc

OBJECTS_X86_32 = $(CCFILES:%.cc=%_x86_32.o)
OBJECTS_X86_64 = $(CCFILES:%.cc=%_x86_64.o)

NACL_SDK_ROOT = ../..

CFLAGS = -Wall -Wno-long-long -pthread -DXP_UNIX -Werror -I "../../../../libfhe/" -I /usr/include
INCLUDES =
LDFLAGS = -lgoogle_nacl_imc \
          -lgoogle_nacl_npruntime \
          -lpthread \
          -lsrpc \
          -lgmp \
          -I "../../../../libfhe/" \
          $(ARCH_FLAGS)
OPT_FLAGS = -O2

all: check_variables fhe_calculator_x86_32.nexe fhe_calculator_x86_64.nexe \
    fhe_calculator_x86_32.nexe fhe_calculator_x86_64.nexe

# common.mk has rules to build .o files from .cc files.
-include ../common.mk

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
