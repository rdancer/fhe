// Copyright 2010 The Native Client Authors. All rights reserved.
// Use of this source code is governed by a BSD-style license that can
// be found in the LICENSE file.

#include "examples/pi_generator/pi_generator.h"

#include <assert.h>
#include <math.h>
#include <stdio.h>
#include <stdlib.h>
#include <string.h>

#if defined(__native_client__)
#include <nacl/nacl_imc.h>
#include <nacl/nacl_npapi.h>
#include <nacl/npapi_extensions.h>
#include <nacl/npruntime.h>
#else
// Building the develop version.
#include "third_party/npapi/bindings/npapi.h"
#include "third_party/npapi/bindings/npapi_extensions.h"
#include "third_party/npapi/bindings/nphostapi.h"
#endif

#include "examples/pi_generator/scripting_bridge.h"

extern NPDevice* NPN_AcquireDevice(NPP instance, NPDeviceID device);

using pi_generator::ScriptingBridge;

// This is called by the brower when the 2D context has been flushed to the
// browser window.
void FlushCallback(NPP instance, NPDeviceContext* context,
                   NPError err, void* user_data) {
}

namespace pi_generator {

PiGenerator::PiGenerator(NPP npp)
    : npp_(npp),
      scriptable_object_(NULL),
      window_(NULL),
      device2d_(NULL),
      quit_(false),
      thread_(0),
      pi_(0.0) {
  ScriptingBridge::InitializeIdentifiers();
}

PiGenerator::~PiGenerator() {
  quit_ = true;
  if (thread_) {
    pthread_join(thread_, NULL);
  }
  if (scriptable_object_) {
    NPN_ReleaseObject(scriptable_object_);
  }
  DestroyContext();
}

NPObject* PiGenerator::GetScriptableObject() {
  if (scriptable_object_ == NULL) {
    scriptable_object_ =
      NPN_CreateObject(npp_, &ScriptingBridge::np_class);
  }
  if (scriptable_object_) {
    NPN_RetainObject(scriptable_object_);
  }
  return scriptable_object_;
}

NPError PiGenerator::SetWindow(NPWindow* window) {
  if (!window)
    return NPERR_NO_ERROR;
  if (!IsContextValid())
    CreateContext();
  if (!IsContextValid())
    return NPERR_GENERIC_ERROR;
  // Clear the 2D drawing context.
  pthread_create(&thread_, NULL, pi, this);
  window_ = window;
  return Paint() ? NPERR_NO_ERROR : NPERR_GENERIC_ERROR;
}

bool PiGenerator::Paint() {
  if (IsContextValid()) {
    NPDeviceFlushContextCallbackPtr callback =
        reinterpret_cast<NPDeviceFlushContextCallbackPtr>(&FlushCallback);
    device2d_->flushContext(npp_, &context2d_, callback, NULL);
    return true;
  }
  return false;
}

void PiGenerator::CreateContext() {
  if (IsContextValid())
    return;
  device2d_ = NPN_AcquireDevice(npp_, NPPepper2DDevice);
  assert(IsContextValid());
  memset(&context2d_, 0, sizeof(context2d_));
  NPDeviceContext2DConfig config;
  NPError init_err = device2d_->initializeContext(npp_, &config, &context2d_);
  assert(NPERR_NO_ERROR == init_err);
}

void PiGenerator::DestroyContext() {
  if (!IsContextValid())
    return;
  device2d_->destroyContext(npp_, &context2d_);
}

// pi() estimates Pi using Monte Carlo method and it is executed by a separate
// thread created in SetWindow(). pi() puts kMaxPointCount points inside the
// square whose length of each side is 1.0, and calculates the ratio of the
// number of points put inside the inscribed quadrant divided by the total
// number of random points to get Pi/4.
void* PiGenerator::pi(void* param) {
  const int kMaxPointCount = 1000000000;  // The total number of points to put.
  const uint32_t kOpaqueColorMask = 0xff000000;  // Opaque pixels.
  const uint32_t kRedMask = 0xff0000;
  const uint32_t kBlueMask = 0xff;
  const unsigned kRedShift = 16;
  const unsigned kBlueShift = 0;
  int count = 0;  // The number of points put inside the inscribed quadrant.
  unsigned int seed = 1;
  PiGenerator* pi_generator = static_cast<PiGenerator*>(param);
  uint32_t* pixel_bits = static_cast<uint32_t*>(pi_generator->pixels());
  srand(seed);
  for (int i = 1; i <= kMaxPointCount && !pi_generator->quit(); ++i) {
    double x = static_cast<double>(rand_r(&seed)) / RAND_MAX;
    double y = static_cast<double>(rand_r(&seed)) / RAND_MAX;
    double distance = sqrt(x * x + y * y);
    int px = x * pi_generator->width();
    int py = (1.0 - y) * pi_generator->height();
    uint32_t color = pixel_bits[pi_generator->width() * py + px];
    if (distance < 1.0) {
      // Set color to blue.
      ++count;
      pi_generator->pi_ = 4.0 * count / i;
      color += 4 << kBlueShift;
      color &= kBlueMask;
    } else {
      // Set color to red.
      color += 4 << kRedShift;
      color &= kRedMask;
    }
    pixel_bits[pi_generator->width() * py + px] = color | kOpaqueColorMask;
  }
  return 0;
}

}  // namespace pi_generator
