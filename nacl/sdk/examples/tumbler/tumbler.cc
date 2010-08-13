// Copyright 2010 The Native Client SDK Authors. All rights reserved.
// Use of this source code is governed by a BSD-style license that can
// be found in the LICENSE file.

#include "examples/tumbler/tumbler.h"

#include <assert.h>
#include <string.h>

#include "examples/tumbler/cube.h"
#include "examples/tumbler/scripting_bridge.h"

extern NPDevice* NPN_AcquireDevice(NPP instance, NPDeviceID device);

namespace {

// Callback given to the browser for things like context repaint and draw
// notifications.

void Draw3DCallback(void* data) {
    static_cast<tumbler::Tumbler*>(data)->DrawSelf();
}

}  // namespace


using tumbler::ScriptingBridge;

namespace tumbler {

static const int32_t kCommandBufferSize = 1024 * 1024;

Tumbler::Tumbler(NPP npp)
    : npp_(npp),
      scriptable_object_(NULL),
      device3d_(NULL),
      pgl_context_(NULL),
      cube_(NULL) {
  memset(&context3d_, 0, sizeof(context3d_));
  ScriptingBridge::InitializeIdentifiers();
}

Tumbler::~Tumbler() {
  if (scriptable_object_) {
    NPN_ReleaseObject(scriptable_object_);
  }
  // Destroy the cube view while GL context is current.
  pglMakeCurrent(pgl_context_);
  delete cube_;
  pglMakeCurrent(PGL_NO_CONTEXT);

  DestroyContext();
}

NPObject* Tumbler::GetScriptableObject() {
  if (scriptable_object_ == NULL) {
    scriptable_object_ =
      NPN_CreateObject(npp_, &ScriptingBridge::np_class);
  }
  if (scriptable_object_) {
    NPN_RetainObject(scriptable_object_);
  }
  return scriptable_object_;
}

NPError Tumbler::SetWindow(const NPWindow& window) {
  if (!pgl_context_) {
    CreateContext();
  }
  if (!pglMakeCurrent(pgl_context_))
    return NPERR_INVALID_INSTANCE_ERROR;
  if (cube_ == NULL) {
    cube_ = new Cube();
    cube_->PrepareOpenGL();
  }
  cube_->Resize(window.width, window.height);
  PostRedrawNotification();
  return NPERR_NO_ERROR;
}

void Tumbler::PostRedrawNotification() {
  NPN_PluginThreadAsyncCall(npp_, Draw3DCallback, this);
}

bool Tumbler::DrawSelf() {
  if (cube_ == NULL)
    return false;

  if (!pglMakeCurrent(pgl_context_) && pglGetError() == PGL_CONTEXT_LOST) {
    DestroyContext();
    CreateContext();
    pglMakeCurrent(pgl_context_);
    delete cube_;
    cube_ = new Cube();
    cube_->PrepareOpenGL();
  }

  cube_->Draw();
  pglSwapBuffers();
  pglMakeCurrent(PGL_NO_CONTEXT);
  return true;
}

bool Tumbler::GetCameraOrientation(float* orientation) const {
  if (cube_ != NULL) {
    cube_->GetOrientation(orientation);
    return true;
  }
  return false;
}

bool Tumbler::SetCameraOrientation(const float* orientation) {
  if (cube_ != NULL) {
    cube_->SetOrientation(orientation);
    PostRedrawNotification();
    return true;
  }
  return false;
}

void Tumbler::CreateContext() {
  if (pgl_context_ != NULL)
    return;
  // Create and initialize a 3D context.
  device3d_ = NPN_AcquireDevice(npp_, NPPepper3DDevice);
  assert(NULL != device3d_);
  NPDeviceContext3DConfig config;
  config.commandBufferSize = kCommandBufferSize;
  device3d_->initializeContext(npp_, &config, &context3d_);

  // Create a PGL context.
  pgl_context_ = pglCreateContext(npp_, device3d_, &context3d_);
}

void Tumbler::DestroyContext() {
  if (pgl_context_ == NULL)
    return;
  pglDestroyContext(pgl_context_);
  pgl_context_ = NULL;

  device3d_->destroyContext(npp_, &context3d_);
}

}  // namespace pinpapi_bridge
