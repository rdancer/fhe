// Copyright 2010 The Native Client SDK Authors. All rights reserved.
// Use of this source code is governed by a BSD-style license that can
// be found in the LICENSE file.

#ifndef PHOTO_SCRIPTING_BRIDGE_H_
#define PHOTO_SCRIPTING_BRIDGE_H_

#include <boost/noncopyable.hpp>
#include <nacl/nacl_npapi.h>
#include <nacl/npruntime.h>

#include <map>
#include <tr1/memory>

namespace photo {

class PropertyAccessorCallbackExecutor;
class PropertyMutatorCallbackExecutor;
class MethodCallbackExecutor;

// This class represents the Photo application object that gets exposed to the
// browser code.
class ScriptingBridge : public boost::noncopyable {
 public:
  // Shared pointer types used in the method and property maps.
  typedef std::tr1::shared_ptr<MethodCallbackExecutor>
      SharedMethodCallbackExecutor;
  typedef std::tr1::shared_ptr<PropertyAccessorCallbackExecutor>
      SharedPropertyAccessorCallbackExecutor;
  typedef std::tr1::shared_ptr<PropertyMutatorCallbackExecutor>
      SharedPropertyMutatorCallbackExecutor;

  typedef std::tr1::shared_ptr<ScriptingBridge> SharedScriptingBridge;

  virtual ~ScriptingBridge();

  // Creates an instance of the scripting bridge object in the browser, with
  // a corresponding ScriptingBridge object instance.
  static SharedScriptingBridge CreateScriptingBridge(NPP npp);

  // Causes |method_name| to be published as a method that can be called by
  // JavaScript.  Associated this method with |method|.
  bool AddMethodNamed(const char* method_name,
                      SharedMethodCallbackExecutor method);

  // Associate property accessor and mutator with |property_name|.  This
  // publishes |property_name| to the JavaScript.  |get_property| must not
  // be NULL; if |set_property| is NULL the property is considered read-only.
  bool AddPropertyNamed(const char* property_name,
      SharedPropertyAccessorCallbackExecutor property_accessor,
      SharedPropertyMutatorCallbackExecutor property_mutator);

  // Make a copy of the browser binding object by asking the browser to retain
  // it.  Use this for the return value of functions that expect the retain
  // count to increment, such as NPP_GetScriptableInstance().
  NPObject* CopyBrowserBinding() {
    if (browser_binding_)
      return NPN_RetainObject(browser_binding_);
    return NULL;
  }

  // Release the browser binding object.  Note that this *might* cause |this|
  // to get deleted, if the ref count of the browser binding object falls to 0.
  void ReleaseBrowserBinding();

  const NPP npp() const {
    return npp_;
  }
  const NPObject* browser_binding() const {
    return browser_binding_;
  }

  // A hidden class that wraps the NPObject, preserving its memory layout
  // for the browser.
  friend class BrowserBinding;

 private:
  typedef std::map<NPIdentifier, SharedMethodCallbackExecutor> MethodDictionary;
  typedef std::map<NPIdentifier, SharedPropertyAccessorCallbackExecutor>
      PropertyAccessorDictionary;
  typedef std::map<NPIdentifier, SharedPropertyMutatorCallbackExecutor>
      PropertyMutatorDictionary;

  ScriptingBridge(NPP npp, NPObject* browser_binding);

  // NPAPI support methods.
  bool HasMethod(NPIdentifier name);
  bool Invoke(NPIdentifier name,
              const NPVariant* args,
              uint32_t arg_count,
              NPVariant* return_value);
  bool HasProperty(NPIdentifier name);
  bool GetProperty(NPIdentifier name, NPVariant* return_value);
  bool SetProperty(NPIdentifier name, const NPVariant* value);

  NPP npp_;
  NPObject* browser_binding_;

  MethodDictionary method_dictionary_;
  PropertyAccessorDictionary property_accessor_dictionary_;
  PropertyMutatorDictionary property_mutator_dictionary_;

  // No implicit ctors.
  ScriptingBridge();
};

}  // namespace photo

#endif  // PHOTO_SCRIPTING_BRIDGE_H_
