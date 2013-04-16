// Copyright 2010 The Native Client SDK Authors. All rights reserved.
// Use of this source code is governed by a BSD-style license that can
// be found in the LICENSE file.

#include <assert.h>
#include <boost/scoped_ptr.hpp>
#include <stdio.h>
#include <string.h>
#include <nacl/npupp.h>
#include <new>

#include "photo.h"

using photo::Photo;

// A small wrapper class that keeps some instance state, such as a pointer to
// the main application object.  This state is used to call the initialize
// methods on the application object during the first call to SetWindow(),
// which is when the browser linkage is fully established and it's OK to make
// NPN_* calls.
class InstanceContext {
 public:
  explicit InstanceContext(NPP instance)
      : photo_(new Photo(instance)), needs_init_(true) {}
  ~InstanceContext() {}
  Photo* photo() const {
    return photo_.get();
  }
  bool needs_init() const {
    return needs_init_;
  }
  void set_needs_init(bool flag) {
    needs_init_ = flag;
  }

 private:
  boost::scoped_ptr<Photo> photo_;
  bool needs_init_;
};

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
  InstanceContext* instance_context = new InstanceContext(instance);
  if (instance_context == NULL) {
    return NPERR_OUT_OF_MEMORY_ERROR;
  }

  instance->pdata = instance_context;
  return NPERR_NO_ERROR;
}

NPError NPP_Destroy(NPP instance, NPSavedData** save) {
  if (instance == NULL) {
    return NPERR_INVALID_INSTANCE_ERROR;
  }

  InstanceContext* instance_context =
      static_cast<InstanceContext*>(instance->pdata);
  if (instance_context != NULL) {
    delete instance_context;
  }
  return NPERR_NO_ERROR;
}

// NPP_GetScriptableInstance retruns the NPObject pointer that corresponds to
// NPPVpluginScriptableNPObject queried by NPP_GetValue() from the browser.
NPObject* NPP_GetScriptableInstance(NPP instance) {
  if (instance == NULL) {
    return NULL;
  }

  InstanceContext* instance_context =
      static_cast<InstanceContext*>(instance->pdata);
  if (!instance_context)
    return NULL;
  return instance_context->photo()->GetScriptableObject(instance);
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
  InstanceContext* instance_context =
      static_cast<InstanceContext*>(instance->pdata);
  if (!instance_context)
    return 0;
  return instance_context->photo()->HandleEvent(
      *reinterpret_cast<const NPPepperEvent*>(event)) ? 1 : 0;
}

NPError NPP_SetWindow(NPP instance, NPWindow* window) {
  if (instance == NULL) {
    return NPERR_INVALID_INSTANCE_ERROR;
  }
  if (window == NULL) {
    return NPERR_GENERIC_ERROR;
  }
  InstanceContext* instance_context =
      static_cast<InstanceContext*>(instance->pdata);
  if (instance_context) {
    if (instance_context->needs_init()) {
      instance_context->photo()->ModuleDidLoad();
      instance_context->set_needs_init(false);
    }
    return instance_context->photo()->SetWindow(window->width, window->height) ?
        NPERR_NO_ERROR : NPERR_GENERIC_ERROR;
  }
  return NPERR_GENERIC_ERROR;
}

NPError NPP_NewStream(NPP instance,
                      NPMIMEType type,
                      NPStream* stream,
                      NPBool seekable,
                      uint16_t* stype) {
  *stype = NP_ASFILEONLY;
  return NPERR_NO_ERROR;
}


NPError NPP_DestroyStream(NPP instance, NPStream* stream, NPReason reason) {
  return NPERR_NO_ERROR;
}

extern "C" int NaClFile(const char *pathname, int fd, int size, int total);

void NPP_StreamAsFile(NPP instance, NPStream* stream, const char* fname) {
  // Called when NPN_GetURL() has finished downloading the file.
  InstanceContext* instance_context =
      static_cast<InstanceContext*>(instance->pdata);
  if (!instance_context)
    return;
  // This is a very strange hack that I don't fully understand.  It appears
  // that in our NPRuntime, |fname| is actually some kind of unique ID that
  // represents a file descriptor.  The NaCl file machinery uses this fd to
  // mmap the underlying file.
  const int fd = *(reinterpret_cast<const int*>(fname));
  if (NaClFile(stream->url, fd, stream->end, 1) == 0) {
    instance_context->photo()->URLDidDownload(stream);
  }
}


int32_t NPP_Write(NPP instance,
                NPStream* stream,
                int32_t offset,
                int32_t len,
                void* buffer) {
  return 0;
}


int32_t NPP_WriteReady(NPP instance, NPStream* stream) {
  return 0;
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
  plugin_funcs->newstream = NPP_NewStream;
  plugin_funcs->destroystream = NPP_DestroyStream;
  plugin_funcs->asfile = NPP_StreamAsFile;
  plugin_funcs->writeready = NPP_WriteReady;
  plugin_funcs->write = NPP_Write;
  return NPERR_NO_ERROR;
}
}  // extern "C"
