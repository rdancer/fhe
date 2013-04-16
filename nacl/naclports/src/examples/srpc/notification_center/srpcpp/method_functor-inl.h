// Copyright (c) 2010 The Native Client SDK Authors. All rights reserved.
// Use of this source code is governed by a BSD-style license that can
// be found in the LICENSE file.

#ifndef EXAMPLES_SRPC_NOTIFICATION_CENTER_SRPCPP_METHOD_FUNCTOR_INL_H_
#define EXAMPLES_SRPC_NOTIFICATION_CENTER_SRPCPP_METHOD_FUNCTOR_INL_H_

#include <nacl/nacl_srpc.h>

namespace srpcpp {
class SrpcFunctor {
 public:
  virtual ~SrpcFunctor() {}

  virtual NaClSrpcError operator()(NaClSrpcChannel *channel,
                                   NaClSrpcArg **in_args,
                                   NaClSrpcArg **out_args)=0;
};

// This template will become necessary if objects want to be able to bind
// member functions to srpc callback hooks.
template <class BoundType>
class BoundSrpcFunctor : public SrpcFunctor {
 public:

  BoundSrpcFunctor(BoundType *object,
                   NaClSrpcError(BoundType::*method)(NaClSrpcChannel *channel,
                                                     NaClSrpcArg **in_args,
                                                     NaClSrpcArg **out_args))
      : method_(method), object_(object) {}

  // Executes the method on the object and returns the result.
  virtual NaClSrpcError operator()(NaClSrpcChannel *channel,
                                   NaClSrpcArg **in_args,
                                   NaClSrpcArg **out_args) {
    return (*object_.*method_)(channel, in_args, out_args);
  }

 private:
  NaClSrpcError (BoundType::*method_)(NaClSrpcChannel *channel,
                                      NaClSrpcArg **in_args,
                                      NaClSrpcArg **out_args);
  BoundType* object_;
};
}  // namespace srpcpp
#endif  // EXAMPLES_SRPC_NOTIFICATION_CENTER_SRPCPP_METHOD_FUNCTOR_INL_H_
