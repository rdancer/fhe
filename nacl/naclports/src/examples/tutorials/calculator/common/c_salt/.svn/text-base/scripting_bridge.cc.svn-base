// Copyright 2010 The Native Client SDK Authors. All rights reserved.
// Use of this source code is governed by a BSD-style license that can
// be found in the LICENSE file.

#include "c_salt/scripting_bridge.h"

#include <assert.h>
#include <string>

#include "c_salt/callback.h"
#include "c_salt/module.h"
#include "c_salt/type.h"

namespace c_salt {

ScriptingBridge::ScriptingBridge(NPP npp) : npp_(npp) {
}

ScriptingBridge::~ScriptingBridge() {
}

bool ScriptingBridge::AddMethodNamed(const char* method_name,
                                     MethodCallbackExecutor* method) {
  if (method_name == NULL || method == NULL)
    return false;
  NPIdentifier method_id = NPN_GetStringIdentifier(method_name);
  method_dictionary_.insert(
      std::pair<NPIdentifier, MethodCallbackExecutor*>(method_id, method));
  return true;
}

bool ScriptingBridge::AddPropertyNamed(
    const char* property_name,
    PropertyAccessorCallbackExecutor* property_accessor,
    PropertyMutatorCallbackExecutor* property_mutator) {
  if (property_name == NULL || property_accessor == NULL)
    return false;
  NPIdentifier property_id = NPN_GetStringIdentifier(property_name);
  property_accessor_dictionary_.insert(
      std::pair<NPIdentifier,
                PropertyAccessorCallbackExecutor*>(property_id, property_accessor));
  if (property_mutator) {
    property_mutator_dictionary_.insert(
        std::pair<NPIdentifier,
                  PropertyMutatorCallbackExecutor*>(property_id, property_mutator));
  }
  return true;
}

bool ScriptingBridge::HasMethod(NPIdentifier name) {
  MethodDictionary::const_iterator i;
  i = method_dictionary_.find(name);
  return i != method_dictionary_.end();
}

bool ScriptingBridge::HasProperty(NPIdentifier name) {
  // Only look for the "get" property - there is never a "set" without a "get",
  // but there can be "get" without "set" (read-only).
  PropertyAccessorDictionary::const_iterator i;
  i = property_accessor_dictionary_.find(name);
  return i != property_accessor_dictionary_.end();
}

bool ScriptingBridge::GetProperty(NPIdentifier name,
                                  NPVariant *return_value) {
  VOID_TO_NPVARIANT(*return_value);

  PropertyAccessorDictionary::iterator i;
  i = property_accessor_dictionary_.find(name);
  if (i != property_accessor_dictionary_.end()) {
    return (*i->second).Execute(this, return_value);
  }
  return true;
}

bool ScriptingBridge::SetProperty(NPIdentifier name, const NPVariant* value) {
  PropertyMutatorDictionary::iterator i;
  i = property_mutator_dictionary_.find(name);
  if (i != property_mutator_dictionary_.end()) {
    return (*i->second).Execute(this, value);
  }
  return true;
}

bool ScriptingBridge::RemoveProperty(NPIdentifier name) {
  return false;  // Not implemented.
}

bool ScriptingBridge::InvokeDefault(const NPVariant* args,
                                    uint32_t arg_count,
                                    NPVariant* return_value) {
  return false;  // Not implemented.
}

bool ScriptingBridge::Invoke(NPIdentifier name,
                             const NPVariant* args,
                             uint32_t arg_count,
                             NPVariant* return_value) {
  MethodDictionary::iterator i;
  i = method_dictionary_.find(name);
  if (i != method_dictionary_.end()) {
    return (*i->second).Execute(this, args, arg_count, return_value);
  }
  return false;
}

void ScriptingBridge::Invalidate() {
  // Not implemented.
}

// These are the function wrappers that the browser calls.

NPObject* Allocate(NPP npp, NPClass* npclass) {
  return new ScriptingBridge(npp);
}

void Deallocate(NPObject* object) {
  delete static_cast<ScriptingBridge*>(object);
}

void Invalidate(NPObject* object) {
  return static_cast<ScriptingBridge*>(object)->Invalidate();
}

bool HasMethod(NPObject* object, NPIdentifier name) {
  return static_cast<ScriptingBridge*>(object)->HasMethod(name);
}

bool Invoke(NPObject* object, NPIdentifier name,
            const NPVariant* args, uint32_t arg_count,
            NPVariant* return_value) {
  return static_cast<ScriptingBridge*>(object)->Invoke(
      name, args, arg_count, return_value);
}

bool InvokeDefault(NPObject* object, const NPVariant* args, uint32_t arg_count,
                   NPVariant* return_value) {
  return static_cast<ScriptingBridge*>(object)->InvokeDefault(
      args, arg_count, return_value);
}

bool HasProperty(NPObject* object, NPIdentifier name) {
  return static_cast<ScriptingBridge*>(object)->HasProperty(name);
}

bool GetProperty(NPObject* object, NPIdentifier name, NPVariant* result) {
  return static_cast<ScriptingBridge*>(object)->GetProperty(name, result);
}

bool SetProperty(NPObject* object, NPIdentifier name, const NPVariant* value) {
  return static_cast<ScriptingBridge*>(object)->SetProperty(name, value);
}

bool RemoveProperty(NPObject* object, NPIdentifier name) {
  return static_cast<ScriptingBridge*>(object)->RemoveProperty(name);
}

}  // namespace c_salt

NPClass c_salt::ScriptingBridge::bridge_class = {
  NP_CLASS_STRUCT_VERSION,
  c_salt::Allocate,
  c_salt::Deallocate,
  c_salt::Invalidate,
  c_salt::HasMethod,
  c_salt::Invoke,
  c_salt::InvokeDefault,
  c_salt::HasProperty,
  c_salt::GetProperty,
  c_salt::SetProperty,
  c_salt::RemoveProperty
};
