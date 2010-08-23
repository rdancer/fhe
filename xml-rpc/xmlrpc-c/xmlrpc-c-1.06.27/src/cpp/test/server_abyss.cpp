/*=============================================================================
                                  server_abyss
===============================================================================
  Test the Abyss server C++ facilities of XML-RPC for C/C++.
  
=============================================================================*/
#include <sys/unistd.h>
#include <sys/socket.h>
#include <arpa/inet.h>
#include <errno.h>
#include <string>
#include <iostream>
#include <vector>
#include <sstream>
#include <memory>
#include <time.h>

#include "xmlrpc-c/girerr.hpp"
using girerr::error;
using girerr::throwf;
#include "xmlrpc-c/base.hpp"
#include "xmlrpc-c/registry.hpp"
#include "xmlrpc-c/server_abyss.hpp"

#include "tools.hpp"
#include "server_abyss.hpp"

using namespace xmlrpc_c;
using namespace std;



class boundSocket {

public:
    boundSocket(short const portNumber) {
        this->fd = socket(PF_INET, SOCK_STREAM, IPPROTO_TCP);

        if (this->fd < 0)
            throwf("socket() failed.  errno=%d (%s)",
                   errno, strerror(errno));
        
        struct sockaddr_in sockAddr;
        int rc;

        sockAddr.sin_family = AF_INET;
        sockAddr.sin_port   = htons(portNumber);
        sockAddr.sin_addr.s_addr = 0;

        rc = bind(this->fd, (struct sockaddr *)&sockAddr, sizeof(sockAddr));
        
        if (rc != 0) {
            close(this->fd);
            throwf("Couldn't bind.  bind() failed with errno=%d (%s)",
                   errno, strerror(errno));
        }
    }

    ~boundSocket() {
        close(this->fd);
    }

    int fd;
};



class sampleAddMethod : public method {
public:
    sampleAddMethod() {
        this->_signature = "i:ii";
        this->_help = "This method adds two integers together";
    }
    void
    execute(xmlrpc_c::paramList const& paramList,
            value *             const  retvalP) {
        
        int const addend(paramList.getInt(0));
        int const adder(paramList.getInt(1));
        
        paramList.verifyEnd(2);
        
        *retvalP = value_int(addend + adder);
    }
};



class addHandlerTestSuite : public testSuite {

public:
    virtual string suiteName() {
        return "addHandlerTestSuite";
    }
    virtual void runtests(unsigned int) {
        TServer abyssServer;

        ServerCreate(&abyssServer, "testserver", 8080, NULL, NULL);

        registry myRegistry;
        
        myRegistry.addMethod("sample.add", methodPtr(new sampleAddMethod));
        
        registryPtr myRegistryP(new registry);
        
        myRegistryP->addMethod("sample.add", methodPtr(new sampleAddMethod));

        server_abyss_set_handlers(&abyssServer, myRegistry);

        server_abyss_set_handlers(&abyssServer, &myRegistry);
        
        server_abyss_set_handlers(&abyssServer, myRegistryP);

        server_abyss_set_handlers(&abyssServer, myRegistry, "/RPC3");

        server_abyss_set_handlers(&abyssServer, &myRegistry, "/RPC3");
        
        server_abyss_set_handlers(&abyssServer, myRegistryP, "/RPC3");

        ServerFree(&abyssServer);
    }
};



string
serverAbyssTestSuite::suiteName() {
    return "serverAbyssTestSuite";
}


void
serverAbyssTestSuite::runtests(unsigned int const indentation) {

    addHandlerTestSuite().run(indentation+1);

    registry myRegistry;
        
    myRegistry.addMethod("sample.add", methodPtr(new sampleAddMethod));

    registryPtr myRegistryP(new registry);

    myRegistryP->addMethod("sample.add", methodPtr(new sampleAddMethod));

    EXPECT_ERROR(  // No registry
        serverAbyss::constrOpt opt;
        serverAbyss abyssServer(opt);
        );
    EXPECT_ERROR(  // Both portNumber and socketFd
        serverAbyss abyssServer(serverAbyss::constrOpt()
                                .portNumber(8080)
                                .socketFd(3));
        );

    // Due to the vagaries of Abyss, construction of the following
    // objects may exit the program if it detects an error, such as
    // port number already in use.  We need to fix Abyss some day.

    {
        serverAbyss abyssServer(serverAbyss::constrOpt()
                                .registryP(&myRegistry)
                                .portNumber(12345)
            );
    }
    {
        serverAbyss abyssServer(serverAbyss::constrOpt()
                                .registryPtr(myRegistryP)
                                .portNumber(12345)
            );

        EXPECT_ERROR(  // Both registryP and registryPtr
            serverAbyss abyssServer(serverAbyss::constrOpt()
                                    .registryPtr(myRegistryP)
                                    .registryP(&myRegistry)
                                    .portNumber(12345)
                );
            );
    }
    {
        boundSocket socket(12345);
        
        serverAbyss abyssServer(serverAbyss::constrOpt()
                                .registryP(&myRegistry)
                                .socketFd(socket.fd)
            );
    }
    {
        serverAbyss abyssServer(serverAbyss::constrOpt()
                                .registryP(&myRegistry)
            );
    }

    {
        // Test all the options
        serverAbyss abyssServer(serverAbyss::constrOpt()
                                .registryPtr(myRegistryP)
                                .portNumber(12345)
                                .logFileName("/tmp/logfile")
                                .keepaliveTimeout(5)
                                .keepaliveMaxConn(4)
                                .timeout(20)
                                .dontAdvertise(true)
                                .uriPath("/xmlrpc")
            );

    }
    {
        serverAbyss abyssServer(
            myRegistry,
            12345,              // TCP port on which to listen
            "/tmp/xmlrpc_log"  // Log file
            );
    }
}
