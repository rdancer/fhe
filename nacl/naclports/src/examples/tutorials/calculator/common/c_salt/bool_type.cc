// Copyright 2010 The Native Client SDK Authors. All rights reserved.
// Use of this source code is governed by a BSD-style license that can
// be found in the LICENSE file.

#include "c_salt/bool_type.h"

namespace c_salt {

BoolType::BoolType(bool bool_value)
    : Type(kBoolTypeId), bool_value_(bool_value) {
}

BoolType::~BoolType() {
}

}  // namespace c_salt