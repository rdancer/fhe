# Copyright 2010, The Native Client SDK Authors. All rights reserved.
# Use of this source code is governed by a BSD-style license that can
# be found in the LICENSE file.

# Makefile the Linux develop version of the PI Generator example.

.PHONY: develop develop_linux

PROGRAM_NAME = pi_generator

CCFILES = npn_bridge.cc \
          npp_gate.cc \
          pi_generator.cc \
          pi_generator_module.cc \
          scripting_bridge.cc

OBJECTS = $(patsubst %,${OBJROOT}/%,${CCFILES:%.cc=%.o})

all: develop

-include ../develop_common.mk

develop:
	@echo make develop in `pwd`
	(SRCROOT=`pwd`/../..; $(MAKE)  -f develop.mk \
            SRCROOT=$$SRCROOT \
            INCLUDES="-I$$SRCROOT \
                -I$$SRCROOT/third_party \
                -I$$SRCROOT/third_party/include \
                -I$$SRCROOT/third_party/npapi/bindings" \
            OBJROOT=develop/$(PLATFORM)_$(TARGET) \
            DSTROOT=../develop/$(PLATFORM)_$(TARGET) \
            OPT_FLAGS="-O0 -g3 -fPIC" develop_linux)
	$(MAKE) -f develop.mk \
	    SRCROOT=`pwd`/../.. \
	    DSTROOT=../develop/$(PLATFORM)_$(TARGET) \
	    copy_files

develop_linux:
	$(MAKE) -f develop.mk LDFLAGS='-shared $(ARCH_FLAGS)' \
	    CFLAGS="-DXP_UNIX -Werror -fPIC $(ARCH_FLAGS)" \
	    CXXFLAGS="-DXP_UNIX -Werror -fPIC $(ARCH_FLAGS)" \
	    $(OBJROOT)/$(PROGRAM_NAME)
	mkdir -p $(DSTROOT)
	cp $(OBJROOT)/$(PROGRAM_NAME) $(DSTROOT)/lib$(PROGRAM_NAME).so

copy_files:
	mkdir -p $(DSTROOT)
	cp pi_generator.html $(DSTROOT)

$(OBJROOT)/$(PROGRAM_NAME): $(OBJECTS)
	$(CPP) $^ $(LDFLAGS) -o $@
