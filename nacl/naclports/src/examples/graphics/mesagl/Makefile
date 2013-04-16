# Copyright (c) 2010 The Native Client Authors. All rights reserved.
# Use of this source code is governed by a BSD-style license that be
# found in the LICENSE file.
#
# Makefile for the MesaGL example.

NACLPORTS_ROOT ?= ../../..

CFLAGS = -Wall -Wno-long-long -pthread -DXP_UNIX -Werror -DGL_GLEXT_PROTOTYPES
INCLUDES = -I$(NACL_SDK_ROOT) -I$(NACLPORTS_ROOT)
# Note that the OpenGL libraries are NOT included.  We use Mesa for rendering.
LDFLAGS = -lgoogle_nacl_imc \
          -lgoogle_nacl_npruntime \
          -lpthread \
          -lsrpc \
          -lOSMesa
OPT_FLAGS = -O2

# Define this target first so that any targets in nacl_build.mk do not become
# the default.
all: all_mesagl

# nacl_build.mk has rules to build .o files from .cc files.
-include $(NACLPORTS_ROOT)/common_build_scripts/nacl_build.mk

LIBMESA = $(NACL_SDK_ROOT)/$(NACL_TOOLCHAIN_DIR)/nacl/usr/lib/libOSMesa.a
PACKAGES_DIR = $(NACLPORTS_ROOT)/packages

# The check_variables target is in nacl_build.mk.
all_mesagl: check_variables $(LIBMESA) mesagl_x86_32.nexe mesagl_x86_64.nexe

mesagl_x86_32.nexe: mesagl.cc
	$(CPP) $(CFLAGS) -m32 $(INCLUDES) $(OPT_FLAGS) $< $(LDFLAGS) -o $@

mesagl_x86_64.nexe: mesagl.cc
	$(CPP) $(CFLAGS) -m64 $(INCLUDES) $(OPT_FLAGS) $< $(LDFLAGS) -o $@

$(LIBMESA):
	(cd $(PACKAGES_DIR)/scripts/Mesa-7.6; ./nacl-Mesa-7.6.sh)

clean:
	-rm mesagl_x86_32.nexe mesagl_x86_64.nexe
