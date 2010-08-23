# -*-makefile-*-    <-- an Emacs control

# This is stuff for creating Irix shared libraries

# This is to be included by 'Makefile'.  Most of the make variables here
# come from the main make file.


TARGET_LINKNAMES = $(TARGET_LIBRARY_NAMES:%=%.$(SHLIB_SUFFIX)

$(TARGET_LINKNAMES): % : %.$(MAJ).$(MIN)
	rm -f $@
	$(SYMLINK) $< $@

LDSHLIB = -lc \
	  -soname $(@:%.$(MAJ)=%) \
	  -set_version `perl -e 'print "sgi$(MAJ).".join(":sgi$(MAJ).",(0..$(MIN)))."\n"'` \


libxmlrpc++.$(SHLIB_SUFFIX).$(MAJ): $(LIBXMLRPCPP_OBJS)
	$(LD) $(LDSHLIB) -o $@ $^ $(LADD)

