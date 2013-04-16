# Copyright 2010, The Native Client SDK Authors. All rights reserved.
# Use of this source code is governed by a BSD-style license that can
# be found in the LICENSE file.

# Makefile for client-server example.

NACLPORTS_ROOT ?= ../../..

SERVER_CCFILES = server.cc

CLIENT_CCFILES = client.cc

SERVER_OBJECTS_X86_32 = $(SERVER_CCFILES:%.cc=%_x86_32.o)
SERVER_OBJECTS_X86_64 = $(SERVER_CCFILES:%.cc=%_x86_64.o)

CLIENT_OBJECTS_X86_32 = $(CLIENT_CCFILES:%.cc=%_x86_32.o)
CLIENT_OBJECTS_X86_64 = $(CLIENT_CCFILES:%.cc=%_x86_64.o)

CFLAGS = -Wall -Wno-long-long -pthread -DXP_UNIX -Werror
INCLUDES = -I$(NACL_SDK_ROOT) -I$(NACL_ROOT)
LDFLAGS = -lsrpc \
	  -lpthread \
          -lgoogle_nacl_imc \
	  -lgoogle_nacl_npruntime \
$(ARCH_FLAGS)
OPT_FLAGS = -O2

# The check_variables target is in nacl_build.mk.
all: check_variables \
     client_x86_32.nexe client_x86_64.nexe \
     server_x86_32.nexe server_x86_64.nexe

# nacl_build.mk has rules to build .o files from .cc files.
-include $(NACLPORTS_ROOT)/common_build_scripts/nacl_build.mk

client_x86_32.nexe: $(CLIENT_OBJECTS_X86_32)
	$(CPP) $^ $(LDFLAGS) -m32 -o $@

client_x86_64.nexe: $(CLIENT_OBJECTS_X86_64)
	$(CPP) $^ $(LDFLAGS) -m64 -o $@

server_x86_32.nexe: $(SERVER_OBJECTS_X86_32)
	$(CPP) $^ $(LDFLAGS) -m32 -o $@

server_x86_64.nexe: $(SERVER_OBJECTS_X86_64)
	$(CPP) $^ $(LDFLAGS) -m64 -o $@

clean:
	-$(RM) $(CLIENT_OBJECTS_X86_32) $(CLIENT_OBJECTS_X86_64) \
	    $(SERVER_OBJECTS_X86_32) $(SERVER_OBJECTS_X86_64) \
	    client_x86_32.nexe client_x86_64.nexe \
	    server_x86_32.nexe server_x86_64.nexe
