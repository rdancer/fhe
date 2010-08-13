// Copyright 2010 The Native Client SDK Authors. All rights reserved.
// Use of this source code is governed by a BSD-style license that can
// be found in the LICENSE file.

#include <assert.h>
#if defined (__native_client__)
#include <nacl/npapi_extensions.h>
#include <nacl/npupp.h>
#else
#include "third_party/npapi/bindings/npapi.h"
#include "third_party/npapi/bindings/npapi_extensions.h"
#include "third_party/npapi/bindings/nphostapi.h"
#endif

// This file implements functions that are used by the plugin to call into the
// browser.  With the exception of any Pepper extensions, They only need to be
// implemented for the TRUSTED build.  In the published build they are
// provided by the environment.


#if !defined(__native_client__)
// The development version needs to call through to the browser directly.
// These wrapper routines are not required when making the version you publish
// to the web.

static NPNetscapeFuncs kBrowserFuncs = { 0 };

extern "C" {

// Called by NP_Initialize for platform specific trusted instances of this
// program.  Not used by untrusted version.  Not declared anywhere, used
// as 'extern' only.  This is implemented here so that the trusted runtime
// can call it to tell this module what browser functions are available
// to it.
void InitializeBrowserFunctions(NPNetscapeFuncs* browser_functions) {
  memcpy(&kBrowserFuncs, browser_functions, sizeof(kBrowserFuncs));
}

}  // extern "C"

// Returns an opaque identifier for the string that is passed in.
// Declaration: npruntime.h
// Documentation URL: https://developer.mozilla.org/en/NPN_GetStringIdentifier
NPIdentifier NPN_GetStringIdentifier(const NPUTF8* name) {
  return kBrowserFuncs.getstringidentifier(name);
}

// Allocates memory for an object using |np_class| to get definition info.
// The object is then associated with |npp|, the plugin that is requesting it.
// Returns a reference counted point to an |NPObject|.
// Declaration: npruntime.h
// Documentation URL: https://developer.mozilla.org/en/NPN_CreateObject
NPObject* NPN_CreateObject(NPP npp, NPClass* np_class) {
  return kBrowserFuncs.createobject(npp, np_class);
}

// Increments the reference count for |obj| and returns a pointer to the same
// object.
// Declaration: npruntime.h
// Documentation URL: https://developer.mozilla.org/en/NPN_RetainObject
NPObject* NPN_RetainObject(NPObject* obj) {
  return kBrowserFuncs.retainobject(obj);
}

// Decrements the reference count for |obj| and cleans up if count hits 0.
// Declaration: npruntime.h
// Documentation URL: https://developer.mozilla.org/en/NPN_ReleaseObject
void NPN_ReleaseObject(NPObject* obj) {
  kBrowserFuncs.releaseobject(obj);
}

#endif

// PINPAPI extensions.  These get filled in when NPP_New is called and
// populated with pepper extensions in the NPN_GetValue call below.
static NPExtensions* kPINPAPIExtensions = NULL;

// Chrome specific, this function calls GetValue to get Pepper extensions
// made available to the plugin, |instance|.
// Declaration of NPNVPepperExtensions: npapi_extensions.h
void InitializePepperExtensions(NPP instance) {
  // Grab the PINPAPI extensions.
  NPN_GetValue(instance, NPNVPepperExtensions,
               reinterpret_cast<void*>(&kPINPAPIExtensions));
  assert(NULL != kPINPAPIExtensions);
}

// These are PINPAPI extensions.  Gets a higher-level device from
// the pepper interface.  For example audio, 2d and 3d devices.
// Returns NULL if device could not be acquired.
// Decaration of kPINPAPIExtensions::acquireDevice: npapi_extensions.h
NPDevice* NPN_AcquireDevice(NPP instance, NPDeviceID device) {
  return kPINPAPIExtensions ?
      kPINPAPIExtensions->acquireDevice(instance, device) : NULL;
}
