// Copyright 2010 The Native Client Authors. All rights reserved.
// Use of this source code is governed by a BSD-style license that can
// be found in the LICENSE file.

#ifndef EXAMPLES_PI_GENERATOR_SCRIPTING_BRIDGE_H_
#define EXAMPLES_PI_GENERATOR_SCRIPTING_BRIDGE_H_

#include <map>

#if defined(__native_client__)
#include <nacl/nacl_npapi.h>
#else
// Building a trusted plugin for debugging.
#include "third_party/npapi/bindings/npapi.h"
#include "third_party/npapi/bindings/nphostapi.h"
#endif


namespace pi_generator {

// This class represents the Pepper3D object that gets exposed to the browser
// code.
class ScriptingBridge : public NPObject {
 public:
  typedef bool (ScriptingBridge::*Method)(const NPVariant* args,
                                          uint32_t arg_count,
                                          NPVariant* result);
  typedef bool (ScriptingBridge::*Property)(NPVariant* result);

  explicit ScriptingBridge(NPP npp) : npp_(npp) {}
  virtual ~ScriptingBridge();

  // These methods represent the NPObject implementation.  The browser calls
  // these methods by calling functions in the |np_class| struct.
  virtual void Invalidate();
  virtual bool HasMethod(NPIdentifier name);
  virtual bool Invoke(NPIdentifier name,
                      const NPVariant* args,
                      uint32_t arg_count,
                      NPVariant* result);
  virtual bool InvokeDefault(const NPVariant* args,
                             uint32_t arg_count,
                             NPVariant* result);
  virtual bool HasProperty(NPIdentifier name);
  virtual bool GetProperty(NPIdentifier name, NPVariant* result);
  virtual bool SetProperty(NPIdentifier name, const NPVariant* value);
  virtual bool RemoveProperty(NPIdentifier name);

  static bool InitializeIdentifiers();

  static NPClass np_class;

  // These methods are exposed via the scripting bridge to the browser.
  // Each one is mapped to a string id, which is the name of the method that
  // the broswer sees.  For example, Paint() has id 'paint', so in the browser
  // JavaScript, you would write tumbler.paint() to invoke the Paint() method
  // in this object.
  //
  // Each of these methods wraps a method in the associated Pepper3D object,
  // which is where the actual implementation lies.

  // Draws the quarter-circle image representing the stochasitc PI generator.
  bool Paint(const NPVariant* args, uint32_t arg_count, NPVariant* result);

 private:
  NPP npp_;
  NPObject* window_object_;

  static NPIdentifier id_paint;

  static std::map<NPIdentifier, Method>* method_table;
  static std::map<NPIdentifier, Property>* property_table;
};

}  // namespace pi_generator

#endif  // EXAMPLES_PI_GENERATOR_SCRIPTING_BRIDGE_H_
