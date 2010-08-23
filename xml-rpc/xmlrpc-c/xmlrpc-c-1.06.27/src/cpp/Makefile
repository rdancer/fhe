ifeq ($(SRCDIR)x,x)
SRCDIR := $(CURDIR)/../..
endif
SUBDIR := src/cpp
# BLDDIR is for use in places where a symbolic link won't work.
# BUILDDIR is for places in Makefile.common that can use the 'blddir'
# symbolic link (but in directories that don't, like this one, have
# blddir, these places use something else -- hence the variable).
BLDDIR = ../..
BUILDDIR := blddir
VPATH = .:$(SRCDIR)

include $(BLDDIR)/Makefile.config

default: all

# libxmlrpc_cpp is the legacy C++ wrapper library.  The others are the
# more elaborate replacements.

TARGET_LIBRARY_NAMES := \
  libxmlrpc_cpp \
  libxmlrpc++ \
  libxmlrpc_server++ \
  libxmlrpc_server_abyss++ \

ifeq ($(MUST_BUILD_CLIENT),yes)
  TARGET_LIBRARY_NAMES += libxmlrpc_client++
endif

STATIC_LIBRARIES_TO_INSTALL = $(TARGET_STATIC_LIBRARIES)

SHARED_LIBS_TO_BUILD := $(TARGET_LIBRARY_NAMES)
SHARED_LIBS_TO_INSTALL := $(TARGET_LIBRARY_NAMES)

# INCLUDES and DEP_SOURCES are used by dep-common target
INCLUDES = $(BASIC_INCLUDES) $(CLIENT_INCLUDES) $(LIBXML_INCLUDES) \
  $(SERVER_INCLUDES) $(SERVER_ABYSS_INCLUDES) $(TRANSPORT_INCLUDES)
DEP_SOURCES = *.cpp

WININET_TRANSPORT_DIR = srcdir/lib/wininet_transport
CURL_TRANSPORT_DIR    = srcdir/lib/curl_transport
LIBWWW_TRANSPORT_DIR  = srcdir/lib/libwww_transport

ifeq ($(ENABLE_LIBXML2_BACKEND),yes)
  LIBXML_INCLUDES = $(LIBXML2_CFLAGS)
else
  LIBXML_INCLUDES = -Isrcdir/lib/expat/xmlparse
endif

LIBXMLRPCPP_OBJS = \
	base64.o \
	env_wrap.o \
	fault.o \
	girerr.o \
	girmem.o \
	outcome.o \
	param_list.o \
	value.o \
	xml.o \

LIBXMLRPC_SERVERPP_OBJS = registry.o
LIBXMLRPC_SERVER_ABYSSPP_OBJS = server_abyss.o
LIBXMLRPC_CLIENTPP_OBJS = client.o client_simple.o curl.o libwww.o wininet.o

ALL_OBJS = \
  XmlRpcCpp.o \
  $(LIBXMLRPCCPP_OBJS) \
  $(LIBXMLRPC_SERVERPP_OBJS) \
  $(LIBXMLRPC_SERVER_ABYSSPP_OBJS) \
  $(LIBXMLRPC_CLIENTPP_OBJS)

include $(SRCDIR)/Makefile.common

# This 'Makefile.common' dependency makes sure the symlinks get built before
# this make file is used for anything.

$(SRCDIR)/Makefile.common: srcdir blddir

TRANSPORT_INCLUDES = \
  -I$(WININET_TRANSPORT_DIR) \
  -I$(CURL_TRANSPORT_DIR) \
  -I$(LIBWWW_TRANSPORT_DIR) \

BASIC_INCLUDES = -Isrcdir/include -Iblddir -Isrcdir \
  -Isrcdir/lib/util/include

ifeq ($(SHARED_LIB_TYPE),unix)
  include unix.make
  endif

ifeq ($(SHARED_LIB_TYPE),irix)
  include irix.make
  endif

ifeq ($(SHARED_LIB_TYPE),dll)
  include dll.make
  endif

ifeq ($(SHARED_LIB_TYPE),dylib)
  include dylib.make
  endif

ifneq ($(SHARED_LIB_TYPE),NONE)
  TARGET_SHARED_LIBRARIES = $(TARGET_LIBRARY_NAMES:%=%.$(SHLIB_SUFFIX))
  endif

TARGET_STATIC_LIBRARIES = $(TARGET_LIBRARY_NAMES:%=%.a)

TARGET_LIBRARIES = $(TARGET_STATIC_LIBRARIES) $(ALL_SHARED_LIBRARIES)

all: $(TARGET_LIBRARIES)

libxmlrpc_cpp.a: XmlRpcCpp.o
	-rm -f $@
	$(AR) cru $@ $^
	$(RANLIB) $@

libxmlrpc++.a: $(LIBXMLRPCPP_OBJS)
	-rm -f $@
	$(AR) cru $@ $^
	$(RANLIB) $@

libxmlrpc_server++.a: $(LIBXMLRPC_SERVERPP_OBJS)
	-rm -f $@
	$(AR) cru $@ $^
	$(RANLIB) $@

libxmlrpc_server_abyss++.a: $(LIBXMLRPC_SERVER_ABYSSPP_OBJS)
	-rm -f $@
	$(AR) cru $@ $^
	$(RANLIB) $@

libxmlrpc_client++.a: $(LIBXMLRPC_CLIENTPP_OBJS)
	-rm -f $@
	$(AR) cru $@ $^
	$(RANLIB) $@

#-----------------------------------------------------------------------------
#  RULES TO COMPILE OBJECT MODULES FOR LIBRARIES
#-----------------------------------------------------------------------------

CXXFLAGS = $(CXXFLAGS_COMMON) $(CFLAGS_PERSONAL) $(CADD)

XmlRpcCpp.o:%.o:%.cpp
	$(CXX) -c $(BASIC_INCLUDES) $(CXXFLAGS) $<

$(LIBXMLRPCPP_OBJS):%.o:%.cpp
	$(CXX) -c $(BASIC_INCLUDES) $(CXXFLAGS) $<

SERVER_INCLUDES = $(BASIC_INCLUDES) $(LIBXML_INCLUDES)

$(LIBXMLRPC_SERVERPP_OBJS):%.o:%.cpp
	$(CXX) -c $(SERVER_INCLUDES) $(CXXFLAGS) $<

SERVER_ABYSS_INCLUDES = $(SERVER_INCLUDES) -Isrcdir/lib/abyss/src

$(LIBXMLRPC_SERVER_ABYSSPP_OBJS):%.o:%.cpp
	$(CXX) -c $(SERVER_ABYSS_INCLUDES) $(CXXFLAGS) $<

CLIENT_INCLUDES = $(BASIC_INCLUDES) $(LIBXML_INCLUDES) $(TRANSPORT_INCLUDES)

$(LIBXMLRPC_CLIENTPP_OBJS):%.o:%.cpp
	$(CXX) -c $(CLIENT_INCLUDES) $(CXXFLAGS) $<

client.o curl.o libwww.o wininet.o: $(BUILDDIR)/transport_config.h

#-----------------------------------------------------------------------------
#  MISCELLANEOUS RULES
#-----------------------------------------------------------------------------

check:
	$(MAKE) -C test runtests

.PHONY: install
install: install-common

.PHONY: clean clean-local distclean distclean-local
clean: clean-common clean-local
clean-local:
	$(MAKE) -C test clean

distclean: clean distclean-local distclean-common

distclean-local:
	$(MAKE) -C test distclean

.PHONY: dep
dep: dep-common $(BUILDDIR)/transport_config.h

include Makefile.depend
