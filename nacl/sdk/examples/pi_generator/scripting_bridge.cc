// Copyright 2010 The Native Client Authors. All rights reserved.
// Use of this source code is governed by a BSD-style license that can
// be found in the LICENSE file.

#include "examples/pi_generator/scripting_bridge.h"

#include "examples/pi_generator/pi_generator.h"

namespace pi_generator {

NPIdentifier ScriptingBridge::id_paint;

// Method table for use by HasMethod and Invoke.
std::map<NPIdentifier, ScriptingBridge::Method>*
    ScriptingBridge::method_table;

// Property table for use by {Has|Get|Set|Remove}Property.
std::map<NPIdentifier, ScriptingBridge::Property>*
    ScriptingBridge::property_table;

// Sets up method_table and property_table.
bool ScriptingBridge::InitializeIdentifiers() {
  id_paint = NPN_GetStringIdentifier("paint");

  method_table =
    new(std::nothrow) std::map<NPIdentifier, Method>;
  if (method_table == NULL) {
    return false;
  }
  method_table->insert(
    std::pair<NPIdentifier, Method>(id_paint,
                                    &ScriptingBridge::Paint));

  property_table =
    new(std::nothrow) std::map<NPIdentifier, Property>;
  if (property_table == NULL) {
    return false;
  }

  return true;
}

ScriptingBridge::~ScriptingBridge() {
}

// Class-specific implementation of HasMethod, used by the C-style one
// below.
bool ScriptingBridge::HasMethod(NPIdentifier name) {
  std::map<NPIdentifier, Method>::iterator i;
  i = method_table->find(name);
  return i != method_table->end();
}

// Class-specific implementation of HasProperty, used by the C-style one
// below.
bool ScriptingBridge::HasProperty(NPIdentifier name) {
  std::map<NPIdentifier, Property>::iterator i;
  i = property_table->find(name);
  return i != property_table->end();
}

// Class-specific implementation of GetProperty, used by the C-style one
// below.
bool ScriptingBridge::GetProperty(NPIdentifier name, NPVariant *result) {
  VOID_TO_NPVARIANT(*result);

  std::map<NPIdentifier, Property>::iterator i;
  i = property_table->find(name);
  if (i != property_table->end()) {
    return (this->*(i->second))(result);
  }
  return false;
}

// Class-specific implementation of SetProperty, used by the C-style one
// below.
bool ScriptingBridge::SetProperty(NPIdentifier name, const NPVariant* value) {
  return false;  // Not implemented.
}

// Class-specific implementation of RemoveProperty, used by the C-style one
// below.
bool ScriptingBridge::RemoveProperty(NPIdentifier name) {
  return false;  // Not implemented.
}

// Class-specific implementation of InvokeDefault, used by the C-style one
// below.
bool ScriptingBridge::InvokeDefault(const NPVariant* args,
                                    uint32_t arg_count,
                                    NPVariant* result) {
  return false;  // Not implemented.
}

// Class-specific implementation of Invoke, used by the C-style one
// below.
bool ScriptingBridge::Invoke(NPIdentifier name,
                             const NPVariant* args, uint32_t arg_count,
                             NPVariant* result) {
  std::map<NPIdentifier, Method>::iterator i;
  i = method_table->find(name);
  if (i != method_table->end()) {
    return (this->*(i->second))(args, arg_count, result);
  }
  return false;
}

// Class-specific implementation of Invalidate, used by the C-style one
// below.
void ScriptingBridge::Invalidate() {
  // Not implemented.
}

bool ScriptingBridge::Paint(const NPVariant* args,
                            uint32_t arg_count,
                            NPVariant* result) {
  PiGenerator* pi_generator = static_cast<PiGenerator*>(npp_->pdata);
  if (pi_generator) {
    DOUBLE_TO_NPVARIANT(pi_generator->pi(), *result);
    return pi_generator->Paint();
  }
  return false;
}

// Called by the browser when a plugin is being destroyed to clean up any
// remaining instances of NPClass.
// Documentation URL: https://developer.mozilla.org/en/NPClass
void Invalidate(NPObject* object) {
  return static_cast<ScriptingBridge*>(object)->Invalidate();
}

// Returns |true| if |method_name| is a recognized method.
// Called by NPN_HasMethod, declared in npruntime.h
// Documentation URL: https://developer.mozilla.org/en/NPClass
bool HasMethod(NPObject* object, NPIdentifier name) {
  return static_cast<ScriptingBridge*>(object)->HasMethod(name);
}

// Called by the browser to invoke a function object whose name is |name|.
// Called by NPN_Invoke, declared in npruntime.h
// Documentation URL: https://developer.mozilla.org/en/NPClass
bool Invoke(NPObject* object, NPIdentifier name,
            const NPVariant* args, uint32_t arg_count,
            NPVariant* result) {
  return static_cast<ScriptingBridge*>(object)->Invoke(
      name, args, arg_count, result);
}

// Called by the browser to invoke the default method on an NPObject.
// In this case the default method just returns false.
// Apparently the plugin won't load properly if we simply
// tell the browser we don't have this method.
// Called by NPN_InvokeDefault, declared in npruntime.h
// Documentation URL: https://developer.mozilla.org/en/NPClass
bool InvokeDefault(NPObject* object, const NPVariant* args, uint32_t arg_count,
                   NPVariant* result) {
  return static_cast<ScriptingBridge*>(object)->InvokeDefault(
      args, arg_count, result);
}

// Returns true if |name| is actually the name of a public property on the
// plugin class being queried.
// Called by NPN_HasProperty, declared in npruntime.h
// Documentation URL: https://developer.mozilla.org/en/NPClass
bool HasProperty(NPObject* object, NPIdentifier name) {
  return static_cast<ScriptingBridge*>(object)->HasProperty(name);
}

// Returns the value of the property called |name| in |result| and true.
// Returns false if |name| is not a property on this object or something else
// goes wrong.
// Called by NPN_GetProperty, declared in npruntime.h
// Documentation URL: https://developer.mozilla.org/en/NPClass
bool GetProperty(NPObject* object, NPIdentifier name, NPVariant* result) {
  return static_cast<ScriptingBridge*>(object)->GetProperty(name, result);
}

// Sets the property |name| of |object| to |value| and return true.
// Returns false if |name| is not the name of a settable property on |object|
// or if something else goes wrong.
// Called by NPN_SetProperty, declared in npruntime.h
// Documentation URL: https://developer.mozilla.org/en/NPClass
bool SetProperty(NPObject* object, NPIdentifier name, const NPVariant* value) {
  return static_cast<ScriptingBridge*>(object)->SetProperty(name, value);
}

// Removes the property |name| from |object| and returns true.
// Returns false if it can't be removed for some reason.
// Called by NPN_RemoveProperty, declared in npruntime.h
// Documentation URL: https://developer.mozilla.org/en/NPClass
bool RemoveProperty(NPObject* object, NPIdentifier name) {
  return static_cast<ScriptingBridge*>(object)->RemoveProperty(name);
}

// Creates the plugin-side instance of NPObject.
// Called by NPN_CreateObject, declared in npruntime.h
// Documentation URL: https://developer.mozilla.org/en/NPClass
NPObject* Allocate(NPP npp, NPClass* npclass) {
  return new ScriptingBridge(npp);
}

// Cleans up the plugin-side instance of an NPObject.
// Called by NPN_ReleaseObject, declared in npruntime.h
// Documentation URL: https://developer.mozilla.org/en/NPClass
void Deallocate(NPObject* object) {
  delete static_cast<ScriptingBridge*>(object);
}

}  // namespace pi_generator

// Represents a class's interface, so that the browser knows what functions it
// can call on this plugin object.  The browser can use the methods in this
// class to discover the rest of the plugin's interface.
// Documentation URL: https://developer.mozilla.org/en/NPClass
NPClass pi_generator::ScriptingBridge::np_class = {
  NP_CLASS_STRUCT_VERSION,
  pi_generator::Allocate,
  pi_generator::Deallocate,
  pi_generator::Invalidate,
  pi_generator::HasMethod,
  pi_generator::Invoke,
  pi_generator::InvokeDefault,
  pi_generator::HasProperty,
  pi_generator::GetProperty,
  pi_generator::SetProperty,
  pi_generator::RemoveProperty
};
