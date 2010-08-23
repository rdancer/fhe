#include <stdlib.h>
#include <string.h>

#include "casprintf.h"
#include "girstring.h"

#include "xmlrpc_config.h"

#include "xmlrpc-c/base.h"
#include "xmlrpc-c/server.h"

#include "test.h"
#include "xml_data.h"
#include "method_registry.h"


#define FOO_USER_DATA ((void*) 0xF00)
#define BAR_USER_DATA ((void*) 0xBAF)



static xmlrpc_value *
test_foo(xmlrpc_env *   const envP,
         xmlrpc_value * const paramArrayP,
         void *         const userData) {

    xmlrpc_int32 x, y;

    TEST_NO_FAULT(envP);
    TEST(paramArrayP != NULL);
    TEST(userData == FOO_USER_DATA);

    xmlrpc_decompose_value(envP, paramArrayP, "(ii)", &x, &y);
    TEST_NO_FAULT(envP);
    TEST(x == 25);
    TEST(y == 17);

    return xmlrpc_build_value(envP, "i", (xmlrpc_int32) x + y);
}



static xmlrpc_value *
test_bar(xmlrpc_env *   const envP,
         xmlrpc_value * const paramArrayP,
         void *         const userData) {

    xmlrpc_int32 x, y;

    TEST_NO_FAULT(envP);
    TEST(paramArrayP != NULL);
    TEST(userData == BAR_USER_DATA);

    xmlrpc_decompose_value(envP, paramArrayP, "(ii)", &x, &y);
    TEST_NO_FAULT(envP);
    TEST(x == 25);
    TEST(y == 17);

    xmlrpc_env_set_fault(envP, 123, "Test fault");

    return NULL;
}



static xmlrpc_value *
test_default(xmlrpc_env *   const envP,
             const char *   const host ATTR_UNUSED,
             const char *   const methodName ATTR_UNUSED,
             xmlrpc_value * const paramArrayP,
             void *         const userData) {

    xmlrpc_int32 x, y;

    TEST_NO_FAULT(envP);
    TEST(paramArrayP != NULL);
    TEST(userData == FOO_USER_DATA);

    xmlrpc_decompose_value(envP, paramArrayP, "(ii)", &x, &y);
    TEST_NO_FAULT(envP);
    TEST(x == 25);
    TEST(y == 17);

    return xmlrpc_build_value(envP, "i", 2 * (x + y));
}



static void
doRpc(xmlrpc_env *      const envP,
      xmlrpc_registry * const registryP,
      const char *      const methodName,
      xmlrpc_value *    const argArrayP,
      xmlrpc_value **   const resultPP) {
/*----------------------------------------------------------------------------
   Do what an XML-RPC server would do -- pass an XML call to the registry
   and get XML back.

   Actually to our caller, we look more like an Xmlrpc-c client.  We're
   both the client and the server all bound together.
-----------------------------------------------------------------------------*/
    xmlrpc_mem_block * callP;
    xmlrpc_mem_block * responseP;

    /* Build a call, and tell the registry to handle it. */
    callP = xmlrpc_mem_block_new(envP, 0);
    TEST_NO_FAULT(envP);
    xmlrpc_serialize_call(envP, callP, methodName, argArrayP);
    TEST_NO_FAULT(envP);
    responseP = xmlrpc_registry_process_call(envP, registryP, NULL,
                                             xmlrpc_mem_block_contents(callP),
                                             xmlrpc_mem_block_size(callP));
    TEST_NO_FAULT(envP);
    TEST(responseP != NULL);

    /* Parse the response. */
    *resultPP = xmlrpc_parse_response(envP,
                                      xmlrpc_mem_block_contents(responseP),
                                      xmlrpc_mem_block_size(responseP));

    xmlrpc_mem_block_free(callP);
    xmlrpc_mem_block_free(responseP);
}



static const char * const validSigString[] = {
    "i:",
    "s:d",
    "i:bds86SA",
    "i:,i:",
    "i:dd,s:,A:A",
    "i:,",
    "b:i,",
    "b:i,b:,",
    NULL
};

static const char * const invalidSigString[] = {
    "",
    "i",
    "q",
    "i:q",
    "i:ddq",
    ",",
    ",i:",
    "i,",
    "b:i,,b:i",
    "ii:",
    "ii:ii",
    NULL
};


static void
test_signature_method(xmlrpc_registry * const registryP) {
/*----------------------------------------------------------------------------
   Test system.methodSignature system method.
-----------------------------------------------------------------------------*/
    xmlrpc_env env;
    xmlrpc_value * argArrayP;
    xmlrpc_value * resultP;
    const char * type0;
    const char * type1;
    const char * type2;
    const char * type3;
    const char * type4;
    const char * type5;
    const char * type6;
    const char * type7;
    const char * nosigstring;

    xmlrpc_env_init(&env);

    argArrayP = xmlrpc_build_value(&env, "(s)", "test.nosuchmethod");
    doRpc(&env, registryP, "system.methodSignature", argArrayP, &resultP);
    TEST_FAULT(&env, XMLRPC_NO_SUCH_METHOD_ERROR);
    xmlrpc_DECREF(argArrayP);

    argArrayP = xmlrpc_build_value(&env, "(s)", "test.nosig0");

    doRpc(&env, registryP, "system.methodSignature", argArrayP, &resultP);
    TEST_NO_FAULT(&env);

    xmlrpc_read_string(&env, resultP, &nosigstring);
    TEST_NO_FAULT(&env);
    
    TEST(streq(nosigstring, "undef"));
    strfree(nosigstring);
    xmlrpc_DECREF(resultP);
    xmlrpc_DECREF(argArrayP);

    argArrayP = xmlrpc_build_value(&env, "(s)", "test.validsig0");
    doRpc(&env, registryP, "system.methodSignature", argArrayP, &resultP);
    TEST_NO_FAULT(&env);

    xmlrpc_decompose_value(&env, resultP, "((s))", &type0);
    TEST_NO_FAULT(&env);
    TEST(streq(type0, "int"));
    strfree(type0);
    xmlrpc_DECREF(resultP);
    xmlrpc_DECREF(argArrayP);

    argArrayP = xmlrpc_build_value(&env, "(s)", "test.validsig2");
    doRpc(&env, registryP, "system.methodSignature", argArrayP, &resultP);
    TEST_NO_FAULT(&env);
    xmlrpc_decompose_value(&env, resultP, "((ssssssss))",
                           &type0, &type1, &type2, &type3,
                           &type4, &type5, &type6, &type7);
    TEST_NO_FAULT(&env);
    TEST(streq(type0, "int"));
    TEST(streq(type1, "boolean"));
    TEST(streq(type2, "double"));
    TEST(streq(type3, "string"));
    TEST(streq(type4, "dateTime.iso8601"));
    TEST(streq(type5, "base64"));
    TEST(streq(type6, "struct"));
    TEST(streq(type7, "array"));
    strfree(type0); strfree(type1); strfree(type2); strfree(type3);
    strfree(type4); strfree(type5); strfree(type6); strfree(type7);
    xmlrpc_DECREF(resultP);
    xmlrpc_DECREF(argArrayP);

    argArrayP = xmlrpc_build_value(&env, "(s)", "test.validsig3");
    doRpc(&env, registryP, "system.methodSignature", argArrayP, &resultP);
    TEST_NO_FAULT(&env);
    xmlrpc_decompose_value(&env, resultP, "((s)(s))", &type0, &type1);

    TEST_NO_FAULT(&env);
    TEST(streq(type0, "int"));
    TEST(streq(type1, "int"));
    xmlrpc_DECREF(resultP);
    xmlrpc_DECREF(argArrayP);

    xmlrpc_env_clean(&env);
}



static void
test_signature(void) {

    xmlrpc_env env;
    xmlrpc_registry * registryP;
    uint i;

    xmlrpc_env_init(&env);

    printf("  Running signature tests.");

    registryP = xmlrpc_registry_new(&env);
    TEST_NO_FAULT(&env);

    xmlrpc_registry_add_method_w_doc(&env, registryP, NULL, "test.nosig0",
                                     test_foo, FOO_USER_DATA,
                                     NULL, NULL);
    TEST_NO_FAULT(&env);

    xmlrpc_registry_add_method_w_doc(&env, registryP, NULL, "test.nosig1",
                                     test_foo, FOO_USER_DATA,
                                     "?", NULL);
    TEST_NO_FAULT(&env);

    for (i = 0; validSigString[i]; ++i) {
        const char * methodName;
        casprintf(&methodName, "test.validsig%u", i);
        xmlrpc_registry_add_method_w_doc(&env, registryP, NULL, methodName,
                                         test_foo, FOO_USER_DATA,
                                         validSigString[i], NULL);
        TEST_NO_FAULT(&env);
        strfree(methodName);
    }

    for (i = 0; invalidSigString[i]; ++i) {
        const char * methodName;
        casprintf(&methodName, "test.invalidsig%u", i);
        xmlrpc_registry_add_method_w_doc(&env, registryP, NULL, methodName,
                                         test_foo, FOO_USER_DATA,
                                         invalidSigString[i], NULL);
        TEST_FAULT(&env, XMLRPC_INTERNAL_ERROR);
        strfree(methodName);
    }

    test_signature_method(registryP);

    xmlrpc_registry_free(registryP);

    xmlrpc_env_clean(&env);

    printf("\n");
}



static void
test_system_multicall(xmlrpc_registry * const registryP) {
/*----------------------------------------------------------------------------
   Test system.multicall
-----------------------------------------------------------------------------*/
    xmlrpc_env env;
    xmlrpc_value * multiP;
    xmlrpc_int32 foo1_result, foo2_result;
    xmlrpc_int32 bar_code, nosuch_code, multi_code, bogus1_code, bogus2_code;
    char *bar_string, *nosuch_string, *multi_string;
    char *bogus1_string, *bogus2_string;
    xmlrpc_value * valueP;
    xmlrpc_value * argArrayP;

    xmlrpc_env_init(&env);

    printf("  Running multicall tests.");

    /* Build an argument array for our calls. */
    argArrayP = xmlrpc_build_value(&env, "(ii)",
                                   (xmlrpc_int32) 25, (xmlrpc_int32) 17); 
    TEST_NO_FAULT(&env);

    multiP = xmlrpc_build_value(&env,
                               "(({s:s,s:V}{s:s,s:V}{s:s,s:V}"
                               "{s:s,s:()}s{}{s:s,s:V}))",
                               "methodName", "test.foo",
                               "params", argArrayP,
                               "methodName", "test.bar",
                               "params", argArrayP,
                               "methodName", "test.nosuch",
                               "params", argArrayP,
                               "methodName", "system.multicall",
                               "params",
                               "bogus_entry",
                               "methodName", "test.foo",
                               "params", argArrayP);
    TEST_NO_FAULT(&env);    
    doRpc(&env, registryP, "system.multicall", multiP, &valueP);
    TEST_NO_FAULT(&env);
    xmlrpc_decompose_value(&env, valueP,
                           "((i){s:i,s:s,*}{s:i,s:s,*}"
                           "{s:i,s:s,*}{s:i,s:s,*}{s:i,s:s,*}(i))",
                           &foo1_result,
                           "faultCode", &bar_code,
                           "faultString", &bar_string,
                           "faultCode", &nosuch_code,
                           "faultString", &nosuch_string,
                           "faultCode", &multi_code,
                           "faultString", &multi_string,
                           "faultCode", &bogus1_code,
                           "faultString", &bogus1_string,
                           "faultCode", &bogus2_code,
                           "faultString", &bogus2_string,
                           &foo2_result);
    xmlrpc_DECREF(valueP);
    TEST_NO_FAULT(&env);    
    TEST(foo1_result == 42);
    TEST(bar_code == 123);
    TEST(strcmp(bar_string, "Test fault") == 0);
    TEST(nosuch_code == XMLRPC_NO_SUCH_METHOD_ERROR);
    TEST(multi_code == XMLRPC_REQUEST_REFUSED_ERROR);
    TEST(foo2_result == 42);
    xmlrpc_DECREF(multiP);
    free(bar_string);
    free(nosuch_string);
    free(multi_string);
    free(bogus1_string);
    free(bogus2_string);
    
    xmlrpc_DECREF(argArrayP);

    xmlrpc_env_clean(&env);

    printf("\n");
}



static void
testCall(xmlrpc_registry * const registryP) {

    xmlrpc_env env;
    xmlrpc_env env2;
    xmlrpc_value * argArrayP;
    xmlrpc_value * valueP;
    xmlrpc_int32 i;

    printf("  Running call tests.");

    xmlrpc_env_init(&env);

    /* Build an argument array for our calls. */
    argArrayP = xmlrpc_build_value(&env, "(ii)",
                                   (xmlrpc_int32) 25, (xmlrpc_int32) 17); 
    TEST_NO_FAULT(&env);

    /* Call test.foo and check the result. */
    doRpc(&env, registryP, "test.foo", argArrayP, &valueP);
    TEST_NO_FAULT(&env);
    TEST(valueP != NULL);
    xmlrpc_decompose_value(&env, valueP, "i", &i);
    xmlrpc_DECREF(valueP);
    TEST_NO_FAULT(&env);
    TEST(i == 42);

    /* Call test.bar and check the result. */
    xmlrpc_env_init(&env2);
    doRpc(&env2, registryP, "test.bar", argArrayP, &valueP);
    TEST(env2.fault_occurred);
    TEST(env2.fault_code == 123);
    TEST(env2.fault_string && strcmp(env2.fault_string, "Test fault") == 0);
    xmlrpc_env_clean(&env2);

    /* Call a non-existant method and check the result. */
    xmlrpc_env_init(&env2);
    doRpc(&env2, registryP, "test.nosuch", argArrayP, &valueP);
    TEST(valueP == NULL);
    TEST_FAULT(&env2, XMLRPC_NO_SUCH_METHOD_ERROR);
    xmlrpc_env_clean(&env2);

    xmlrpc_DECREF(argArrayP);

    xmlrpc_env_clean(&env);

    printf("\n");
}



static void
testDefaultMethod(xmlrpc_registry * const registryP) {
    
    xmlrpc_env env;
    xmlrpc_value * argArrayP;
    xmlrpc_value * valueP;
    xmlrpc_int32 i;
 
    xmlrpc_env_init(&env);

    printf("  Running default method tests.");

    /* Build an argument array for our calls. */
    argArrayP = xmlrpc_build_value(&env, "(ii)",
                                   (xmlrpc_int32) 25, (xmlrpc_int32) 17); 

    xmlrpc_registry_set_default_method(&env, registryP, &test_default,
                                       FOO_USER_DATA);
    TEST_NO_FAULT(&env);
    doRpc(&env, registryP, "test.nosuch", argArrayP, &valueP);
    TEST_NO_FAULT(&env);
    TEST(valueP != NULL);
    xmlrpc_decompose_value(&env, valueP, "i", &i);
    xmlrpc_DECREF(valueP);
    TEST_NO_FAULT(&env);
    TEST(i == 84);

    /* Change the default method. */
    xmlrpc_registry_set_default_method(&env, registryP, &test_default,
                                       BAR_USER_DATA);
    TEST_NO_FAULT(&env);

    xmlrpc_DECREF(argArrayP);

    xmlrpc_env_clean(&env);

    printf("\n");
}



void
test_method_registry(void) {

    xmlrpc_env env, env2;
    xmlrpc_value * valueP;
    xmlrpc_registry *registryP;
    xmlrpc_mem_block *response;

    xmlrpc_env_init(&env);

    printf("Running method registry tests.");

    /* Create a new registry. */
    registryP = xmlrpc_registry_new(&env);
    TEST_NO_FAULT(&env);
    TEST(registryP != NULL);

    /* Add some test methods. */
    xmlrpc_registry_add_method(&env, registryP, NULL, "test.foo",
                               test_foo, FOO_USER_DATA);
    TEST_NO_FAULT(&env);
    xmlrpc_registry_add_method(&env, registryP, NULL, "test.bar",
                               test_bar, BAR_USER_DATA);
    TEST_NO_FAULT(&env);

    printf("\n");
    testCall(registryP);

    test_system_multicall(registryP);

    /* PASS bogus XML data and make sure our parser pukes gracefully.
    ** (Because of the way the code is laid out, and the presence of other
    ** test suites, this lets us skip tests for invalid XML-RPC data.) */
    xmlrpc_env_init(&env2);
    response = xmlrpc_registry_process_call(&env, registryP, NULL,
                                            expat_error_data,
                                            strlen(expat_error_data));
    TEST_NO_FAULT(&env);
    TEST(response != NULL);
    valueP = xmlrpc_parse_response(&env2, xmlrpc_mem_block_contents(response),
                                  xmlrpc_mem_block_size(response));
    TEST(valueP == NULL);
    TEST_FAULT(&env2, XMLRPC_PARSE_ERROR);
    xmlrpc_mem_block_free(response);
    xmlrpc_env_clean(&env2);

    printf("\n");
    testDefaultMethod(registryP);

    test_signature();
    
    /* Test cleanup code (w/memprof). */
    xmlrpc_registry_free(registryP);

    printf("\n");

    xmlrpc_env_clean(&env);
}

