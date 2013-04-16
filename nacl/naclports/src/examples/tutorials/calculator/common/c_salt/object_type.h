// Copyright 2010 The Native Client Authors. All rights reserved.
// Use of this source code is governed by a BSD-style license that can
// be found in the LICENSE file.

#ifndef CALCULATOR_C_SALT_OBJECT_TYPE_H_
#define CALCULATOR_C_SALT_OBJECT_TYPE_H_

#include "c_salt/type.h"

namespace c_salt {

class ObjectType : public Type {
 public:
  // Ctor ref counts the object in the browser; dtor releases.
  explicit ObjectType(NPObject* np_object);
  virtual ~ObjectType();

  // Assign the internal NPObject to |np_var|, and increase the ref count.
  // Caller must free the resulting object using NPN_ReleaseObject().
  bool CreateNPVariantCopy(NPVariant& np_var);

  virtual bool bool_value() const;
  virtual int32_t int32_value() const;
  virtual double double_value() const;

  NPObject* object_value() const {
    return np_object_;
  }

 private:
  NPObject* np_object_;
};

}  // namespace c_salt
#endif  // CALCULATOR_C_SALT_OBJECT_TYPE_H_
