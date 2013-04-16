// Copyright 2010 The Native Client SDK Authors. All rights reserved.
// Use of this source code is governed by a BSD-style license that can
// be found in the LICENSE file.

#ifndef CALCULATOR_C_SALT_SCRIPTING_BRIDGE_H_
#define CALCULATOR_C_SALT_SCRIPTING_BRIDGE_H_

#include <map>

#if defined(__native_client__)
#include <nacl/nacl_npapi.h>
#include <nacl/npruntime.h>
#else
// Building a trusted plugin for debugging.
#include "third_party/npapi/bindings/npapi.h"
#include "third_party/npapi/bindings/npruntime.h"
#endif

namespace c_salt {

class MethodCallbackExecutor;
class Module;
class PropertyAccessorCallbackExecutor;
class PropertyMutatorCallbackExecutor;

// This class handles all the calls across the bridge to the browser.  Use
// AddMethodNamed() and AddPropertyNamed() to publish methods and properties
// that can be accessed from the browser code.
class ScriptingBridge : public NPObject {
 public:
  explicit ScriptingBridge(NPP npp);
  virtual ~ScriptingBridge();

  // Causes |method_name| to be published as a method that can be called by
  // JavaScript.  Associated this method with |method|.
  bool AddMethodNamed(const char* method_name, MethodCallbackExecutor* method);

  // Associate property accessor and mutator with |property_name|.  This
  // publishes |property_name| to the JavaScript.  |property_accessor| must not
  // be NULL; if |property_mutator| is NULL the property is considered read-only.
  bool AddPropertyNamed(const char* property_name,
                        PropertyAccessorCallbackExecutor* property_accessor,
                        PropertyMutatorCallbackExecutor* property_mutator);

  // These methods represent the NPObject implementation.  The browser calls
  // these methods by calling functions in the |np_class| struct.
  virtual void Invalidate();
  virtual bool HasMethod(NPIdentifier name);
  virtual bool Invoke(NPIdentifier name,
                      const NPVariant* args,
                      uint32_t arg_count,
                      NPVariant* return_value);
  virtual bool InvokeDefault(const NPVariant* args,
                             uint32_t arg_count,
                             NPVariant* return_value);
  virtual bool HasProperty(NPIdentifier name);
  virtual bool GetProperty(NPIdentifier name, NPVariant* return_value);
  virtual bool SetProperty(NPIdentifier name, const NPVariant* return_value);
  virtual bool RemoveProperty(NPIdentifier name);
  
  const NPP npp() const {
    return npp_;
  }

  // The browser-facing entry points that represent the bridge's class methods.
  static NPClass bridge_class;

 private:
  typedef std::map<NPIdentifier, MethodCallbackExecutor*> MethodDictionary;
  typedef std::map<NPIdentifier, PropertyAccessorCallbackExecutor*>
      PropertyAccessorDictionary;
  typedef std::map<NPIdentifier, PropertyMutatorCallbackExecutor*>
      PropertyMutatorDictionary;
  NPP npp_;
  MethodDictionary method_dictionary_;
  PropertyAccessorDictionary property_accessor_dictionary_;
  PropertyMutatorDictionary property_mutator_dictionary_;
};

}  // namespace c_salt

#endif  // CALCULATOR_C_SALT_SCRIPTING_BRIDGE_H_
