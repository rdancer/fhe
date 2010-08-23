ifeq ($(SRCDIR)x,x)
SRCDIR = $(CURDIR)/..
endif
SUBDIR = src
BUILDDIR = $(SRCDIR)
VPATH = .:$(SRCDIR)

include $(BUILDDIR)/Makefile.config

ABYSS_LIBDIR = $(BUILDDIR)/lib/abyss/src/.libs
LIBUTIL_LIBDIR = $(BUILDDIR)/lib/libutil/.libs

default: all

SUBDIRS =

ifeq ($(ENABLE_CPLUSPLUS),yes)
  SUBDIRS += cpp
endif

WININET_TRANSPORT_DIR = $(SRCDIR)/lib/wininet_transport
CURL_TRANSPORT_DIR    = $(SRCDIR)/lib/curl_transport
LIBWWW_TRANSPORT_DIR  = $(SRCDIR)/lib/libwww_transport

# TRANSPORT_OBJS is the list of modules that have to go into the client
# library to provide the client XML transport functions.  Since there is
# some variability in what XML transports we can build into the client
# library, this is a little complicated.

# TRANSPORT_LIBDEP is linker -l options to declare what libraries contain
# things to which the transport objects refer.  (like LIBxxx_LIBDEP --
# see below)

TRANSPORT_OBJS =
TRANSPORT_LIBDEP =
ifeq ($(MUST_BUILD_WININET_CLIENT),yes)
  TRANSPORT_OBJS += $(WININET_TRANSPORT_DIR)/xmlrpc_wininet_transport.lo
  TRANSPORT_LIBDEP += $(shell wininet-config --libs)
endif
ifeq ($(MUST_BUILD_CURL_CLIENT),yes)
  TRANSPORT_OBJS += $(CURL_TRANSPORT_DIR)/xmlrpc_curl_transport.lo
  TRANSPORT_LIBDEP += $(shell curl-config --libs)
endif
ifeq ($(MUST_BUILD_LIBWWW_CLIENT),yes)
  TRANSPORT_OBJS += $(LIBWWW_TRANSPORT_DIR)/xmlrpc_libwww_transport.lo
  TRANSPORT_LIBDEP += $(shell libwww-config --libs)
endif

ifeq ($(ENABLE_LIBXML2_BACKEND),yes)
  LIBXML_INCLUDES = $(shell xml2-config --cflags)
else
  LIBXML_INCLUDES = -I$(SRCDIR)/lib/expat/xmlparse
endif

ABYSS_INCLUDES = -I$(SRCDIR)/lib/abyss/src

ifeq ($(ENABLE_LIBXML2_BACKEND),yes)
  XMLRPC_XML_PARSER = xmlrpc_libxml2.lo
else
  XMLRPC_XML_PARSER = xmlrpc_expat.lo
endif

# LIBxxx_OBJS is the list of object files that make up library libxxx.

# LIBxxx_LIBDEP is linker -l options to declare what libraries contain
# things to which the library being built refers.  This information
# makes its way to the dynamic linker, so it knows to load the
# referred-to library before it loads libxxx.  Note that the link command
# may require the necessary -L options in addition.

LIBXMLRPC_CLIENT_OBJS = xmlrpc_client.lo xmlrpc_client_global.lo
LIBXMLRPC_CLIENT_LIBDEP = -lxmlrpc_util -lxmlrpc

LIBXMLRPC_SERVER_OBJS = registry.lo system_method.lo
LIBXMLRPC_SERVER_LIBDEP = -lxmlrpc_util -lxmlrpc

LIBXMLRPC_SERVER_ABYSS_OBJS = xmlrpc_server_abyss.lo
LIBXMLRPC_SERVER_ABYSS_LIBDEP = \
  -lxmlrpc_util -lxmlrpc_server -L$(ABYSS_LIBDIR) -lxmlrpc_abyss -lxmlrpc

LIBXMLRPC_SERVER_CGI_OBJS = xmlrpc_server_cgi.lo
LIBXMLRPC_SERVER_CGI_LIBDEP = -lxmlrpc_util -lxmlrpc_server -lxmlrpc

LIBXMLRPC_OBJS = \
	trace.lo \
	xmlrpc_data.lo \
	xmlrpc_builddecomp.lo \
	xmlrpc_datetime.lo \
	xmlrpc_array.lo \
	xmlrpc_struct.lo \
	$(XMLRPC_XML_PARSER) \
	xmlrpc_parse.lo \
	xmlrpc_serialize.lo \
	xmlrpc_base64.lo \
	xmlrpc_utf8.lo \
	xmlrpc_authcookie.lo \

LIBXMLRPC_LIBDEP = -lxmlrpc_util

LIB_OBJS = $(LIBXMLRPC_CLIENT_OBJS) $(LIBXMLRPC_SERVER_OBJS)

TRANSPORT_INCLUDES = \
  -I$(WININET_TRANSPORT_DIR) \
  -I$(CURL_TRANSPORT_DIR) \
  -I$(LIBWWW_TRANSPORT_DIR) \

INCLUDES = -I$(BUILDDIR) -I$(SRCDIR) \
           -I$(SRCDIR)/include -I$(SRCDIR)/lib/util/include \
	   -I$(SRCDIR)/lib/abyss/src \
	   $(TRANSPORT_INCLUDES) \
	   $(LIBXML_INCLUDES) \

# People sometimes think that when the Xmlrpc-c build has been
# configured for no Abyss server (configure --disable-abyss), that
# libmxlrpc_server_abyss should not get built.  But
# libxmlrpc_server_abyss is not part of the Abyss server; it is merely
# some code that exploits an Abyss server, and you don't need to have a
# built Abyss server to build it.

TARGET_LTLIBRARIES = libxmlrpc.la libxmlrpc_server.la \
     libxmlrpc_server_abyss.la

ifeq ($(MUST_BUILD_CLIENT),yes)
  TARGET_LTLIBRARIES += libxmlrpc_client.la
endif

ifeq ($(ENABLE_CGI_SERVER),yes)
  TARGET_LTLIBRARIES += libxmlrpc_server_cgi.la
endif

all: $(TARGET_LTLIBRARIES) $(SUBDIRS:%=%/all)

LDFLAGS = $(LADD)

LIBPATHS = -L.libs -L$(LIBUTIL_LIBDIR)

LIBLDFLAGS = $(LDFLAGS_VERSINFO) -rpath $(LIBINST_DIR) $(LIBPATHS) $(LADD)


libxmlrpc.la: $(LIBXMLRPC_OBJS)
	$(LIBTOOL) --mode=link $(CCLD) -o $@ $(LIBLDFLAGS) \
	  $(LIBXMLRPC_OBJS) $(LIBXMLRPC_LIBDEP) 

libxmlrpc_client.la: $(LIBXMLRPC_CLIENT_OBJS) $(TRANSPORT_OBJS)
	$(LIBTOOL) --mode=link $(CCLD) -o $@ $(LIBLDFLAGS) \
	  $(LIBXMLRPC_CLIENT_OBJS) $(TRANSPORT_OBJS) \
	  $(LIBXMLRPC_CLIENT_LIBDEP) $(TRANSPORT_LIBDEP) \

libxmlrpc_server.la: $(LIBXMLRPC_SERVER_OBJS)
	$(LIBTOOL) --mode=link $(CCLD) -o $@ $(LIBLDFLAGS) \
	  $(LIBXMLRPC_SERVER_OBJS) $(LIBXMLRPC_SERVER_LIBDEP)

libxmlrpc_server_abyss.la: $(LIBXMLRPC_SERVER_ABYSS_OBJS)
	$(LIBTOOL) --mode=link $(CCLD) -o $@ $(LIBLDFLAGS) \
	  $(LIBXMLRPC_SERVER_ABYSS_OBJS) $(LIBXMLRPC_SERVER_ABYSS_LIBDEP)

libxmlrpc_server_cgi.la: $(LIBXMLRPC_SERVER_CGI_OBJS)
	$(LIBTOOL) --mode=link $(CCLD) -o $@ $(LIBLDFLAGS) \
	  $(LIBXMLRPC_SERVER_CGI_OBJS) $(LIBXMLRPC_SERVER_CGI_LIBDEP)


CFLAGS = $(CFLAGS_COMMON) $(CFLAGS_PERSONAL) $(CADD)

$(LIBXMLRPC_CLIENT_OBJS) $(LIBXMLRPC_SERVER_OBJS):%.lo:%.c
	$(LIBTOOL) --mode=compile $(CC) -c $(INCLUDES) $(CFLAGS) $<

$(LIBXMLRPC_OBJS):%.lo:%.c
	$(LIBTOOL) --mode=compile $(CC) -c $(INCLUDES) $(LIBXML_INCLUDES) \
	  $(CFLAGS) $<

$(LIBXMLRPC_SERVER_ABYSS_OBJS):%.lo:%.c
	$(LIBTOOL) --mode=compile $(CC) -c $(INCLUDES) $(ABYSS_INCLUDES) \
	  $(CFLAGS) $<

xmlrpc_server_cgi.lo:%.lo:%.c
	$(LIBTOOL) --mode=compile $(CC) -c $(INCLUDES) \
	  $(CFLAGS) $<

LTLIBRARIES_TO_INSTALL = $(TARGET_LTLIBRARIES)

check:
	$(MAKE) -C test runtests
	$(MAKE) -C cpp  check

.PHONY: install
install: install-common $(SUBDIRS:%=%/install)

.PHONY: clean clean-local distclean
clean: clean-common clean-local $(SUBDIRS:%=%/clean)
clean-local:
	$(MAKE) -C test clean

distclean: clean-common clean-local distclean-common $(SUBDIRS:%=%/distclean)

.PHONY: dep
dep: $(SUBDIRS:%=%/dep) $(BUILDDIR)/transport_config.h dep-common

include $(SRCDIR)/Makefile.common

xmlrpc_client.lo: $(BUILDDIR)/transport_config.h

include Makefile.depend
