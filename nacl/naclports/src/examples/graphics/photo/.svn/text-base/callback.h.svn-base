// Copyright 2010 The Native Client SDK Authors. All rights reserved.
// Use of this source code is governed by a BSD-style license that can
// be found in the LICENSE file.

#ifndef PHOTO_CALLBACK_H_
#define PHOTO_CALLBACK_H_

namespace photo {

class ScriptingBridge;

// Templates used to support method call-backs when a method or property is
// accessed from the browser code.

// Class suite used to publish a method name to JavaScript.  Typical use is
// like this:
//     photo::MethodCallback<Calculator>* calculate_callback_;
//     calculate_callback_ =
//        new photo::MethodCallback<Calculator>(this, &Calculator::Calculate);
//     bridge->AddMethodNamed("calculate", calculate_callback_);
//     ...
//     delete calculate_callback_;
//
// Tne caller must delete the callback.

// Pure virtual class used in STL containers.
class MethodCallbackExecutor {
 public:
  virtual ~MethodCallbackExecutor() {}
  virtual bool Execute(ScriptingBridge* bridge,
                       const NPVariant* args,
                       uint32_t arg_count,
                       NPVariant* return_value) = 0;
};

template <class T>
class MethodCallback : public MethodCallbackExecutor {
 public:
  typedef bool (T::*Method)(ScriptingBridge* bridge,
                            const NPVariant* args,
                            uint32_t arg_count,
                            NPVariant* return_value);

  MethodCallback(T* instance, Method method)
      : instance_(instance), method_(method) {}
  virtual ~MethodCallback() {}
  virtual bool Execute(ScriptingBridge* bridge,
                       const NPVariant* args,
                       uint32_t arg_count,
                       NPVariant* return_value) {
    // Use "this->" to force C++ to look inside our templatized base class; see
    // Effective C++, 3rd Ed, item 43, p210 for details.
    return ((this->instance_)->*(this->method_))(bridge,
                                                 args,
                                                 arg_count,
                                                 return_value);
  }

 private:
  T* instance_;
  Method method_;
};

template <class T>
class ConstMethodCallback : public MethodCallbackExecutor {
 public:
  typedef bool (T::*ConstMethod)(ScriptingBridge* bridge,
                                 const NPVariant* args,
                                 uint32_t arg_count,
                                 NPVariant* return_value) const;

  ConstMethodCallback(T* instance, ConstMethod method)
      : instance_(instance), const_method_(method) {}
  virtual ~ConstMethodCallback() {}
  virtual bool Execute(ScriptingBridge* bridge,
                       const NPVariant* args,
                       uint32_t arg_count,
                       NPVariant* return_value) {
    // Use "this->" to force C++ to look inside our templatized base class; see
    // Effective C++, 3rd Ed, item 43, p210 for details.
    return ((this->instance_)->*(this->const_method_))(bridge,
                                                       args,
                                                       arg_count,
                                                       return_value);
  }

 private:
  T* instance_;
  ConstMethod const_method_;
};

// Class suite used to publish properties to the browser code.  Usage is
// similar to MethodCallback.
//     photo::PropertyAccessorCallbackExecutor<Photo>* get_brightness;
//     get_brightness =
//        new photo::PropertyAccessorCallback<Photo>(
//            this, &Photo::GetBrightness);
//     photo::PropertyMutatorCallbackExecutor<Photo>* set_brightness;
//     set_brightness =
//        new photo::PropertyMutatorCallback<Photo>(
//            this, &Photo::SetBrightness);
//     bridge->AddPropertyNamed("brightness", get_brightness, set_brightness);
//     ...
//     delete get_brightness;
//     delete set_brightness
// Note that the accessor method has to be declared const.
//
// Tne caller must delete the callback.
class PropertyAccessorCallbackExecutor {
 public:
  virtual bool Execute(ScriptingBridge* bridge,
                       NPVariant* return_value) = 0;
};

template <class T>
class PropertyAccessorCallback : public PropertyAccessorCallbackExecutor {
 public:
  typedef bool (T::*Method)(ScriptingBridge* bridge,
                            NPVariant* return_value) const;

  PropertyAccessorCallback(T* instance, Method method)
      : instance_(instance), method_(method) {}
  virtual ~PropertyAccessorCallback() {}
  virtual bool Execute(ScriptingBridge* bridge,
                       NPVariant* return_value) {
    // Use "this->" to force C++ to look inside our templatized base class; see
    // Effective C++, 3rd Ed, item 43, p210 for details.
    return ((this->instance_)->*(this->method_))(bridge, return_value);
  }

 private:
  T* instance_;
  Method method_;
};

class PropertyMutatorCallbackExecutor {
 public:
  virtual bool Execute(ScriptingBridge* bridge,
                       const NPVariant* value) = 0;
};

template <class T>
class PropertyMutatorCallback : public PropertyMutatorCallbackExecutor {
 public:
  typedef bool (T::*Method)(ScriptingBridge* bridge,
                            const NPVariant* return_value);

  PropertyMutatorCallback(T* instance, Method method)
      : instance_(instance), method_(method) {}
  virtual ~PropertyMutatorCallback() {}
  virtual bool Execute(ScriptingBridge* bridge,
                       const NPVariant* value) {
    // Use "this->" to force C++ to look inside our templatized base class; see
    // Effective C++, 3rd Ed, item 43, p210 for details.
    return ((this->instance_)->*(this->method_))(bridge, value);
  }

 private:
  T* instance_;
  Method method_;
};

}  // namespace photo

#endif  // PHOTO_CALLBACK_H_

