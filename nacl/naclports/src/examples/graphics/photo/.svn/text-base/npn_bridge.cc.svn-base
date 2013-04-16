// Copyright 2010 The Native Client SDK Authors. All rights reserved.
// Use of this source code is governed by a BSD-style license that can
// be found in the LICENSE file.

#include <assert.h>
#include <nacl/npapi_extensions.h>
#include <nacl/npupp.h>

// Pepper extensions.  These get filled in when NPP_New is called and
// populated with pepper extensions in the NPN_GetValue call below.
static NPExtensions* kPepperExtensions = NULL;

// Chrome specific, this function calls GetValue to get Pepper extensions
// made available to the plugin, |instance|.
// Declaration of NPNVPepperExtensions: npapi_extensions.h
void InitializePepperExtensions(NPP instance) {
  // Grab the Pepper extensions.
  NPN_GetValue(instance, NPNVPepperExtensions,
               reinterpret_cast<void*>(&kPepperExtensions));
  assert(NULL != kPepperExtensions);
}

// These are Pepper extensions.  Gets a higher-level device from
// the pepper interface.  For example audio, 2d and 3d devices.
// Returns NULL if device could not be acquired.
// Decaration of kPepperExtensions::acquireDevice: npapi_extensions.h
NPDevice* NPN_AcquireDevice(NPP instance, NPDeviceID device) {
  return kPepperExtensions ?
      kPepperExtensions->acquireDevice(instance, device) : NULL;
}
