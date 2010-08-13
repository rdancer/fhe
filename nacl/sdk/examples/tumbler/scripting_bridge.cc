// Copyright 2010 The Native Client SDK Authors. All rights reserved.
// Use of this source code is governed by a BSD-style license that can
// be found in the LICENSE file.

#include "examples/tumbler/scripting_bridge.h"

#include <assert.h>
#include <string.h>

#include "examples/tumbler/tumbler.h"

namespace tumbler {

NPIdentifier ScriptingBridge::id_get_camera_orientation;
NPIdentifier ScriptingBridge::id_set_camera_orientation;

static const uint32_t kQuaternionElementCount = 4;

// Method table for use by HasMethod and Invoke.
std::map<NPIdentifier, ScriptingBridge::Method>*
    ScriptingBridge::method_table;

// Property table for use by {Has|Get|Set|Remove}Property.
std::map<NPIdentifier, ScriptingBridge::Property>*
    ScriptingBridge::property_table;

// Sets up method_table and property_table.
bool ScriptingBridge::InitializeIdentifiers() {
  id_get_camera_orientation = NPN_GetStringIdentifier("getCameraOrientation");
  id_set_camera_orientation = NPN_GetStringIdentifier("setCameraOrientation");

  method_table = new(std::nothrow) std::map<NPIdentifier, Method>;
  if (method_table == NULL) {
    return false;
  }
  method_table->insert(
    std::pair<NPIdentifier, Method>(
        id_get_camera_orientation,
        &ScriptingBridge::GetCameraOrientation));
  method_table->insert(
    std::pair<NPIdentifier, Method>(
        id_set_camera_orientation,
        &ScriptingBridge::SetCameraOrientation));

  property_table =
    new(std::nothrow) std::map<NPIdentifier, Property>;
  if (property_table == NULL) {
    return false;
  }

  return true;
}

ScriptingBridge::ScriptingBridge(NPP npp)
    : npp_(npp), window_object_(NULL) {
  NPError error = NPN_GetValue(npp_, NPNVWindowNPObject, &window_object_);
  assert(NPERR_NO_ERROR == error);
}

ScriptingBridge::~ScriptingBridge() {
  if (window_object_) {
    NPN_ReleaseObject(window_object_);
  }
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
bool ScriptingBridge::GetProperty(NPIdentifier name,
                                         NPVariant *result) {
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

void ScriptingBridge::Invalidate() {
  // Not implemented.
}

bool ScriptingBridge::GetCameraOrientation(const NPVariant* args,
                                           uint32_t arg_count,
                                           NPVariant* result) {
  Tumbler* tumbler = static_cast<Tumbler*>(npp_->pdata);
  if (tumbler && window_object_) {
    float orientation[4];
    if (!tumbler->GetCameraOrientation(orientation))
      return false;

    // Initialize the return value.
    NULL_TO_NPVARIANT(*result);
    // Ask the browser to create a new JavaScript array object.
    NPVariant variant;
    NPString npstr;
    npstr.UTF8Characters = "new Array();";
    npstr.UTF8Length = static_cast<uint32_t>(strlen(npstr.UTF8Characters));
    if (!NPN_Evaluate(npp_, window_object_, &npstr, &variant) ||
        !NPVARIANT_IS_OBJECT(variant)) {
      return false;
    }
    // Set the properties, each array subscript has its own property id.
    NPObject* array_object = NPVARIANT_TO_OBJECT(variant);
    if (array_object) {
      for (size_t j = 0; j < kQuaternionElementCount; ++j) {
        NPVariant array_value;
        DOUBLE_TO_NPVARIANT(static_cast<double>(orientation[j]), array_value);
        NPN_SetProperty(npp_,
                        array_object,
                        NPN_GetIntIdentifier(j),
                        &array_value);
      }
      OBJECT_TO_NPVARIANT(array_object, *result);
    }
    return true;
  }
  return false;
}

bool ScriptingBridge::SetCameraOrientation(const NPVariant* args,
                                           uint32_t arg_count,
                                           NPVariant* value) {
  Tumbler* tumbler = static_cast<Tumbler*>(npp_->pdata);
  if (!tumbler || arg_count != 1 || !NPVARIANT_IS_OBJECT(*args))
    return false;

  // Unpack the array object.  This is done by enumerating the identifiers on
  // the array; the identifiers are the array subscripts.
  bool success = false;
  NPIdentifier* identifier = NULL;
  uint32_t element_count = 0;
  NPObject* array_object = NPVARIANT_TO_OBJECT(*args);
  if (NPN_Enumerate(npp_, array_object, &identifier, &element_count)) {
    if (element_count == kQuaternionElementCount) {
      float orientation[4] = {0.0f, 0.0f, 0.0f, 1.0f};
      tumbler->GetCameraOrientation(orientation);
      for (uint32_t j = 0; j < element_count; ++j) {
        if (NPN_HasProperty(npp_, array_object, identifier[j])) {
          // Get each element out of the array by accessing the property whose
          // identifier is the array subscript.
          NPVariant array_elem;
          VOID_TO_NPVARIANT(array_elem);
          if (NPN_GetProperty(npp_,
                              array_object,
                              identifier[j],
                              &array_elem)) {
            // Process both integer and double values.  Other value types are
            // not handled.
            switch (array_elem.type) {
            case NPVariantType_Int32:
              orientation[j] =
                  static_cast<float>(NPVARIANT_TO_INT32(array_elem));
              break;
            case NPVariantType_Double:
              orientation[j] =
                  static_cast<float>(NPVARIANT_TO_DOUBLE(array_elem));
              break;
            default:
              break;
            }
          }
        }
      }
      success = tumbler->SetCameraOrientation(orientation);
      NPN_MemFree(identifier);
    }
  }

  return success;
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

}  // namespace tumbler

// Represents a class's interface, so that the browser knows what functions it
// can call on this plugin object.  The browser can use HasMethod and Invoke
// to discover the plugin class's specific interface.
// Documentation URL: https://developer.mozilla.org/en/NPClass
NPClass tumbler::ScriptingBridge::np_class = {
  NP_CLASS_STRUCT_VERSION,
  tumbler::Allocate,
  tumbler::Deallocate,
  tumbler::Invalidate,
  tumbler::HasMethod,
  tumbler::Invoke,
  tumbler::InvokeDefault,
  tumbler::HasProperty,
  tumbler::GetProperty,
  tumbler::SetProperty,
  tumbler::RemoveProperty
};
