# Copyright (c) 2009 The Native Client Authors. All rights reserved.
# Use of this source code is governed by a BSD-style license that be
# found in the LICENSE file.
#

# simple Makefile for Native Client OpenGL demo
# (Linux, Mac & Cygwin)
#
# Please see .../native_client/common/Makefile.mk for details
# TODO: remove dependency on native_client directory
#
# type:
#
#   make debug nacl run
# to build the demo and run it as a Native Client application.
#

NAME:=glsample

ifeq (release,$(filter release,$(MAKECMDGOALS)))
  OPT := -O2
else
  OPT := -O0 -g
endif

CCFLAGS := -DGL_GLEXT_PROTOTYPES

#if nacl target, copy nexe to glsample.nexe for glsample.html to launch

ifeq (nacl,$(filter nacl,$(MAKECMDGOALS)))
  POST_BUILD = cp $(EXE_NAME) glsample.nexe
else
  $(warning This demo requires 'nacl' as a build target.)
  $(warning For example, 'make release nacl')
  $(warning See native_client/common/Makefile.mk for all build options.)
  $(error Unable to continue)
endif

FILES := glsample.cc
LIBS := -lOSMesa 

CLIENT:=$(filter-out *%,$(subst /native_client,/native_client *,$(CURDIR)))
include $(CLIENT)/common/Makefile.mk

