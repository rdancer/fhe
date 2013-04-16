// Copyright 2010 The Native Client SDK Authors. All rights reserved.
// Use of this source code is governed by a BSD-style license that can
// be found in the LICENSE file.

#include "scripting_bridge.h"

#include <assert.h>
#include <string.h>
#include <tr1/memory>

#include "callback.h"

namespace photo {

// A thin wrapper that owns the ScriptingBridge class.  This is necessary
// because the NPObject layout has to be preserved, and it cannot have things
// like a vtable inserted into it.
// Construction is done via ScriptingBridge::CreateScriptingBridge() in three
// steps:
//   1. CreateScriptingBridge() calls NPN_CreateObject() with |bridge_class|,
//      this instructs the browser to call photo::Allocate().
//   2. photo::Allocate() creates a new instance of BrowserBinding in the heap.
//   3. The ctor of BrowserBinding connects the NPObject that the browser sees
//      with an instance of ScriptingBridge.
// The BrowserBinding object returned by CreateScriptingBridge() is managed by
// the browser, the dtor is called from the browser via photo::Deallocate().
class BrowserBinding : public NPObject {
 public:
  BrowserBinding() {}
  virtual ~BrowserBinding() {}

  bool HasMethod(NPIdentifier name) {
    return scripting_bridge_->HasMethod(name);
  }
  bool Invoke(NPIdentifier name,
              const NPVariant* args,
              uint32_t arg_count,
              NPVariant* return_value) {
    return scripting_bridge_->Invoke(name, args, arg_count, return_value);
  }
  bool HasProperty(NPIdentifier name) {
    return scripting_bridge_->HasProperty(name);
  }
  bool GetProperty(NPIdentifier name, NPVariant* return_value) {
    return scripting_bridge_->GetProperty(name, return_value);
  }
  bool SetProperty(NPIdentifier name, const NPVariant* return_value) {
    return scripting_bridge_->SetProperty(name, return_value);
  }

  ScriptingBridge::SharedScriptingBridge scripting_bridge() const {
    return scripting_bridge_;
  }
  void set_scripting_bridge(
      const ScriptingBridge::SharedScriptingBridge& scripting_bridge) {
    scripting_bridge_ = scripting_bridge;
  }

 private:
  ScriptingBridge::SharedScriptingBridge scripting_bridge_;
};

// Helper function for dereferencing the bridging object.
static inline BrowserBinding* cast_browser_binding(NPObject* np_object) {
  assert(np_object);
  return static_cast<BrowserBinding*>(np_object);
}
// The browser-facing entry points that represent the bridge's class methods.
// These are the function wrappers that the browser calls.

NPObject* Allocate(NPP npp, NPClass* npclass) {
  return new BrowserBinding();
}

void Deallocate(NPObject* object) {
  delete cast_browser_binding(object);
}

bool HasMethod(NPObject* object, NPIdentifier name) {
  return cast_browser_binding(object)->HasMethod(name);
}

bool Invoke(NPObject* object, NPIdentifier name,
            const NPVariant* args, uint32_t arg_count,
            NPVariant* return_value) {
  return cast_browser_binding(object)->Invoke(
      name, args, arg_count, return_value);
}

bool HasProperty(NPObject* object, NPIdentifier name) {
  return cast_browser_binding(object)->HasProperty(name);
}

bool GetProperty(NPObject* object, NPIdentifier name, NPVariant* result) {
  return cast_browser_binding(object)->GetProperty(name, result);
}

bool SetProperty(NPObject* object, NPIdentifier name, const NPVariant* value) {
  return cast_browser_binding(object)->SetProperty(name, value);
}

static NPClass bridge_class = {
    NP_CLASS_STRUCT_VERSION,
    photo::Allocate,
    photo::Deallocate,
    NULL,  // Invalidate is not implemented in this module.
    photo::HasMethod,
    photo::Invoke,
    NULL,  // InvokeDefault is not implemented in this module.
    photo::HasProperty,
    photo::GetProperty,
    photo::SetProperty,
    NULL  // RemoveProperty is not implemented in this module.
};

ScriptingBridge::SharedScriptingBridge ScriptingBridge::CreateScriptingBridge(
    NPP npp) {
  // This is a synchronous call to the browser.  Memory has been allocated
  // and ctors called by the time it returns.  |browser_binding| is deleted
  // by the browser when its ref count falls to 0.
  BrowserBinding* browser_binding =
       static_cast<BrowserBinding*>(NPN_CreateObject(npp, &bridge_class));
  SharedScriptingBridge scripting_bridge;
  if (browser_binding) {
    scripting_bridge.reset(new ScriptingBridge(npp, browser_binding));
    browser_binding->set_scripting_bridge(scripting_bridge);
  }
  return scripting_bridge;
}

ScriptingBridge::ScriptingBridge(NPP npp, NPObject* browser_binding)
    : npp_(npp),
      browser_binding_(browser_binding) {
}

ScriptingBridge::~ScriptingBridge() {
  ReleaseBrowserBinding();
}

void ScriptingBridge::ReleaseBrowserBinding() {
  if (browser_binding_) {
    bool will_dealloc = browser_binding_->referenceCount == 1;
    NPObject* tmp_binding = browser_binding_;
    browser_binding_ = NULL;
    NPN_ReleaseObject(tmp_binding);
    // |this| might be deallocated at this point.  Further calls might have
    // unpredictable results.
    if (!will_dealloc) {
      browser_binding_ = tmp_binding;
    }
  }
  // |this| might be deallocated at this point.  Further calls might have
  // unpredictable results.
}

bool ScriptingBridge::AddMethodNamed(const char* method_name,
                                     SharedMethodCallbackExecutor method) {
  if (method_name == NULL || method == NULL)
    return false;
  NPIdentifier method_id = NPN_GetStringIdentifier(method_name);
  method_dictionary_.insert(
      std::pair<NPIdentifier, SharedMethodCallbackExecutor>(method_id, method));
  return true;
}

bool ScriptingBridge::AddPropertyNamed(
    const char* property_name,
    SharedPropertyAccessorCallbackExecutor property_accessor,
    SharedPropertyMutatorCallbackExecutor property_mutator) {
  if (property_name == NULL || property_accessor == NULL)
    return false;
  NPIdentifier property_id = NPN_GetStringIdentifier(property_name);
  property_accessor_dictionary_.insert(
      std::pair<NPIdentifier,
      SharedPropertyAccessorCallbackExecutor>(property_id, property_accessor));
  if (property_mutator) {
    property_mutator_dictionary_.insert(
        std::pair<NPIdentifier,
        SharedPropertyMutatorCallbackExecutor>(property_id, property_mutator));
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

bool ScriptingBridge::Invoke(NPIdentifier name,
                                    const NPVariant* args, uint32_t arg_count,
                                    NPVariant* return_value) {
  MethodDictionary::iterator i;
  i = method_dictionary_.find(name);
  if (i != method_dictionary_.end()) {
    return (*i->second).Execute(this, args, arg_count, return_value);
  }
  return false;
}

}  // namespace photo
