// Copyright 2010 The Native Client SDK Authors. All rights reserved.
// Use of this source code is governed by a BSD-style license that can
// be found in the LICENSE file.


#include <string.h>

#include <nacl/nacl_npapi.h>
#include <examples/scriptable/duality/scriptable.h>

#include <map>


Scriptable::Scriptable()
    : method_table_(NULL),
      property_table_(NULL),
      npp_(NULL) {
  printf("Duality: Scriptable() was called!\n");
  fflush(stdout);
}

Scriptable::~Scriptable() {
  printf("Duality: ~Scriptable() was called!\n");
  fflush(stdout);
  if (method_table_ != NULL) {
    delete method_table_;
  }
  if (property_table_ != NULL) {
    delete property_table_;
  }
}

void Scriptable::Init(NPP npp) {
  printf("Duality: Scriptable::Init was called!\n");
  fflush(stdout);
  npp_ = npp;
  if (method_table_ == NULL) {
    method_table_ = new IdentifierToMethodMap();
  }
  if (property_table_ == NULL) {
    property_table_ = new IdentifierToPropertyMap();
  }

  InitializeMethodTable();
  InitializePropertyTable();
}

bool Scriptable::GetProperty(NPIdentifier name, NPVariant *result) {
  printf("Duality: Scriptable::GetProperty was called!\n");
  fflush(stdout);
  VOID_TO_NPVARIANT(*result);

  Scriptable::IdentifierToPropertyMap::const_iterator i;
  i = property_table_->find(name);
  if (i != property_table_->end()) {
    return ((i->second))(this, result);
  }
  return false;
}

bool Scriptable::HasMethod(NPIdentifier name) const {
  printf("Duality: Scriptable::HasMethod was called!\n");
  fflush(stdout);
  bool have_method = false;
  Scriptable::IdentifierToMethodMap::const_iterator i;
  i = method_table_->find(name);
  if (i != method_table_->end()) {
    have_method = true;
  }
  printf("Duality: Scriptable::HasMethod returning %d!\n", have_method);
  fflush(stdout);
  return have_method;
}

bool Scriptable::HasProperty(NPIdentifier name) const {
  printf("Duality: Scriptable::HasProperty was called!\n");
  fflush(stdout);
  bool have_property = false;
  Scriptable::IdentifierToPropertyMap::const_iterator i;
  i = property_table_->find(name);
  if (i != property_table_->end()) {
    have_property = true;
  }
  printf("Duality: Scriptable::HasProperty returning %d!\n",
         have_property);
  fflush(stdout);
  return have_property;
}

bool Scriptable::Invoke(NPIdentifier method_name,
                        const NPVariant* args,
                        uint32_t arg_count,
                        NPVariant* result) {
  printf("Duality: Scriptable::Invoke was called!\n");
  fflush(stdout);
  bool rval = false;
  Scriptable::IdentifierToMethodMap::const_iterator i;
  i = method_table_->find(method_name);
  if (i != method_table_->end()) {
    rval = (i->second)(this, args, arg_count, result);
  }
  return rval;
}

bool Scriptable::InvokeDefault(const NPVariant* args,
                               uint32_t arg_count,
                               NPVariant* result) {
  printf("Duality: Scriptable::InvokeDefault was called!\n");
  fflush(stdout);
  return false;  // Not implemented.
}

bool Scriptable::RemoveProperty(NPIdentifier name) {
  printf("Duality: Scriptable::RemoveProperty was called!\n");
  fflush(stdout);
  return false;  // Not implemented.
}

bool Scriptable::SetProperty(NPIdentifier name, const NPVariant* value) {
  printf("Duality: Scriptable::SetProperty was called!\n");
  fflush(stdout);
  return false;  // Not implemented.
}
