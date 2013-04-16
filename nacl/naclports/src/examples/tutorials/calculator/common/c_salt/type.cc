// Copyright 2010 The Native Client SDK Authors. All rights reserved.
// Use of this source code is governed by a BSD-style license that can
// be found in the LICENSE file.

#include "c_salt/type.h"
#include "c_salt/bool_type.h"
#include "c_salt/double_type.h"
#include "c_salt/int32_type.h"
#include "c_salt/object_type.h"
#include "c_salt/scripting_bridge.h"
#include "c_salt/string_type.h"

namespace c_salt {

Type* Type::CreateFromNPVariant(const NPVariant& np_var) {
  Type* type = NULL;
  switch (np_var.type) {
  case NPVariantType_Bool:
    type = new BoolType(np_var.value.boolValue);
    break;
  case NPVariantType_Int32:
    type = new Int32Type(np_var.value.intValue);
    break;
  case NPVariantType_Double:
    type = new DoubleType(np_var.value.doubleValue);
    break;
  case NPVariantType_String:
    type = new StringType(np_var.value.stringValue);
    break;
  case NPVariantType_Object:
    type = new ObjectType(np_var.value.objectValue);
    break;
  default:
    break;
  }
  return type;
}

Type::TypeArray* Type::CreateArrayFromNPVariant(const ScriptingBridge* bridge,
                                                const NPVariant& np_array) {
  if (!NPVARIANT_IS_OBJECT(np_array))
    return NULL;
  // Unpack the expression object.  This turns the JavasCript array into a
  // vector of strings, which is then passed into the Calculator object.
  NPP npp = bridge->npp();
  NPIdentifier* identifier = NULL;
  uint32_t element_count = 0;
  NPObject* array_object = NPVARIANT_TO_OBJECT(np_array);
  TypeArray* type_array = new TypeArray;
  if (NPN_Enumerate(npp, array_object, &identifier, &element_count)) {
    for (uint32_t j = 0; j < element_count; ++j) {
      if (NPN_HasProperty(npp, array_object, identifier[j])) {
        // Get each element out of the array by accessing the property whose
        // identifier is the array subscript.
        NPVariant array_elem;
        VOID_TO_NPVARIANT(array_elem);
        if (NPN_GetProperty(npp,
                            array_object,
                            identifier[j],
                            &array_elem)) {
          type_array->push_back(CreateFromNPVariant(array_elem));
        }
      }
    }
    NPN_MemFree(identifier);
  }
  return type_array;
}

Type::Type(TypeId type_id) : class_version_(kClassVersion), type_id_(type_id) {
}

Type::~Type() {
}

bool Type::bool_value() const {
  return false;
}

int32_t Type::int32_value() const {
  return 0;
}

double Type::double_value() const {
  return 0.0;
}

}  // namespace c_salt
