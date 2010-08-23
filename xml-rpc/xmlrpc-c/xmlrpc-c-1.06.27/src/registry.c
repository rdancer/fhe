/* Copyright information is at end of file */

#include "xmlrpc_config.h"

#include <assert.h>
#include <stdlib.h>
#include <string.h>

#include "bool.h"
#include "mallocvar.h"
#include "xmlrpc-c/base_int.h"
#include "xmlrpc-c/string_int.h"
#include "xmlrpc-c/base.h"
#include "xmlrpc-c/server.h"
#include "system_method.h"

#include "registry.h"

/*=========================================================================
  XML-RPC Server Method Registry
===========================================================================
  A method registry is a list of XML-RPC methods for a server to
  implement, along with the details of how to implement each -- most
  notably a function pointer for a function that executes the method.

  To build an XML-RPC server, just add a communication facility.

  The registry object consists principally of an xmlrpc_value.  That
  xmlrpc_value is a struct, with method name as key.  Each value in
  the struct a "method info" array.  A method info array has the
  following items:

     0: cptr: method function ptr
     1: cptr: user data
     2: array: signature list
     3: string: help text.
    
  The signature list array contains one item for each form of call (a
  single method might have multiple forms, e.g. one takes two integer
  arguments; another takes a single string).  The array for each form
  represents a signature.  It has an item for each parameter and one
  for the result.  Item 0 is for the result, the rest are in order of
  the parameters.  Each item of that array is the name of the XML-RPC
  element that represents that type, e.g.  "int" or
  "dateTime.iso8601".

  Example signature:

    (("int"
      "double"
      "double"
     )
     ("int"
     )
    )

  The signature list array is empty to indicate that there is no signature
  information in the registry (it doesn't mean there are no valid forms
  of calling the method -- just that the registry declines to state).


  WARNING: there's a basic problem with using xmlrpc_value objects to
  represent the registry: xmlrpc_value objects are defined to be not
  thread-safe: you can't use one from two threads at the same time.
  But XML-RPC servers are often threaded, with multiple threads
  simultaneously executing multiple RPCs.  The normal Xmlrpc-c Abyss
  server is a good example.

  As a hack to make this work, we use the old, deprecated "get"
  functions that don't take a reference in the call dispatching code.
  Maintaining the reference count is the only way that the the
  thread-unsafety can manifest itself in our application.  Since the
  registry has at least one reference to each xmlrpc_value as long as
  the registry exists, we don't really need the exact reference count,
  so the deprecated functions work fine.

=========================================================================*/



xmlrpc_registry *
xmlrpc_registry_new(xmlrpc_env * const envP) {

    xmlrpc_registry * registryP;

    XMLRPC_ASSERT_ENV_OK(envP);
    
    MALLOCVAR(registryP);

    if (registryP == NULL)
        xmlrpc_faultf(envP, "Could not allocate memory for registry");
    else {
        registryP->_introspection_enabled = true;
        registryP->_default_method        = NULL;
        registryP->_preinvoke_method      = NULL;
        registryP->_shutdown_server_fn    = NULL;

        registryP->_methods = xmlrpc_struct_new(envP);
        if (!envP->fault_occurred) {
            xmlrpc_installSystemMethods(envP, registryP);
        }    
        if (envP->fault_occurred)
            free(registryP);
    }
    return registryP;
}



void 
xmlrpc_registry_free(xmlrpc_registry * const registryP) {

    XMLRPC_ASSERT_PTR_OK(registryP);
    XMLRPC_ASSERT(registryP->_methods != XMLRPC_BAD_POINTER);

    xmlrpc_DECREF(registryP->_methods);

    if (registryP->_default_method != NULL)
        xmlrpc_DECREF(registryP->_default_method);

    if (registryP->_preinvoke_method != NULL)
        xmlrpc_DECREF(registryP->_preinvoke_method);

    free(registryP);
}



void 
xmlrpc_registry_add_method_w_doc(
    xmlrpc_env *      const envP,
    xmlrpc_registry * const registryP,
    const char *      const host ATTR_UNUSED,
    const char *      const methodName,
    xmlrpc_method     const method,
    void *            const userData,
    const char *      const signatureString,
    const char *      const help) {

    const char * const helpString =
        help ? help : "No help is available for this method.";

    xmlrpc_env env;
    xmlrpc_value * signatureListP;

    XMLRPC_ASSERT_ENV_OK(envP);
    XMLRPC_ASSERT_PTR_OK(registryP);
    XMLRPC_ASSERT(host == NULL);
    XMLRPC_ASSERT_PTR_OK(methodName);
    XMLRPC_ASSERT_PTR_OK(method);

    xmlrpc_env_init(&env);

    xmlrpc_buildSignatureArray(&env, signatureString, &signatureListP);
    if (env.fault_occurred)
        xmlrpc_faultf(envP, "Can't interpret signature string '%s'.  %s",
                      signatureString, env.fault_string);
    else {
        xmlrpc_value * methodInfoP;

        XMLRPC_ASSERT_VALUE_OK(signatureListP);

        methodInfoP = xmlrpc_build_value(envP, "(ppVs)", (void*) method,
                                         userData, signatureListP, helpString);
        if (!envP->fault_occurred) {
            xmlrpc_struct_set_value(envP, registryP->_methods,
                                    methodName, methodInfoP);

            xmlrpc_DECREF(methodInfoP);
        }
        xmlrpc_DECREF(signatureListP);
    }
    xmlrpc_env_clean(&env);
}



void 
xmlrpc_registry_add_method(xmlrpc_env *env,
                           xmlrpc_registry *registry,
                           const char *host,
                           const char *method_name,
                           xmlrpc_method method,
                           void *user_data) {

    xmlrpc_registry_add_method_w_doc (env, registry, host, method_name,
                      method, user_data, "?",
                      "No help is available for this method.");
}



/*=========================================================================
**  xmlrpc_registry_set_default_method
**=========================================================================
**  See xmlrpc.h for more documentation.
*/

void 
xmlrpc_registry_set_default_method(xmlrpc_env *env,
                                   xmlrpc_registry *registry,
                                   xmlrpc_default_method handler,
                                   void *user_data) {
    xmlrpc_value *method_info;

    XMLRPC_ASSERT_ENV_OK(env);
    XMLRPC_ASSERT_PTR_OK(registry);
    XMLRPC_ASSERT_PTR_OK(handler);

    /* Error-handling preconditions. */
    method_info = NULL;
    
    /* Store our method and user data into our hash table. */
    method_info = xmlrpc_build_value(env, "(pp)", (void*) handler, user_data);
    XMLRPC_FAIL_IF_FAULT(env);

    /* Dispose of any pre-existing default method and install ours. */
    if (registry->_default_method)
        xmlrpc_DECREF(registry->_default_method);
    registry->_default_method = method_info;
    
cleanup:
    if (env->fault_occurred) {
        if (method_info)
            xmlrpc_DECREF(method_info);
    }
}




void 
xmlrpc_registry_set_preinvoke_method(xmlrpc_env *env,
                                     xmlrpc_registry *registry,
                                     xmlrpc_preinvoke_method handler,
                                     void *user_data) {
    xmlrpc_value *method_info;

    XMLRPC_ASSERT_ENV_OK(env);
    XMLRPC_ASSERT_PTR_OK(registry);
    XMLRPC_ASSERT_PTR_OK(handler);

    /* Error-handling preconditions. */
    method_info = NULL;

    /* Store our method and user data into our hash table. */
    method_info = xmlrpc_build_value(env, "(pp)", (void*) handler, user_data);
    XMLRPC_FAIL_IF_FAULT(env);

    /* Dispose of any pre-existing preinvoke method and install ours. */
    if (registry->_preinvoke_method)
        xmlrpc_DECREF(registry->_preinvoke_method);
    registry->_preinvoke_method = method_info;

 cleanup:
    if (env->fault_occurred) {
        if (method_info)
            xmlrpc_DECREF(method_info);
    }
}



void
xmlrpc_registry_set_shutdown(xmlrpc_registry *           const registryP,
                             xmlrpc_server_shutdown_fn * const shutdownFn,
                             void *                      const context) {

    XMLRPC_ASSERT_PTR_OK(registryP);
    XMLRPC_ASSERT_PTR_OK(shutdownFn);

    registryP->_shutdown_server_fn = shutdownFn;

    registryP->_shutdown_context = context;
}



/*=========================================================================
**  dispatch_call
**=========================================================================
**  An internal method which actually does the dispatch. This may get
**  prettified and exported at some point in the future.
*/

static void
callPreinvokeMethodIfAny(xmlrpc_env *      const envP,
                         xmlrpc_registry * const registryP,
                         const char *      const methodName,
                         xmlrpc_value *    const paramArrayP) {

    /* Get the preinvoke method, if it is set. */
    if (registryP->_preinvoke_method) {
        xmlrpc_preinvoke_method preinvoke_method;
        void * user_data;

        xmlrpc_parse_value(envP, registryP->_preinvoke_method, "(pp)",
                           &preinvoke_method, &user_data);
        if (!envP->fault_occurred)
            (*preinvoke_method)(envP, methodName,
                                paramArrayP, user_data);
    }
}



static void
callDefaultMethod(xmlrpc_env *    const envP,
                  xmlrpc_value *  const defaultMethodInfo,
                  const char *    const methodName,
                  xmlrpc_value *  const paramArrayP,
                  xmlrpc_value ** const resultPP) {

    xmlrpc_default_method default_method;
    void * user_data;

    xmlrpc_parse_value(envP, defaultMethodInfo, "(pp)",
                       &default_method, &user_data);

    if (!envP->fault_occurred)
        *resultPP = (*default_method)(envP, NULL, methodName,
                                      paramArrayP, user_data);
}
    


static void
callNamedMethod(xmlrpc_env *    const envP,
                xmlrpc_value *  const methodInfo,
                xmlrpc_value *  const paramArrayP,
                xmlrpc_value ** const resultPP) {

    xmlrpc_method method;
    void * user_data;
    
    xmlrpc_parse_value(envP, methodInfo, "(pp*)", &method, &user_data);
    if (!envP->fault_occurred)
        *resultPP = (*method)(envP, paramArrayP, user_data);
}



void
xmlrpc_dispatchCall(xmlrpc_env *      const envP, 
                    xmlrpc_registry * const registryP,
                    const char *      const methodName, 
                    xmlrpc_value *    const paramArrayP,
                    xmlrpc_value **   const resultPP) {

    callPreinvokeMethodIfAny(envP, registryP, methodName, paramArrayP);
    if (!envP->fault_occurred) {
        xmlrpc_value * methodInfoP;
        xmlrpc_env methodLookupEnv;

        xmlrpc_env_init(&methodLookupEnv);

        /* See comments at top of file about why we use the deprecated
           xmlrpc_struct_get_value() here
        */
        methodInfoP = xmlrpc_struct_get_value(&methodLookupEnv,
                                              registryP->_methods,
                                              methodName);
        if (!methodLookupEnv.fault_occurred)
            callNamedMethod(envP, methodInfoP, paramArrayP, resultPP);
        else if (methodLookupEnv.fault_code == XMLRPC_INDEX_ERROR) {
            if (registryP->_default_method)
                callDefaultMethod(envP, registryP->_default_method, 
                                  methodName, paramArrayP,
                                  resultPP);
            else {
                /* No matching method, and no default. */
                xmlrpc_env_set_fault_formatted(
                    envP, XMLRPC_NO_SUCH_METHOD_ERROR,
                    "Method '%s' not defined", methodName);
            }
        } else
            xmlrpc_faultf(envP, "failed to lookup method in registry's "
                          "internal method struct.  %s",
                          methodLookupEnv.fault_string);
        xmlrpc_env_clean(&methodLookupEnv); 
    }
    /* For backward compatibility, for sloppy users: */
    if (envP->fault_occurred)
        *resultPP = NULL;
}



/*=========================================================================
**  xmlrpc_registry_process_call
**=========================================================================
**
*/

xmlrpc_mem_block *
xmlrpc_registry_process_call(xmlrpc_env *      const envP,
                             xmlrpc_registry * const registryP,
                             const char *      const host ATTR_UNUSED,
                             const char *      const xml_data,
                             size_t            const xml_len) {

    xmlrpc_mem_block * output;

    XMLRPC_ASSERT_ENV_OK(envP);
    XMLRPC_ASSERT_PTR_OK(xml_data);
    
    xmlrpc_traceXml("XML-RPC CALL", xml_data, xml_len);

    /* Allocate our output buffer.
    ** If this fails, we need to die in a special fashion. */
    output = XMLRPC_MEMBLOCK_NEW(char, envP, 0);
    if (!envP->fault_occurred) {
        const char * methodName;
        xmlrpc_value * paramArray;
        xmlrpc_env fault;
        xmlrpc_env parseEnv;

        xmlrpc_env_init(&fault);
        xmlrpc_env_init(&parseEnv);

        xmlrpc_parse_call(&parseEnv, xml_data, xml_len, 
                          &methodName, &paramArray);

        if (parseEnv.fault_occurred)
            xmlrpc_env_set_fault_formatted(
                &fault, XMLRPC_PARSE_ERROR,
                "Call XML not a proper XML-RPC call.  %s",
                parseEnv.fault_string);
        else {
            xmlrpc_value * result;
            
            xmlrpc_dispatchCall(&fault, registryP, methodName, paramArray,
                                &result);

            if (!fault.fault_occurred) {
                xmlrpc_serialize_response(envP, output, result);

                /* A comment here used to say that
                   xmlrpc_serialize_response() could fail and "leave
                   stuff in the buffer."  Don't know what that means,
                   but it sounds like something that needs to be
                   fixed.  The old code aborted the program here if
                   xmlrpc_serialize_repsonse() failed.  04.11.17 
                */
                xmlrpc_DECREF(result);
            } 
            xmlrpc_strfree(methodName);
            xmlrpc_DECREF(paramArray);
        }
        if (!envP->fault_occurred && fault.fault_occurred)
            xmlrpc_serialize_fault(envP, output, &fault);

        xmlrpc_env_clean(&parseEnv);
        xmlrpc_env_clean(&fault);

        if (envP->fault_occurred)
            XMLRPC_MEMBLOCK_FREE(char, output);
        else
            xmlrpc_traceXml("XML-RPC RESPONSE", 
                            XMLRPC_MEMBLOCK_CONTENTS(char, output),
                            XMLRPC_MEMBLOCK_SIZE(char, output));
    }
    return output;
}


/* Copyright (C) 2001 by First Peer, Inc. All rights reserved.
** Copyright (C) 2001 by Eric Kidd. All rights reserved.
** Copyright (C) 2001 by Luke Howard. All rights reserved.
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
** SUCH DAMAGE. */
