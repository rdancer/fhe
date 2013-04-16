// Copyright (c) 2010 The Native Client SDK Authors. All rights reserved.
// Use of this source code is governed by a BSD-style license that can
// be found in the LICENSE file.

#include "examples/srpc/notification_center/srpcpp/listener_config.h"

#include <nacl/nacl_srpc.h>

#include <cstdlib>
#include <cstring>
#include <string>

namespace srpcpp {
ListenerConfig::ListenerConfig()
    : capped_(false), handler_descriptors_(), socket_handle_(-1) {
}

ListenerConfig::~ListenerConfig() {
  // The array does not really have to be emptied.  Only the memory that was
  // allocated by AddHandlerDescriptor needs to be freed.
  for (HandlerDescriptorVector::size_type i = 0;
       i < handler_descriptors_.size();
       ++i) {
    free(const_cast<char*>(handler_descriptors_[i].entry_fmt));
  }
}

int ListenerConfig::socket_handle() const {
  return socket_handle_;
}

void ListenerConfig::set_socket_handle(int handle) {
  socket_handle_ = handle;
}

bool ListenerConfig::AddHandlerDescriptor(const std::string& format,
                                          NaClSrpcMethod method) {
  bool rval = false;
  // Don't modify a capped vector.
  if (!capped_) {
    NaClSrpcHandlerDesc handler_descriptor;
    // Have to clean up this memory in the destructor
    handler_descriptor.entry_fmt = strndup(format.c_str(), format.size() + 1);
    handler_descriptor.handler = method;
    handler_descriptors_.push_back(handler_descriptor);
    rval = true;
  }
  return rval;
}

NaClSrpcHandlerDesc* ListenerConfig::handler_descriptors() {
  if (!capped_) {
    // The array has to be capped with NULL entries before it can be used by
    // SRPC
    NaClSrpcHandlerDesc array_terminator;
    array_terminator.entry_fmt = NULL;
    array_terminator.handler = NULL;
    handler_descriptors_.push_back(array_terminator);
    capped_ = true;
  }
  return &handler_descriptors_[0];
}
}  // namespace srpcpp
