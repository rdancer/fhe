// Copyright 2010 The Native Client SDK Authors. All rights reserved.
// Use of this source code is governed by a BSD-style license that can
// be found in the LICENSE file.

#ifndef EXAMPLES_TUMBLER_TUMBLER_H_
#define EXAMPLES_TUMBLER_TUMBLER_H_

#include <pthread.h>
#if defined(__native_client__)
#include <nacl/nacl_npapi.h>
#include <nacl/npapi_extensions.h>
#include <nacl/npupp.h>
#include <nacl/npruntime.h>
#include <pgl/pgl.h>
#else
// Building a trusted plugin for debugging.
#include "third_party/include/pgl/pgl.h"
#include "third_party/npapi/bindings/npapi.h"
#include "third_party/npapi/bindings/npapi_extensions.h"
#include "third_party/npapi/bindings/nphostapi.h"
#include "third_party/npapi/bindings/npruntime.h"
#endif

#include <map>

#include "examples/tumbler/basic_macros.h"

namespace tumbler {

class Cube;

class Tumbler {
 public:
  explicit Tumbler(NPP npp);
  
  // The dtor makes the 3D context current before deleting the cube view, then
  // destroys the 3D context both in the module and in the browser.
  ~Tumbler();

  NPObject* GetScriptableObject();

  // SetWindow is called by the browser when the window is created, moved,
  // sized, or destroyed.
  NPError SetWindow(const NPWindow& window);

  // Called to post a notification that the module needs to be redrawn by the
  // browser.
  void PostRedrawNotification();

  // Called to draw the contents of the module's browser area.
  bool DrawSelf();

  // Accessor/mutators for the camera orientation in |cube_|.
  bool GetCameraOrientation(float* orientation) const;
  bool SetCameraOrientation(const float* orientation);

 private:
  // Create a 3D context both in the module and in the browser.  CreateContext()
  // used both PGL and Pepper to establish the 3D context.
  void CreateContext();

  // Tear down the 3D context.
  void DestroyContext();

  // TODO(dspringer): Once we get smart pointers in the Native Client SDK,
  // all the following pointers should become scoped_ptr<> types.
  NPP npp_;
  NPObject* scriptable_object_;
  NPDevice* device3d_;
  NPDeviceContext3D context3d_;
  PGLContext pgl_context_;
  Cube* cube_;

  DISALLOW_IMPLICIT_CONSTRUCTORS(Tumbler);
};

}  // namespace tumbler

#endif  // EXAMPLES_TUMBLER_TUMBLER_H_
