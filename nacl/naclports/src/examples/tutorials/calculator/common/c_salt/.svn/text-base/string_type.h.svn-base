// Copyright 2010 The Native Client Authors. All rights reserved.
// Use of this source code is governed by a BSD-style license that can
// be found in the LICENSE file.

#ifndef CALCULATOR_C_SALT_STRING_TYPE_H_
#define CALCULATOR_C_SALT_STRING_TYPE_H_

#include "c_salt/type.h"

#include <string>

namespace c_salt {

class StringType : public Type {
 public:
  // Ctor makes a copy of string in local memeory.  If the string ever needs to
  // be sent back to the browser, use CreateNPStringCopy();
  explicit StringType(const NPString& np_string);
  virtual ~StringType();

  // Creates a copy of the internal string in the browser's memory.  The caller
  // is responsible for deleting the memory.  The string pointer must be freed
  // using NPN_MemFree().
  bool CreateNPVariantCopy(NPVariant& np_var) const;

  virtual bool bool_value() const;
  virtual int32_t int32_value() const;
  virtual double double_value() const;

  // Accessor for the internal string.
  const std::string* string_value() const {
    return string_value_;
  }

 private:
  std::string* string_value_;
};

}  // namespace c_salt

#endif  // CALCULATOR_C_SALT_STRING_TYPE_H_
