// Copyright (c) 2010 The Native Client SDK Authors. All rights reserved.
// Use of this source code is governed by a BSD-style license that can
// be found in the LICENSE file.

#ifndef EXAMPLES_SRPC_NOTIFICATION_CENTER_SRPCPP_LISTENER_CONFIG_H_
#define EXAMPLES_SRPC_NOTIFICATION_CENTER_SRPCPP_LISTENER_CONFIG_H_

#include <nacl/nacl_srpc.h>

#include <string>

#include "boost/noncopyable.hpp"
#include "examples/srpc/notification_center/srpcpp/srpcpp.h"


namespace srpcpp {
// This class can be handed to a listener thread as a constructor argument.
// It is necessary because pthread_create will only allow the function it
// runs to accept one argument.  It may grow if a need for more data in the
// listener body is discovered.
// This class protects the srpc handler descriptors which are given to
// the srpc interface from unsafe operations.
class ListenerConfig : private boost::noncopyable {
 public:
  ListenerConfig();
  virtual ~ListenerConfig();

  int socket_handle() const;
  void set_socket_handle(int handle);

  bool AddHandlerDescriptor(const std::string& format,
                            NaClSrpcMethod method);
  // This returns a pointer to the content of handler_descriptors_.  That
  // means that this container should not be modified after this function
  // has been called.
  NaClSrpcHandlerDesc* handler_descriptors();
 private:
  // This flag exists because there may be a point after which the array's
  // contents really should not be modified.
  bool capped_;
  HandlerDescriptorVector handler_descriptors_;
  int socket_handle_;
};
}  // namespace srpcpp
#endif  // EXAMPLES_SRPC_NOTIFICATION_CENTER_SRPCPP_LISTENER_CONFIG_H_
