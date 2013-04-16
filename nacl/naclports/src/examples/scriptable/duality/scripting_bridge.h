// Copyright 2010 The Native Client SDK Authors. All rights reserved.
// Use of this source code is governed by a BSD-style license that can
// be found in the LICENSE file.

#ifndef EXAMPLES_SCRIPTABLE_DUALITY_SCRIPTING_BRIDGE_H_
#define EXAMPLES_SCRIPTABLE_DUALITY_SCRIPTING_BRIDGE_H_

#include <examples/scriptable/duality/scriptable.h>

#include <nacl/nacl_npapi.h>

#include <map>


// General scripting bridge class that uses an implementable interface to
// provide NaCl scriptability.
class ScriptingBridge : public NPObject {
 public:
  explicit ScriptingBridge(Scriptable * scriptable_instance);
  virtual ~ScriptingBridge();

  // Creates the plugin-side instance of NPObject.
  // Called by NPN_CreateObject, declared in npruntime.h
  // Documentation URL: https://developer.mozilla.org/en/NPClass
  template<typename ScriptableType>
  static NPObject* AllocateCallback(NPP npp, NPClass* npclass);

  // Called by NPP_GetScriptableInstance to get the scripting interface for
  // a plugin object of ScriptableType. The browser may dereference the
  // returned pointer any time after it gets it, until the plugin is cleaned
  // up.  It should clean it up whenever it decides to collect garbage and
  // there are no references remaining.
  template<typename ScriptableType>
  static NPClass* GetNPSimpleClass();

 private:

  // Cleans up the plugin-side instance of an NPObject.
  // Called by NPN_ReleaseObject, declared in npruntime.h
  // Documentation URL: https://developer.mozilla.org/en/NPClass
  static void DeallocateCallback(NPObject* object);

  // Returns the value of the property called |name| in |result| and true.
  // Returns false if |name| is not a property on this object or something else
  // goes wrong.
  // Called by NPN_GetProperty, declared in npruntime.h
  // Documentation URL: https://developer.mozilla.org/en/NPClass
  static bool GetPropertyCallback(NPObject* object,
                                  NPIdentifier name,
                                  NPVariant* result);

  // Returns |true| if |method_name| is a recognized method.
  // Called by NPN_HasMethod, declared in npruntime.h
  // Documentation URL: https://developer.mozilla.org/en/NPClass
  static bool HasMethodCallback(NPObject* object, NPIdentifier name);

  // Returns true if |name| is actually the name of a public property on the
  // plugin class being queried.
  // Called by NPN_HasProperty, declared in npruntime.h
  // Documentation URL: https://developer.mozilla.org/en/NPClass
  static bool HasPropertyCallback(NPObject* object, NPIdentifier name);

  // Not public because it is called by Allocate
  void Init();

  // Called by the browser when a plugin is being destroyed to clean up any
  // remaining instances of NPClass.
  // Documentation URL: https://developer.mozilla.org/en/NPClass
  static void InvalidateCallback(NPObject* object);
  void Invalidate();

  // Called by the browser to invoke a function object whose name is |name|.
  // Called by NPN_Invoke, declared in npruntime.h
  // Documentation URL: https://developer.mozilla.org/en/NPClass
  static bool InvokeCallback(NPObject* object,
                             NPIdentifier name,
                             const NPVariant* args,
                             uint32_t arg_count,
                             NPVariant* result);

  // Called by the browser to invoke the default method on an NPObject.
  // In this case the default method just returns false.
  // Apparently the plugin won't load properly if we simply
  // tell the browser we don't have this method.
  // Called by NPN_InvokeDefault, declared in npruntime.h
  // Documentation URL: https://developer.mozilla.org/en/NPClass
  static bool InvokeDefaultCallback(NPObject* object,
                                    const NPVariant* args,
                                    uint32_t arg_count,
                                    NPVariant* result);

  // Removes the property |name| from |object| and returns true.
  // Returns false if it can't be removed for some reason.
  // Called by NPN_RemoveProperty, declared in npruntime.h
  // Documentation URL: https://developer.mozilla.org/en/NPClass
  static bool RemovePropertyCallback(NPObject* object, NPIdentifier name);

  // Sets the property |name| of |object| to |value| and return true.
  // Returns false if |name| is not the name of a settable property on |object|
  // or if something else goes wrong.
  // Called by NPN_SetProperty, declared in npruntime.h
  // Documentation URL: https://developer.mozilla.org/en/NPClass
  static bool SetPropertyCallback(NPObject* object,
                                  NPIdentifier name,
                                  const NPVariant* value);

  // Not called GetInstance because that would be misleading.
  // GetInstance would imply a singleton design pattern.  ToTYPE implies
  // the type cast which is appropriate.
  static ScriptingBridge * ToInstance(NPObject * object);


  Scriptable * scriptable_instance_;
};

template<typename ScriptableType>
NPClass* ScriptingBridge::GetNPSimpleClass() {
  printf("Duality: ScriptingBridge::GetNPSimpleClass was called!\n");
  fflush(stdout);
  void* np_class_pointer = NPN_MemAlloc(sizeof(NPClass));
  NPClass* np_class = static_cast<NPClass *>(np_class_pointer);
  np_class->structVersion = NP_CLASS_STRUCT_VERSION;
  np_class->allocate = ScriptingBridge::AllocateCallback<ScriptableType>;
  np_class->deallocate = ScriptingBridge::DeallocateCallback;
  np_class->invalidate = ScriptingBridge::InvalidateCallback;
  np_class->hasMethod = ScriptingBridge::HasMethodCallback;
  np_class->invoke = ScriptingBridge::InvokeCallback;
  np_class->invokeDefault = ScriptingBridge::InvokeDefaultCallback;
  np_class->hasProperty = ScriptingBridge::HasPropertyCallback;
  np_class->getProperty = ScriptingBridge::GetPropertyCallback;
  np_class->setProperty = ScriptingBridge::SetPropertyCallback;
  np_class->removeProperty = ScriptingBridge::RemovePropertyCallback;
  np_class->enumerate = NULL;
  np_class->construct = NULL;
  return np_class;
}

template<typename ScriptableType>
NPObject* ScriptingBridge::AllocateCallback(NPP npp, NPClass* npclass) {
  printf("Duality: ScriptingBridge::Allocate was called!\n");
  fflush(stdout);
  ScriptableType* scriptable_object = new ScriptableType();
  ScriptingBridge* bridge = new ScriptingBridge(scriptable_object);
  bridge->Init();
  scriptable_object->Init(npp);
  printf("Duality: ScriptingBridge::Allocate returning a bridge!\n");
  fflush(stdout);
  return bridge;
}


#endif  // EXAMPLES_SCRIPTABLE_DUALITY_SCRIPTING_BRIDGE_H_
