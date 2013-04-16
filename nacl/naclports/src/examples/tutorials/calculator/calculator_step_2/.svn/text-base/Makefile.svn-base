# Copyright 2010, The Native Client SDK Authors. All rights reserved.
# Use of this source code is governed by a BSD-style license that can
# be found in the LICENSE file.

# Makefile for the publish version of the Calculator Tutorial, stage 2.

.PHONY: publish copy_files nacl_module clean

PROGRAM_NAME = calculator

COMMON_DIR = ../common
NACLPORTS_ROOT = ../../../..
OBJDIR = obj
PUBLISH_DIR ?= $$HOME/Sites/$(PROGRAM_NAME)_step_2

C_SALT_FILES = bool_type.cc \
               double_type.cc \
               int32_type.cc \
               module.cc \
               npn_bridge.cc \
               npp_gate.cc \
               object_type.cc \
               scripting_bridge.cc \
               string_type.cc \
               type.cc

NACL_FILES = calculator.cc \
             calculator_module.cc

CCFILES = $(C_SALT_FILES) $(NACL_FILES)

INCLUDES += -I. -I$(COMMON_DIR) -Inacl

VPATH = $(COMMON_DIR)/c_salt nacl

APP_FILES = calculator.html \
            $(COMMON_DIR)/application/expressionParser.js \
            $(COMMON_DIR)/application/nexeapi.js

OBJECTS_X86_32 = $(patsubst %,${OBJDIR}/%,${CCFILES:%.cc=%_x86_32.o})
OBJECTS_X86_64 = $(patsubst %,${OBJDIR}/%,${CCFILES:%.cc=%_x86_64.o})

all: publish

# nacl_build.mk has rules to build .o files from .cc files.
-include $(NACLPORTS_ROOT)/common_build_scripts/nacl_build.mk

CFLAGS = -Wall -Wno-long-long -pthread -DXP_UNIX -Werror
LDFLAGS = -lgoogle_nacl_imc \
          -lgoogle_nacl_npruntime \
          -lpthread \
          -lsrpc
OPT_FLAGS = -O2

NACL_MODULE_X86_32 = $(OBJDIR)/$(PROGRAM_NAME)_x86_32.nexe
NACL_MODULE_X86_64 = $(OBJDIR)/$(PROGRAM_NAME)_x86_64.nexe
NACL_MODULES = $(NACL_MODULE_X86_32) $(NACL_MODULE_X86_64)

publish: $(OBJDIR) publish_nacl_module copy_files

publish_nacl_module: $(NACL_MODULES)
	mkdir -p $(PUBLISH_DIR)
	cp $^ $(PUBLISH_DIR)

copy_files:
	mkdir -p $(PUBLISH_DIR)
	cp $(APP_FILES) $(PUBLISH_DIR)

$(NACL_MODULE_X86_32): $(OBJECTS_X86_32)
	$(CPP) -m32 $^ $(LDFLAGS) -o $@

$(NACL_MODULE_X86_64): $(OBJECTS_X86_64)
	$(CPP) -m64 $^ $(LDFLAGS) -o $@

clean:
	-rm -rf $(OBJDIR) $(NACL_MODULES)
