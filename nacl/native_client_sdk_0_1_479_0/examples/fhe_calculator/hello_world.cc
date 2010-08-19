// Copyright 2008 The Native Client Authors. All rights reserved.
// Use of this source code is governed by a BSD-style license that can
// be found in the LICENSE file.

#include <stdbool.h>
#include <stdio.h>
#include <stdlib.h>
#include <string.h>

#if defined(__native_client__)
#include <nacl/nacl_npapi.h>
#else
// Building a develop version for debugging.
#include "third_party/npapi/bindings/npapi.h"
#include "third_party/npapi/bindings/nphostapi.h"
#endif

// These are the method names as JavaScript sees them.
static const char* kHelloWorldMethodId = "helloworld";
static const char* kFortyTwoMethodId = "fortytwo";
static const char* kEvaluateMethodId = "evaluate";

/** Return '43' */
static bool Evaluate(NPVariant *result) {
  if (result) {
    INT32_TO_NPVARIANT(43, *result);
  }
  return true;
}

// This is the module's function that does the work to set the value of the
// result variable to '42'.  The Invoke() function that called this function
// then returns the result back to the browser as a JavaScript value.
static bool FortyTwo(NPVariant *result) {
  if (result) {
    INT32_TO_NPVARIANT(43, *result);
  }
  return true;
}

// This function creates a string in the browser's memory pool and then returns
// a variable containing a pointer to that string.  The variable is later
// returned back to the browser by the Invoke() function that called this.
static bool HelloWorld(const NPVariant *args,
                       uint32_t arg_count,
		       NPVariant *result) {
  if (result) {
    //const char *msg = "hello, stranger.";
    //const int msg_length = strlen(msg) + 1;
    //// Note: |msg_copy| will be freed later on by the browser, so it needs to
    //// be allocated here with NPN_MemAlloc().
    //char *msg_copy = reinterpret_cast<char*>(NPN_MemAlloc(msg_length));
    //strncpy(msg_copy, msg, msg_length);
    //STRINGN_TO_NPVARIANT(msg_copy, msg_length - 1, *result);
    if (arg_count > 0) {
	//NPString s = NPVARIANT_TO_STRING(args[0]);
	*result = *args;
    }
  }
  return true;
}

// Creates the plugin-side instance of NPObject.
// Called by NPN_CreateObject, declared in npruntime.h
// Documentation URL: https://developer.mozilla.org/en/NPClass
static NPObject* Allocate(NPP npp, NPClass* npclass) {
  return new NPObject;
}

// Cleans up the plugin-side instance of an NPObject.
// Called by NPN_ReleaseObject, declared in npruntime.h
// Documentation URL: https://developer.mozilla.org/en/NPClass
static void Deallocate(NPObject* object) {
  delete object;
}

// Returns |true| if |method_name| is a recognized method.
// Called by NPN_HasMethod, declared in npruntime.h
// Documentation URL: https://developer.mozilla.org/en/NPClass
static bool HasMethod(NPObject* obj, NPIdentifier method_name) {
  char *name = NPN_UTF8FromIdentifier(method_name);
  bool is_method = false;
  if (!strcmp((const char *)name, kHelloWorldMethodId)) {
    is_method = true;
  } else if (!strcmp((const char*)name, kFortyTwoMethodId)) {
    is_method = true;
  } else if (!strcmp((const char*)name, kEvaluateMethodId)) {
    is_method = true;
  }
  NPN_MemFree(name);
  return is_method;
}

// Called by the browser to invoke the default method on an NPObject.
// Returns null.
// Apparently the plugin won't load properly if we simply
// tell the browser we don't have this method.
// Called by NPN_InvokeDefault, declared in npruntime.h
// Documentation URL: https://developer.mozilla.org/en/NPClass
static bool InvokeDefault(NPObject *obj, const NPVariant *args,
                          uint32_t argCount, NPVariant *result) {
  if (result) {
    NULL_TO_NPVARIANT(*result);
  }
  return true;
}

// Called by the browser to invoke a function object whose name
// is |method_name|.
// Called by NPN_Invoke, declared in npruntime.h
// Documentation URL: https://developer.mozilla.org/en/NPClass
static bool Invoke(NPObject* obj,
                   NPIdentifier method_name,
                   const NPVariant *args,
                   uint32_t arg_count,
                   NPVariant *result) {
  NULL_TO_NPVARIANT(*result);
  char *name = NPN_UTF8FromIdentifier(method_name);
  if (name == NULL)
    return false;
  bool rval = false;

  // Map the method name to a function call.  |result| is filled in by the
  // called function, then gets returned to the browser when Invoke() returns.
  if (!strcmp((const char *)name, kHelloWorldMethodId)) {
    rval = HelloWorld(args, arg_count, result);
  } else if (!strcmp((const char*)name, kFortyTwoMethodId)) {
    rval = FortyTwo(result);
  } else if (!strcmp((const char*)name, kEvaluateMethodId)) {
    rval = Evaluate(result);
  }
  // Since name was allocated above by NPN_UTF8FromIdentifier,
  // it needs to be freed here.
  NPN_MemFree(name);
  return rval;
}

// Represents a class's interface, so that the browser knows what functions it
// can call on this plugin object.  The browser can use the methods in this
// class to discover the rest of the plugin's interface.
// Documentation URL: https://developer.mozilla.org/en/NPClass
static NPClass kHelloWorldClass = {
  NP_CLASS_STRUCT_VERSION,
  Allocate,
  Deallocate,
  NULL,  // Invalidate is not implemented
  HasMethod,
  Invoke,
  InvokeDefault,
  NULL,  // HasProperty is not implemented
  NULL,  // GetProperty is not implemented
  NULL,  // SetProperty is not implemented
};

// Called by NPP_GetScriptableInstance to get the scripting interface for
// this plugin.
NPClass *GetNPSimpleClass() {
  return &kHelloWorldClass;
}
