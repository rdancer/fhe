/* Copyright information is at end of file */

#include "xmlrpc_config.h"

#undef PACKAGE
#undef VERSION

#include <stdarg.h>
#include <stddef.h>
#include <stdio.h>
#include <string.h>
#include <stdlib.h>
#include <assert.h>
#include <errno.h>

#include "bool.h"
#include "mallocvar.h"

#include "xmlrpc-c/base.h"
#include "xmlrpc-c/base_int.h"
#include "xmlrpc-c/string_int.h"
#include "xmlrpc-c/client.h"
#include "xmlrpc-c/client_int.h"
/* transport_config.h defines XMLRPC_DEFAULT_TRANSPORT,
    MUST_BUILD_WININET_CLIENT, MUST_BUILD_CURL_CLIENT,
    MUST_BUILD_LIBWWW_CLIENT 
*/
#include "transport_config.h"

#if MUST_BUILD_WININET_CLIENT
#include "xmlrpc_wininet_transport.h"
#endif
#if MUST_BUILD_CURL_CLIENT
#include "xmlrpc_curl_transport.h"
#endif
#if MUST_BUILD_LIBWWW_CLIENT
#include "xmlrpc_libwww_transport.h"
#endif

struct xmlrpc_client {
/*----------------------------------------------------------------------------
   This represents a client object.
-----------------------------------------------------------------------------*/
    struct xmlrpc_client_transport *   transportP;
    struct xmlrpc_client_transport_ops clientTransportOps;
};



typedef struct xmlrpc_call_info {
    /* These fields are used when performing asynchronous calls.
    ** The _asynch_data_holder contains server_url, method_name and
    ** param_array, so it's the only thing we need to free. */
    xmlrpc_value *_asynch_data_holder;
    char *server_url;
    char *method_name;
    xmlrpc_value *param_array;
    xmlrpc_response_handler callback;
    void *user_data;

    /* The serialized XML data passed to this call. We keep this around
    ** for use by our source_anchor field. */
    xmlrpc_mem_block *serialized_xml;
} xmlrpc_call_info;



/*=========================================================================
   Global Constant Setup/Teardown
=========================================================================*/

static void
callTransportSetup(xmlrpc_env *           const envP,
                   xmlrpc_transport_setup       setupFn) {

    if (setupFn)
        setupFn(envP);
}



static void
setupTransportGlobalConst(xmlrpc_env * const envP) {

#if MUST_BUILD_WININET_CLIENT
    if (!envP->fault_occurred)
        callTransportSetup(envP,
                           xmlrpc_wininet_transport_ops.setup_global_const);
#endif
#if MUST_BUILD_CURL_CLIENT
    if (!envP->fault_occurred)
        callTransportSetup(envP,
                           xmlrpc_curl_transport_ops.setup_global_const);
#endif
#if MUST_BUILD_LIBWWW_CLIENT
    if (!envP->fault_occurred)
        callTransportSetup(envP,
                           xmlrpc_libwww_transport_ops.setup_global_const);
#endif
}



static void
callTransportTeardown(xmlrpc_transport_teardown teardownFn) {

    if (teardownFn)
        teardownFn();
}



static void
teardownTransportGlobalConst(void) {

#if MUST_BUILD_WININET_CLIENT
    callTransportTeardown(
        xmlrpc_wininet_transport_ops.teardown_global_const);
#endif
#if MUST_BUILD_CURL_CLIENT
    callTransportTeardown(
        xmlrpc_curl_transport_ops.teardown_global_const);
#endif
#if MUST_BUILD_LIBWWW_CLIENT
    callTransportTeardown(
        xmlrpc_libwww_transport_ops.teardown_global_const);
#endif
}



static unsigned int constSetupCount = 0;


void
xmlrpc_client_setup_global_const(xmlrpc_env * const envP) {
/*----------------------------------------------------------------------------
   Set up pseudo-constant global variables (they'd be constant, except that
   the library loader doesn't set them.  An explicit call from the loaded
   program does).

   This function is not thread-safe.  The user is supposed to call it
   (perhaps cascaded down from a multitude of higher level libraries)
   as part of early program setup, when the program is only one thread.
-----------------------------------------------------------------------------*/
    if (constSetupCount == 0)
        setupTransportGlobalConst(envP);

    ++constSetupCount;
}



void
xmlrpc_client_teardown_global_const(void) {
/*----------------------------------------------------------------------------
   Complement to xmlrpc_client_setup_global_const().

   This function is not thread-safe.  The user is supposed to call it
   (perhaps cascaded down from a multitude of higher level libraries)
   as part of final program cleanup, when the program is only one thread.
-----------------------------------------------------------------------------*/
    assert(constSetupCount > 0);

    --constSetupCount;

    if (constSetupCount == 0)
        teardownTransportGlobalConst();
}



/*=========================================================================
   Client Create/Destroy
=========================================================================*/

static void
getTransportOps(xmlrpc_env *                         const envP,
                const char *                         const transportName,
                struct xmlrpc_client_transport_ops * const opsP) {

    if (false) {
    }
#if MUST_BUILD_WININET_CLIENT
    else if (strcmp(transportName, "wininet") == 0)
        *opsP = xmlrpc_wininet_transport_ops;
#endif
#if MUST_BUILD_CURL_CLIENT
    else if (strcmp(transportName, "curl") == 0)
        *opsP = xmlrpc_curl_transport_ops;
#endif
#if MUST_BUILD_LIBWWW_CLIENT
    else if (strcmp(transportName, "libwww") == 0)
        *opsP = xmlrpc_libwww_transport_ops;
#endif
    else
        xmlrpc_env_set_fault_formatted(
            envP, XMLRPC_INTERNAL_ERROR, 
            "Unrecognized XML transport name '%s'", transportName);
}



static void
getTransportParmsFromClientParms(
    xmlrpc_env *                      const envP,
    const struct xmlrpc_clientparms * const clientparmsP,
    unsigned int                      const parmSize,
    const struct xmlrpc_xportparms ** const transportparmsPP,
    size_t *                          const transportparmSizeP) {

    if (parmSize < XMLRPC_CPSIZE(transportparmsP) ||
        clientparmsP->transportparmsP == NULL) {

        *transportparmsPP = NULL;
        *transportparmSizeP = 0;
    } else {
        *transportparmsPP = clientparmsP->transportparmsP;
        if (parmSize < XMLRPC_CPSIZE(transportparm_size))
            xmlrpc_faultf(envP, "Your 'clientparms' argument contains the "
                          "transportparmsP member, "
                          "but no transportparms_size member");
        else
            *transportparmSizeP = clientparmsP->transportparm_size;
    }
}



static void
getTransportInfo(xmlrpc_env *                      const envP,
                 const struct xmlrpc_clientparms * const clientparmsP,
                 unsigned int                      const parmSize,
                 const char **                     const transportNameP,
                 const struct xmlrpc_xportparms ** const transportparmsPP,
                 size_t *                          const transportparmSizeP) {

    getTransportParmsFromClientParms(
        envP, clientparmsP, parmSize, 
        transportparmsPP, transportparmSizeP);
    
    if (!envP->fault_occurred) {
        if (parmSize < XMLRPC_CPSIZE(transport) ||
            clientparmsP->transport == NULL) {

            /* He didn't specify a transport class.  Use the default */

            *transportNameP = xmlrpc_client_get_default_transport(envP);
            if (*transportparmsPP)
                xmlrpc_faultf(envP,
                    "You specified transport parameters, but did not "
                    "specify a transport type.  Parameters are specific to "
                    "a particular type.");
        } else
            *transportNameP = clientparmsP->transport;
    }
}



void 
xmlrpc_client_create(xmlrpc_env *                      const envP,
                     int                               const flags,
                     const char *                      const appname,
                     const char *                      const appversion,
                     const struct xmlrpc_clientparms * const clientparmsP,
                     unsigned int                      const parmSize,
                     xmlrpc_client **                  const clientPP) {
    
    XMLRPC_ASSERT_PTR_OK(clientPP);

    if (constSetupCount == 0) {
        xmlrpc_faultf(envP,
                      "You have not called "
                      "xmlrpc_client_setup_global_const().");
        /* Impl note:  We can't just call it now because it isn't
           thread-safe.
        */
    } else {
        xmlrpc_client * clientP;

        MALLOCVAR(clientP);

        if (clientP == NULL)
            xmlrpc_faultf(envP, "Unable to allocate memory for "
                          "client descriptor.");
        else {
            const char * transportName;
            const struct xmlrpc_xportparms * transportparmsP;
            size_t transportparmSize;
        
            getTransportInfo(envP, clientparmsP, parmSize, &transportName, 
                             &transportparmsP, &transportparmSize);
            
            if (!envP->fault_occurred) {
                getTransportOps(envP, transportName,
                                &clientP->clientTransportOps);
                if (!envP->fault_occurred) {
                    /* The following call is not thread-safe */
                    clientP->clientTransportOps.create(
                        envP, flags, appname, appversion,
                        transportparmsP, transportparmSize,
                        &clientP->transportP);
                    if (!envP->fault_occurred)
                        *clientPP = clientP;
                }
            }
            if (envP->fault_occurred)
                free(clientP);
        }
    }
}



void 
xmlrpc_client_destroy(xmlrpc_client * const clientP) {

    XMLRPC_ASSERT_PTR_OK(clientP);

    clientP->clientTransportOps.destroy(clientP->transportP);

    free(clientP);
}



/*=========================================================================
   Call/Response Utilities
=========================================================================*/

static void
makeCallXml(xmlrpc_env *               const envP,
            const char *               const methodName,
            xmlrpc_value *             const paramArrayP,
            xmlrpc_mem_block **        const callXmlPP) {

    XMLRPC_ASSERT_VALUE_OK(paramArrayP);
    XMLRPC_ASSERT_PTR_OK(callXmlPP);

    if (methodName == NULL)
        xmlrpc_env_set_fault_formatted(
            envP, XMLRPC_INTERNAL_ERROR,
            "method name argument is NULL pointer");
    else {
        xmlrpc_mem_block * callXmlP;

        callXmlP = XMLRPC_MEMBLOCK_NEW(char, envP, 0);
        if (!envP->fault_occurred) {
            xmlrpc_serialize_call(envP, callXmlP, methodName, paramArrayP);

            *callXmlPP = callXmlP;

            if (envP->fault_occurred)
                XMLRPC_MEMBLOCK_FREE(char, callXmlP);
        }
    }    
}



/*=========================================================================
    xmlrpc_server_info
=========================================================================*/

xmlrpc_server_info *
xmlrpc_server_info_new(xmlrpc_env * const envP,
                       const char * const serverUrl) {
    
    xmlrpc_server_info * serverInfoP;

    XMLRPC_ASSERT_ENV_OK(envP);
    XMLRPC_ASSERT_PTR_OK(serverUrl);

    /* Allocate our memory blocks. */
    MALLOCVAR(serverInfoP);
    if (serverInfoP == NULL)
        xmlrpc_faultf(envP, "Couldn't allocate memory for xmlrpc_server_info");
    else {
        memset(serverInfoP, 0, sizeof(xmlrpc_server_info));

        serverInfoP->_server_url = strdup(serverUrl);
        if (serverInfoP->_server_url == NULL)
            xmlrpc_faultf(envP, "Couldn't allocate memory for server URL");
        else {
            serverInfoP->_http_basic_auth = NULL;
            if (envP->fault_occurred)
                xmlrpc_strfree(serverInfoP->_server_url);
        }
        if (envP->fault_occurred)
            free(serverInfoP);
    }
    return serverInfoP;
}



xmlrpc_server_info *
xmlrpc_server_info_copy(xmlrpc_env *         const envP,
                        xmlrpc_server_info * const aserverInfoP) {

    xmlrpc_server_info * serverInfoP;

    XMLRPC_ASSERT_ENV_OK(envP);
    XMLRPC_ASSERT_PTR_OK(aserverInfoP);

    MALLOCVAR(serverInfoP);
    if (serverInfoP == NULL)
        xmlrpc_faultf(envP,
                      "Couldn't allocate memory for xmlrpc_server_info");
    else {
        serverInfoP->_server_url = strdup(aserverInfoP->_server_url);
        if (serverInfoP->_server_url == NULL)
            xmlrpc_faultf(envP, "Couldn't allocate memory for server URL");
        else {
            if (aserverInfoP->_http_basic_auth == NULL)
                serverInfoP->_http_basic_auth = NULL;
            else {
                serverInfoP->_http_basic_auth =
                    strdup(aserverInfoP->_http_basic_auth);
                if (serverInfoP->_http_basic_auth == NULL)
                    xmlrpc_faultf(envP, "Couldn't allocate memory "
                                  "for authentication info");
            }
            if (envP->fault_occurred)
                xmlrpc_strfree(serverInfoP->_server_url);
        }
        if (envP->fault_occurred)
            free(serverInfoP);
    }
    return serverInfoP;
}



void
xmlrpc_server_info_free(xmlrpc_server_info * const serverInfoP) {

    XMLRPC_ASSERT_PTR_OK(serverInfoP);
    XMLRPC_ASSERT(serverInfoP->_server_url != XMLRPC_BAD_POINTER);

    if (serverInfoP->_http_basic_auth)
        free(serverInfoP->_http_basic_auth);
    serverInfoP->_http_basic_auth = XMLRPC_BAD_POINTER;
    free(serverInfoP->_server_url);
    serverInfoP->_server_url = XMLRPC_BAD_POINTER;
    free(serverInfoP);
}



/*=========================================================================
   Synchronous Call
=========================================================================*/

void
xmlrpc_client_transport_call2(
    xmlrpc_env *               const envP,
    xmlrpc_client *            const clientP,
    const xmlrpc_server_info * const serverP,
    xmlrpc_mem_block *         const callXmlP,
    xmlrpc_mem_block **        const respXmlPP) {

    XMLRPC_ASSERT_PTR_OK(clientP);
    XMLRPC_ASSERT_PTR_OK(serverP);
    XMLRPC_ASSERT_PTR_OK(callXmlP);
    XMLRPC_ASSERT_PTR_OK(respXmlPP);

    clientP->clientTransportOps.call(
        envP, clientP->transportP, serverP, callXmlP,
        respXmlPP);
}



void
xmlrpc_client_call2(xmlrpc_env *               const envP,
                    struct xmlrpc_client *     const clientP,
                    const xmlrpc_server_info * const serverInfoP,
                    const char *               const methodName,
                    xmlrpc_value *             const paramArrayP,
                    xmlrpc_value **            const resultPP) {

    xmlrpc_mem_block * callXmlP;

    XMLRPC_ASSERT_ENV_OK(envP);
    XMLRPC_ASSERT_PTR_OK(clientP);
    XMLRPC_ASSERT_PTR_OK(serverInfoP);
    XMLRPC_ASSERT_PTR_OK(paramArrayP);

    makeCallXml(envP, methodName, paramArrayP, &callXmlP);
    
    if (!envP->fault_occurred) {
        xmlrpc_mem_block * respXmlP;
        
        xmlrpc_traceXml("XML-RPC CALL", 
                        XMLRPC_MEMBLOCK_CONTENTS(char, callXmlP),
                        XMLRPC_MEMBLOCK_SIZE(char, callXmlP));
        
        clientP->clientTransportOps.call(
            envP, clientP->transportP, serverInfoP, callXmlP, &respXmlP);
        if (!envP->fault_occurred) {
            int faultCode;
            const char * faultString;

            xmlrpc_traceXml("XML-RPC RESPONSE", 
                            XMLRPC_MEMBLOCK_CONTENTS(char, respXmlP),
                            XMLRPC_MEMBLOCK_SIZE(char, respXmlP));
            
            xmlrpc_parse_response2(
                envP,
                XMLRPC_MEMBLOCK_CONTENTS(char, respXmlP),
                XMLRPC_MEMBLOCK_SIZE(char, respXmlP),
                resultPP, &faultCode, &faultString);

            if (!envP->fault_occurred) {
                if (faultString) {
                    xmlrpc_env_set_fault_formatted(
                        envP, faultCode,
                        "RPC failed at server.  %s", faultString);
                    xmlrpc_strfree(faultString);
                } else
                    XMLRPC_ASSERT_VALUE_OK(*resultPP);
            }
            XMLRPC_MEMBLOCK_FREE(char, respXmlP);
        }
        XMLRPC_MEMBLOCK_FREE(char, callXmlP);
    }
}



static void
clientCall2f_va(xmlrpc_env *               const envP,
                xmlrpc_client *            const clientP,
                const char *               const serverUrl,
                const char *               const methodName,
                const char *               const format,
                xmlrpc_value **            const resultPP,
                va_list                          args) {

    xmlrpc_value * argP;
    xmlrpc_env argenv;
    const char * suffix;

    XMLRPC_ASSERT_ENV_OK(envP);
    XMLRPC_ASSERT_PTR_OK(serverUrl);
    XMLRPC_ASSERT_PTR_OK(methodName);
    XMLRPC_ASSERT_PTR_OK(format);
    XMLRPC_ASSERT_PTR_OK(resultPP);

    /* Build our argument value. */
    xmlrpc_env_init(&argenv);
    xmlrpc_build_value_va(&argenv, format, args, &argP, &suffix);
    if (argenv.fault_occurred)
        xmlrpc_env_set_fault_formatted(
            envP, argenv.fault_code, "Invalid RPC arguments.  "
            "The format argument must indicate a single array, and the "
            "following arguments must correspond to that format argument.  "
            "The failure is: %s",
            argenv.fault_string);
    else {
        XMLRPC_ASSERT_VALUE_OK(argP);
        
        if (*suffix != '\0')
            xmlrpc_faultf(envP, "Junk after the argument specifier: '%s'.  "
                          "There must be exactly one argument.",
                          suffix);
        else {
            xmlrpc_server_info * serverInfoP;

            serverInfoP = xmlrpc_server_info_new(envP, serverUrl);
            
            if (!envP->fault_occurred) {
                /* Perform the actual XML-RPC call. */
                xmlrpc_client_call2(envP, clientP,
                                    serverInfoP, methodName, argP, resultPP);
                if (!envP->fault_occurred)
                    XMLRPC_ASSERT_VALUE_OK(*resultPP);
                xmlrpc_server_info_free(serverInfoP);
            }
        }
        xmlrpc_DECREF(argP);
    }
    xmlrpc_env_clean(&argenv);
}



void
xmlrpc_client_call2f(xmlrpc_env *    const envP,
                     xmlrpc_client * const clientP,
                     const char *    const serverUrl,
                     const char *    const methodName,
                     xmlrpc_value ** const resultPP,
                     const char *    const format,
                     ...) {

    va_list args;

    va_start(args, format);
    clientCall2f_va(envP, clientP, serverUrl,
                    methodName, format, resultPP, args);
    va_end(args);
}



/*=========================================================================
   Asynchronous Call
=========================================================================*/

static void 
call_info_set_asynch_data(xmlrpc_env *       const env,
                          xmlrpc_call_info * const info,
                          const char *       const server_url,
                          const char *       const method_name,
                          xmlrpc_value *     const argP,
                          xmlrpc_response_handler responseHandler,
                          void *             const user_data) {

    xmlrpc_value *holder;

    /* Error-handling preconditions. */
    holder = NULL;

    XMLRPC_ASSERT_ENV_OK(env);
    XMLRPC_ASSERT_PTR_OK(info);
    XMLRPC_ASSERT(info->_asynch_data_holder == NULL);
    XMLRPC_ASSERT_PTR_OK(server_url);
    XMLRPC_ASSERT_PTR_OK(method_name);
    XMLRPC_ASSERT_VALUE_OK(argP);

    /* Install our callback and user_data.
    ** (We're not responsible for destroying the user_data.) */
    info->callback  = responseHandler;
    info->user_data = user_data;

    /* Build an XML-RPC data structure to hold our other data. This makes
    ** copies of server_url and method_name, and increments the reference
    ** to the argument *argP. */
    holder = xmlrpc_build_value(env, "(ssV)",
                                server_url, method_name, argP);
    XMLRPC_FAIL_IF_FAULT(env);

    /* Parse the newly-allocated structure into our public member variables.
    ** This doesn't make any new references, so we can dispose of the whole
    ** thing by DECREF'ing the one master reference. Nifty, huh? */
    xmlrpc_parse_value(env, holder, "(ssV)",
                       &info->server_url,
                       &info->method_name,
                       &info->param_array);
    XMLRPC_FAIL_IF_FAULT(env);

    /* Hand over ownership of the holder to the call_info struct. */
    info->_asynch_data_holder = holder;
    holder = NULL;

 cleanup:
    if (env->fault_occurred) {
        if (holder)
            xmlrpc_DECREF(holder);
    }
}



static void 
call_info_free(xmlrpc_call_info * const callInfoP) {

    /* Assume the worst.. That only parts of the call_info are valid. */

    XMLRPC_ASSERT_PTR_OK(callInfoP);

    /* If this has been allocated, we're responsible for destroying it. */
    if (callInfoP->_asynch_data_holder)
        xmlrpc_DECREF(callInfoP->_asynch_data_holder);

    /* Now we can blow away the XML data. */
    if (callInfoP->serialized_xml)
         xmlrpc_mem_block_free(callInfoP->serialized_xml);

    free(callInfoP);
}



static void
call_info_new(xmlrpc_env *               const envP,
              const char *               const methodName,
              xmlrpc_value *             const paramArrayP,
              xmlrpc_call_info **        const callInfoPP) {
/*----------------------------------------------------------------------------
   Create a call_info object.  A call_info object represents an XML-RPC
   call.
-----------------------------------------------------------------------------*/
    struct xmlrpc_call_info * callInfoP;

    XMLRPC_ASSERT_PTR_OK(paramArrayP);
    XMLRPC_ASSERT_PTR_OK(callInfoPP);

    MALLOCVAR(callInfoP);
    if (callInfoP == NULL)
        xmlrpc_env_set_fault_formatted(
            envP, XMLRPC_INTERNAL_ERROR,
            "Couldn't allocate memory for xmlrpc_call_info");
    else {
        xmlrpc_mem_block * callXmlP;

        /* Clear contents. */
        memset(callInfoP, 0, sizeof(*callInfoP));
        
        makeCallXml(envP, methodName, paramArrayP, &callXmlP);

        if (!envP->fault_occurred) {
            xmlrpc_traceXml("XML-RPC CALL", 
                            XMLRPC_MEMBLOCK_CONTENTS(char, callXmlP),
                            XMLRPC_MEMBLOCK_SIZE(char, callXmlP));
            
            callInfoP->serialized_xml = callXmlP;
            
            *callInfoPP = callInfoP;

            if (envP->fault_occurred)
                free(callInfoP);
        }
    }
}



void 
xmlrpc_client_event_loop_finish(xmlrpc_client * const clientP) {

    XMLRPC_ASSERT_PTR_OK(clientP);

    clientP->clientTransportOps.finish_asynch(
        clientP->transportP, timeout_no, 0);
}



void 
xmlrpc_client_event_loop_finish_timeout(xmlrpc_client * const clientP,
                                        xmlrpc_timeout  const timeout) {

    XMLRPC_ASSERT_PTR_OK(clientP);

    clientP->clientTransportOps.finish_asynch(
        clientP->transportP, timeout_yes, timeout);
}



static void
asynchComplete(struct xmlrpc_call_info * const callInfoP,
               xmlrpc_mem_block *        const responseXmlP,
               xmlrpc_env                const transportEnv) {
/*----------------------------------------------------------------------------
   Complete an asynchronous XML-RPC call request.

   This includes calling the user's RPC completion routine.

   'transportEnv' describes an error that the transport
   encountered in processing the call.  If the transport successfully
   sent the call to the server and processed the response but the
   server failed the call, 'transportEnv' indicates no error, and the
   response in *responseXmlP might very well indicate that the server
   failed the request.
-----------------------------------------------------------------------------*/
    xmlrpc_env env;
    xmlrpc_value * resultP;

    xmlrpc_env_init(&env);

    resultP = NULL;  /* Just to quiet compiler warning */

    if (transportEnv.fault_occurred)
        xmlrpc_env_set_fault_formatted(
            &env, transportEnv.fault_code,
            "Client transport failed to execute the RPC.  %s",
            transportEnv.fault_string);

    if (!env.fault_occurred) {
        int faultCode;
        const char * faultString;

        xmlrpc_parse_response2(&env,
                               XMLRPC_MEMBLOCK_CONTENTS(char, responseXmlP),
                               XMLRPC_MEMBLOCK_SIZE(char, responseXmlP),
                               &resultP, &faultCode, &faultString);

        if (!env.fault_occurred) {
            if (faultString) {
                xmlrpc_env_set_fault_formatted(
                    &env, faultCode,
                    "RPC failed at server.  %s", faultString);
                xmlrpc_strfree(faultString);
            }
        }
    }
    /* Call the user's callback function with the result */
    (*callInfoP->callback)(callInfoP->server_url, 
                           callInfoP->method_name, 
                           callInfoP->param_array,
                           callInfoP->user_data, &env, resultP);

    if (!env.fault_occurred)
        xmlrpc_DECREF(resultP);

    call_info_free(callInfoP);

    xmlrpc_env_clean(&env);
}



void
xmlrpc_client_start_rpc(xmlrpc_env *             const envP,
                        struct xmlrpc_client *   const clientP,
                        xmlrpc_server_info *     const serverInfoP,
                        const char *             const methodName,
                        xmlrpc_value *           const argP,
                        xmlrpc_response_handler        responseHandler,
                        void *                   const userData) {
    
    xmlrpc_call_info * callInfoP;

    XMLRPC_ASSERT_ENV_OK(envP);
    XMLRPC_ASSERT_PTR_OK(clientP);
    XMLRPC_ASSERT_PTR_OK(serverInfoP);
    XMLRPC_ASSERT_PTR_OK(methodName);
    XMLRPC_ASSERT_PTR_OK(responseHandler);
    XMLRPC_ASSERT_VALUE_OK(argP);

    call_info_new(envP, methodName, argP, &callInfoP);
    if (!envP->fault_occurred) {
        call_info_set_asynch_data(envP, callInfoP, 
                                  serverInfoP->_server_url, methodName,
                                  argP, responseHandler, userData);
        if (!envP->fault_occurred)
            clientP->clientTransportOps.send_request(
                envP, clientP->transportP, serverInfoP,
                callInfoP->serialized_xml,
                &asynchComplete, callInfoP);

        if (envP->fault_occurred)
            call_info_free(callInfoP);
        else {
            /* asynchComplete() will free *callInfoP */
        }
    }
}



void 
xmlrpc_client_start_rpcf(xmlrpc_env *    const envP,
                         xmlrpc_client * const clientP,
                         const char *    const serverUrl,
                         const char *    const methodName,
                         xmlrpc_response_handler responseHandler,
                         void *          const userData,
                         const char *    const format,
                         ...) {

    va_list args;
    xmlrpc_value * paramArrayP;
    const char * suffix;

    XMLRPC_ASSERT_PTR_OK(serverUrl);
    XMLRPC_ASSERT_PTR_OK(format);

    /* Build our argument array. */
    va_start(args, format);
    xmlrpc_build_value_va(envP, format, args, &paramArrayP, &suffix);
    va_end(args);
    if (!envP->fault_occurred) {
        if (*suffix != '\0')
            xmlrpc_faultf(envP, "Junk after the argument "
                          "specifier: '%s'.  "
                          "There must be exactly one arument.",
                          suffix);
        else {
            xmlrpc_server_info * serverInfoP;

            serverInfoP = xmlrpc_server_info_new(envP, serverUrl);
            if (!envP->fault_occurred) {
                xmlrpc_client_start_rpc(
                    envP, clientP,
                    serverInfoP, methodName, paramArrayP,
                    responseHandler, userData);
            }
            xmlrpc_server_info_free(serverInfoP);
        }
        xmlrpc_DECREF(paramArrayP);
    }
}



/*=========================================================================
   Miscellaneous
=========================================================================*/

void 
xmlrpc_server_info_set_basic_auth(xmlrpc_env *         const envP,
                                  xmlrpc_server_info * const serverP,
                                  const char *         const username,
                                  const char *         const password) {

    size_t username_len, password_len, raw_token_len;
    char *raw_token;
    xmlrpc_mem_block *token;
    char *token_data, *auth_type, *auth_header;
    size_t token_len, auth_type_len, auth_header_len;

    /* Error-handling preconditions. */
    raw_token = NULL;
    token = NULL;
    token_data = auth_type = auth_header = NULL;

    XMLRPC_ASSERT_ENV_OK(envP);
    XMLRPC_ASSERT_PTR_OK(serverP);
    XMLRPC_ASSERT_PTR_OK(username);
    XMLRPC_ASSERT_PTR_OK(password);

    /* Calculate some lengths. */
    username_len = strlen(username);
    password_len = strlen(password);
    raw_token_len = username_len + password_len + 1;

    /* Build a raw token of the form 'username:password'. */
    raw_token = (char*) malloc(raw_token_len + 1);
    XMLRPC_FAIL_IF_NULL(raw_token, envP, XMLRPC_INTERNAL_ERROR,
                        "Couldn't allocate memory for auth token");
    strcpy(raw_token, username);
    raw_token[username_len] = ':';
    strcpy(&raw_token[username_len + 1], password);

    /* Encode our raw token using Base64. */
    token = xmlrpc_base64_encode_without_newlines(envP, 
                                                  (unsigned char*) raw_token,
                                                  raw_token_len);
    XMLRPC_FAIL_IF_FAULT(envP);
    token_data = XMLRPC_TYPED_MEM_BLOCK_CONTENTS(char, token);
    token_len = XMLRPC_TYPED_MEM_BLOCK_SIZE(char, token);

    /* Build our actual header value. (I hate string processing in C.) */
    auth_type = "Basic ";
    auth_type_len = strlen(auth_type);
    auth_header_len = auth_type_len + token_len;
    auth_header = (char*) malloc(auth_header_len + 1);
    XMLRPC_FAIL_IF_NULL(auth_header, envP, XMLRPC_INTERNAL_ERROR,
                        "Couldn't allocate memory for auth header");
    memcpy(auth_header, auth_type, auth_type_len);
    memcpy(&auth_header[auth_type_len], token_data, token_len);
    auth_header[auth_header_len] = '\0';

    /* Clean up any pre-existing authentication information, and install
    ** the new value. */
    if (serverP->_http_basic_auth)
        free(serverP->_http_basic_auth);
    serverP->_http_basic_auth = auth_header;

 cleanup:
    if (raw_token)
        free(raw_token);
    if (token)
        xmlrpc_mem_block_free(token);
    if (envP->fault_occurred) {
        if (auth_header)
            free(auth_header);
    }
}



const char * 
xmlrpc_client_get_default_transport(xmlrpc_env * const env ATTR_UNUSED) {

    return XMLRPC_DEFAULT_TRANSPORT;
}



/* Copyright (C) 2001 by First Peer, Inc. All rights reserved.
**
** Redistribution and use in source and binary forms, with or without
** modification, are permitted provided that the following conditions
** are met:
** 1. Redistributions of source code must retain the above copyright
**    notice, this list of conditions and the following disclaimer.
** 2. Redistributions in binary form must reproduce the above copyright
**    notice, this list of conditions and the following disclaimer in the
**    documentation and/or other materials provided with the distribution.
** 3. The name of the author may not be used to endorse or promote products
**    derived from this software without specific prior written permission. 
**  
** THIS SOFTWARE IS PROVIDED BY THE AUTHOR AND CONTRIBUTORS ``AS IS'' AND
** ANY EXPRESS OR IMPLIED WARRANTIES, INCLUDING, BUT NOT LIMITED TO, THE
** IMPLIED WARRANTIES OF MERCHANTABILITY AND FITNESS FOR A PARTICULAR PURPOSE
** ARE DISCLAIMED.  IN NO EVENT SHALL THE AUTHOR OR CONTRIBUTORS BE LIABLE
** FOR ANY DIRECT, INDIRECT, INCIDENTAL, SPECIAL, EXEMPLARY, OR CONSEQUENTIAL
** DAMAGES (INCLUDING, BUT NOT LIMITED TO, PROCUREMENT OF SUBSTITUTE GOODS
** OR SERVICES; LOSS OF USE, DATA, OR PROFITS; OR BUSINESS INTERRUPTION)
** HOWEVER CAUSED AND ON ANY THEORY OF LIABILITY, WHETHER IN CONTRACT, STRICT
** LIABILITY, OR TORT (INCLUDING NEGLIGENCE OR OTHERWISE) ARISING IN ANY WAY
** OUT OF THE USE OF THIS SOFTWARE, EVEN IF ADVISED OF THE POSSIBILITY OF
** SUCH DAMAGE.
*/
