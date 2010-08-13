// Copyright 2010 The Native Client Authors. All rights reserved.
// Use of this source code is governed by a BSD-style license that can
// be found in the LICENSE file.

#ifndef EXAMPLES_PI_GENERATOR_H_
#define EXAMPLES_PI_GENERATOR_H_

#include <pthread.h>
#if defined(__native_client__)
#include <nacl/nacl_npapi.h>
#include <nacl/npapi_extensions.h>
#else
// Building a trusted plugin for debugging.
#include "third_party/npapi/bindings/npapi.h"
#include "third_party/npapi/bindings/npapi_extensions.h"
#include "third_party/npapi/bindings/nphostapi.h"
#endif
#include <map>

namespace pi_generator {

class PiGenerator {
 public:
  explicit PiGenerator(NPP npp);
  ~PiGenerator();

  NPObject* GetScriptableObject();
  NPError SetWindow(NPWindow* window);
  bool Paint();
  bool quit() const {
    return quit_;
  }
  double pi() const {
    return pi_;
  }
  void* pixels() const {
    return context2d_.region;
  }
  int width() const {
    return window_ ? window_->width : 0;
  }
  int height() const {
    return window_ ? window_->height : 0;
  }

 private:
  // Create and initialize the 2D context used for drawing.
  void CreateContext();
  // Destroy the 2D drawing context.
  void DestroyContext();
  bool IsContextValid() {
    return device2d_ != NULL;
  }

  NPP       npp_;
  NPObject* scriptable_object_;  // strong reference

  NPWindow* window_;
  NPDevice* device2d_;  // The PINPAPI 2D device.
  NPDeviceContext2D context2d_;  // The PINPAPI 2D drawing context.

  bool quit_;
  pthread_t thread_;
  double pi_;

  static void* pi(void* param);
};

}  // namespace pi_generator

#endif  // EXAMPLES_PI_GENERATOR_H_
