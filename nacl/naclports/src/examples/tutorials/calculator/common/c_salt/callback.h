// Copyright 2010 The Native Client SDK Authors. All rights reserved.
// Use of this source code is governed by a BSD-style license that can
// be found in the LICENSE file.

#ifndef C_SALT_CALLBACK_H_
#define C_SALT_CALLBACK_H_

namespace c_salt {

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

// Class suite used to publish properties to the browser code.  Usage is
// similar to MethodCallback.
class PropertyAccessorCallbackExecutor {
 public:
  virtual bool Execute(ScriptingBridge* bridge,
                       NPVariant* return_value) = 0;
};

template <class T>
class PropertyAccessorCallback : public PropertyAccessorCallbackExecutor {
 public:
  typedef bool (T::*Method)(ScriptingBridge* bridge,
                            NPVariant* return_value);

  PropertyAccessorCallback(T* instance, Method method)
      : instance_(instance), method_(method) {}
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

}  // namespace c_salt

#endif  // C_SALT_CALLBACK_H_

