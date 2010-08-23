#ifndef SERVER_ABYSS_HPP_INCLUDED
#define SERVER_ABYSS_HPP_INCLUDED

#include "xmlrpc-c/base.hpp"
#include "abyss.h"

namespace xmlrpc_c {

class serverAbyss {
    
public:
    class constrOpt {
    public:
        constrOpt();

        constrOpt & registryPtr       (xmlrpc_c::registryPtr      const& arg);
        constrOpt & registryP         (const xmlrpc_c::registry * const& arg);
        constrOpt & socketFd          (xmlrpc_socket  const& arg);
        constrOpt & portNumber        (uint           const& arg);
        constrOpt & logFileName       (std::string    const& arg);
        constrOpt & keepaliveTimeout  (uint           const& arg);
        constrOpt & keepaliveMaxConn  (uint           const& arg);
        constrOpt & timeout           (uint           const& arg);
        constrOpt & dontAdvertise     (bool           const& arg);
        constrOpt & uriPath           (std::string    const& arg);
        constrOpt & chunkResponse     (bool           const& arg);

        struct value {
            xmlrpc_c::registryPtr      registryPtr;
            const xmlrpc_c::registry * registryP;
            xmlrpc_socket  socketFd;
            uint           portNumber;
            std::string    logFileName;
            uint           keepaliveTimeout;
            uint           keepaliveMaxConn;
            uint           timeout;
            bool           dontAdvertise;
            std::string    uriPath;
            bool           chunkResponse;
        } value;
        struct {
            bool registryPtr;
            bool registryP;
            bool socketFd;
            bool portNumber;
            bool logFileName;
            bool keepaliveTimeout;
            bool keepaliveMaxConn;
            bool timeout;
            bool dontAdvertise;
            bool uriPath;
            bool chunkResponse;
        } present;
    };

    serverAbyss(constrOpt const& opt);

    serverAbyss(
        xmlrpc_c::registry const& registry,
        unsigned int       const  portNumber = 8080,
        std::string        const& logFileName = "",
        unsigned int       const  keepaliveTimeout = 0,
        unsigned int       const  keepaliveMaxConn = 0,
        unsigned int       const  timeout = 0,
        bool               const  dontAdvertise = false,
        bool               const  socketBound = false,
        xmlrpc_socket      const  socketFd = 0
        );
    ~serverAbyss();
    
    void
    run();

    void
    runOnce();

    void
    runConn(int const socketFd);
    
private:
    // The user has the choice of supplying the registry by plain pointer
    // (and managing the object's existence himself) or by autoObjectPtr
    // (with automatic management).  'registryPtr' exists here only to
    // maintain a reference count in the case that the user supplied an
    // autoObjectPtr.  The object doesn't reference the C++ registry
    // object except during construction, because the C registry is the
    // real registry.
    xmlrpc_c::registryPtr registryPtr;

    TServer cServer;

    void
    setAdditionalServerParms(constrOpt const& opt);

    void
    initialize(constrOpt const& opt);
};


void
server_abyss_set_handlers(TServer *          const  srvP,
                          xmlrpc_c::registry const& registry,
                          std::string        const& uriPath = "/RPC2");

void
server_abyss_set_handlers(TServer *                  const  srvP,
                          const xmlrpc_c::registry * const  registryP,
                          std::string                const& uriPath = "/RPC2");

void
server_abyss_set_handlers(TServer *             const srvP,
                          xmlrpc_c::registryPtr const registryPtr,
                          std::string           const& uriPath = "/RPC2");

} // namespace

#endif
