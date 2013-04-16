// Copyright 2010 The Native Client SDK Authors. All rights reserved.
// Use of this source code is governed by a BSD-style license that can
// be found in the LICENSE file.

#include "c_salt/module.h"

#include <assert.h>

#include "c_salt/scripting_bridge.h"

namespace c_salt {

// Must be implemented in the sub-class!
// Module* Module::CreateModule() {}

Module::~Module() {
  if (scripting_bridge_) {
    assert((static_cast<NPObject*>(scripting_bridge_))->referenceCount == 1);
    NPN_ReleaseObject(scripting_bridge_);
    scripting_bridge_ = NULL;
  }
}

void Module::InitializeMethods(ScriptingBridge* bridge) {
}

void Module::InitializeProperties(ScriptingBridge* bridge) {
}

void Module::SetWindow(NPP instance, int width, int height) {
}

NPObject* Module::CreateScriptingBridge(NPP instance) {
  if (!scripting_bridge_) {
    // This is a synchronous call, and actually returns a ScriptingBridge
    // via the bridge_class function table.ß
    scripting_bridge_ = static_cast<c_salt::ScriptingBridge*>(
        NPN_CreateObject(instance, &c_salt::ScriptingBridge::bridge_class));
    InitializeMethods(scripting_bridge_);
    InitializeProperties(scripting_bridge_);
  }
  return NPN_RetainObject(scripting_bridge_);
}

}  // namespace c_salt
