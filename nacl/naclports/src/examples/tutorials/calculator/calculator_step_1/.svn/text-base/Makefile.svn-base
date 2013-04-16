# Copyright 2010, The Native Client SDK Authors. All rights reserved.
# Use of this source code is governed by a BSD-style license that can
# be found in the LICENSE file.

# Makefile for the publish version of the Calculator Tutorial stage 1.

.PHONY: publish copy_files

PROGRAM_NAME = calculator_step_1

COMMON_DIR = ../common

APP_FILES = calculator.html \
            $(COMMON_DIR)/application/expressionParser.js \
            $(COMMON_DIR)/application/nexeapi.js

all: publish

OBJROOT ?= publish/$(NACL_ARCH)
DSTROOT ?= $$HOME/Sites/$(PROGRAM_NAME)

publish: copy_files

copy_files:
	mkdir -p $(DSTROOT)
	cp $(APP_FILES) $(DSTROOT)
