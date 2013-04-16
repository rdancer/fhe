// Copyright 2008 The Native Client SDK Authors. All rights reserved.
// Use of this source code is governed by a BSD-style license that can
// be found in the LICENSE file.


#include <examples/scriptable/duality/print_number.h>

#include <stdbool.h>
#include <stdio.h>
#include <stdlib.h>
#include <string.h>

#include <nacl/nacl_npapi.h>
#include <nacl/nacl_srpc.h>

PrintNumber::PrintNumber()
    : Scriptable() {
  printf("Daulity: PrintNumber() was called!\n");
  fflush(stdout);
}

PrintNumber::~PrintNumber() {
  printf("Daulity: ~PrintNumber() was called!\n");
  fflush(stdout);
}

bool PrintNumber::Print42(Scriptable * instance,
                          const NPVariant* args,
                          uint32_t arg_count,
                          NPVariant* result) {
  printf("Daulity: PrintNumber::Print42 was called via NPAPI!\n");
  fflush(stdout);
  if (result) {
    INT32_TO_NPVARIANT(42, *result);
  }
  return true;
}

void PrintNumber::InitializeMethodTable() {
  printf("Daulity: PrintNumber::InitializeMethodTable was called!\n");
  fflush(stdout);
  NPIdentifier print_number_id = NPN_GetStringIdentifier("Print42");
  IdentifierToMethodMap::value_type tMethodEntry(print_number_id,
                                                 &PrintNumber::Print42);
  method_table_->insert(tMethodEntry);
}
