External Native Client SDK Packages

The scripts directory contains bash scripts and patch files to build
common packages for Native Client. The bash scripts will download, patch
build and copy the binary library and developer header files into your
Native Client SDK.

The nacl-install-all.sh script will iterate over all scripts.  This
will download, patch, build and install all the libraries supported by the
SDK.  It is recommended to install all the SDK libraries once using this
higher level script, instead of the individual scripts.

Headers and libraries are installed where nacl-gcc and nacl-g++ will
be able to automatically find them without having to add extra -I or -L
options.  (Currently, these scripts will generate a gcc "specs" file
to add the required extra paths.)

The source code & build out for each package is placed in:

  naclports/packages/repository

NOTE:  These external libraries each have their own licenses for use.
Please read and understand these licenses before using these packages
in your projects.

NOTE to Windows users:  These scripts are written in bash and must be
launched from a Cygwin shell.

To add a package:
1. Make sure you have a writable version of the repository
    gclient config https://naclports.googlecode.com/svn/trunk/src
2. Add a directory to the scripts directory using the name and version of
    your new package.  For example, nacl-esidl-0.1.5
3. Add the build script to that directory.
4. Optionally build a tarball.  If you choose to do this, you will need to
    create a checksum for it using scripts/sha1sum.py.  Redirect the script
    output to a .sha1 file so that the common.sh script can pick it up.  E.g.:
    python scripts/sha1sum.py mytarball.zip > scripts/nacl-esidl-0.1.5/nacl-esidl-0.1.5.sha1
5. Add the invocation of you rscript to nacl-install-all-bitsize.sh, e.g.:
    (cd scripts/nacl-esidl-0.1.5; ./nacl-esidl-0.1.5.sh)

