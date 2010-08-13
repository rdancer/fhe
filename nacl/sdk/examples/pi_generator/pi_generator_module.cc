// Copyright 2010 The Native Client SDK Authors. All rights reserved.
// Use of this source code is governed by a BSD-style license that can
// be found in the LICENSE file.

#if defined (__native_client__)
#include <nacl/npupp.h>
#else
#include "third_party/npapi/bindings/npapi.h"
#include "third_party/npapi/bindings/nphostapi.h"
#endif

// These functions are required by both the develop and publish versions,
// they are called when a module instance is first loaded, and when the module
// instance is finally deleted.  They must use C-style linkage.

extern "C" {

// Populates |plugin_funcs| by calling InitializePluginFunctions.
// Declaration: npupp.h
// Web Reference: N/A
NPError NP_GetEntryPoints(NPPluginFuncs* plugin_funcs) {
  extern NPError InitializePluginFunctions(NPPluginFuncs* plugin_funcs);
  return InitializePluginFunctions(plugin_funcs);
}

// Some platforms, including Native Client uses the two-parameter version of
// NP_Initialize(), and do not call NP_GetEntryPoints().  Others (Mac, e.g.)
// use single-parameter version of NP_Initialize(), and then call
// NP_GetEntryPoints() to get the NPP functions.  Also, the NPN entry points
// are defined by the Native Client loader, but are not defined in the trusted
// plugin loader (and must be filled in in NP_Initialize()).
#if defined(__native_client__)
// Called when the first instance of this plugin is first allocated to
// initialize global state.  The browser is hereby telling the plugin its
// interface in |browser_functions| and expects the plugin to populate
// |plugin_functions| in return.  Memory allocated by this function may only
// be cleaned up by NP_Shutdown.
// returns an NPError if anything went wrong.
// Declaration: npupp.h
// Documentation URL: https://developer.mozilla.org/en/NP_Initialize
NPError NP_Initialize(NPNetscapeFuncs* browser_functions,
                      NPPluginFuncs* plugin_functions) {
  return NP_GetEntryPoints(plugin_functions);
}
#elif defined(OS_LINUX)
NPError NP_Initialize(NPNetscapeFuncs* browser_functions,
                      NPPluginFuncs* plugin_functions) {
  extern void InitializeBrowserFunctions(NPNetscapeFuncs* browser_functions);
  InitializeBrowserFunctions(browser_functions);
  return NP_GetEntryPoints(plugin_functions);
}
#elif defined(OS_MACOSX)
NPError NP_Initialize(NPNetscapeFuncs* browser_functions) {
  extern void InitializeBrowserFunctions(NPNetscapeFuncs* browser_functions);
  InitializeBrowserFunctions(browser_functions);
  return NPERR_NO_ERROR;
}
#elif defined(OS_WIN)
NPError NP_Initialize(NPNetscapeFuncs* browser_functions) {
  extern void InitializeBrowserFunctions(NPNetscapeFuncs* browser_functions);
  InitializeBrowserFunctions(browser_functions);
  return NPERR_NO_ERROR;
}
#else
#error "Unrecognized platform"
#endif

// Called just before the plugin itself is completely unloaded from the
// browser.  Should clean up anything allocated by NP_Initialize.
// Declaration: npupp.h
// Documentation URL: https://developer.mozilla.org/en/NP_Shutdown
NPError NP_Shutdown() {
  return NPERR_NO_ERROR;
}


#if !defined(__native_client__) && defined(OS_LINUX)
// Usually called early by the browser to ask the plugin for its name or
// description.  Calls to other properties are forwarded to NPP_GetValue.
// Declaration: npupp.h
// Documentation URL: https://developer.mozilla.org/en/NP_GetValue
NPError NP_GetValue(NPP instance, NPPVariable variable, void* value) {
  extern NPError NPP_GetValue(NPP instance, NPPVariable variable, void* value);
  NPError err = NPERR_NO_ERROR;
  switch (variable) {
    case NPPVpluginNameString:
      *(static_cast<const char**>(value)) = "Pi Generator";
      break;
    case NPPVpluginDescriptionString:
      *(static_cast<const char**>(value)) =
          "Compute pi using a stochastic method.";
      break;
    case NPPVpluginNeedsXEmbed:
      *(static_cast<NPBool*>(value)) = TRUE;
      break;
    default:
      err = NPP_GetValue(instance, variable, value);
      break;
  }
  return err;
}

// Called by the browser to get the MIME Type for this plugin.
// Note that this MIME type has to match the type in the <embed> tag used to
// load the develop version of the module.  See the Mozilla docs for more info
// on the MIME type format:
//   https://developer.mozilla.org/En/NP_GetMIMEDescription
// Declaration: npupp.h
// Documentation URL: https://developer.mozilla.org/En/NP_GetMIMEDescription
const char* NP_GetMIMEDescription(void) {
  return "pepper-application/pi-generator:nexe:Pi Generator example";
}
#endif

}  // extern "C"
