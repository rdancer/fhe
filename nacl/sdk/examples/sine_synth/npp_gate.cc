// Copyright 2010 The Native Client Authors. All rights reserved.
// Use of this source code is governed by a BSD-style license that can
// be found in the LICENSE file.

#include <assert.h>
#include <stdio.h>
#include <string.h>
#if defined (__native_client__)
#include <nacl/npapi_extensions.h>
#include <nacl/npupp.h>
#else
// Building a trusted plugin for debugging.
#include "third_party/npapi/bindings/npapi_extensions.h"
#include "third_party/npapi/bindings/nphostapi.h"
#endif
#include <new>

#include "examples/sine_synth/sine_synth.h"

using sine_synth::SineSynth;

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
  extern void InitializePepperExtensions(NPP instance);
  if (instance == NULL) {
    return NPERR_INVALID_INSTANCE_ERROR;
  }

  InitializePepperExtensions(instance);

  SineSynth* sine_synth = new(std::nothrow) SineSynth(instance);
  if (sine_synth == NULL) {
    return NPERR_OUT_OF_MEMORY_ERROR;
  }

  instance->pdata = sine_synth;
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
  if (instance == NULL) {
    return NPERR_INVALID_INSTANCE_ERROR;
  }

  SineSynth* sine_synth = static_cast<SineSynth*>(instance->pdata);
  if (sine_synth != NULL) {
    delete sine_synth;
  }
  return NPERR_NO_ERROR;
}

// NPP_GetScriptableInstance retruns the NPObject pointer that corresponds to
// NPPVpluginScriptableNPObject queried by NPP_GetValue() from the browser.
// Helper function for NPP_GetValue to create this plugin's NPObject.
// |instance| is this plugin's representation in the browser.  Returns the new
// NPObject or NULL.
// Declaration: local
// Documentation URL: N/A (not actually an NPAPI function)
NPObject* NPP_GetScriptableInstance(NPP instance) {
  if (instance == NULL) {
    return NULL;
  }

  NPObject* object = NULL;
  SineSynth* sine_synth = static_cast<SineSynth*>(instance->pdata);
  if (sine_synth) {
    object = sine_synth->GetScriptableObject();
  }
  return object;
}

// Implemented so the browser can get a scriptable instance from this plugin.
// Declaration: npapi.h
// Documentation URL: https://developer.mozilla.org/en/NPP_GetValue
NPError NPP_GetValue(NPP instance, NPPVariable variable, void *value) {
  if (NPPVpluginScriptableNPObject == variable) {
    NPObject* scriptable_object = NPP_GetScriptableInstance(instance);
    if (scriptable_object == NULL)
      return NPERR_INVALID_INSTANCE_ERROR;
    *reinterpret_cast<NPObject**>(value) = scriptable_object;
    return NPERR_NO_ERROR;
  }
  return NPERR_INVALID_PARAM;
}

// |event| just took place in this plugin's window in the browser.  This
// function should return true if the event was handled, false if it was
// ignored.
// Declaration: npapi.h
// Documentation URL: https://developer.mozilla.org/en/NPP_HandleEvent
int16_t NPP_HandleEvent(NPP instance, void* event) {
  return 0;
}

// |window| contains the current state of the window in the browser.  If this
// is called, that state has probably changed recently.
// Declaration: npapi.h
// Documentation URL: https://developer.mozilla.org/en/NPP_SetWindow
NPError NPP_SetWindow(NPP instance, NPWindow* window) {
  if (instance == NULL) {
    return NPERR_INVALID_INSTANCE_ERROR;
  }
  if (window == NULL) {
    return NPERR_GENERIC_ERROR;
  }
  SineSynth* sine_synth = static_cast<SineSynth*>(instance->pdata);
  if (sine_synth != NULL) {
    return sine_synth->SetWindow(window);
  }
  return NPERR_GENERIC_ERROR;
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
  plugin_funcs->event = NPP_HandleEvent;
  plugin_funcs->getvalue = NPP_GetValue;
  return NPERR_NO_ERROR;
}

}  // extern "C"
