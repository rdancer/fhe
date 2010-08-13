# Copyright 2010, The Native Client SDK Authors. All rights reserved.
# Use of this source code is governed by a BSD-style license that can
# be found in the LICENSE file.

# Common makefile for building the develop version of the examples on Linux.

.SUFFIXES:
.SUFFIXES: .c .cc .cpp .o

# To make a 64-bit build, set WORD_SIZE=64 on the command line.  For example:
#   make WORD_SIZE=64
# Note that 64-bit builds are not supported on Mac or Linux.
WORD_SIZE ?= 32
ARCH_FLAGS = -m$(WORD_SIZE)
SDK_ROOT ?= .

PLATFORM = linux
TARGET = x86
RM = rm -f

CC = /usr/bin/gcc
CPP = /usr/bin/g++

EXTRA_CFLAGS += -Wall -Wno-long-long -pthread
ALL_CFLAGS = $(CFLAGS) $(EXTRA_CFLAGS) $(ARCH_FLAGS)
ALL_CXXFLAGS = $(CXXFLAGS) $(EXTRA_CFLAGS) $(ARCH_FLAGS)
ALL_OPT_FLAGS = $(OPT_FLAGS)

$(OBJROOT)/%.o: %.c
	mkdir -p $(OBJROOT)
	$(CC) $(ALL_CFLAGS) $(INCLUDES) $(ALL_OPT_FLAGS) -c -o $@ $<

$(OBJROOT)/%.o: %.cc
	mkdir -p $(OBJROOT)
	$(CPP) $(ALL_CXXFLAGS) $(INCLUDES) $(ALL_OPT_FLAGS) -c -o $@ $<

$(OBJROOT)/%.o: %.cpp
	mkdir -p $(OBJROOT)
	$(CPP) $(ALL_CXXFLAGS) $(INCLUDES) $(ALL_OPT_FLAGS) -c -o $@ $<

clean::
	-rm -rf develop
