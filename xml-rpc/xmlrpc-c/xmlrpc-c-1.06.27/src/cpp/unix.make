# -*-makefile-*-    <-- an Emacs control

# This is stuff for creating General case Unix shared libraries.
# Some Unix systems can't use this.

# This is to be included by 'Makefile'.  Most of the make variables here
# come from the main make file.

libxmlrpc_cpp.$(SHLIB_SUFFIX).$(MAJ).$(MIN): XmlRpcCpp.o
	$(SHLIB_RULE)

libxmlrpc++.$(SHLIB_SUFFIX).$(MAJ).$(MIN): $(LIBXMLRPCPP_OBJS)
	$(SHLIB_RULE)

libxmlrpc_server++.$(SHLIB_SUFFIX).$(MAJ).$(MIN): $(LIBXMLRPC_SERVERPP_OBJS)
	$(SHLIB_RULE)

libxmlrpc_server_abyss++.$(SHLIB_SUFFIX).$(MAJ).$(MIN): \
  $(LIBXMLRPC_SERVER_ABYSSPP_OBJS)
	$(SHLIB_RULE)

libxmlrpc_client++.$(SHLIB_SUFFIX).$(MAJ).$(MIN): $(LIBXMLRPC_CLIENTPP_OBJS)
	$(SHLIB_RULE)
