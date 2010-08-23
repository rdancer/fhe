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

include ../Makefile.common

INCLUDES = -I$(SRCDIR) -I$(SRCDIR)/include

CXXFLAGS = $(INCLUDES) $(CXXFLAGS_COMMON) $(CFLAGS_PERSONAL) $(CADD)

LDFLAGS = $(LADD)

LDLIBS = -Lblddir/src/cpp -lxmlrpc_cpp -lxmlrpc_server $(CLIENT_LDLIBS)

PROGS :=

ifeq ($(MUST_BUILD_CLIENT),yes)
  PROGS += xml-rpc-api2cpp
endif

all: $(PROGS)

OBJECTS = \
  xml-rpc-api2cpp.o \
  DataType.o \
  XmlRpcFunction.o \
  XmlRpcClass.o \
  SystemProxy.o \

xml-rpc-api2cpp: \
  $(OBJECTS) \
  $(LIBXMLRPC_CPP) \
  $(LIBXMLRPC_CLIENT) \
  $(LIBXMLRPC_SERVER) \
  $(LIBXMLRPC) \
  $(LIBXMLRPC_XML) \
  $(LIBXMLRPC_UTIL)
	$(CXXLD) -o $@ $(LDFLAGS) $(OBJECTS) $(LDLIBS)

%.o:%.cpp
	$(CXX) -c $(CXXFLAGS) $<

# This Makefile.config dependency makes sure the symlinks get built before
# this make file is used for anything.

$(BLDDIR)/Makefile.config: blddir srcdir

include Makefile.depend

.PHONY: clean
clean: clean-common
	rm -f xml-rpc-api2cpp

.PHONY: distclean
distclean: clean distclean-common

.PHONY: dep
dep: dep-common
