/* fhe_calculator_module.cc -- FHE Calculator container (init and shutdown) */

/*
 * Copyright © 2010 The Native Client SDK Authors
 * Copyright © 2010 Jan Minář <rdancer@rdancer.org>
 *
 * This program is free software; you can redistribute it and/or modify
 * it under the terms of the GNU General Public License version 2 (two),
 * as published by the Free Software Foundation.
 *
 * This program is distributed in the hope that it will be useful,
 * but WITHOUT ANY WARRANTY; without even the implied warranty of
 * MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
 * GNU General Public License for more details.
 *
 * You should have received a copy of the GNU General Public License along
 * with this program; if not, write to the Free Software Foundation, Inc.,
 * 51 Franklin Street, Fifth Floor, Boston, MA 02110-1301 USA.
 */

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


// Some platforms, including Native Client use the two-parameter version of
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
  extern NPError NPP_GetValue(NPP instance,
                              NPPVariable variable,
                              void* value);

  NPError err = NPERR_NO_ERROR;
  switch (variable) {
    case NPPVpluginNameString:
      *(static_cast<const char**>(value)) = "FHE Calculator";
      break;
    case NPPVpluginDescriptionString:
      *(static_cast<const char**>(value))
	    = "Fully Homomorphic Encryption: Calculator Demo";
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
  return "pepper-application/fhe_calculator:nexe:FHE Calculator";
}
#endif

}  // extern "C"
