###############################################################################
# This directory builds libxmlrpc_util, which contains utility
# functions that are used by the Xmlprc-c # libraries, and also
# directly by Xmlrpc-c programs.
#
# The functions in this library are characterized by being general purpose
# programming functions, such as one might wish were in the standard C
# library, which have nothing in particular to do with XML-RPC.
###############################################################################

ifeq ($(SRCDIR)x,x)
SRCDIR = $(CURDIR)/../..
endif
SUBDIR = lib/libutil
# BLDDIR is for use in places where a symbolic link won't work.
# BUILDDIR is for places in Makefile.common that can use the 'blddir'
# symbolic link (but in other directories, doesn't).
BLDDIR = ../..
BUILDDIR = blddir
VPATH = .:$(SRCDIR)

include $(BLDDIR)/Makefile.config

default: all

all: libxmlrpc_util.la

LIBXMLRPC_UTIL_OBJS = \
  casprintf.lo \
  error.lo \
  make_printable.lo \
  memblock.lo \
  resource.lo \
  sleep.lo \

INCLUDES = -Iblddir -I$(SRCDIR) \
           -Isrcdir/include -Isrcdir/lib/util/include

LDFLAGS = $(LADD)

LIBLDFLAGS = $(LDFLAGS_VERSINFO) -rpath $(LIBINST_DIR) $(LADD)

libxmlrpc_util.la: $(LIBXMLRPC_UTIL_OBJS)
	$(LIBTOOL) --mode=link $(CCLD) -o $@ $(LIBLDFLAGS) $^

CFLAGS = $(CFLAGS_COMMON) $(CFLAGS_PERSONAL) $(CADD)

$(LIBXMLRPC_UTIL_OBJS):%.lo:%.c
	$(LIBTOOL) --mode=compile $(CC) -c $(INCLUDES) $(CFLAGS) $<

LTLIBRARIES_TO_INSTALL = libxmlrpc_util.la

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
