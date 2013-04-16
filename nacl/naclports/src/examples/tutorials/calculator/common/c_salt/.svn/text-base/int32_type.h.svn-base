// Copyright 2010 The Native Client Authors. All rights reserved.
// Use of this source code is governed by a BSD-style license that can
// be found in the LICENSE file.

#ifndef CALCULATOR_C_SALT_INT32_TYPE_H_
#define CALCULATOR_C_SALT_INT32_TYPE_H_

#include "c_salt/type.h"

namespace c_salt {

class Int32Type : public Type {
 public:
  explicit Int32Type(int32_t int32_value);
  virtual ~Int32Type();

  virtual bool bool_value() const {
    return int32_value() != 0;
  }
  virtual double double_value() const {
    return static_cast<double>(int32_value());
  }

  virtual int32_t int32_value() const {
    return int32_value_;
  }

 private:
  static const size_t kMaxStrLength;
  int32_t int32_value_;
};

}  // namespace c_salt

#endif  // CALCULATOR_C_SALT_INT32_TYPE_H_
