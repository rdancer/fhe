// Copyright 2010 The Native Client Authors. All rights reserved.
// Use of this source code is governed by a BSD-style license that can
// be found in the LICENSE file.

#ifndef CALCULATOR_C_SALT_DOUBLE_TYPE_H_
#define CALCULATOR_C_SALT_DOUBLE_TYPE_H_

#include "c_salt/type.h"

#include <math.h>

namespace c_salt {

class DoubleType : public Type {
 public:
  explicit DoubleType(double double_value);
  virtual ~DoubleType();

  virtual bool bool_value() const {
    return double_value() != 0.0;
  }
  virtual int32_t int32_value() const {
    return static_cast<int32_t>(floor(double_value()) + 0.5);
  }

  virtual double double_value() const {
    return double_value_;
  }

 private:
  static const size_t kMaxStrLength;
  double double_value_;
};

}  // namespace c_salt

#endif  // CALCULATOR_C_SALT_DOUBLE_TYPE_H_
