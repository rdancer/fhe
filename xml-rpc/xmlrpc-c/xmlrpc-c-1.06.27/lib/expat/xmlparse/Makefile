ifeq ($(SRCDIR)x,x)
SRCDIR = $(CURDIR)/../../..
endif
SUBDIR = lib/expat/xmlparse
# BLDDIR is for use in places where a symbolic link won't work.
# BUILDDIR is for places in Makefile.common that can use the 'blddir'
# symbolic link (but in other directories, doesn't).
BLDDIR = ../../..
BUILDDIR = blddir
VPATH = .:$(SRCDIR)

XMLTOKDIR = ../xmltok
UTILDIR = $(SRCDIR)/lib/util

include $(BLDDIR)/Makefile.config

default: all

all: libxmlrpc_xmlparse.la

LIBXMLRPC_XMLPARSE_OBJS = xmlparse.lo

INCLUDES = -Iblddir -I$(SRCDIR) -I$(UTILDIR)/include -I$(XMLTOKDIR)

LDFLAGS = $(LADD)

LIBLDFLAGS = $(LDFLAGS_VERSINFO) -rpath $(LIBINST_DIR) $(LADD)

libxmlrpc_xmlparse.la: $(LIBXMLRPC_XMLPARSE_OBJS)
	$(LIBTOOL) --mode=link $(CCLD) -o $@ $(LIBLDFLAGS) $^

CFLAGS = $(CFLAGS_COMMON) $(CFLAGS_PERSONAL) $(CADD)

$(LIBXMLRPC_XMLPARSE_OBJS):%.lo:%.c
	$(LIBTOOL) --mode=compile $(CC) -c $(INCLUDES) $(CFLAGS) $<

LTLIBRARIES_TO_INSTALL = libxmlrpc_xmlparse.la

.PHONY: install
install: install-common

.PHONY: clean distclean
clean: clean-common

distclean: clean distclean-common

.PHONY: dep
dep: dep-common

# This 'Makefile.common' dependency makes sure the symlinks get built before
# this make file is used for anything.

$(SRCDIR)/Makefile.common: srcdir blddir

include $(SRCDIR)/Makefile.common

include Makefile.depend
