# -*-makefile-*-    <-- an Emacs control

# This is stuff for creating Windows DLLs (shared libraries).

# This is to be included by 'Makefile'.  Most of the make variables here
# come from the main make file.
 

$(SHLIB_PREFIX)libxmlrpc++$(DLLVER).dll: $(LIBXMLRPCPP_OBJS)
	$(LD) $(LDSHLIB) -Wl,--export-all-symbols \
            -Wl,-soname,$@ \
            -Wl,--out-implib,libxmlrpc++.dll.a -o $@ $(LDFLAGS) \
            $^ $(LDLIBS) $(LADD) 
