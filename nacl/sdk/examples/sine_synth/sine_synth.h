// Copyright 2010 The Native Client Authors. All rights reserved.
// Use of this source code is governed by a BSD-style license that can
// be found in the LICENSE file.

#ifndef EXAMPLES_SINE_SYNTH_H_
#define EXAMPLES_SINE_SYNTH_H_

#include <pthread.h>
#include <algorithm>
#include <limits>
#include <map>

#if defined(__native_client__)
#include <nacl/nacl_npapi.h>
#include <nacl/npapi_extensions.h>
#else
// Building a trusted plugin for debugging.
#include "third_party/npapi/bindings/npapi.h"
#include "third_party/npapi/bindings/npapi_extensions.h"
#include "third_party/npapi/bindings/nphostapi.h"
#endif

namespace sine_synth {

class SineSynth {
 public:
  explicit SineSynth(NPP npp);
  ~SineSynth();

  NPObject* GetScriptableObject();
  NPError SetWindow(NPWindow* window);
  bool PlaySound();
  bool StopSound();

  // The Pepper audio callback that does the actual synthesis of the sine wave.
  // This is called "open loop" - that is it is always called by the sound
  // thread in the Pepper runtime.  This example uses a simple flag to decide
  // whether to fill the buffer with sound samples (playing) or all-0 (stopped).
  void SynthesizeSineWave(NPDeviceContextAudio *context);

  // Accessor/.mutators for the frequency property.  Frequency is clamped to
  // (20, 22000) Hz.
  int32_t frequency() const {
    return frequency_;
  }
  void set_frequency(int32_t freq) {
    frequency_ = std::max<int32_t>(std::min<int32_t>(22000, freq), 20);
  }

  int width() const {
    return window_ ? window_->width : 0;
  }
  int height() const {
    return window_ ? window_->height : 0;
  }

 private:
  // Create and initialize the audio context used for playback.
  void CreateContext();
  // Destroy the audio context.
  void DestroyContext();
  bool IsContextValid() {
    return device_audio_ != NULL;
  }

  NPP       npp_;
  NPObject* scriptable_object_;  // strong reference

  NPWindow* window_;
  NPDevice* device_audio_;  // The PINPAPI audio device.
  NPDeviceContextAudio context_audio_;  // The PINPAPI audio context.

  bool play_sound_;
  int32_t frequency_;
  // Store the time value to avoid clicks at buffer boundaries.
  uint32_t time_value_;
};

}  // namespace sine_synth

#endif  // EXAMPLES_SINE_SYNTH_H_
