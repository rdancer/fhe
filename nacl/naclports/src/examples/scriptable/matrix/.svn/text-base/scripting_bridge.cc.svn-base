// Copyright 2010 The Native Client SDK Authors. All rights reserved.
// Use of this source code is governed by a BSD-style license that can
// be found in the LICENSE file.

#include "examples/scriptable/matrix/scripting_bridge.h"

#include <string.h>

ScriptingBridge::ScriptingBridge(Scriptable* scriptable_instance)
    : scriptable_instance_(scriptable_instance) {
  printf("ScriptingBridge::Constructor was called!\n");
  fflush(stdout);
}

ScriptingBridge::~ScriptingBridge() {
  printf("ScriptingBridge::Destructor was called!\n");
  fflush(stdout);
  // delete scriptable_instance here so that the allocate function can be a
  // template - as opposed to the entire ScriptingBridge
  if (scriptable_instance_ != NULL) {
    delete scriptable_instance_;
  }
}

void ScriptingBridge::DeallocateCallback(NPObject* object) {
  printf("ScriptingBridge::Deallocate was called!\n");
  fflush(stdout);
  delete static_cast<ScriptingBridge*>(object);
}

bool ScriptingBridge::GetPropertyCallback(NPObject* object,
                                          NPIdentifier name,
                                          NPVariant* result) {
  printf("ScriptingBridge::GetProperty was called!\n");
  fflush(stdout);
  return ToInstance(object)->scriptable_instance_->GetProperty(name, result);
}

bool ScriptingBridge::HasMethodCallback(NPObject* object, NPIdentifier name) {
  printf("ScriptingBridge::HasMethod was called!\n");
  fflush(stdout);
  return ToInstance(object)->scriptable_instance_->HasMethod(name);
}

bool ScriptingBridge::HasPropertyCallback(NPObject* object,
                                          NPIdentifier name) {
  printf("ScriptingBridge::HasProperty was called!\n");
  fflush(stdout);
  return ToInstance(object)->scriptable_instance_->HasProperty(name);
}

void ScriptingBridge::Init() {
  printf("ScriptingBridge::Init was called!\n");
  fflush(stdout);
}

void ScriptingBridge::InvalidateCallback(NPObject* object) {
  printf("ScriptingBridge::Invalidate was called!\n");
  fflush(stdout);
  return ToInstance(object)->Invalidate();
}

void ScriptingBridge::Invalidate() {
  printf("ScriptingBridge::Invalidate was called!\n");
  fflush(stdout);
  // Not implemented.
}

bool ScriptingBridge::InvokeCallback(NPObject* object,
                                     NPIdentifier name,
                                     const NPVariant* args,
                                     uint32_t arg_count,
                                     NPVariant* result) {
  printf("ScriptingBridge::Invoke was called!\n");
  fflush(stdout);
  return ToInstance(object)->scriptable_instance_->Invoke(name,
                                                          args,
                                                          arg_count,
                                                          result);
}

bool ScriptingBridge::InvokeDefaultCallback(NPObject* object,
                                            const NPVariant* args,
                                            uint32_t arg_count,
                                            NPVariant* result) {
  printf("ScriptingBridge::InvokeDefault was called!\n");
  fflush(stdout);
  return ToInstance(object)->scriptable_instance_->InvokeDefault(args,
                                                                 arg_count,
                                                                 result);
}

bool ScriptingBridge::RemovePropertyCallback(NPObject* object,
                                             NPIdentifier name) {
  printf("ScriptingBridge::RemoveProperty was called!\n");
  fflush(stdout);
  return ToInstance(object)->scriptable_instance_->RemoveProperty(name);
}

bool ScriptingBridge::SetPropertyCallback(NPObject* object,
                                          NPIdentifier name,
                                          const NPVariant* value) {
  printf("ScriptingBridge::SetProperty was called!\n");
  fflush(stdout);
  return ToInstance(object)->scriptable_instance_->SetProperty(name, value);
}

ScriptingBridge* ScriptingBridge::ToInstance(NPObject * object) {
  return static_cast<ScriptingBridge*>(object);
}
