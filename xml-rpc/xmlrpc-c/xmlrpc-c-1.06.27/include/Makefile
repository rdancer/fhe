ifeq ($(SRCDIR)x,x)
SRCDIR = $(CURDIR)/..
endif
SUBDIR = src
BUILDDIR = $(SRCDIR)
VPATH = .:$(SRCDIR)

include $(BUILDDIR)/Makefile.config

default: all

all: xmlrpc-c/config.h

xmlrpc-c/config.h:
	echo "#define XMLRPC_HAVE_WCHAR $(HAVE_WCHAR_H_DEFINE)" >$@


HEADERS_TO_INSTALL = \
  xmlrpc-c/config.h \
  xmlrpc-c/util.h \
  xmlrpc-c/base.h \
  xmlrpc-c/abyss.h \
  xmlrpc-c/server.h \
  xmlrpc-c/server_abyss.h \
  xmlrpc-c/server_w32httpsys.h \
  xmlrpc-c/oldxmlrpc.h \

ifeq ($(ENABLE_CPLUSPLUS),yes)
  HEADERS_TO_INSTALL += \
    xmlrpc-c/oldcppwrapper.hpp \
    xmlrpc-c/girerr.hpp \
    xmlrpc-c/girmem.hpp \
    xmlrpc-c/base.hpp \
    xmlrpc-c/timeout.hpp \
    xmlrpc-c/xml.hpp \
    xmlrpc-c/registry.hpp \
    xmlrpc-c/server_abyss.hpp \

endif

HEADERINST_PREFIX = /xmlrpc-c

ifeq ($(MUST_BUILD_CLIENT),yes)
  HEADERS_TO_INSTALL += \
    xmlrpc-c/client.h \
    xmlrpc-c/transport.h \
    xmlrpc-c/client_global.h \

  ifeq ($(ENABLE_CPLUSPLUS),yes)
    HEADERS_TO_INSTALL += \
      xmlrpc-c/client.hpp \
      xmlrpc-c/client_transport.hpp \
      xmlrpc-c/client_simple.hpp \

  endif
endif
ifeq ($(ENABLE_CGI_SERVER),yes)
  HEADERS_TO_INSTALL += xmlrpc-c/server_cgi.h
endif

default: all

all:

.PHONY: install
install: install-common

.PHONY: clean distclean dep
clean:
distclean:
	rm -f xmlrpc-c/config.h

.PHONY: check
check:

.PHONY: dep
dep:

include $(SRCDIR)/Makefile.common
