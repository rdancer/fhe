// Copyright 2010 The Native Client Authors. All rights reserved.
// Use of this source code is governed by a BSD-style license that can
// be found in the LICENSE file.

#ifndef PHOTO_H_
#define PHOTO_H_

#include <boost/scoped_ptr.hpp>
#include <pthread.h>
#include <nacl/nacl_npapi.h>
#include <nacl/npapi_extensions.h>

#include <map>
#include <string>
#include <vector>

#include "callback.h"
#include "scripting_bridge.h"
#include "surface.h"

namespace photo {

// A photo editing demo for Native Client using open source Javascript sliders
// from Carpe Design.  For more info on these sliders, please see:
//     http://carpe.ambiprospect.com/slider/

class Photo {
 public:
  explicit Photo(NPP npp);
  ~Photo();

  // Bind and publish the module's methods to JavaScript.
  void InitializeMethods(const ScriptingBridge::SharedScriptingBridge& bridge);
  // Bind and publish the module's properties to JavaScript.
  void InitializeProperties(
      const ScriptingBridge::SharedScriptingBridge& bridge);

  // Get the value for a key from |parameter_dictionary_|; the value is
  // returned via |return_value|.  If the key does not exist, then a NULL
  // value is returned.  |arg_count| must be atleast 1; |args[0]| is the key
  // and must be an NPString.  Return |true| on success.
  bool GetValueForKey(photo::ScriptingBridge* bridge,
                      const NPVariant* args,
                      uint32_t arg_count,
                      NPVariant* return_value) const;

  // Set a value in |parameter_dictionary_|; the requested key must already
  // exist in |parameter_dictionary_|.  See ModuleDidLoad() for a list of
  // available keys.  |arg_count| must be at least 2; |args[0]| is the value,
  // and must be a simple type (int, double, string, bool).  Objects are not
  // accepted.  All values are converted to and stored as floats; for example
  // the string "1.3" is stored as a float 1.3f.  |args[1]| is the key and must
  // be an NPString.  Return |true| on success.
  // This method is bound to the JavaScript "setValueForKey" method and is
  // called like this:
  //     module.setValueForKey(value, 'myKey');
  bool SetValueForKey(photo::ScriptingBridge* bridge,
                      const NPVariant* args,
                      uint32_t arg_count,
                      NPVariant* value);

  // Accessor for the "imageUrl" property.
  bool GetImageUrl(ScriptingBridge* bridge, NPVariant* return_value) const;

  // Mutator for the "imageUrl" property.  Copies the URL in |value| into local
  // storage and the initiates a GET request to the URL.
  bool SetImageUrl(ScriptingBridge* bridge, const NPVariant* value);

  // Copy the original photo to the working photo, possbily resampling and
  // rotating the working photo.  The working photo has the same dimensions as
  // the module's window, inset by the border size.
  void FitPhotoToWorking();

  // Rotate |photo| by |angle| degrees, leaving the result in |rotated_photo|.
  void RotatePhoto(const Surface* photo, Surface* rotated_photo, float angle);

  // Apply the current values in |parameter_dictionary_| to |working_photo|
  // and puts the results in |final_photo|.
  void ApplyWorkingToFinal(const Surface* working_photo,
                           Surface* final_photo);

  // Called once when SetWindow() is called on the module for the first time.
  // At this point it is safe to call NPN_* functions on the browser and set up
  // the Pepper context, etc.
  void ModuleDidLoad();

  // Handle a Pepper event.  Return |true| if the event was handled by the
  // module.  In this case the event is not propagated to other elements in
  // the browser.
  bool HandleEvent(const NPPepperEvent& event);

  // Called whenever the in-browser window changes size.
  bool SetWindow(int width, int height);

  // Called when the GET request initiated in SetImageUrl() completes.  At this
  // point, the file is available to the module.
  bool URLDidDownload(NPStream* stream);

  // Return the browser-facing NPObject binding to the ScriptingBridge that
  // represents the module.
  NPObject* GetScriptableObject(NPP instance);

 private:
  typedef std::map<std::string, float> ParameterDictionary;

  // Create and initialize the 2D context used for drawing.
  void CreateContext();
  // Destroy the 2D drawing context.
  void DestroyContext();
  // A context is valid when the Pepper device has been successfully acuqired
  // and initialized.
  bool IsContextValid();
  // Copy the fully constructed photo to the Pepper 2D context and flush it
  // to the browser.
  bool Paint();

  NPP npp_;
  ScriptingBridge::SharedScriptingBridge scripting_bridge_;

  // Internal storage for the imageUrl property exposed to JavaScript.
  std::string image_url_;

  // The dimensions of the module's window in the browser.
  int window_width_;
  int window_height_;

  // A dictionary of photo editing parameters.  This is populated with keys and
  // default values in ModuleDidLoad().
  ParameterDictionary parameter_dictionary_;

  // The various surfaces used to assemble the final processed image.
  boost::scoped_ptr<Surface> original_photo_;
  boost::scoped_ptr<Surface> temp_photo_;
  boost::scoped_ptr<Surface> working_photo_;
  boost::scoped_ptr<Surface> rotated_photo_;
  boost::scoped_ptr<Surface> final_photo_;

  NPDevice* device2d_;  // The Pepper 2D device.
  NPDeviceContext2D context2d_;  // The Pepper 2D drawing context.
};

}  // namespace photo

#endif  // PHOTO_H_
