// Copyright 2010 The Native Client SDK Authors. All rights reserved.
// Use of this source code is governed by a BSD-style license that can
// be found in the LICENSE file.

#include <assert.h>
#include <stdio.h>
#include <string.h>
#if defined (__native_client__)
#include <nacl/npupp.h>
#else
#include "third_party/npapi/bindings/npapi.h"
#include "third_party/npapi/bindings/nphostapi.h"
#endif
#include <new>

#include "c_salt/module.h"

using c_salt::Module;
using c_salt::ScriptingBridge;

// Please refer to the Gecko Plugin API Reference for the description of
// NPP_New.
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

  Module* module_instance = Module::CreateModule();
  if (module_instance == NULL) {
    return NPERR_OUT_OF_MEMORY_ERROR;
  }
  instance->pdata = reinterpret_cast<void*>(module_instance);
  return NPERR_NO_ERROR;
}

// Please refer to the Gecko Plugin API Reference for the description of
// NPP_Destroy.
NPError NPP_Destroy(NPP instance, NPSavedData** save) {
  if (instance == NULL) {
    return NPERR_INVALID_INSTANCE_ERROR;
  }
  Module* module_instance = static_cast<Module*>(instance->pdata);
  if (module_instance != NULL) {
    delete module_instance;
  }
  return NPERR_NO_ERROR;
}

// NPP_GetScriptableInstance retruns the NPObject pointer that corresponds to
// NPPVpluginScriptableNPObject queried by NPP_GetValue() from the browser.
NPObject* NPP_GetScriptableInstance(NPP instance) {
  if (instance == NULL || instance->pdata == NULL) {
    return NULL;
  }

  Module* module_instance = static_cast<Module*>(instance->pdata);
  if (!module_instance) {
    return NULL;
  }
  return module_instance->CreateScriptingBridge(instance);
}

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

int16_t NPP_HandleEvent(NPP instance, void* event) {
  return 0;
}

NPError NPP_SetWindow(NPP instance, NPWindow* window) {
  if (instance == NULL) {
    return NPERR_INVALID_INSTANCE_ERROR;
  }
  if (window == NULL) {
    return NPERR_GENERIC_ERROR;
  }
  Module* module_instance = static_cast<Module*>(instance->pdata);
  if (!module_instance)
    return NPERR_INVALID_INSTANCE_ERROR;
  module_instance->SetWindow(instance, window->width, window->height);
  return NPERR_NO_ERROR;
}

extern "C" {

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
