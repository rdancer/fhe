// Copyright (c) 2010 The Native Client SDK Authors. All rights reserved.
// Use of this source code is governed by a BSD-style license that can
// be found in the LICENSE file.

// the header of the class being tested
#include "examples/srpc/notification_center/srpcpp/listener_config.h"

#include "gtest/gtest.h"


namespace srpcpp {

// The fixture for testing class ListenerConfig.  This class stub is required.
class ListenerConfigTest : public ::testing::Test {
};

// Tests that the ListenerConfig::socked_handle methods set and get the
// socket handle properly.
TEST_F(ListenerConfigTest, socket_handle) {
  ListenerConfig config;
  config.set_socket_handle(42);
  EXPECT_EQ(42, config.socket_handle());
}

// Functions to add to the listener configuration
//  Return a simple integer.
NaClSrpcError FortyTwo(NaClSrpcChannel *channel,
                       NaClSrpcArg **in_args,
                       NaClSrpcArg **out_args) {
  printf("FortyTwo: running in server\n");
  out_args[0]->u.ival = 42;
  return NACL_SRPC_RESULT_OK;
}

//  Return a simple integer.
NaClSrpcError FortyThree(NaClSrpcChannel *channel,
                         NaClSrpcArg **in_args,
                         NaClSrpcArg **out_args) {
  printf("FortyThree: running in server\n");
  out_args[0]->u.ival = 43;
  return NACL_SRPC_RESULT_OK;
}

//  Return a clever string.
NaClSrpcError HelloWorld(NaClSrpcChannel *channel,
                         NaClSrpcArg **in_args,
                         NaClSrpcArg **out_args) {
  // Strdup must be used because the SRPC layer frees the string passed to it.
  printf("HelloWorld: running in server\n");
  out_args[0]->u.sval = strndup("hello, world.", 1024);
  return NACL_SRPC_RESULT_OK;
}

// Tests that the ListenerConfig::AddHandlerDesriptor adds the desciptors as
// it is supposed to and that it makes the array.  Has to check the array
// contents, verify that the array is capped and that no entries can be added
// after the array is capped.
TEST_F(ListenerConfigTest, handler_descriptor) {
  ListenerConfig config;
  std::string fortytwo_format("fortytwo::i");
  std::string helloworld_format("helloworld::s");
  ASSERT_TRUE(config.AddHandlerDescriptor(fortytwo_format.c_str(), FortyTwo));
  ASSERT_TRUE(config.AddHandlerDescriptor(helloworld_format.c_str(),
                                          HelloWorld));
  NaClSrpcHandlerDesc* descriptors = config.handler_descriptors();
  ASSERT_TRUE(descriptors != NULL);
  // Converts to std::string for the comparison.  We could try calling the
  // functions too, but it's a fair amount of code for fairly little added
  // certainty.
  ASSERT_TRUE(descriptors[0].entry_fmt == fortytwo_format);
  ASSERT_TRUE(descriptors[1].entry_fmt == helloworld_format);
  ASSERT_TRUE(descriptors[2].entry_fmt == NULL);
  // Now that the array has been capped, Add should no longer work.
  ASSERT_FALSE(config.AddHandlerDescriptor("fortythree::i", FortyThree));
}
}  // namespace srpcpp

int main(int argc, char **argv) {
  ::testing::InitGoogleTest(&argc, argv);
  return RUN_ALL_TESTS();
}
