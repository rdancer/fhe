# Copyright 2010, The Native Client SDK Authors. All rights reserved.
# Use of this source code is governed by a BSD-style license that can
# be found in the LICENSE file.

# Makefile the Linux develop version of the Tumbler example.

.PHONY: develop develop_linux

PROGRAM_NAME = tumbler

CCFILES = cube.cc \
          npn_bridge.cc \
          npp_gate.cc \
          tumbler.cc \
          tumbler_module.cc \
          scripting_bridge.cc \
          shader_util.cc \
          transforms.cc

APP_FILES = dragger.js \
            tumbler.html \
            tumbler.js \
            trackball.js \
            vector3.js

OBJECTS = $(patsubst %,${OBJROOT}/%,${CCFILES:%.cc=%.o})

all: develop

-include ../develop_common.mk

develop:
	@echo make develop in `pwd`
	(NACL_SDK_ROOT=`pwd`/../..; $(MAKE) -f develop.mk \
            NACL_SDK_ROOT=$$NACL_SDK_ROOT \
            INCLUDES="-I$$NACL_SDK_ROOT \
                -I$$NACL_SDK_ROOT/third_party \
                -I$$NACL_SDK_ROOT/third_party/include \
                -I$$NACL_SDK_ROOT/third_party/npapi/bindings" \
            OBJROOT=develop/$(PLATFORM)_$(TARGET) \
            DSTROOT=../develop/$(PLATFORM)_$(TARGET) \
            OPT_FLAGS="-O0 -g3 -fPIC" develop_linux)
	$(MAKE) -f develop.mk \
	    NACL_SDK_ROOT=`pwd`/../.. \
	    DSTROOT=../develop/$(PLATFORM)_$(TARGET) \
	    copy_files

develop_linux:
	mkdir -p $(DSTROOT)
	$(MAKE) -f develop.mk \
      LDFLAGS="-shared $(ARCH_FLAGS) \
               -L$(NACL_SDK_ROOT)/debug_libs/$(PLATFORM)_$(TARGET)_$(WORD_SIZE) \
               -ltrusted_gpu" \
	    CFLAGS="-DXP_UNIX -Werror -fPIC $(ARCH_FLAGS)" \
	    CXXFLAGS="-DXP_UNIX -Werror -fPIC $(ARCH_FLAGS)" \
	    $(OBJROOT)/$(PROGRAM_NAME)
	cp $(OBJROOT)/$(PROGRAM_NAME) $(DSTROOT)/lib$(PROGRAM_NAME).so

copy_files:
	mkdir -p $(DSTROOT)
	cp $(APP_FILES) $(DSTROOT)

$(OBJROOT)/$(PROGRAM_NAME): $(OBJECTS)
	$(CPP) $^ $(LDFLAGS) -o $@
