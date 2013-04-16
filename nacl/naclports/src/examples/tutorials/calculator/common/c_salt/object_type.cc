// Copyright 2010 The Native Client SDK Authors. All rights reserved.
// Use of this source code is governed by a BSD-style license that can
// be found in the LICENSE file.

#include "c_salt/object_type.h"

namespace c_salt {

ObjectType::ObjectType(NPObject* np_object) : Type(kObjectTypeId) {
  if (np_object == NULL)
    np_object_ = NULL;
  else
    np_object_ = NPN_RetainObject(np_object);
}

ObjectType::~ObjectType() {
  if (np_object_ != NULL)
    NPN_ReleaseObject(np_object_);
}

bool ObjectType::CreateNPVariantCopy(NPVariant& np_var) {
  if (np_object_ == NULL)
    return false;
  OBJECT_TO_NPVARIANT(NPN_RetainObject(np_object_), np_var);
  return true;
}

// TODO(dspringer): these all need to try and get the object's value, then
// return something sensible.
bool ObjectType::bool_value() const {
  return false;
}

int32_t ObjectType::int32_value() const {
  return 0;
}

double ObjectType::double_value() const {
  return 0.0;
}

}  // namespace c_salt
