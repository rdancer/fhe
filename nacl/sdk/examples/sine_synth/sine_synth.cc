// Copyright 2010 The Native Client Authors. All rights reserved.
// Use of this source code is governed by a BSD-style license that can
// be found in the LICENSE file.

#include "examples/sine_synth/sine_synth.h"

#include <assert.h>
#include <math.h>
#include <stdio.h>
#include <stdlib.h>
#include <string.h>

#include <limits>

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

#include "examples/sine_synth/scripting_bridge.h"

extern NPDevice* NPN_AcquireDevice(NPP instance, NPDeviceID device);

using sine_synth::ScriptingBridge;

// Audio device call-back wrapper.
void AudioCallback(NPDeviceContextAudio *context) {
  if (!context)
    return;
  sine_synth::SineSynth* sine_synth =
      static_cast<sine_synth::SineSynth*>(context->config.userData);
  if (sine_synth) {
    sine_synth->SynthesizeSineWave(context);
  }
}

namespace sine_synth {

static const double kPi = 3.141592653589;

SineSynth::SineSynth(NPP npp)
    : npp_(npp),
      scriptable_object_(NULL),
      window_(NULL),
      device_audio_(NULL),
      play_sound_(false),
      frequency_(440),
      time_value_(0) {
  ScriptingBridge::InitializeIdentifiers();
}

SineSynth::~SineSynth() {
  if (scriptable_object_) {
    NPN_ReleaseObject(scriptable_object_);
  }
}

NPObject* SineSynth::GetScriptableObject() {
  if (scriptable_object_ == NULL) {
    scriptable_object_ =
      NPN_CreateObject(npp_, &ScriptingBridge::np_class);
  }
  if (scriptable_object_) {
    NPN_RetainObject(scriptable_object_);
  }
  return scriptable_object_;
}

NPError SineSynth::SetWindow(NPWindow* window) {
  if (!window)
    return NPERR_NO_ERROR;
  if (!IsContextValid())
    CreateContext();
  if (!IsContextValid())
    return NPERR_GENERIC_ERROR;
  window_ = window;
  return NPERR_NO_ERROR;
}

void SineSynth::SynthesizeSineWave(NPDeviceContextAudio *context) {
  const size_t sample_count  = context->config.sampleFrameCount;
  const size_t channel_count = context->config.outputChannelMap;
  const double theta = 2 * kPi * frequency_ / context->config.sampleRate;
  int16_t* buf = reinterpret_cast<int16_t*>(context->outBuffer);
  if (play_sound_) {
    for (size_t sample = 0; sample < sample_count; ++sample) {
      int16_t value = static_cast<int16_t>(sin(theta * time_value_) *
                                       std::numeric_limits<int16_t>::max());
      ++time_value_;  // Just let this wrap.
      for (size_t channel = 0; channel < channel_count; ++channel) {
        *buf++ = value;
      }
    }
  } else {
    memset(buf, 0, sample_count * channel_count * sizeof(int16_t));
  }
}

bool SineSynth::PlaySound() {
  play_sound_ = true;
  return true;
}

bool SineSynth::StopSound() {
  play_sound_ = false;
  return true;
}

void SineSynth::CreateContext() {
  if (IsContextValid())
    return;
  device_audio_ = NPN_AcquireDevice(npp_, NPPepperAudioDevice);
  assert(IsContextValid());
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
  NPError init_err = device_audio_->initializeContext(npp_,
                                                      &cfg,
                                                      &context_audio_);
  assert(NPERR_NO_ERROR == init_err);
}

void SineSynth::DestroyContext() {
  if (!IsContextValid())
    return;
}

}  // namespace sine_synth
