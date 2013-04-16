// Copyright 2010 The Native Client SDK Authors. All rights reserved.
// Use of this source code is governed by a BSD-style license that can
// be found in the LICENSE file.

#ifndef CALCULATOR_NACL_CS_TYPE_H_
#define CALCULATOR_NACL_CS_TYPE_H_

#if defined(__native_client__)
#include <nacl/npruntime.h>
#else
#include "third_party/npapi/bindings/npruntime.h"
#endif

#include "c_salt/basic_macros.h"

#include <string>
#include <vector>

// A polymorphic type container.  All the various number and string types
// are derived from this basic class.

namespace c_salt {

class ScriptingBridge;

class Type {
 public:
  typedef int TypeId;
  typedef std::vector<Type*> TypeArray;

  static const int kClassVersion = 1;
  enum {
    kNullTypeId,
    kBoolTypeId,
    kInt32TypeId,
    kDoubleTypeId,
    kStringTypeId,
    kObjectTypeId
  };

  // Create a new Type from the contents of |np_var|.  The caller is
  // responsible for freeing the resulting Type.  Note that complex types
  // such as strings and objects are retained in the browser, and a pointer
  // to that memory is cached in the Type.  This is the only way to create
  // a generic Type.  To create a specific Type, use one of the sub-class
  // ctors.
  static Type* CreateFromNPVariant(const NPVariant& np_var);

  // Create a new TypeArray from the contents of |np_array|.  The caller is
  // responsible for freeing the resulting array.
  static TypeArray* CreateArrayFromNPVariant(const ScriptingBridge* bridge,
                                             const NPVariant& np_array);

  explicit Type(TypeId type_id);
  virtual ~Type();

  TypeId type_id() const {
    return type_id_;
  }

  // Return the closest representation of the internal type that matches the
  // requested type.  Some conversion are not possible; if the the internal
  // type is an object, an attempt is made to evaluate the object.
  virtual bool bool_value() const;
  virtual int32_t int32_value() const;
  virtual double double_value() const;
  // TODO(dspringer): This needs to return a const scoped_ptr<std::string>&;
  // implement this when we can pull in boost o some other lib that has a
  // scoped_ptr.
  //virtual const std::string& string_value() const;

 private:
  uint32_t class_version_;
  TypeId type_id_;

  DISALLOW_IMPLICIT_CONSTRUCTORS(Type);
};

}  // namespace c_salt

#endif  // CALCULATOR_NACL_CS_TYPE_H_
