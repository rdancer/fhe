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

#if !defined(__native_client__)
// The trusted plugin needs to call through to the browser directly.  These
// wrapper routines are not required when making a Native Client module.

static NPNetscapeFuncs kBrowserFuncs = { 0 };

extern "C" {

void InitializeBrowserFunctions(NPNetscapeFuncs* browser_functions) {
  memcpy(&kBrowserFuncs, browser_functions, sizeof(kBrowserFuncs));
}

}  // extern "C"

bool NPN_Invoke(NPP npp,
                NPObject *npobj,
                NPIdentifier method_name,
                const NPVariant *args,
                uint32_t arg_count,
                NPVariant *result) {
  return kBrowserFuncs.invoke(npp, npobj, method_name, args, arg_count, result);
}

bool NPN_InvokeDefault(NPP npp,
                       NPObject *npobj,
                       const NPVariant *args,
                       uint32_t arg_count,
                       NPVariant *result) {
  return kBrowserFuncs.invokeDefault(npp, npobj, args, arg_count, result);
}

NPError NPN_GetValue(NPP instance, NPNVariable variable, void* value) {
  return kBrowserFuncs.getvalue(instance, variable, value);
}

// Returns an opaque identifier for the string that is passed in.
NPIdentifier NPN_GetStringIdentifier(const NPUTF8* name) {
  return kBrowserFuncs.getstringidentifier(name);
}

NPUTF8* NPN_UTF8FromIdentifier(NPIdentifier identifier) {
  return kBrowserFuncs.utf8fromidentifier(identifier);
}

void* NPN_MemAlloc(uint32 size) {
  return kBrowserFuncs.memalloc(size);
}

void NPN_MemFree(void* mem) {
  kBrowserFuncs.memfree(mem);
}

NPObject* NPN_CreateObject(NPP npp, NPClass* np_class) {
  return kBrowserFuncs.createobject(npp, np_class);
}

NPObject* NPN_RetainObject(NPObject* obj) {
  return kBrowserFuncs.retainobject(obj);
}

void NPN_ReleaseObject(NPObject* obj) {
  kBrowserFuncs.releaseobject(obj);
}

bool NPN_Enumerate(NPP npp, NPObject *npobj, NPIdentifier **identifier,
                   uint32_t *count) {
  return kBrowserFuncs.enumerate(npp, npobj, identifier, count);
}

bool NPN_GetProperty(NPP npp, NPObject *npobj, NPIdentifier property_name,
                     NPVariant *result) {
  return kBrowserFuncs.getproperty(npp, npobj, property_name, result);
}

bool NPN_SetProperty(NPP npp, NPObject *npobj, NPIdentifier property_name,
                     const NPVariant *value) {
  return kBrowserFuncs.setproperty(npp, npobj, property_name, value);
}

bool NPN_HasProperty(NPP npp, NPObject *npobj, NPIdentifier property_name) {
  return kBrowserFuncs.hasproperty(npp, npobj, property_name);
}

bool NPN_Evaluate(NPP npp, NPObject *npobj, NPString *script,
                  NPVariant *result) {
  return kBrowserFuncs.evaluate(npp, npobj, script, result);
}

NPIdentifier NPN_GetIntIdentifier(int32_t intid) {
  return kBrowserFuncs.getintidentifier(intid);
}

void NPN_PluginThreadAsyncCall(NPP npp, void (*func)(void *), void *user_data) {
  kBrowserFuncs.pluginthreadasynccall(npp, func, user_data);
}

#endif

// PINPAPI extensions.  These get filled in when NPP_New is called.
static NPExtensions* kPINPAPIExtensions = NULL;

void InitializePepperExtensions(NPP instance) {
  // Grab the PINPAPI extensions.
  NPN_GetValue(instance, NPNVPepperExtensions,
               reinterpret_cast<void*>(&kPINPAPIExtensions));
  assert(NULL != kPINPAPIExtensions);
}

// These are PINPAPI extensions.
NPDevice* NPN_AcquireDevice(NPP instance, NPDeviceID device) {
  return kPINPAPIExtensions ?
      kPINPAPIExtensions->acquireDevice(instance, device) : NULL;
}

