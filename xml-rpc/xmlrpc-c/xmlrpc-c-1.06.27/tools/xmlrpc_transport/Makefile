ifeq ($(SRCDIR)x,x)
SRCDIR = $(CURDIR)/../..
endif
# BLDDIR is for use in places where a symbolic link won't work.
# BUILDDIR is for places in Makefile.common that can use the 'blddir'
# symbolic link (but in other directories, doesn't).
BLDDIR = $(SRCDIR)
BUILDDIR = blddir

default: all

include $(BLDDIR)/Makefile.config

PROGRAMS_TO_INSTALL = xmlrpc_transport

include ../Makefile.common

INCLUDES = -I$(SRCDIR) -I$(SRCDIR)/include -I$(SRCDIR)/lib/util/include

CFLAGS = $(CFLAGS_COMMON) $(INCLUDES) $(CFLAGS_PERSONAL) $(CADD)

LDFLAGS = $(LADD)

all: xmlrpc_transport

UTIL_OBJS = cmdline_parser.o getoptx.o casprintf.o
UTILS = $(UTIL_OBJS:%=$(UTIL_DIR)/%)

# These are the Libtool .la files.  We use them only for make dependencies.
# We'd like to use these in the link rule, by using libtool --link, but
# Libtool adds -l options to the link to cover the dependencies that are
# recorded inside the shared libraries.  And it doesn't add the necessary
# -L options (it can't), so we end up with a messy mixture of the two
# forms of specifying libraries.

LIBS = $(LIBXMLRPC_CLIENT) $(LIBXMLRPC) $(LIBXMLRPC_XML) $(LIBXMLRPC_UTIL)

xmlrpc_transport:%:%.o $(LIBS) $(UTILS)
	$(CCLD) -o $@ $(LDFLAGS) $< $(CLIENT_LDLIBS) $(UTILS)

%.o:%.c
	$(CC) -c $(CFLAGS) $<

*.c: config.h

config.h:
	$(LN_S) $(BLDDIR)/xmlrpc_config.h $@

# This Makefile.config dependency makes sure the symlinks get built before
# this make file is used for anything.

$(BLDDIR)/Makefile.config: blddir srcdir

include Makefile.depend

.PHONY: dep
dep: dep-common

.PHONY: clean
clean: clean-common
	rm -f xmlrpc_transport config.h

.PHONY: distclean
distclean: clean distclean-common
