// Copyright (c) 2010 The Native Client SDK Authors. All rights reserved.
// Use of this source code is governed by a BSD-style license that can
// be found in the LICENSE file.

#ifndef EXAMPLES_SRPC_NOTIFICATION_CENTER_SRPCPP_SRPCPP_H_
#define EXAMPLES_SRPC_NOTIFICATION_CENTER_SRPCPP_SRPCPP_H_

#include <nacl/nacl_srpc.h>

#include <vector>

// Any typedefs that belong into the srpcpp namespace but not on a particular
// class should go here.
namespace srpcpp {
typedef std::vector<NaClSrpcHandlerDesc> HandlerDescriptorVector;
}  // namespace srpcpp

#endif  // EXAMPLES_SRPC_NOTIFICATION_CENTER_SRPCPP_SRPCPP_H_
