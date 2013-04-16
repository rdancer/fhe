#!/usr/bin/python
# Copyright 2010, Google Inc.
# All rights reserved.
#
# Redistribution and use in source and binary forms, with or without
# modification, are permitted provided that the following conditions are
# met:
#
#     * Redistributions of source code must retain the above copyright
# notice, this list of conditions and the following disclaimer.
#     * Redistributions in binary form must reproduce the above
# copyright notice, this list of conditions and the following disclaimer
# in the documentation and/or other materials provided with the
# distribution.
#     * Neither the name of Google Inc. nor the names of its
# contributors may be used to endorse or promote products derived from
# this software without specific prior written permission.
#
# THIS SOFTWARE IS PROVIDED BY THE COPYRIGHT HOLDERS AND CONTRIBUTORS
# "AS IS" AND ANY EXPRESS OR IMPLIED WARRANTIES, INCLUDING, BUT NOT
# LIMITED TO, THE IMPLIED WARRANTIES OF MERCHANTABILITY AND FITNESS FOR
# A PARTICULAR PURPOSE ARE DISCLAIMED. IN NO EVENT SHALL THE COPYRIGHT
# OWNER OR CONTRIBUTORS BE LIABLE FOR ANY DIRECT, INDIRECT, INCIDENTAL,
# SPECIAL, EXEMPLARY, OR CONSEQUENTIAL DAMAGES (INCLUDING, BUT NOT
# LIMITED TO, PROCUREMENT OF SUBSTITUTE GOODS OR SERVICES; LOSS OF USE,
# DATA, OR PROFITS; OR BUSINESS INTERRUPTION) HOWEVER CAUSED AND ON ANY
# THEORY OF LIABILITY, WHETHER IN CONTRACT, STRICT LIABILITY, OR TORT
# (INCLUDING NEGLIGENCE OR OTHERWISE) ARISING IN ANY WAY OUT OF THE USE
# OF THIS SOFTWARE, EVEN IF ADVISED OF THE POSSIBILITY OF SUCH DAMAGE.

"""Download all Native Client toolchains for this platform.

This module downloads multiple tgz's and expands them.
"""

import optparse
import os
import shutil
import stat
import subprocess
import sys
import tempfile
import time
import urllib


def DownloadSDK(platform, base_url, version):
  """Download one Native Client toolchain and extract it.

  Arguments:
    platform: platform of the sdk to download
    base_url: base url to download toolchain tarballs from
    version: version directory to select tarballs from
  """
  if sys.platform in ['win32', 'cygwin']:
    path = 'naclsdk_' + platform + '.exe'
  else:
    path = 'naclsdk_' + platform + '.tgz'
  url = base_url + version + '/' + path

  # Pick target directory.
  script_dir = os.path.abspath(os.path.dirname(__file__))
  parent_dir = os.path.split(script_dir)[0]
  toolchain_dir = os.path.join(parent_dir, 'toolchain')
  target = os.path.join(toolchain_dir, platform)

  tgz_dir = os.path.join(script_dir)
  tgz_filename = os.path.join(tgz_dir, path)

  # Drop old versions on mac/linux.
  if sys.platform not in ['win32', 'cygwin']:
    print 'Cleaning up old SDKs...'
    cmd = 'rm -rf "%s"/native_client_sdk_*' % tgz_dir
    p = subprocess.Popen(cmd, shell=True)
    p.communicate()
    assert p.returncode == 0

  print 'Downloading "%s" to "%s"...' % (url, tgz_filename)
  sys.stdout.flush()

  # Download it.
  urllib.urlretrieve(url, tgz_filename)

  # Extract toolchain.
  old_cwd = os.getcwd()
  os.chdir(tgz_dir)
  if sys.platform in ['win32', 'cygwin']:
    cmd = tgz_filename + (
        ' /S /D=c:\\native_client_sdk&& '
        'cd .. && '
        r'c:\native_client_sdk\third_party\cygwin\bin\ln.exe -fsn '
        'c:/native_client_sdk/toolchain toolchain')
  else:
    cmd = (
        'tar xfzv "%s" && '
        'cd .. && rm -f toolchain && '
        'ln -fsn build_tools/native_client_sdk_*/toolchain toolchain'
    ) % path
  p = subprocess.Popen(cmd, shell=True)
  p.communicate()
  assert p.returncode == 0
  os.chdir(old_cwd)

  # Clean up: remove the sdk tgz/exe.
  time.sleep(2)  # Wait for windows.
  os.remove(tgz_filename)

  print 'Install complete.'


PLATFORM_COLLAPSE = {
    'win32': 'win',
    'cygwin': 'win',
    'linux': 'linux',
    'linux2': 'linux',
    'darwin': 'mac',
}


def main(argv):
  parser = optparse.OptionParser()
  parser.add_option(
      '-b', '--base-url', dest='base_url',
      default='http://build.chromium.org/buildbot/nacl_archive/sdk/',
      help='base url to download from')
  parser.add_option(
      '-v', '--version', dest='version',
      default='latest',
      help='which version of the toolchain to download')
  (options, args) = parser.parse_args(argv)
  if args:
    parser.print_help()
    print 'ERROR: invalid argument'
    sys.exit(1)

  flavor = PLATFORM_COLLAPSE[sys.platform]
  DownloadSDK(flavor, base_url=options.base_url, version=options.version)


if __name__ == '__main__':
  main(sys.argv[1:])
