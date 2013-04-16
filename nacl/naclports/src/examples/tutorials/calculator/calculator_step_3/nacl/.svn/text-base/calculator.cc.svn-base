// Copyright 2010 The Native Client Authors. All rights reserved.
// Use of this source code is governed by a BSD-style license that can
// be found in the LICENSE file.

#include "nacl/calculator.h"

#include <assert.h>
#include <math.h>
#include <stdio.h>
#include <stdlib.h>
#include <string.h>

#if defined(__native_client__)
#include <nacl/nacl_imc.h>
#include <nacl/nacl_npapi.h>
#include <nacl/npapi_extensions.h>
#include <nacl/npruntime.h>
#else
// Building the develop version.
#include "third_party/npapi/bindings/npapi.h"
#include "third_party/npapi/bindings/npapi_extensions.h"
#include "third_party/npapi/bindings/nphostapi.h"
#endif

#include "c_salt/double_type.h"
#include "c_salt/int32_type.h"
#include "c_salt/object_type.h"
#include "c_salt/scripting_bridge.h"
#include "c_salt/string_type.h"

#include <limits>

extern NPDevice* NPN_AcquireDevice(NPP instance, NPDeviceID device);
extern const int16_t click_sound_samples[];
extern const int kClickSampleCount;

namespace c_salt {

// The required factory method.
Module* Module::CreateModule() {
  return new calculator::Calculator();
}

}  // namespace c_salt

// Audio device call-back wrapper.
void AudioCallback(NPDeviceContextAudio *context) {
  if (!context)
    return;
  calculator::Calculator* calculator =
      static_cast<calculator::Calculator*>(context->config.userData);
  if (calculator) {
    calculator->SynthesizeButtonClick(context);
  }
}

namespace calculator {

Calculator::Calculator()
    : calculate_callback_(NULL),
      click_callback_(NULL),
      get_button_sound_(NULL),
      set_button_sound_(NULL),
      play_button_sound_(false),
      click_pending_(false),
      current_sample_index_(0),
      device_audio_(NULL) {
}

Calculator::~Calculator() {
  delete calculate_callback_;
  delete click_callback_;
  delete get_button_sound_;
  delete set_button_sound_;
}

void Calculator::InitializeMethods(c_salt::ScriptingBridge* bridge) {
  calculate_callback_ =
      new c_salt::MethodCallback<Calculator>(this, &Calculator::Calculate);
  bridge->AddMethodNamed("calculate", calculate_callback_);
  click_callback_ =
      new c_salt::MethodCallback<Calculator>(this, &Calculator::Click);
  bridge->AddMethodNamed("click", click_callback_);
}

void Calculator::InitializeProperties(c_salt::ScriptingBridge* bridge) {
  get_button_sound_ = new c_salt::PropertyAccessorCallback<Calculator>(
      this, &Calculator::GetButtonSound);
  set_button_sound_ = new c_salt::PropertyMutatorCallback<Calculator>(
      this, &Calculator::SetButtonSound);
  bridge->AddPropertyNamed("buttonSound",
                           get_button_sound_,
                           set_button_sound_);
}

bool Calculator::Calculate(c_salt::ScriptingBridge* bridge,
                           const NPVariant* args,
                           uint32_t arg_count,
                           NPVariant* return_value) {
  if (arg_count < 1 || !NPVARIANT_IS_OBJECT(args[0]))
    return false;

  c_salt::Type::TypeArray* expression_array =
      c_salt::Type::CreateArrayFromNPVariant(bridge, args[0]);
  double expr_value = EvaluateExpression(expression_array);

  // If there was a second argument, assumes it's the oncalculate callback.
  if (arg_count > 1 && NPVARIANT_IS_OBJECT(args[1])) {
    // The ObjectType ctor bumps the ref count of the callback function.
    c_salt::ObjectType function_obj(NPVARIANT_TO_OBJECT(args[1]));
    // Pack the value of the expression into the first arg of the callback
    // function, and invoke it.
    NPVariant argv;
    NPVariant result;
    DOUBLE_TO_NPVARIANT(expr_value, argv);
    NULL_TO_NPVARIANT(result);
    NPN_InvokeDefault(bridge->npp(),
                      function_obj.object_value(),
                      &argv,
                      1,
                      &result);
  }

  return true;
}

bool Calculator::Click(c_salt::ScriptingBridge* bridge,
                      const NPVariant* args,
                      uint32_t arg_count,
                      NPVariant* return_value) {
  if (play_button_sound_) {
    // Schedule a click sound to be played.
    click_pending_ = true;
  }
  return true;
}

bool Calculator::GetButtonSound(c_salt::ScriptingBridge* bridge,
                                NPVariant* return_value) {
  BOOLEAN_TO_NPVARIANT(play_button_sound_, *return_value);
  return true;
}

bool Calculator::SetButtonSound(c_salt::ScriptingBridge* bridge,
                                const NPVariant* value) {
  if (!value)
    return false;
  c_salt::Type* set_sound = c_salt::Type::CreateFromNPVariant(*value);
  play_button_sound_ = set_sound ? set_sound->bool_value() : false;
  delete set_sound;
  return true;
}

double Calculator::EvaluateExpression(
    const c_salt::Type::TypeArray* expression) {
  if (expression->size() == 0)
    return 0.0;
  // This is a pretty simple-minded expression evaulator.  It assumes that the
  // input vector represents a correct postfix expression and does basically
  // no error checking.  If the input vector is badly formed, then you will get
  // unpredicatble results.
  std::vector<double> expr_stack;
  std::vector<c_salt::Type*>::const_iterator it;
  for (it = expression->begin(); it != expression->end(); ++it) {
    if ((*it)->type_id() == c_salt::Type::kStringTypeId) {
      const std::string* oper =
          (static_cast<c_salt::StringType*>(*it))->string_value();
      double term0, term1;
      switch (oper->at(0)) {
      case '+':
        if (expr_stack.size() < 2)
          return 0.0;
        term0 = expr_stack.back();
        expr_stack.pop_back();
        term1 = expr_stack.back();
        expr_stack.pop_back();
        expr_stack.push_back(term1 + term0);
        break;
      case '-':
        if (expr_stack.size() < 2)
          return 0.0;
        term0 = expr_stack.back();
        expr_stack.pop_back();
        term1 = expr_stack.back();
        expr_stack.pop_back();
        expr_stack.push_back(term1 - term0);
        break;
      case '/':
        if (expr_stack.size() < 2)
          return 0.0;
        term0 = expr_stack.back();
        expr_stack.pop_back();
        term1 = expr_stack.back();
        expr_stack.pop_back();
        expr_stack.push_back(term1 / term0);
        break;
      case '*':
        if (expr_stack.size() < 2)
          return 0.0;
        term0 = expr_stack.back();
        expr_stack.pop_back();
        term1 = expr_stack.back();
        expr_stack.pop_back();
        expr_stack.push_back(term1 * term0);
        break;
      default:
        expr_stack.push_back(atof(oper->c_str()));
        break;
      }
    } else {
      switch ((*it)->type_id()) {
      case c_salt::Type::kInt32TypeId:
        expr_stack.push_back(static_cast<double>(
            (static_cast<c_salt::Int32Type*>(*it))->int32_value()));
        break;
      case c_salt::Type::kDoubleTypeId:
        expr_stack.push_back(
            (static_cast<c_salt::DoubleType*>(*it))->double_value());
        break;
      }
    }
  }

  assert(expr_stack.size() == 1);
  double expr_value = expr_stack.back();
  expr_stack.pop_back();
  return expr_value;
}

void Calculator::SynthesizeButtonClick(NPDeviceContextAudio *context) {
  const size_t sample_count  = context->config.sampleFrameCount;
  const size_t channel_count = context->config.outputChannelMap;
  const size_t sample_copy_count = sample_count * channel_count;
  int16_t* buf = reinterpret_cast<int16_t*>(context->outBuffer);
  memset(buf, 0, sample_copy_count * sizeof(int16_t));
  if (click_pending_) {
    size_t remaining_samples = kClickSampleCount - current_sample_index_;
    size_t copy_count = std::min(sample_copy_count, remaining_samples);
    memcpy(buf,
           &click_sound_samples[current_sample_index_],
           copy_count * sizeof(int16_t));
    current_sample_index_ += sample_copy_count;
    if (current_sample_index_ > kClickSampleCount) {
      current_sample_index_ = 0;
      // Indicate that the sound has finished playing.
      click_pending_ = false;
    }
  }
}

void Calculator::SetWindow(NPP instance, int width, int height) {
  if (device_audio_)
    return;
  // Initialize the audio context.
  device_audio_ = NPN_AcquireDevice(instance, NPPepperAudioDevice);
  assert(device_audio_);
  memset(&context_audio_, 0, sizeof(context_audio_));
  NPDeviceContextAudioConfig cfg;
  cfg.sampleRate = 44100;
  cfg.sampleType = NPAudioSampleTypeInt16;
  cfg.outputChannelMap = NPAudioChannelStereo;
  cfg.inputChannelMap = NPAudioChannelNone;
  cfg.sampleFrameCount = 1024;
  cfg.startThread = 1;  // Start a thread for the audio producer.
  cfg.flags = 0;
  cfg.callback = &AudioCallback;
  cfg.userData = reinterpret_cast<void*>(this);
  NPError init_err = device_audio_->initializeContext(instance,
                                                      &cfg,
                                                      &context_audio_);
  assert(NPERR_NO_ERROR == init_err);
}

}  // namespace calculator
