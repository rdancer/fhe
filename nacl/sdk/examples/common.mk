# Copyright 2010, The Native Client SDK Authors. All rights reserved.
# Use of this source code is governed by a BSD-style license that can
# be found in the LICENSE file.

# Common makefile for the examples.  This has some basic variables, such as
# CC (the compiler) and some suffix rules such as .c.o.
#
# The main purpose of this makefile component is to demonstrate building a
# Native Client module (.nexe)

.SUFFIXES:
.SUFFIXES: .c .cc .cpp .o

.PHONY: check_variables

ifeq ($(origin OS), undefined)
  ifeq ($(shell uname -s), Darwin)
    OS=Darwin
  else
    OS=$(shell uname -o)
  endif
endif

ifeq ($(OS), $(filter $(OS), Windows_NT Cygwin))
  PLATFORM = win
  TARGET = x86
endif
ifeq ($(OS), $(filter $(OS), Darwin MACOS))
  PLATFORM = mac
  TARGET = x86
endif

# Look for 'Linux' in the $(OS) string.  $(OS) is assumed to be a Linux
# variant if the result of $(findstring) is not empty.
ifneq (, $(findstring Linux, $(OS)))
  PLATFORM = linux
  TARGET = x86
endif

NACL_SDK_ROOT ?= .

NACL_TOOLCHAIN_DIR = toolchain/$(PLATFORM)_$(TARGET)

CC = $(NACL_SDK_ROOT)/$(NACL_TOOLCHAIN_DIR)/bin/nacl-gcc
CPP = $(NACL_SDK_ROOT)/$(NACL_TOOLCHAIN_DIR)/bin/nacl-g++

%_x86_32.o: %.c
	$(CC) $(CFLAGS) -m32 $(INCLUDES) $(OPT_FLAGS) -c -o $@ $<

%_x86_32.o: %.cc
	$(CPP) $(CFLAGS) -m32 $(INCLUDES) $(OPT_FLAGS) -c -o $@ $<

%_x86_32.o: %.cpp
	$(CPP) $(CFLAGS) -m32 $(INCLUDES) $(OPT_FLAGS) -c -o $@ $<

%_x86_64.o: %.c
	$(CC) $(CFLAGS) -m64 $(INCLUDES) $(OPT_FLAGS) -c -o $@ $<

%_x86_64.o: %.cc
	$(CPP) $(CFLAGS) -m64 $(INCLUDES) $(OPT_FLAGS) -c -o $@ $<

%_x86_64.o: %.cpp
	$(CPP) $(CFLAGS) -m64 $(INCLUDES) $(OPT_FLAGS) -c -o $@ $<

# Make sure certain variables are defined.  This rule is set as a dependency
# for all the .nexe builds in the examples.
check_variables:
ifeq ($(origin OS), undefined)
	@echo "Error: OS is undefined" ; exit 42
endif
ifeq ($(origin PLATFORM), undefined)
	@echo "Error: PLATFORM is undefined (OS = $(OS))" ; exit 42
endif
ifeq ($(origin TARGET), undefined)
	@echo "Error: TARGET is undefined (OS = $(OS))" ; exit 42
endif
ifeq ($(origin NACL_SDK_ROOT), undefined)
	@echo "Error: NACL_SDK_ROOT is undefined" ; exit 42
endif
