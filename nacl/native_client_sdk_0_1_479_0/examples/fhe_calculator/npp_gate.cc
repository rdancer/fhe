/* npn_bridge.cc -- NPAPI interface for calls: browser -> NaCl */

/*
 * Copyright © 2008 The Native Client Authors
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

// Copyright 2008 The Native Client Authors. All rights reserved.
// Use of this source code is governed by a BSD-style license that can
// be found in the LICENSE file.


#include <stdbool.h>
#include <stdio.h>
#include <stdlib.h>
#include <string.h>

#if defined(__native_client__)
#include <nacl/nacl_npapi.h>
#include <nacl/npupp.h>
#else
// Building a development version.
#include "third_party/npapi/bindings/npapi.h"
#include "third_party/npapi/bindings/nphostapi.h"
#endif

struct FheCalculator {
  NPP npp;
  NPObject *npobject;
};

// This file implements functions that the plugin is expected to implement so
// that the browser can all them.  All of them are required to be implemented
// regardless of whether this is a trusted or untrusted build of the module.


// Called after NP_Initialize with a Plugin Instance Pointer and context
// information for the plugin instance that is being allocated.
// Declaration: npapi.h
// Documentation URL: https://developer.mozilla.org/en/NPP_New
NPError NPP_New(NPMIMEType mime_type,
                NPP instance,
                uint16_t mode,
                int16_t argc,
                char* argn[],
                char* argv[],
                NPSavedData* saved) {
  if (instance == NULL)
    return NPERR_INVALID_INSTANCE_ERROR;

  struct FheCalculator *fhe_calculator = NULL;
  fhe_calculator = new FheCalculator;
  fhe_calculator->npp = instance;
  fhe_calculator->npobject = NULL;

  instance->pdata = fhe_calculator;
  return NPERR_NO_ERROR;
}

// Called when a Plugin |instance| is being deleted by the browser.  This
// function should clean up any information allocated by NPP_New but not
// NP_Initialize.  Use |save| to store any information that should persist but
// note that browser may choose to throw it away.
// In the NaCl module, NPP_Destroy is called from NaClNP_MainLoop().
// Declaration: npapi.h
// Documentation URL: https://developer.mozilla.org/en/NPP_Destroy
NPError NPP_Destroy(NPP instance, NPSavedData** save) {
  if (NULL == instance)
    return NPERR_INVALID_INSTANCE_ERROR;

  // free plugin
  if (NULL != instance->pdata) {
    FheCalculator* fhe_calculator = static_cast<FheCalculator*>(instance->pdata);
    delete fhe_calculator;
    instance->pdata = NULL;
  }
  return NPERR_NO_ERROR;
}

// NPP_GetScriptableInstance returns the NPObject pointer that corresponds to
// NPPVpluginScriptableNPObject queried by NPP_GetValue() from the browser.
// Helper function for NPP_GetValue to create this plugin's NPObject.
// |instance| is this plugin's representation in the browser.  Returns the new
// NPObject or NULL.
// Declaration: local
// Documentation URL: N/A (not actually an NPAPI function)
NPObject *NPP_GetScriptableInstance(NPP instance) {
  struct FheCalculator* fhe_calculator;

  extern NPClass *GetNPSimpleClass();

  if (NULL == instance) {
    return NULL;
  }
  fhe_calculator = static_cast<FheCalculator*>(instance->pdata);
  if (NULL == fhe_calculator->npobject) {
    fhe_calculator->npobject = NPN_CreateObject(instance, GetNPSimpleClass());
  }
  if (NULL != fhe_calculator->npobject) {
    NPN_RetainObject(fhe_calculator->npobject);
  }
  return fhe_calculator->npobject;
}

// Implemented so the browser can get a scriptable instance from this plugin.
// Declaration: npapi.h
// Documentation URL: https://developer.mozilla.org/en/NPP_GetValue
NPError NPP_GetValue(NPP instance, NPPVariable variable, void* ret_value) {
  if (NPPVpluginScriptableNPObject == variable) {
    void** v = reinterpret_cast<void**>(ret_value);
    *v = NPP_GetScriptableInstance(instance);
    return NPERR_NO_ERROR;
  } else {
    return NPERR_GENERIC_ERROR;
  }
}

// |window| contains the current state of the window in the browser.  If this
// is called, that state has probably changed recently.
// Declaration: npapi.h
// Documentation URL: https://developer.mozilla.org/en/NPP_SetWindow
NPError NPP_SetWindow(NPP instance, NPWindow* window) {
  return NPERR_NO_ERROR;
}

extern "C" {
// When the browser calls NP_Initialize the plugin needs to return a list
// of functions that have been implemented so that the browser can
// communicate with the plugin.  This function populates that list,
// |plugin_funcs|, with pointers to the functions.
NPError InitializePluginFunctions(NPPluginFuncs* plugin_funcs) {
  memset(plugin_funcs, 0, sizeof(*plugin_funcs));
  plugin_funcs->version = NPVERS_HAS_PLUGIN_THREAD_ASYNC_CALL;
  plugin_funcs->size = sizeof(*plugin_funcs);
  plugin_funcs->newp = NPP_New;
  plugin_funcs->destroy = NPP_Destroy;
  plugin_funcs->setwindow = NPP_SetWindow;
  plugin_funcs->getvalue = NPP_GetValue;
  return NPERR_NO_ERROR;
}

}  // extern "C"
