# Copyright 2010, The Native Client SDK Authors. All rights reserved.
# Use of this source code is governed by a BSD-style license that can
# be found in the LICENSE file.

# Main makefile for the Native Client SDK examples.  These targets set certain
# make variables and then call the descendant makefiles in each of the
# examples.
#
# Please refer to the individual makefiles in each example for details on how
# to build the native Client modules.


.PHONY: all publish clean install_prebuilt

EXAMPLES = hello_world pi_generator sine_synth tumbler fhe_calculator

all: publish

# Make publish versions of all the modules.
publish: $(foreach examp,$(EXAMPLES),publish_$(examp))

clean: $(foreach examp,$(EXAMPLES),clean_$(examp))

install_prebuilt: $(foreach examp,$(EXAMPLES),install_prebuilt_$(examp))

# Auto-generate all the publish and clean targets.
define EXAMPLE_TARGETS

.PHONY: publish_$(1) clean_$(1) install_prebuilt_$(1)

# Publish target for each example
publish_$(1):
	$(MAKE) -C $(1) -f Makefile

# Clean target for each example
clean_$(1):
	$(MAKE) -C $(1) -f Makefile clean

# Target used by the SDK build system to make pre=built versions of the .nexes.
# You do not need to call this target.
install_prebuilt_$(1):
	$(MAKE) -C $(1) -f Makefile clean
	$(MAKE) -C $(1) -f Makefile install_prebuilt

endef

$(foreach examp,$(EXAMPLES),$(eval $(call EXAMPLE_TARGETS,$(examp))))
