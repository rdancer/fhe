# Copyright 2010, The Native Client SDK Authors. All rights reserved.
# Use of this source code is governed by a BSD-style license that can
# be found in the LICENSE file.

# Makefile for ListenerConfig unit test.

NACL_SDK_ROOT = ../../../..

CFLAGS = -Wall -Wno-long-long -pthread -DXP_UNIX -Werror
INCLUDES = -I$(NACL_SDK_ROOT) -I$(NACL_ROOT)

LDFLAGS = -lsrpc \
	  -lpthread \
	  -lgtest \
	  -lnosys \

OPT_FLAGS = -O0

CCFILES = listener_config.cc \
	  listener_config_test.cc

OBJECTS_X86_32 = $(CCFILES:%.cc=%_x86_32.o)

all: listener_config_test_x86_32.nexe 

# nacl_build.mk has rules to build .o files from .cc files.
-include ../nacl_build.mk

listener_config_test_x86_32.nexe: $(OBJECTS_X86_32)
	$(CPP) $^ $(LDFLAGS) -m32 -o $@



