// Copyright (c) 2010 The Native Client SDK Authors. All rights reserved.
// Use of this source code is governed by a BSD-style license that can
// be found in the LICENSE file.

// Simple test server.  Note the difference between exporting functions
// to the browser (with the NACL_SRPC macros) and exporting them on the
// socket connection in ListenerThread().  FortyTwo() and HelloWorld() are
// visible to the browser and the sockets, being exported both ways.
// Disconnect() is only visible over the socket because it does not apply to
// the browser.

// C includes
#include <nacl/nacl_srpc.h>
#include <pthread.h>
#include <sys/nacl_syscalls.h>

// C++ includes
#include <cstdio>
#include <cstring>

namespace {
// default buffer size for this code module
const size_t kBufferSize = 1024;
}

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
  out_args[0]->u.sval = strndup("hello, world.", kBufferSize);
  return NACL_SRPC_RESULT_OK;
}
// Export the method as taking no arguments and returning one string.
NACL_SRPC_METHOD("helloworld::s", HelloWorld);

// Disconnect stops the RPC service.  Not exported globally because
// it is not visible to the browser.
NaClSrpcError Disconnect(NaClSrpcChannel *channel,
                                NaClSrpcArg **in_args,
                                NaClSrpcArg **out_args) {
  printf("Disconnect\n");
  return NACL_SRPC_RESULT_BREAK;
}

// ListenerThread awaits connection on the specified descriptor.  Once
// connected, ListenerThread runs an SRPC server that has three methods.
void* ListenerThread(void* desc);


// OpenSocket creates a new SRPC socket and listener thread each time it is
// invoked.  This function has to be called by the bowser and the handle it
// returns has to be passed into any client module.
NaClSrpcError OpenSocket(NaClSrpcChannel *channel,
                         NaClSrpcArg **in_args,
                         NaClSrpcArg **out_args) {
  int socket_info[2];
  pthread_t server;
  int rval;

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
                        reinterpret_cast<void*>(socket_info[0]));
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
