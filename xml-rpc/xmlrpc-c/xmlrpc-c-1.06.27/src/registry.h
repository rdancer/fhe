#ifndef REGISTRY_H_INCLUDED
#define REGISTRY_H_INCLUDED

#include "bool.h"
#include "xmlrpc-c/base.h"
#include "xmlrpc-c/server.h"

struct _xmlrpc_registry {
    bool           _introspection_enabled;
    xmlrpc_value * _methods;
    xmlrpc_value * _default_method;
    xmlrpc_value * _preinvoke_method;
    xmlrpc_server_shutdown_fn * _shutdown_server_fn;
        /* Function that can be called to shut down the server that is
           using this registry.  NULL if none.
        */
    void * _shutdown_context;
        /* Context for _shutdown_server_fn -- understood only by
           that function, passed to it as argument.
        */
};

void
xmlrpc_dispatchCall(struct _xmlrpc_env *      const envP, 
                    struct _xmlrpc_registry * const registryP,
                    const char *              const methodName, 
                    struct _xmlrpc_value *    const paramArrayP,
                    struct _xmlrpc_value **   const resultPP);

#endif
