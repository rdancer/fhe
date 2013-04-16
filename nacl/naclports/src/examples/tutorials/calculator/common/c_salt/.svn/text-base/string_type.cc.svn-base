// Copyright 2010 The Native Client SDK Authors. All rights reserved.
// Use of this source code is governed by a BSD-style license that can
// be found in the LICENSE file.

#include "c_salt/string_type.h"

#include <stdlib.h>
#include <string.h>

namespace c_salt {

StringType::StringType(const NPString& np_string) : Type(kStringTypeId) {
  string_value_ = new std::string(
      static_cast<const char*>(np_string.UTF8Characters),
      np_string.UTF8Length);
}

StringType::~StringType() {
  delete string_value_;
}

bool StringType::CreateNPVariantCopy(NPVariant& np_var) const {
  NULL_TO_NPVARIANT(np_var);
  if (string_value_ == NULL)
    return false;
  uint32_t length = string_value_->size();
  NPUTF8* utf8_string = reinterpret_cast<NPUTF8*>(NPN_MemAlloc(length+1));
  memcpy(utf8_string, string_value_->c_str(), length);
  utf8_string[length] = '\0';
  STRINGN_TO_NPVARIANT(utf8_string, length, np_var);
  return true;
}

bool StringType::bool_value() const {
  if (!string_value() || string_value()->length() == 0)
    return false;
  int ch = tolower(string_value()->at(0));
  return ch == 'y' || ch == '1' || ch == 't';
}

int32_t StringType::int32_value() const {
  return string_value() ? atol(string_value()->c_str()) : 0;
}

double StringType::double_value() const {
  return string_value() ? atof(string_value()->c_str()) : 0.0;
}

}  // namespace c_salt
