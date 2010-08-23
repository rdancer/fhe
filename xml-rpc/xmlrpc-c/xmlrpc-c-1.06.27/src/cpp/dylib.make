# -*-makefile-*-    <-- an Emacs control

# This is stuff for creating Macintosh shared libraries.

# This is to be included by 'Makefile'.  Most of the make variables here
# come from the main make file.


TARGET_LINKNAMES = $(TARGET_LIBRARY_NAMES:%=%.dylib)
TARGET_SONAMES = $(TARGET_LIBRARY_NAMES:%=%.$(MAJ).dylib

$(TARGET_LINKNAMES): %.dylib : %.$(MAJ).dylib
	rm -f $@
	$(SYMLINK) $< $@

$(TARGET_SONAMES): %.dylib : %.$(MIN).dylib
	rm -f $@
	$(SYMLINK) $< $@

libxmlrpc++.$(MAJ).$(MIN).dylib: $(LIBXMLRPCPP_OBJS)
	$(LD) $(LDSHLIB) -o $@ $^ -lc $(LADD)
