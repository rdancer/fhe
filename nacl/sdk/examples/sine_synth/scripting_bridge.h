// Copyright 2010 The Native Client Authors. All rights reserved.
// Use of this source code is governed by a BSD-style license that can
// be found in the LICENSE file.

#ifndef EXAMPLES_SINE_SYNTH_SCRIPTING_BRIDGE_H_
#define EXAMPLES_SINE_SYNTH_SCRIPTING_BRIDGE_H_

#include <map>

#if defined(__native_client__)
#include <nacl/nacl_npapi.h>
#else
// Building a trusted plugin for debugging.
#include "third_party/npapi/bindings/npapi.h"
#include "third_party/npapi/bindings/nphostapi.h"
#endif


namespace sine_synth {

// This class represents the Pepper3D object that gets exposed to the browser
// code.
class ScriptingBridge : public NPObject {
 public:
  typedef bool (ScriptingBridge::*MethodSelector)(const NPVariant* args,
                                                  uint32_t arg_count,
                                                  NPVariant* result);
  typedef bool (ScriptingBridge::*GetPropertySelector)(NPVariant* value);
  typedef bool (ScriptingBridge::*SetPropertySelector)(const NPVariant* result);

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
  // Each of these methods wraps a method in the associated SineSynth object,
  // which is where the actual implementation lies.

  // Starts the synthetic sine wave.
  bool PlaySound(const NPVariant* args, uint32_t arg_count, NPVariant* result);
  // Stops the synthetic sine wave.
  bool StopSound(const NPVariant* args, uint32_t arg_count, NPVariant* result);

  // Accessor/mutator for the frequency property.
  bool GetFrequency(NPVariant* value);
  bool SetFrequency(const NPVariant* value);

 private:
  NPP npp_;

  static NPIdentifier id_play_sound;
  static NPIdentifier id_stop_sound;
  static NPIdentifier id_frequency;

  static std::map<NPIdentifier, MethodSelector>* method_table;
  static std::map<NPIdentifier, GetPropertySelector>* get_property_table;
  static std::map<NPIdentifier, SetPropertySelector>* set_property_table;
};

}  // namespace sine_synth

#endif  // EXAMPLES_SINE_SYNTH_SCRIPTING_BRIDGE_H_
