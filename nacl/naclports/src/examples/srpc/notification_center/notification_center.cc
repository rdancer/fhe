// Copyright (c) 2010 The Native Client SDK Authors. All rights reserved.
// Use of this source code is governed by a BSD-style license that can
// be found in the LICENSE file.

// Simple test for simple rpc.

// C includes
#include <nacl/nacl_srpc.h>
#include <pthread.h>
#include <sys/nacl_syscalls.h>

// C++ includes
#include <cstdio>
#include <cstring>

#include "examples/srpc/notification_center/srpcpp/method_functor-inl.h"

// the number of methods that will be available to the client
const int g_client_method_count = 3;
// the array of methods that will be available to the client
NaClSrpcHandlerDesc g_client_methods [g_client_method_count + 1];

//  Return a simple integer.
NaClSrpcError FortyTwo(NaClSrpcChannel *channel,
                       NaClSrpcArg **in_args,
                       NaClSrpcArg **out_args) {
  printf("FortyTwo: running in server\n");
  out_args[0]->u.ival = 42;
  return NACL_SRPC_RESULT_OK;
}
// Export the method as taking no arguments and returning one integer.
NACL_SRPC_METHOD("fortytwo::i", FortyTwo);

//  Return a clever string.
NaClSrpcError HelloWorld(NaClSrpcChannel *channel,
                         NaClSrpcArg **in_args,
                         NaClSrpcArg **out_args) {
  // Strdup must be used because the SRPC layer frees the string passed to it.
  printf("HelloWorld: running in server\n");
  out_args[0]->u.sval = strndup("hello, world.", 1024);
  return NACL_SRPC_RESULT_OK;
}
// Export the method as taking no arguments and returning one string.
NACL_SRPC_METHOD("helloworld::s", HelloWorld);

// Disconnect stops the RPC service.  Not exported globally because
// it is not visible to the browser.
static NaClSrpcError Disconnect(NaClSrpcChannel *channel,
                                NaClSrpcArg **in_args,
                                NaClSrpcArg **out_args) {
  printf("Disconnect\n");
  return NACL_SRPC_RESULT_BREAK;
}

// initializes the client methods
void InitializeClientMethods() {
  g_client_methods[0].entry_fmt = "fortytwo::i";
  g_client_methods[0].handler = FortyTwo;
  g_client_methods[1].entry_fmt = "helloworld::s";
  g_client_methods[1].handler = HelloWorld;
  g_client_methods[2].entry_fmt = "disconnect::";
  g_client_methods[2].handler = Disconnect;
  g_client_methods[3].entry_fmt = NULL;
  g_client_methods[3].handler = NULL;
}

// ListenerThread awaits connection on the specified descriptor.  Once
// connected, ListenerThread runs an SRPC server that has two methods.
void* ListenerThread(void* desc);//, NaClSrpcHandlerDesc *listener_methods);


// OpenSocket creates a new SRPC server thread each time it is invoked.
NaClSrpcError OpenSocket(NaClSrpcChannel *channel,
                         NaClSrpcArg **in_args,
                         NaClSrpcArg **out_args) {
  int            socket_info[2];
  pthread_t      server;
  int            rval;

  InitializeClientMethods();

  // Create a socket_info (bound socket, socket address).
  printf("OpenSocket: creating bound socket\n");
  rval = imc_makeboundsock(socket_info);
  if (rval == 0) {
    printf("OpenSocket: bound socket %d, address %d\n",
           socket_info[0],
           socket_info[1]);
  } else {
    printf("OpenSocket: bound socket creation FAILED\n");
  }
  // Pass the socket address back to the caller.
  out_args[0]->u.hval = socket_info[1];
  // Start the server, passing ownership of the bound socket.
  printf("OpenSocket: socket listener...\n");
  // static_cast won't convert int to void * or vice versa
  rval = pthread_create(&server,
                        NULL,
                        ListenerThread,
                        reinterpret_cast<void*>(socket_info[0]));//,
                        //g_client_methods);
  if (rval == 0) {
    printf("OpenSocket: socket listener created\n");
  } else {
    printf("OpenSocket: socket listener creation FAILED\n");
  }
  // Return success.
  printf("START RPC FINISHED\n");
  return NACL_SRPC_RESULT_OK;
}
NACL_SRPC_METHOD("opensocket::h", OpenSocket);

void* ListenerThread(void* desc) {
  // not ready for this yet,
  //NaClSrpcHandlerDesc *listener_methods) {
  int connected_desc;
  int descriptor_arg = reinterpret_cast<int>(desc);

  printf("ListenerThread: waiting on %d to accept connections...\n",
         descriptor_arg);
  fflush(stdout);
  // Wait for connections from the client.  Export client facing interface.
  connected_desc = imc_accept(descriptor_arg);
  if (connected_desc >= 0) {
    static struct NaClSrpcHandlerDesc listener_methods[] = {
      { "fortytwo::i", FortyTwo },
      { "helloworld::s", HelloWorld },
      { "disconnect::", Disconnect },
      { NULL, NULL }
    };
      // Export the server on the connected socket descriptor.
    if (!NaClSrpcServerLoop(connected_desc, listener_methods, NULL)) {
      printf("SRPC server loop failed.\n");
    }
    printf("ListenerThread: shutting down\n");
    // Close the connected socket
    if (0 != close(connected_desc)) {
      printf("ListenerThread: connected socket close failed.\n");
    }
  } else {
    printf("ListenerThread: connection FAILED\n");
  }
  // Close the bound socket
  if (0 != close(descriptor_arg)) {
    printf("ListenerThread: bound socket close failed.\n");
  }
  printf("THREAD EXIT\n");
  return 0;
}
