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
// The trusted plugin needs to call through to the browser directly.  These
// wrapper routines are not required when making a Native Client module.

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

// Allows the plugin to query the browser for a specific, limited set of
// ifnromation.  |instance| is the plugin itself.
// |variable| is the question being asked.  |value| is populated by the
// browser.  Returns NPERR_NO_ERROR if successfull, an error otherwise.
// Declaration: npapi.h
// Documentation URL: https://developer.mozilla.org/en/NPN_GetValue
NPError NPN_GetValue(NPP instance, NPNVariable variable, void* value) {
  return kBrowserFuncs.getvalue(instance, variable, value);
}

// Returns an opaque identifier for the string that is passed in.
// Declaration: npruntime.h
// Documentation URL: https://developer.mozilla.org/en/NPN_GetStringIdentifier
NPIdentifier NPN_GetStringIdentifier(const NPUTF8* name) {
  return kBrowserFuncs.getstringidentifier(name);
}

// Frees the memory referenced by |mem|
// Declaration: npapi.h
// Documentation URL: https://developer.mozilla.org/en/NPN_MemFree
void NPN_MemFree(void* mem) {
  kBrowserFuncs.memfree(mem);
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

// Enumerates the interface of |npobj|  Returns names of public members in
// |identifier|  Identifier will have |count| members.
// Returns true if npobj was successfully enumerate it.
// Declaration: npruntime.h
// Documentation URL: https://developer.mozilla.org/En/NPN_Enumerate
bool NPN_Enumerate(NPP npp, NPObject *npobj, NPIdentifier **identifier,
                   uint32_t *count) {
  return kBrowserFuncs.enumerate(npp, npobj, identifier, count);
}

// Populates |result| with the value of |property_name| on |npobj|.
// Returns true if the value was found.
// Declaration: npruntime.h
// Documentation URL: https://developer.mozilla.org/en/NPN_GetProperty
bool NPN_GetProperty(NPP npp, NPObject *npobj, NPIdentifier property_name,
                     NPVariant *result) {
  return kBrowserFuncs.getproperty(npp, npobj, property_name, result);
}

// Sets |property_name| on |npobj| equal to |value|.
// Declaration: npruntime.h
// Documentation URL: https://developer.mozilla.org/en/NPN_SetProperty
bool NPN_SetProperty(NPP npp, NPObject *npobj, NPIdentifier property_name,
                     const NPVariant *value) {
  return kBrowserFuncs.setproperty(npp, npobj, property_name, value);
}

// Returns true iff |npobj| has a property called |property_name|
// Declaration: npruntime.h
// Documentation URL: https://developer.mozilla.org/en/NPN_HasProperty
bool NPN_HasProperty(NPP npp, NPObject *npobj, NPIdentifier property_name) {
  return kBrowserFuncs.hasproperty(npp, npobj, property_name);
}

// Tries to run javascript, |script|.  |npp| and |npobj| provide the context.
// The first is the plugin requesting that the script be run.  The second is
// the window that it is runing in.  Whatever the script returns will be
// returned in |result| (to be preallocated).
// Returns false if any of this fails.
// Declaration: npruntime.h
// Documentation URL: https://developer.mozilla.org/en/NPN_Evaluate
bool NPN_Evaluate(NPP npp, NPObject *npobj, NPString *script,
                  NPVariant *result) {
  return kBrowserFuncs.evaluate(npp, npobj, script, result);
}

// Returns an opaque identifier for |intid|
// Declaration: npruntime.h
// Documentation URL: https://developer.mozilla.org/en/NPN_GetIntIdentifier
NPIdentifier NPN_GetIntIdentifier(int32_t intid) {
  return kBrowserFuncs.getintidentifier(intid);
}

// Requests that the browser call |func| on a separate thread.  Func should
// take |user_data| as an argument like with any callback.
// Declaration: npapi.h
// Documentation URL:
//   https://developer.mozilla.org/en/NPN_PluginThreadAsyncCall
void NPN_PluginThreadAsyncCall(NPP npp,
                               void (*func)(void *),
                               void *user_data) {
  kBrowserFuncs.pluginthreadasynccall(npp, func, user_data);
}

#endif

// PINPAPI extensions.  These get instantiated when NPN_New is called and
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
