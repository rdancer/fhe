// Copyright 2010 The Native Client SDK Authors. All rights reserved.
// Use of this source code is governed by a BSD-style license that can
// be found in the LICENSE file.

#ifndef CALCULATOR_C_SALT_MODULE_H_
#define CALCULATOR_C_SALT_MODULE_H_

#if defined(__native_client__)
#include <nacl/nacl_npapi.h>
#include <nacl/npruntime.h>
#else
// Building a trusted plugin for debugging.
#include "third_party/npapi/bindings/npapi.h"
#include "third_party/npapi/bindings/npruntime.h"
#endif

#include "c_salt/basic_macros.h"

namespace c_salt {

class ScriptingBridge;

// The main class for the Native Client module.  Subclassers must implement the
// CreateModule() factory method.  An instance of this object is owned by the
// ScriptingBridge.

class Module {
 public:
  // Factory method must be implemented in the subclass.
  static Module* CreateModule();

  Module() : scripting_bridge_(NULL) {}
  virtual ~Module();

  // Called during initialization to publish the module's method names that
  // can be called from JavaScript.
  virtual void InitializeMethods(ScriptingBridge* bridge);
  
  // Called during initialization to publish the module's properties that can
  // be called from JavaScript.
  virtual void InitializeProperties(ScriptingBridge* bridge);

  // Called when there is a valid browser window for rendering.
  virtual void SetWindow(NPP instance, int width, int height);

  // Called when the browser wants an object that comforms to the scripting
  // protocol.
  virtual NPObject* CreateScriptingBridge(NPP instance);

  // Accessor for the internal scripting bridge object.
  ScriptingBridge* scripting_bridge() const {
    return scripting_bridge_;
  }

 private:
  // TODO(dspringer): this should be a smart (scoped?) pointer.
  ScriptingBridge* scripting_bridge_;
  DISALLOW_COPY_AND_ASSIGN(Module);
};

}  // namespace c_salt

#endif  // CALCULATOR_C_SALT_MODULE_H_