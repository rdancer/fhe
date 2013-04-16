// Copyright 2010 The Native Client Authors. All rights reserved.
// Use of this source code is governed by a BSD-style license that can
// be found in the LICENSE file.

#ifndef CALCULATOR_NACL_CALCULATOR_H_
#define CALCULATOR_NACL_CALCULATOR_H_

#include <pthread.h>
#if defined(__native_client__)
#include <nacl/nacl_npapi.h>
#include <nacl/npapi_extensions.h>
#else
#include "third_party/npapi/bindings/npapi.h"
#include "third_party/npapi/bindings/npapi_extensions.h"
#include "third_party/npapi/bindings/nphostapi.h"
#endif
#include <string>
#include <vector>

#include "c_salt/callback.h"
#include "c_salt/module.h"
#include "c_salt/scripting_bridge.h"
#include "c_salt/type.h"

namespace c_salt {
class ObjectType;
}  // namespace c_salt

namespace calculator {

class Calculator : public c_salt::Module {
 public:
  Calculator();
  virtual ~Calculator();
  virtual void InitializeMethods(c_salt::ScriptingBridge* bridge);
  virtual void InitializeProperties(c_salt::ScriptingBridge* bridge);

  // Accessor/mutator to handle the "buttonSound" property.
  bool GetButtonSound(c_salt::ScriptingBridge* bridge, NPVariant* return_value);
  bool SetButtonSound(c_salt::ScriptingBridge* bridge, const NPVariant* value);

  // Unpack the args: args[0] is assumed to be the expression array, in postfix
  // notation.  args[1] is assumed to be the JavaScript function object that
  // is used as the computation result callback.  Once the args are all
  // unpacked, call EvaluateExpression().
  bool Calculate(c_salt::ScriptingBridge* bridge,
                 const NPVariant* args,
                 uint32_t arg_count,
                 NPVariant* return_value);

  // Evaulate |expression| as a statement in postfix notation.  Return the
  // result.
  double EvaluateExpression(const c_salt::Type::TypeArray* expression);

  // Play the button's "Click" sound.  This call-back is called continuously
  // from the audio thread, which runs open-loop.  This method uses some state
  // in the obect to decide whether to fill the sound out buffer with 0's or
  // the click sound.
  void SynthesizeButtonClick(NPDeviceContextAudio *context);

  // Schedule a button click sound to get played.  This sets some state that
  // is used in the SynthesizeButtonClick() method to fill the audio buffers
  // with the click sound.
  bool Click(c_salt::ScriptingBridge* bridge,
             const NPVariant* args,
             uint32_t arg_count,
             NPVariant* return_value);

  // Initialize the audio context once the browser has finished setting and
  // loading the runtime for our module.
  virtual void SetWindow(NPP instance, int width, int height);

 private:
  c_salt::MethodCallback<Calculator>* calculate_callback_;
  c_salt::MethodCallback<Calculator>* click_callback_;
  c_salt::PropertyAccessorCallback<Calculator>* get_button_sound_;
  c_salt::PropertyMutatorCallback<Calculator>* set_button_sound_;
  bool play_button_sound_;  // |true| if bunttons should play sounds.
  bool click_pending_;  // |true| is a click needs to get played.
  int current_sample_index_;  // Start sample copy at this pointer.

  // Pepper Audio device varaibles.
  // TODO(dspringer): this should be migrated to a c_salt class!
  NPDevice* device_audio_;  // The PINPAPI audio device.
  NPDeviceContextAudio context_audio_;  // The PINPAPI audio context.

  DISALLOW_COPY_AND_ASSIGN(Calculator);
};

}  // namespace calculator

#endif  // CALCULATOR_NACL_CALCULATOR_H_
