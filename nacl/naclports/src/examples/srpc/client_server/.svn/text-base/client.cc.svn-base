// Copyright (c) 2010 The Native Client SDK Authors. All rights reserved.
// Use of this source code is governed by a BSD-style license that can
// be found in the LICENSE file.

// Simple test client.  The client uses the NACL_SRPC macros to expose
// methods to the browser.  The browser can call these methods.  If the
// browser calls Connect with a socket handle as the in_arg, the client
// will try to use the handle to connect to the server.
// FortyTwo and HelloWorld can then be used to get the client to run
// functions on the server.
// TODO(mlinck) add a shared memory region to this example to demonstrate how
// that works in srpc.

// C includes
#include <nacl/nacl_srpc.h>
#include <sys/nacl_syscalls.h>

// C++ includes
#include <cstdlib>
#include <cstdio>
#include <cstring>

namespace {
// default buffer size for this code module
const size_t kBufferSize = 1024;
// The channel to communicate with the server on.
NaClSrpcChannel g_ipc_channel;
// the connection handle
int g_connected_socket = -1;
// the socket's address on the server
int g_socket_address = -1;
}

namespace testnamespace {
// Return a simple integer, by getting it from the server.  Note that an
// ipc_channel to the server module has to be open for this call to succeed.
NaClSrpcError FortyTwo(NaClSrpcChannel *channel,
                       NaClSrpcArg **in_args,
                       NaClSrpcArg **out_args) {
  // always assume failure
  NaClSrpcError rval = NACL_SRPC_RESULT_APP_ERROR;
  printf("FortyTwo: running in client.\n");
  // Perform an RPC on the SRPC server, which returns a message.
  int number;
  if (NACL_SRPC_RESULT_OK ==
      NaClSrpcInvokeBySignature(&g_ipc_channel, "fortytwo::i", &number)) {
    printf("FortyTwo: RPC SUCCESS got %d.\n", number);
    out_args[0]->u.ival = number;
    rval = NACL_SRPC_RESULT_OK;
  } else {
    printf("FortyTwo: RPC FAILURE.\n");
  }
  return rval;
}
}  // testnamespace

// The Array macro must be used to export functions that exist inside
// classes or namespaces.
NACL_SRPC_METHOD_ARRAY(NaClSrpcMethodFortyTwo) = \
{ { "fortytwo::i", testnamespace::FortyTwo } };

// Return a string, by getting it from the server.  Note that an
// ipc_channel to the server module has to be open for this call to succeed.
NaClSrpcError HelloWorld(NaClSrpcChannel *channel,
                         NaClSrpcArg **in_args,
                         NaClSrpcArg **out_args) {
  // always assume failure
  NaClSrpcError rval = NACL_SRPC_RESULT_APP_ERROR;
  printf("HelloWorld: running in client\n");
  // Perform an RPC on the SRPC server, which returns a message.  The server
  // allocates the memory for the hello_string return parameter the nacl_srpc
  // layer returns it.  nacl_srpc is responsible for cleaning up that memory
  // after this function returns.
  char *hello_string;
  if (NACL_SRPC_RESULT_OK == NaClSrpcInvokeBySignature(&g_ipc_channel,
                                                       "helloworld::s",
                                                       &hello_string)) {
    printf("HelloWorld: RPC SUCCESS. Got: %s.\n", hello_string);
    // Strndup must be used because the SRPC layer frees the string passed to
    // it.
    out_args[0]->u.sval = strndup(hello_string, kBufferSize);
    rval = NACL_SRPC_RESULT_OK;
  } else {
    printf("HelloWorld: RPC FAILURE.\n");
  }
  return rval;
}

// Export the method to the browser as taking no arguments and returning one
// C-string.
NACL_SRPC_METHOD("helloworld::s", HelloWorld);

// Connects to the server.  This function must be called to populate
// g_ipc_channel so the other functions can access the server.
NaClSrpcError Connect(NaClSrpcChannel *old_channel,
                      NaClSrpcArg **in_args,
                      NaClSrpcArg **out_args) {
  // always assume failure
  NaClSrpcError rval = NACL_SRPC_RESULT_APP_ERROR;
  g_socket_address = in_args[0]->u.hval;

  // Start up banner.
  printf("Connect: socket address is %d\n", g_socket_address);
  // Connect to the socket address passed in, giving a new connected socket.
  g_connected_socket = imc_connect(g_socket_address);
  if (g_connected_socket >= 0) {
    printf("Connect: connected as %d\n", g_connected_socket);
    // Start the SRPC client to communicate over the connected socket.
    if (NaClSrpcClientCtor(&g_ipc_channel, g_connected_socket)) {
      printf("Connect: SRPC constructor SUCCESS\n");
      rval = NACL_SRPC_RESULT_OK;
    } else {
      printf("Connect: SRPC constructor FAILED\n");
    }
  } else {
    printf("Connect: connect FAILED\n");
  }
  return rval;
}
// Export the Connect method to the browser
NACL_SRPC_METHOD("connect:h:", Connect);

//  Return a clever string.
NaClSrpcError Disconnect(NaClSrpcChannel *channel,
                       NaClSrpcArg **in_args,
                       NaClSrpcArg **out_args) {
  // always assume failure
  NaClSrpcError rval = NACL_SRPC_RESULT_APP_ERROR;
  printf("Disconnect: running\n");
  // Perform an RPC on the SRPC client, which returns a message.
  if (NACL_SRPC_RESULT_OK ==
      NaClSrpcInvokeBySignature(&g_ipc_channel, "disconnect::")) {
    printf("Disconnect: RPC SUCCESS.\n");
    rval = NACL_SRPC_RESULT_OK;
  } else {
    printf("Disconnect: RPC FAILURE.\n");
  }
  return rval;
}

// Export the method to the browser as taking no arguments and returning one
// string.
NACL_SRPC_METHOD("disconnect::", Disconnect);

//  Return a clever string.
NaClSrpcError Close(NaClSrpcChannel *channel,
                    NaClSrpcArg **in_args,
                    NaClSrpcArg **out_args) {
  // always assume failure
  NaClSrpcError rval = NACL_SRPC_RESULT_APP_ERROR;
  printf("Close: running\n");
  // Close the connected socket and the socket address descriptor.
  if (0 == close(g_connected_socket)) {
    printf("Close: connected socket closed successfully\n");
    if (0 == close(g_socket_address)) {
      printf("Close: socket address closed successfully\n");
      rval = NACL_SRPC_RESULT_OK;
    } else {
      printf("Close: socket address close failure\n");
    }
  } else {
    printf("Close: connected socket close failure\n");
  }
  return rval;
}
