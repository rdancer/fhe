ifeq ($(SRCDIR)x,x)
SRCDIR = $(CURDIR)/../..
endif
SUBDIR = lib/expat

include $(SRCDIR)/Makefile.config

# Build up SUBDIRS:
SUBDIRS = gennmtab xmlparse xmltok

default: all

.PHONY: all clean distclean tags distdir intall check dep

all: $(SUBDIRS:%=%/all)

clean: $(SUBDIRS:%=%/clean) clean-common

distclean: $(SUBDIRS:%=%/distclean) distclean-common

tags: $(SUBDIRS:%=%/tags) TAGS

DISTFILES = 

distdir: distdir-common

install: $(SUBDIRS:%=%/install)

check:

dep: $(SUBDIRS:%=%/dep)

include $(SRCDIR)/Makefile.common



