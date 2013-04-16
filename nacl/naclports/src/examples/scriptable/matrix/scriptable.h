// Copyright 2010 The Native Client SDK Authors. All rights reserved.
// Use of this source code is governed by a BSD-style license that can
// be found in the LICENSE file.

#ifndef EXAMPLES_SRPC_MATRIX_SCRIPTABLE_H_
#define EXAMPLES_SRPC_MATRIX_SCRIPTABLE_H_


#include <string.h>

#include <nacl/nacl_npapi.h>

#include <map>

// Extend this class and add object-specific functions and properties to the
// method table and property table.
class Scriptable {
 public:
  Scriptable();
  virtual ~Scriptable();

  // Ensures that members are initialized.
  virtual void Init(NPP npp);

  bool GetProperty(NPIdentifier name, NPVariant *result);

  bool HasMethod(NPIdentifier name) const;

  bool HasProperty(NPIdentifier name) const;

  bool Invoke(NPIdentifier method_name,
              const NPVariant* args,
              uint32_t arg_count,
              NPVariant* result);

  bool InvokeDefault(const NPVariant* args,
                     uint32_t arg_count,
                     NPVariant* result);

  bool RemoveProperty(NPIdentifier name);

  bool SetProperty(NPIdentifier name, const NPVariant* value);

  // NPP is needed in order to get the NPP object when calling NPN_GetProperty
  const NPP npp() const { return npp_; }

 protected:
  // These can't be scriptable::functions because that would make this
  // uninheritable.
  typedef bool (*Method)(Scriptable* instance,
                         const NPVariant* args,
                         uint32_t arg_count,
                         NPVariant* result);
  typedef bool (*Property)(Scriptable* instance,
                           NPVariant* result);

  typedef std::map<NPIdentifier, Method> IdentifierToMethodMap;
  typedef std::map<NPIdentifier, Property> IdentifierToPropertyMap;


  // Implemented by the final class.
  virtual void InitializeMethodTable() = 0;
  // Implemented by the final class.
  virtual void InitializePropertyTable() = 0;

  IdentifierToMethodMap* method_table_;
  IdentifierToPropertyMap* property_table_;

 private:
  NPP npp_;
};

#endif  // EXAMPLES_SRPC_DUALITY_SCRIPTABLE_H_
