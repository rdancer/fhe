#!/usr/bin/python

"""Download appropriate sdk for buildbot."""

import download_sdk


# Update this value to select alternate sdk versions.
SDK_VERSION = 'latest'


if __name__ == '__main__':
  download_sdk.main(['--version', SDK_VERSION])
