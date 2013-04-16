// Copyright 2008 The Native Client SDK Authors. All rights reserved.
// Use of this source code is governed by a BSD-style license that can
// be found in the LICENSE file.


#include <examples/scriptable/duality/say_hello.h>
#include <examples/scriptable/duality/print_number.h>
#include <examples/scriptable/duality/scripting_bridge.h>

#include <stdbool.h>
#include <stdio.h>
#include <stdlib.h>
#include <string.h>

#include <nacl/nacl_npapi.h>
#include <nacl/nacl_srpc.h>

SayHello::SayHello()
    : Scriptable() {
  printf("Duality: SayHello() was called!\n");
  fflush(stdout);
}

SayHello::~SayHello() {
  printf("Duality: ~SayHello() was called!\n");
  fflush(stdout);
}

bool SayHello::GetPrint42(const NPVariant* args,
                          uint32_t arg_count,
                          NPVariant* result) {
  printf("Duality: SayHello::GetPrint42 was called!\n");
  fflush(stdout);
  if (result) {
    NPObject * print42_object =
        NPN_CreateObject(NPP(),
                         ScriptingBridge::GetNPSimpleClass<PrintNumber>());
    OBJECT_TO_NPVARIANT(print42_object, *result);

  }
  return true;
}

bool SayHello::GetPrint42CB(Scriptable * instance,
                            const NPVariant* args,
                            uint32_t arg_count,
                            NPVariant* result) {
  printf("Duality: SayHello::GetPrint42Callback was called via NPAPI!\n");
  fflush(stdout);
  SayHello * say_hello = static_cast<SayHello *>(instance);
  return say_hello->GetPrint42(args, arg_count, result);
}

bool SayHello::HelloWorld(Scriptable * instance,
                          const NPVariant* args,
                          uint32_t arg_count,
                          NPVariant* result) {
  printf("Duality: SayHello::HelloWorld was called via NPAPI!\n");
  fflush(stdout);
  if (result) {
    const char *msg = "Hello from a specialized Scriptable!";
    const int msg_length = strlen(msg) + 1;
    // Note: |msg_copy| will be freed later on by the browser, so it needs to
    // be allocated here with NPN_MemAlloc().
    char *msg_copy = reinterpret_cast<char*>(NPN_MemAlloc(msg_length));
    strncpy(msg_copy, msg, msg_length);
    STRINGN_TO_NPVARIANT(msg_copy, msg_length - 1, *result);
  }
  return true;
}

void SayHello::InitializeMethodTable() {
  printf("Duality: SayHello::InitializeMethodTable was called!\n");
  fflush(stdout);
  NPIdentifier say_hello_id = NPN_GetStringIdentifier("HelloWorld");
  NPIdentifier get_print_42_id = NPN_GetStringIdentifier("GetPrint42");
  IdentifierToMethodMap::value_type tSayHelloEntry(say_hello_id,
                                                   &SayHello::HelloWorld);
  IdentifierToMethodMap::value_type tGetPrint42Entry(get_print_42_id,
                                                     &SayHello::GetPrint42CB);
  method_table_->insert(tSayHelloEntry);
  method_table_->insert(tGetPrint42Entry);
}
