// Copyright 2010 The Native Client Authors. All rights reserved.
// Use of this source code is governed by a BSD-style license that can
// be found in the LICENSE file.

#include "nacl/calculator.h"

#include <assert.h>
#include <math.h>
#include <stdio.h>
#include <stdlib.h>
#include <string.h>

#include <nacl/nacl_imc.h>
#include <nacl/nacl_npapi.h>
#include <nacl/npapi_extensions.h>
#include <nacl/npruntime.h>

#include "c_salt/double_type.h"
#include "c_salt/int32_type.h"
#include "c_salt/object_type.h"
#include "c_salt/scripting_bridge.h"
#include "c_salt/string_type.h"

namespace c_salt {

// The required factory method.
Module* Module::CreateModule() {
  return new calculator::Calculator();
}

}  // namespace c_salt

namespace calculator {

Calculator::Calculator()
    : calculate_callback_(NULL) {
}

Calculator::~Calculator() {
  delete calculate_callback_;
}

void Calculator::InitializeMethods(c_salt::ScriptingBridge* bridge) {
  calculate_callback_ =
      new c_salt::MethodCallback<Calculator>(this, &Calculator::Calculate);
  bridge->AddMethodNamed("calculate", calculate_callback_);
}

bool Calculator::Calculate(c_salt::ScriptingBridge* bridge,
                           const NPVariant* args,
                           uint32_t arg_count,
                           NPVariant* return_value) {
  if (arg_count < 1 || !NPVARIANT_IS_OBJECT(args[0]))
    return false;

  c_salt::Type::TypeArray* expression_array =
      c_salt::Type::CreateArrayFromNPVariant(bridge, args[0]);
  double expr_value = EvaluateExpression(expression_array);

  // If there was a second argument, assume it's the oncalculate callback.
  if (arg_count > 1 && NPVARIANT_IS_OBJECT(args[1])) {
    // The ObjectType ctor bumps the ref count of the callback function.
    c_salt::ObjectType function_obj(NPVARIANT_TO_OBJECT(args[1]));
    // Pack the value of the expression into the first arg of the callback
    // function, and invoke it.
    NPVariant argv;
    NPVariant result;
    DOUBLE_TO_NPVARIANT(expr_value, argv);
    NULL_TO_NPVARIANT(result);
    NPN_InvokeDefault(bridge->npp(),
                      function_obj.object_value(),
                      &argv,
                      1,
                      &result);
  }

  return true;
}

double Calculator::EvaluateExpression(
    const c_salt::Type::TypeArray* expression) {
  if (expression->size() == 0)
    return 0.0;
  // This is a pretty simple-minded expression evaulator.  It assumes that the
  // input vector represents a correct postfix expression and does basically
  // no error checking.  If the input vector is badly formed, then you will get
  // unpredicatble results.
  std::vector<double> expr_stack;
  std::vector<c_salt::Type*>::const_iterator it;
  for (it = expression->begin(); it != expression->end(); ++it) {
    if ((*it)->type_id() == c_salt::Type::kStringTypeId) {
      const std::string* oper =
          (static_cast<c_salt::StringType*>(*it))->string_value();
      double term0, term1;
      switch (oper->at(0)) {
      case '+':
        if (expr_stack.size() < 2)
          return 0.0;
        term0 = expr_stack.back();
        expr_stack.pop_back();
        term1 = expr_stack.back();
        expr_stack.pop_back();
        expr_stack.push_back(term1 + term0);
        break;
      case '-':
        if (expr_stack.size() < 2)
          return 0.0;
        term0 = expr_stack.back();
        expr_stack.pop_back();
        term1 = expr_stack.back();
        expr_stack.pop_back();
        expr_stack.push_back(term1 - term0);
        break;
      case '/':
        if (expr_stack.size() < 2)
          return 0.0;
        term0 = expr_stack.back();
        expr_stack.pop_back();
        term1 = expr_stack.back();
        expr_stack.pop_back();
        expr_stack.push_back(term1 / term0);
        break;
      case '*':
        if (expr_stack.size() < 2)
          return 0.0;
        term0 = expr_stack.back();
        expr_stack.pop_back();
        term1 = expr_stack.back();
        expr_stack.pop_back();
        expr_stack.push_back(term1 * term0);
        break;
      default:
        expr_stack.push_back(atof(oper->c_str()));
        break;
      }
    } else {
      switch ((*it)->type_id()) {
      case c_salt::Type::kInt32TypeId:
        expr_stack.push_back(static_cast<double>(
            (static_cast<c_salt::Int32Type*>(*it))->int32_value()));
        break;
      case c_salt::Type::kDoubleTypeId:
        expr_stack.push_back(
            (static_cast<c_salt::DoubleType*>(*it))->double_value());
        break;
      }
    }
  }

  assert(expr_stack.size() == 1);
  double expr_value = expr_stack.back();
  expr_stack.pop_back();
  return expr_value;
}

}  // namespace calculator
