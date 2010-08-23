#include "unistdx.h"
#include <stdio.h>

#include "xmlrpc_config.h"

#include "xmlrpc-c/base.h"
#include "xmlrpc-c/server.h"
#include "xmlrpc-c/abyss.h"
#include "xmlrpc-c/server_abyss.h"

#include "test.h"

#include "server_abyss.h"


static void
testSetHandlers(TServer * const abyssServerP) {

    xmlrpc_env env;
    xmlrpc_registry * registryP;

    xmlrpc_env_init(&env);

    registryP = xmlrpc_registry_new(&env);
    TEST_NO_FAULT(&env);
    TEST(registryP != NULL);

    xmlrpc_server_abyss_set_handler(&env, abyssServerP, "/RPC3", registryP);
    TEST_NO_FAULT(&env);

    xmlrpc_server_abyss_set_handlers2(abyssServerP, "/RPC4", registryP);

    xmlrpc_registry_free(registryP);

    {
        xmlrpc_registry * registryP;
        registryP = xmlrpc_registry_new(&env);
        xmlrpc_server_abyss_set_handlers(abyssServerP, registryP);
        xmlrpc_registry_free(registryP);
    }
    xmlrpc_env_clean(&env);
}



static void
testServerParms(void) {
    xmlrpc_server_abyss_parms parms;

    parms.port_number = 1000;
    parms.log_file_name = "/tmp/xmlrpc_logfile";
    parms.keepalive_timeout = 5;
    parms.keepalive_max_conn = 4;
    parms.timeout = 50;
    parms.dont_advertise = TRUE;
    parms.uri_path = "/RPC9";
    parms.chunk_response = TRUE;
};



void
test_server_abyss(void) {

    xmlrpc_env env;
    TServer abyssServer;

    printf("Running Abyss server tests...\n");

    xmlrpc_env_init(&env);

    ServerCreate(&abyssServer, "testserver", 8080, NULL, NULL);
    
    testSetHandlers(&abyssServer);

    ServerSetKeepaliveTimeout(&abyssServer, 60);
    ServerSetKeepaliveMaxConn(&abyssServer, 10);
    ServerSetTimeout(&abyssServer, 0);
    ServerSetAdvertise(&abyssServer, FALSE);

    ServerFree(&abyssServer);

    ServerCreateSocket(&abyssServer, "testserver", STDIN_FILENO,
                       "/home/http", "/tmp/logfile");

    ServerFree(&abyssServer);

    testServerParms();

    printf("\n");
    printf("Abyss server tests done.\n");
}
