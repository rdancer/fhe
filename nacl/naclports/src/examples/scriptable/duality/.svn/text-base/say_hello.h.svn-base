// Copyright 2010 The Native Client SDK Authors. All rights reserved.
// Use of this source code is governed by a BSD-style license that can
// be found in the LICENSE file.

#ifndef EXAMPLES_SCRIPTABLE_DUALITY_SAY_HELLO_H_
#define EXAMPLES_SCRIPTABLE_DUALITY_SAY_HELLO_H_

#include <examples/scriptable/duality/scriptable.h>

#include <nacl/nacl_srpc.h>

#include <string>

// Extends Scriptable and adds the plugin's specific functionality.
class SayHello : public Scriptable {
 public:
  SayHello();
  virtual ~SayHello();

  bool GetPrint42(const NPVariant* args,
                  uint32_t arg_count,
                  NPVariant* result);


  static bool GetPrint42CB(Scriptable * instance,
                           const NPVariant* args,
                           uint32_t arg_count,
                           NPVariant* result);

  static bool HelloWorld(Scriptable * instance,
                         const NPVariant* args,
                         uint32_t arg_count,
                         NPVariant* result);

 private:
  // Populates the method table with our specific interface.
  virtual void InitializeMethodTable();
  // Populates the propert table with our specific interface.
  // Since this class has no visible properties the function does nothing.
  virtual void InitializePropertyTable() {}
};

#endif  // EXAMPLES_SCRIPTABLE_DUALITY_SAY_HELLO_H_
