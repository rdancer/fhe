# Copyright 2010, The Native Client SDK Authors. All rights reserved.
# Use of this source code is governed by a BSD-style license that can
# be found in the LICENSE file.

# Main makefile for building develop versions of the Native Client SDK examples
# on Linux platforms.  To build a develop version of the examples on other
# platforms, please refer to the specific project files (Xcode files on Mac,
# Visual Studio on Windows).

.PHONY: all develop clean

EXAMPLES = hello_world pi_generator sine_synth tumbler

all: develop

-include develop_common.mk

# Make development versions of all the modules.
develop:
	@for examp in $(EXAMPLES) ; do \
	    (cd $$examp; $(MAKE) -f develop.mk -w develop); \
	done

clean::
	@for examp in $(EXAMPLES) ; do \
	    (cd $$examp; $(MAKE) -f develop.mk -w clean); \
	done
