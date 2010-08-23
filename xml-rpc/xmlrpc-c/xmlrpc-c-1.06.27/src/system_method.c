/* Copyright information is at end of file */

#include "xmlrpc_config.h"

#include <assert.h>
#include <stdlib.h>
#include <string.h>

#include "xmlrpc-c/base_int.h"
#include "xmlrpc-c/string_int.h"
#include "xmlrpc-c/base.h"
#include "xmlrpc-c/server.h"
#include "registry.h"

#include "system_method.h"


struct systemMethodReg {
/*----------------------------------------------------------------------------
   Information needed to register a system method
-----------------------------------------------------------------------------*/
    const char *  const methodName;
    xmlrpc_method const methodFunction;
    const char *  const signatureString;
    const char *  const helpText;
};



void 
xmlrpc_registry_disable_introspection(xmlrpc_registry * const registryP) {

    XMLRPC_ASSERT_PTR_OK(registryP);

    registryP->_introspection_enabled = false;
}



static void
translateTypeSpecifierToName(xmlrpc_env *  const envP,
                             char          const typeSpecifier,
                             const char ** const typeNameP) {

    switch (typeSpecifier) {
    case 'i': *typeNameP = "int";              break;
    case 'b': *typeNameP = "boolean";          break;
    case 'd': *typeNameP = "double";           break;
    case 's': *typeNameP = "string";           break;
    case '8': *typeNameP = "dateTime.iso8601"; break;
    case '6': *typeNameP = "base64";           break;
    case 'S': *typeNameP = "struct";           break;
    case 'A': *typeNameP = "array";            break;
    case 'n': *typeNameP = "nil";              break;
    default:
        xmlrpc_faultf(envP, 
                      "Method registry contains invalid signature "
                      "data.  It contains the type specifier '%c'",
                      typeSpecifier);
    }
}
                


static void
parseOneTypeSpecifier(xmlrpc_env *   const envP,
                      const char *   const startP,
                      xmlrpc_value * const signatureP,
                      const char **  const nextP) {
/*----------------------------------------------------------------------------
   Parse one type specifier at 'startP' within a signature string.

   Add the appropriate item for it to the array 'signatureP'.

   Return as *nextP the location the signature string just past the
   type specifier, and also past the colon that comes after the 
   type specifier for a return value.
-----------------------------------------------------------------------------*/
    const char * typeName;
    const char * cursorP;

    cursorP = startP;
                
    translateTypeSpecifierToName(envP, *cursorP, &typeName);
    
    if (!envP->fault_occurred) {
        xmlrpc_value * typeP;
        int sigArraySize;
        
        /* Append the appropriate string to the signature. */
        typeP = xmlrpc_string_new(envP, typeName);
        xmlrpc_array_append_item(envP, signatureP, typeP);
        xmlrpc_DECREF(typeP);
        
        ++cursorP; /* move past the type specifier */
        
        sigArraySize = xmlrpc_array_size(envP, signatureP);
        if (!envP->fault_occurred) {
            if (sigArraySize == 1) {
                /* We parsed off the result type, so we should now
                   see the colon that separates the result type from
                   the parameter types
                */
                if (*cursorP != ':')
                    xmlrpc_faultf(envP, "No colon (':') after "
                                  "the result type specifier");
                else
                    ++cursorP;
            }
        }
    }
    *nextP = cursorP;
}



static void
parseOneSignature(xmlrpc_env *    const envP,
                  const char *    const startP,
                  xmlrpc_value ** const signaturePP,
                  const char **   const nextP) {
/*----------------------------------------------------------------------------
   Parse one signature from the signature string that starts at 'startP'.

   Return that signature as an array xmlrpc_value pointer
   *signaturePP.  The array has one element for the return value,
   followed by one element for each parameter described in the
   signature.  That element is a string naming the return value or
   parameter type, e.g. "int".

   Return as *nextP the location in the signature string of the next
   signature (i.e. right after the next comma).  If there is no next
   signature (the string ends before any comma), make it point to the
   terminating NUL.
-----------------------------------------------------------------------------*/
    xmlrpc_value * signatureP;

    signatureP = xmlrpc_array_new(envP);  /* Start with empty array */
    if (!envP->fault_occurred) {
        const char * cursorP;

        cursorP = startP;  /* start at the beginning */

        while (!envP->fault_occurred && *cursorP != ',' && *cursorP != '\0')
            parseOneTypeSpecifier(envP, cursorP, signatureP, &cursorP);

        if (!envP->fault_occurred) {
            if (xmlrpc_array_size(envP, signatureP) < 1)
                xmlrpc_faultf(envP, "empty signature (a signature "
                              "must have at least  return value type)");
            if (*cursorP != '\0') {
                assert(*cursorP == ',');
                ++cursorP;
            }
            *nextP = cursorP;
        }
        if (envP->fault_occurred)
            xmlrpc_DECREF(signatureP);
        else
            *signaturePP = signatureP;
    }
}    



void
xmlrpc_buildSignatureArray(xmlrpc_env *    const envP,
                           const char *    const sigListString,
                           xmlrpc_value ** const resultPP) {
/*----------------------------------------------------------------------------
  Turn the signature string 'sig' (e.g. "ii,s") into an array
  as *resultP.  The array contains one element for each signature in
  the string.  (Signatures are separated by commas.  The "ii,s" example
  is two signatures: "ii" and "s").  Each element is itself an array
  as described under parseOneSignature().
-----------------------------------------------------------------------------*/
    xmlrpc_value * signatureListP;

    signatureListP = xmlrpc_array_new(envP);
    if (!envP->fault_occurred) {
        if (sigListString == NULL || xmlrpc_streq(sigListString, "?")) {
            /* No signatures -- leave the array empty */
        } else {
            const char * cursorP;
            
            cursorP = &sigListString[0];
            
            while (!envP->fault_occurred && *cursorP != '\0') {
                xmlrpc_value * signatureP;
                
                parseOneSignature(envP, cursorP, &signatureP, &cursorP);
                
                /* cursorP now points at next signature in the list or the
                   terminating NUL.
                */
                
                if (!envP->fault_occurred) {
                    xmlrpc_array_append_item(envP, signatureListP, signatureP);
                    xmlrpc_DECREF(signatureP);
                }
            }
            if (!envP->fault_occurred) {
                unsigned int const arraySize = 
                    xmlrpc_array_size(envP, signatureListP);
                XMLRPC_ASSERT_ENV_OK(envP);
                if (arraySize < 1)
                    xmlrpc_faultf(envP, "Signature string is empty.");
            }
        }
        if (envP->fault_occurred)
            xmlrpc_DECREF(signatureListP);
    }
    *resultPP = signatureListP;
}



/*=========================================================================
  system.multicall
=========================================================================*/

static xmlrpc_value *
call_one_method(xmlrpc_env *env, xmlrpc_registry *registry,
                xmlrpc_value *method_info) {

    xmlrpc_value *result_val, *result;
    char *method_name;
    xmlrpc_value *param_array;

    /* Error-handling preconditions. */
    result = result_val = NULL;
    
    /* Extract our method name and parameters. */
    xmlrpc_parse_value(env, method_info, "{s:s,s:A,*}",
                       "methodName", &method_name,
                       "params", &param_array);
    XMLRPC_FAIL_IF_FAULT(env);

    /* Watch out for a deep recursion attack. */
    if (strcmp(method_name, "system.multicall") == 0)
        XMLRPC_FAIL(env, XMLRPC_REQUEST_REFUSED_ERROR,
                    "Recursive system.multicall strictly forbidden");
    
    /* Perform the call. */
    xmlrpc_dispatchCall(env, registry, method_name, param_array, &result_val);
    XMLRPC_FAIL_IF_FAULT(env);
    
    /* Build our one-item result array. */
    result = xmlrpc_build_value(env, "(V)", result_val);
    XMLRPC_FAIL_IF_FAULT(env);
    
 cleanup:
    if (result_val)
        xmlrpc_DECREF(result_val);
    if (env->fault_occurred) {
        if (result)
            xmlrpc_DECREF(result);
        return NULL;
    }
    return result;
}



static xmlrpc_value *
system_multicall(xmlrpc_env *env,
                 xmlrpc_value *param_array,
                 void *user_data) {

    xmlrpc_registry *registry;
    xmlrpc_value *methlist, *methinfo, *results, *result;
    size_t size, i;
    xmlrpc_env env2;

    XMLRPC_ASSERT_ENV_OK(env);
    XMLRPC_ASSERT_VALUE_OK(param_array);
    XMLRPC_ASSERT_PTR_OK(user_data);

    /* Error-handling preconditions. */
    results = result = NULL;
    xmlrpc_env_init(&env2);
    
    /* Turn our arguments into something more useful. */
    registry = (xmlrpc_registry*) user_data;
    xmlrpc_parse_value(env, param_array, "(A)", &methlist);
    XMLRPC_FAIL_IF_FAULT(env);

    /* Create an empty result list. */
    results = xmlrpc_build_value(env, "()");
    XMLRPC_FAIL_IF_FAULT(env);

    /* Loop over our input list, calling each method in turn. */
    size = xmlrpc_array_size(env, methlist);
    XMLRPC_ASSERT_ENV_OK(env);
    for (i = 0; i < size; i++) {
        methinfo = xmlrpc_array_get_item(env, methlist, i);
        XMLRPC_ASSERT_ENV_OK(env);
        
        /* Call our method. */
        xmlrpc_env_clean(&env2);
        xmlrpc_env_init(&env2);
        result = call_one_method(&env2, registry, methinfo);
        
        /* Turn any fault into a structure. */
        if (env2.fault_occurred) {
            XMLRPC_ASSERT(result == NULL);
            result = 
                xmlrpc_build_value(env, "{s:i,s:s}",
                                   "faultCode", (xmlrpc_int32) env2.fault_code,
                                   "faultString", env2.fault_string);
            XMLRPC_FAIL_IF_FAULT(env);
        }
        
        /* Append this method result to our master array. */
        xmlrpc_array_append_item(env, results, result);
        xmlrpc_DECREF(result);
        result = NULL;
        XMLRPC_FAIL_IF_FAULT(env);
    }

 cleanup:
    xmlrpc_env_clean(&env2);
    if (result)
        xmlrpc_DECREF(result);
    if (env->fault_occurred) {
        if (results)
            xmlrpc_DECREF(results);
        return NULL;
    }
    return results;
}



static struct systemMethodReg const multicall = {
    "system.multicall",
    &system_multicall,
    "A:A",
    "Process an array of calls, and return an array of results.  Calls should "
    "be structs of the form {'methodName': string, 'params': array}. Each "
    "result will either be a single-item array containg the result value, or "
    "a struct of the form {'faultCode': int, 'faultString': string}.  This "
    "is useful when you need to make lots of small calls without lots of "
    "round trips.",
};


/*=========================================================================
   system.listMethods
=========================================================================*/



static xmlrpc_value *
system_listMethods(xmlrpc_env *env,
                   xmlrpc_value *param_array,
                   void *user_data) {

    xmlrpc_registry *registry;
    xmlrpc_value *method_names, *method_name, *method_info;
    size_t size, i;

    XMLRPC_ASSERT_ENV_OK(env);
    XMLRPC_ASSERT_VALUE_OK(param_array);
    XMLRPC_ASSERT_PTR_OK(user_data);

    /* Error-handling preconditions. */
    method_names = NULL;

    /* Turn our arguments into something more useful. */
    registry = (xmlrpc_registry*) user_data;
    xmlrpc_parse_value(env, param_array, "()");
    XMLRPC_FAIL_IF_FAULT(env);
    
    /* Make sure we're allowed to introspect. */
    if (!registry->_introspection_enabled)
        XMLRPC_FAIL(env, XMLRPC_INTROSPECTION_DISABLED_ERROR,
                    "Introspection disabled for security reasons");
    
    /* Iterate over all the methods in the registry, adding their names
    ** to a list. */
    method_names = xmlrpc_build_value(env, "()");
    XMLRPC_FAIL_IF_FAULT(env);
    size = xmlrpc_struct_size(env, registry->_methods);
    XMLRPC_FAIL_IF_FAULT(env);
    for (i = 0; i < size; i++) {
        xmlrpc_struct_get_key_and_value(env, registry->_methods, i,
                                        &method_name, &method_info);
        XMLRPC_FAIL_IF_FAULT(env);
        xmlrpc_array_append_item(env, method_names, method_name);
        XMLRPC_FAIL_IF_FAULT(env);
    }

 cleanup:
    if (env->fault_occurred) {
        if (method_names)
            xmlrpc_DECREF(method_names);
        return NULL;
    }
    return method_names;
}

static struct systemMethodReg const listMethods = {
    "system.listMethods",
    &system_listMethods,
    "A:",
    "Return an array of all available XML-RPC methods on this server.",
};



/*=========================================================================
  system.methodHelp
=========================================================================*/

static xmlrpc_value *
system_methodHelp(xmlrpc_env *env,
                  xmlrpc_value *param_array,
                  void *user_data) {

    xmlrpc_registry *registry;
    char *method_name;
    xmlrpc_value *ignored1, *ignored2, *ignored3, *help;

    XMLRPC_ASSERT_ENV_OK(env);
    XMLRPC_ASSERT_VALUE_OK(param_array);
    XMLRPC_ASSERT_PTR_OK(user_data);

    /* Turn our arguments into something more useful. */
    registry = (xmlrpc_registry*) user_data;
    xmlrpc_parse_value(env, param_array, "(s)", &method_name);
    XMLRPC_FAIL_IF_FAULT(env);
    
    /* Make sure we're allowed to introspect. */
    if (!registry->_introspection_enabled)
        XMLRPC_FAIL(env, XMLRPC_INTROSPECTION_DISABLED_ERROR,
                    "Introspection disabled for security reasons");
    
    /* Get our documentation string. */
    xmlrpc_parse_value(env, registry->_methods, "{s:(VVVV*),*}",
                       method_name, &ignored1, &ignored2, &ignored3, &help);
    XMLRPC_FAIL_IF_FAULT(env);
    
 cleanup:
    if (env->fault_occurred)
        return NULL;
    xmlrpc_INCREF(help);
    return help;
}


static struct systemMethodReg const methodHelp = {
    "system.methodHelp",
    &system_methodHelp,
    "s:s",
    "Given the name of a method, return a help string.",
};



static void
getMethodInfo(xmlrpc_env *      const envP,
              xmlrpc_registry * const registryP,
              const char *      const methodName,
              xmlrpc_value **   const methodInfoPP) {
/*----------------------------------------------------------------------------
   Look up the method info for the named method.  Method info
   is an array (ppss):
-----------------------------------------------------------------------------*/
    xmlrpc_env env;
    xmlrpc_value * methodInfoP;
    
    xmlrpc_env_init(&env);
    
    /* We can't use xmlrpc_struct_find_value() here because it isn't
       thread-safe (it manipulates the reference count) and servers
       sometimes call system methods from multiple threads at once.
    */
    methodInfoP = xmlrpc_struct_get_value(
        &env, registryP->_methods, methodName);
    
    if (env.fault_occurred) {
        if (env.fault_code == XMLRPC_INDEX_ERROR)
            xmlrpc_env_set_fault_formatted(
                envP, XMLRPC_NO_SUCH_METHOD_ERROR,
                "Method '%s' does not exist", methodName);
        else
            xmlrpc_faultf(envP, "Unable to look up method named '%s' in the "
                          "registry.  %s", methodName, env.fault_string);
    } else
        *methodInfoPP = methodInfoP;

    xmlrpc_env_clean(&env);
}



/*=========================================================================
  system.methodSignature
==========================================================================*/

static void
buildNoSigSuppliedResult(xmlrpc_env *    const envP,
                         xmlrpc_value ** const resultPP) {

    xmlrpc_env env;

    xmlrpc_env_init(&env);

    *resultPP = xmlrpc_string_new(&env, "undef");
    if (env.fault_occurred)
        xmlrpc_faultf(envP, "Unable to construct 'undef'.  %s",
                      env.fault_string);

    xmlrpc_env_clean(&env);
}
    


static void
makeSigListCopy(xmlrpc_env *    const envP,
                xmlrpc_value *  const oldP,
                xmlrpc_value ** const newPP) {

    xmlrpc_value * newP;

    newP = xmlrpc_array_new(envP);

    if (!envP->fault_occurred) {
        unsigned int const size = xmlrpc_array_size(envP, oldP);
        if (!envP->fault_occurred) {
            unsigned int i;
            for (i = 0; i < size; ++i) {
                /* We can't use xmlrpc_array_read_item() here because
                   it isn't thread-safe (it manipulates the reference count)
                   an servers sometimes call system methods from multiple
                   threads at once.
                */
                xmlrpc_value * const itemP =
                    xmlrpc_array_get_item(envP, oldP, i);
                xmlrpc_array_append_item(envP, newP, itemP);
            }
        }                
    }
    *newPP = newP;
}



static void
getSignatureList(xmlrpc_env *      const envP,
                 xmlrpc_registry * const registryP,
                 const char *      const methodName,
                 xmlrpc_value **   const signatureListPP) {
/*----------------------------------------------------------------------------
  Get the signature list array for method named 'methodName' from registry
  'registryP'.

  If there is no signature information for the method in the registry,
  return *signatureListPP == NULL.

  Nonexistent method is considered a failure.
-----------------------------------------------------------------------------*/
    xmlrpc_value * methodInfoP;

    getMethodInfo(envP, registryP, methodName, &methodInfoP);
    if (!envP->fault_occurred) {
        xmlrpc_env env;
        xmlrpc_value * signatureListP;
        
        xmlrpc_env_init(&env);
        
        /* We can't use xmlrpc_array_read_item() because it isn't thread
           safe (it manipulates the reference count) and servers sometimes
           run system methods from multiple threads at once.
        */
        signatureListP = xmlrpc_array_get_item(&env, methodInfoP, 2);

        if (env.fault_occurred)
            xmlrpc_faultf(envP, "Failed to read signature list "
                          "from method info array.  %s",
                          env.fault_string);
        else {
            int arraySize;

            arraySize = xmlrpc_array_size(&env, signatureListP);
            if (env.fault_occurred)
                xmlrpc_faultf(envP, "xmlrpc_array_size() on signature "
                              "list array failed!  %s", env.fault_string);
            else {
                if (arraySize == 0)
                    *signatureListPP = NULL;
                else {
                    makeSigListCopy(envP, signatureListP, signatureListPP);
                }
            }
        }
        xmlrpc_env_clean(&env);
    }
}



static xmlrpc_value *
system_methodSignature(xmlrpc_env *   const envP,
                       xmlrpc_value * const paramArrayP,
                       void *         const userData) {

    xmlrpc_registry * const registryP = (xmlrpc_registry *) userData;

    xmlrpc_value * retvalP;
    const char * methodName;
    xmlrpc_env env;

    XMLRPC_ASSERT_ENV_OK(envP);
    XMLRPC_ASSERT_VALUE_OK(paramArrayP);
    XMLRPC_ASSERT_PTR_OK(userData);

    xmlrpc_env_init(&env);

    /* Turn our arguments into something more useful. */
    xmlrpc_decompose_value(&env, paramArrayP, "(s)", &methodName);
    if (env.fault_occurred)
        xmlrpc_env_set_fault_formatted(
            envP, env.fault_code,
            "Invalid parameter list.  %s", env.fault_string);
    else {
        if (!registryP->_introspection_enabled)
            xmlrpc_env_set_fault(envP, XMLRPC_INTROSPECTION_DISABLED_ERROR,
                                 "Introspection disabled on this server");
        else {
            xmlrpc_value * signatureListP;

            getSignatureList(envP, registryP, methodName, &signatureListP);

            if (!envP->fault_occurred) {
                if (signatureListP)
                    retvalP = signatureListP;
                else
                    buildNoSigSuppliedResult(envP, &retvalP);
            }
        }
        xmlrpc_strfree(methodName);
    }
    xmlrpc_env_clean(&env);

    return retvalP;
}



static struct systemMethodReg const methodSignature = {
    "system.methodSignature",
    &system_methodSignature,
    "A:s",
    "Given the name of a method, return an array of legal signatures. "
    "Each signature is an array of strings.  The first item of each signature "
    "is the return type, and any others items are parameter types.",
};




/*=========================================================================
  system.shutdown
==========================================================================*/

static xmlrpc_value *
system_shutdown(xmlrpc_env *   const envP,
                xmlrpc_value * const paramArrayP,
                void *         const userData) {
    
    xmlrpc_registry * const registryP = (xmlrpc_registry *) userData;

    xmlrpc_value * retvalP;
    const char * comment;
    xmlrpc_env env;

    XMLRPC_ASSERT_ENV_OK(envP);
    XMLRPC_ASSERT_VALUE_OK(paramArrayP);
    XMLRPC_ASSERT_PTR_OK(userData);

    xmlrpc_env_init(&env);

    retvalP = NULL;  /* quiet compiler warning */

    /* Turn our arguments into something more useful. */
    xmlrpc_decompose_value(&env, paramArrayP, "(s)", &comment);
    if (env.fault_occurred)
        xmlrpc_env_set_fault_formatted(
            envP, env.fault_code,
            "Invalid parameter list.  %s", env.fault_string);
    else {
        if (!registryP->_shutdown_server_fn)
            xmlrpc_env_set_fault(
                envP, 0, "This server program is not capable of "
                "shutting down");
        else {
            registryP->_shutdown_server_fn(
                &env, registryP->_shutdown_context, comment);

            if (env.fault_occurred)
                xmlrpc_env_set_fault(envP, env.fault_code, env.fault_string);
            else {
                retvalP = xmlrpc_int_new(&env, 0);
                
                if (env.fault_occurred)
                    xmlrpc_faultf(envP,
                                  "Failed to construct return value.  %s",
                                  env.fault_string);
            }
        }
        xmlrpc_strfree(comment);
    }
    xmlrpc_env_clean(&env);

    return retvalP;
}



static struct systemMethodReg const shutdown = {
    "system.shutdown",
    &system_shutdown,
    "i:s",
    "Shut down the server.  Return code is always zero.",
};



/*============================================================================
  Installer of system methods
============================================================================*/

static void
registerSystemMethod(xmlrpc_env *           const envP,
                     xmlrpc_registry *      const registryP,
                     struct systemMethodReg const methodReg) {

    xmlrpc_env env;
    xmlrpc_env_init(&env);
    
    xmlrpc_registry_add_method_w_doc(
        &env, registryP, NULL, methodReg.methodName,
        methodReg.methodFunction, registryP,
        methodReg.signatureString, methodReg.helpText);
    
    if (env.fault_occurred)
        xmlrpc_faultf(envP, "Failed to register '%s' system method.  %s",
                      methodReg.methodName, env.fault_string);
    
    xmlrpc_env_clean(&env);
}



void
xmlrpc_installSystemMethods(xmlrpc_env *      const envP,
                            xmlrpc_registry * const registryP) {
/*----------------------------------------------------------------------------
   Install the built-in methods (system.*) into registry 'registryP'.
-----------------------------------------------------------------------------*/
    if (!envP->fault_occurred)
        registerSystemMethod(envP, registryP, listMethods);

    if (!envP->fault_occurred) 
        registerSystemMethod(envP, registryP, methodSignature);

    if (!envP->fault_occurred)
        registerSystemMethod(envP, registryP, methodHelp);

    if (!envP->fault_occurred)
        registerSystemMethod(envP, registryP, multicall);

    if (!envP->fault_occurred)
        registerSystemMethod(envP, registryP, shutdown);
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

