// Copyright 2008 The Native Client SDK Authors. All rights reserved.
// Use of this source code is governed by a BSD-style license that can
// be found in the LICENSE file.


#include <stdbool.h>
#include <stdio.h>
#include <stdlib.h>
#include <string.h>

#include <nacl/nacl_npapi.h>
#include <nacl/npupp.h>

#include <examples/scriptable/matrix/matrix_comp.h>
#include <examples/scriptable/matrix/scripting_bridge.h>

struct NaClContext {
  NPP npp;
  NPObject *npobject;
};

// This file implements functions that the plugin is expected to implement so
// that the browser can call them.


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
  printf("NPP_New was called!\n");
  fflush(stdout);
  if (instance == NULL)
    return NPERR_INVALID_INSTANCE_ERROR;

  struct NaClContext *nacl_context = NULL;
  nacl_context = new NaClContext;
  nacl_context->npp = instance;
  nacl_context->npobject = NULL;

  instance->pdata = nacl_context;
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
  printf("NPP_Destroy was called!\n");
  fflush(stdout);
  if (NULL == instance)
    return NPERR_INVALID_INSTANCE_ERROR;

  // Free plugin.
  if (NULL != instance->pdata) {
    NaClContext* nacl_context = static_cast<NaClContext*>(instance->pdata);
    if (NULL != nacl_context->npobject) {
      NPN_ReleaseObject(nacl_context->npobject);
      nacl_context->npobject = NULL;
    }
    delete nacl_context;
    instance->pdata = NULL;
  }
  return NPERR_NO_ERROR;
}

// NPP_GetScriptableInstance returns the NPObject pointer that corresponds to
// NPPVpluginScriptableNPObject queried by NPP_GetValue() from the browser.
// |instance| is this plugin's representation in the browser.  Returns the new
// NPObject or NULL.
// Declaration: local
// Documentation URL: N/A (not actually an NPAPI function)
NPObject *NPP_GetScriptableInstance(NPP instance) {
  printf("NPP_GetScriptableInstance was called!\n");
  fflush(stdout);
  struct NaClContext* nacl_context;

  if (NULL == instance) {
    return NULL;
  }
  nacl_context = static_cast<NaClContext*>(instance->pdata);
  if (NULL == nacl_context->npobject) {
    nacl_context->npobject =
        NPN_CreateObject(instance,
                         ScriptingBridge::GetNPSimpleClass<MatrixCompute>());
  }
  if (NULL != nacl_context->npobject) {
    NPN_RetainObject(nacl_context->npobject);
  }
  return nacl_context->npobject;
}

// Implemented so the browser can get a scriptable instance from this plugin.
// Declaration: npapi.h
// Documentation URL: https://developer.mozilla.org/en/NPP_GetValue
NPError NPP_GetValue(NPP instance, NPPVariable variable, void* ret_value) {
  printf("NPP_GetValue was called!\n");
  fflush(stdout);
  if (NPPVpluginScriptableNPObject == variable) {
    void** v = reinterpret_cast<void**>(ret_value);
    *v = NPP_GetScriptableInstance(instance);
    return NPERR_NO_ERROR;
  } else {
    return NPERR_GENERIC_ERROR;
  }
}

// |event| just took place in this plugin's window in the browser.  This
// function should return true if the event was handled, false if it was
// ignored.
// Declaration: npapi.h
// Documentation URL: https://developer.mozilla.org/en/NPP_HandleEvent
int16_t NPP_HandleEvent(NPP instance, void* event) {
  printf("NPP_HandleEvent was called!\n");
  fflush(stdout);
  return 0;
}


// |window| contains the current state of the window in the browser.  If this
// is called, that state has probably changed recently.
// Declaration: npapi.h
// Documentation URL: https://developer.mozilla.org/en/NPP_SetWindow
NPError NPP_SetWindow(NPP instance, NPWindow* window) {
  printf("NPP_SetWindow was called!\n");
  fflush(stdout);
  return NPERR_NO_ERROR;
}

extern "C" {
// When the browser calls NP_Initialize the plugin needs to return a list
// of functions that have been implemented so that the browser can
// communicate with the plugin.  This function populates that list,
// |plugin_funcs|, with pointers to the functions.
NPError InitializePluginFunctions(NPPluginFuncs* plugin_funcs) {
  printf("InitializePluginFunctions was called!\n");
  fflush(stdout);
  memset(plugin_funcs, 0, sizeof(*plugin_funcs));
  plugin_funcs->version = NPVERS_HAS_PLUGIN_THREAD_ASYNC_CALL;
  plugin_funcs->size = sizeof(*plugin_funcs);
  plugin_funcs->newp = NPP_New;
  plugin_funcs->destroy = NPP_Destroy;
  plugin_funcs->setwindow = NPP_SetWindow;
  plugin_funcs->event = NPP_HandleEvent;
  plugin_funcs->getvalue = NPP_GetValue;
  return NPERR_GENERIC_ERROR;
  //  return NPERR_NO_ERROR;
}
}  // extern "C"
