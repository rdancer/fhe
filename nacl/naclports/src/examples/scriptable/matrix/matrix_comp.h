// Copyright 2010 The Native Client SDK Authors. All rights reserved.
// Use of this source code is governed by a BSD-style license that can
// be found in the LICENSE file.

#ifndef EXAMPLES_SCRIPTABLE_MATRIX_MATRIX_COMP_H_
#define EXAMPLES_SCRIPTABLE_MATRIX_MATRIX_COMP_H_

#include <string>
#include "examples/scriptable/matrix/scriptable.h"

// Extends Scriptable and adds the plugin's specific functionality.
class MatrixCompute : public Scriptable {
 public:
  MatrixCompute();
  virtual ~MatrixCompute();

  static bool ComputeAnswer(Scriptable* instance,
                      const NPVariant* args,
                      uint32_t arg_count,
                      NPVariant* result);

  static bool ComputeUsingArray(Scriptable* instance,
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

#endif  // EXAMPLES_SCRIPTABLE_MATRIX_MATRIX_COMP_H_
