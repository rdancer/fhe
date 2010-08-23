/*=============================================================================
                                 xmlrpc-c/util.h
===============================================================================

  This is the interface to the libxmlrpc_util library, which contains
  utility routines that have nothing to do with XML-RPC.  The library
  exists because other Xmlrpc-c libraries use the utilities.

  By Bryan Henderson, San Jose, CA 05.09.21.

  Contributed to the public domain by its author.
=============================================================================*/

#ifndef XMLRPC_C_UTIL_H_INCLUDED
#define XMLRPC_C_UTIL_H_INCLUDED

#include <sys/types.h>

#ifdef __cplusplus
extern "C" {
#endif


/* GNU_PRINTF_ATTR lets the GNU compiler check printf-type
   calls to be sure the arguments match the format string, thus preventing
   runtime segmentation faults and incorrect messages.
*/
#ifdef __GNUC__
#define GNU_PRINTF_ATTR(a,b) __attribute__ ((format (printf, a, b)))
#else
#define GNU_PRINTF_ATTR(a,b)
#endif


/*=========================================================================
**  C struct size computations
**=======================================================================*/

/* Use XMLRPC_STRUCT_MEMBER_SIZE() to determine how big a structure is
   up to and including a specified member.  E.g. if you have
   struct mystruct {int red; int green; int blue};, then
   XMLRPC_STRUCT_MEMBER_SIZE(mystruct, green) is (8).
*/

#define _XMLRPC_STRUCT_MEMBER_OFFSET(TYPE, MBRNAME) \
  ((unsigned long)(char*)&((TYPE *)0)->MBRNAME)
#define _XMLRPC_STRUCT_MEMBER_SIZE(TYPE, MBRNAME) \
  sizeof(((TYPE *)0)->MBRNAME)
#define XMLRPC_STRUCTSIZE(TYPE, MBRNAME) \
  (_XMLRPC_STRUCT_MEMBER_OFFSET(TYPE, MBRNAME) + \
  _XMLRPC_STRUCT_MEMBER_SIZE(TYPE, MBRNAME))

/*=========================================================================
**  Assertions and Debugging
**=========================================================================
**  Note that an assertion is _not_ a directive to check a condition and
**  crash if it isn't true.  It is an assertion that the condition _is_
**  true.  This assertion helps people to read the code.  The program
**  may also check the assertion as it runs, and if it conflicts with reality,
**  recognize that the program is incorrect and abort it.  In practice,
**  it does this checking when the program was compiled without the NDEBUG
**  macro defined.
*/

#ifndef NDEBUG

#define XMLRPC_ASSERT(cond) \
    do \
        if (!(cond)) \
            xmlrpc_assertion_failed(__FILE__, __LINE__); \
    while (0)

#else
#define XMLRPC_ASSERT(cond) while (0) {}
#endif

void
xmlrpc_assertion_failed(const char * const fileName,
                        int          const lineNumber);

/* Validate a pointer. */
#define XMLRPC_ASSERT_PTR_OK(ptr) \
    XMLRPC_ASSERT((ptr) != NULL)

/* We only call this if something truly drastic happens. */
#define XMLRPC_FATAL_ERROR(msg) xmlrpc_fatal_error(__FILE__, __LINE__, (msg))

extern void xmlrpc_fatal_error (char* file, int line, char* msg);


/*=========================================================================
**  xmlrpc_env
**=========================================================================
**  XML-RPC represents runtime errors as <fault> elements. These contain
**  <faultCode> and <faultString> elements.
**
**  Since we need as much thread-safety as possible, we borrow an idea from
**  CORBA--we store exception information in an "environment" object.
**  You'll pass this to many different functions, and it will get filled
**  out appropriately.
**
**  For example:
**
**    xmlrpc_env env;
**
**    xmlrpc_env_init(&env);
**
**    xmlrpc_do_something(&env);
**    if (env.fault_occurred)
**        report_error_appropriately();
**
**    xmlrpc_env_clean(&env);
*/

#define XMLRPC_INTERNAL_ERROR               (-500)
#define XMLRPC_TYPE_ERROR                   (-501)
#define XMLRPC_INDEX_ERROR                  (-502)
#define XMLRPC_PARSE_ERROR                  (-503)
#define XMLRPC_NETWORK_ERROR                (-504)
#define XMLRPC_TIMEOUT_ERROR                (-505)
#define XMLRPC_NO_SUCH_METHOD_ERROR         (-506)
#define XMLRPC_REQUEST_REFUSED_ERROR        (-507)
#define XMLRPC_INTROSPECTION_DISABLED_ERROR (-508)
#define XMLRPC_LIMIT_EXCEEDED_ERROR         (-509)
#define XMLRPC_INVALID_UTF8_ERROR           (-510)

typedef struct _xmlrpc_env {
    int    fault_occurred;
    int    fault_code;
    char * fault_string;
} xmlrpc_env;

/* Initialize and destroy the contents of the provided xmlrpc_env object.
** These functions will never fail. */
void xmlrpc_env_init (xmlrpc_env* env);
void xmlrpc_env_clean (xmlrpc_env* env);

/* Fill out an xmlrpc_fault with the specified values, and set the
** fault_occurred flag. This function will make a private copy of 'string',
** so you retain responsibility for your copy. */
void 
xmlrpc_env_set_fault(xmlrpc_env * const env, 
                     int          const faultCode, 
                     const char * const faultDescription);

/* The same as the above, but using a printf-style format string. */
void 
xmlrpc_env_set_fault_formatted(xmlrpc_env * const envP, 
                               int          const code,
                               const char * const format, 
                               ...) GNU_PRINTF_ATTR(3,4);

/* This one infers XMLRPC_INTERNAL_ERROR and has a shorter name.
   So a call takes up less source code space.
*/
void
xmlrpc_faultf(xmlrpc_env * const envP,
              const char * const format,
              ...) GNU_PRINTF_ATTR(2,3);

/* A simple debugging assertion. */
#define XMLRPC_ASSERT_ENV_OK(envP) \
    XMLRPC_ASSERT((envP) != NULL && \
    (envP->fault_string == NULL) && \
    !(envP)->fault_occurred)

/* This version must *not* interpret 'str' as a format string, to avoid
** several evil attacks. */
#define XMLRPC_FAIL(env,code,str) \
    do { xmlrpc_env_set_fault((env),(code),(str)); goto cleanup; } while (0)

#define XMLRPC_FAIL1(env,code,str,arg1) \
    do { \
        xmlrpc_env_set_fault_formatted((env),(code),(str),(arg1)); \
        goto cleanup; \
    } while (0)

#define XMLRPC_FAIL2(env,code,str,arg1,arg2) \
    do { \
        xmlrpc_env_set_fault_formatted((env),(code),(str),(arg1),(arg2)); \
        goto cleanup; \
    } while (0)

#define XMLRPC_FAIL3(env,code,str,arg1,arg2,arg3) \
    do { \
        xmlrpc_env_set_fault_formatted((env),(code), \
                                       (str),(arg1),(arg2),(arg3)); \
        goto cleanup; \
    } while (0)

#define XMLRPC_FAIL_IF_NULL(ptr,env,code,str) \
    do { \
        if ((ptr) == NULL) \
            XMLRPC_FAIL((env),(code),(str)); \
    } while (0)

#define XMLRPC_FAIL_IF_FAULT(env) \
    do { if ((env)->fault_occurred) goto cleanup; } while (0)


/*=========================================================================
**  Resource Limits
**=========================================================================
**  To discourage denial-of-service attacks, we provide several adjustable
**  resource limits. These functions are *not* re-entrant.
*/

/* Limit IDs. There will be more of these as time goes on. */
#define XMLRPC_NESTING_LIMIT_ID   (0)
#define XMLRPC_XML_SIZE_LIMIT_ID  (1)
#define XMLRPC_LAST_LIMIT_ID      (XMLRPC_XML_SIZE_LIMIT_ID)

/* By default, deserialized data may be no more than 64 levels deep. */
#define XMLRPC_NESTING_LIMIT_DEFAULT  (64)

/* By default, XML data from the network may be no larger than 512K.
** Some client and server modules may fail to enforce this properly. */
#define XMLRPC_XML_SIZE_LIMIT_DEFAULT (512*1024)

/* Set a specific limit to the specified value. */
extern void xmlrpc_limit_set (int limit_id, size_t value);

/* Get the value of a specified limit. */
extern size_t xmlrpc_limit_get (int limit_id);


/*=========================================================================
**  xmlrpc_mem_block
**=========================================================================
**  A resizable chunk of memory. This is mostly used internally, but it is
**  also used by the public API in a few places.
**  The struct fields are private!
*/

typedef struct _xmlrpc_mem_block {
    size_t _size;
    size_t _allocated;
    void*  _block;
} xmlrpc_mem_block;

/* Allocate a new xmlrpc_mem_block. */
xmlrpc_mem_block* xmlrpc_mem_block_new (xmlrpc_env* env, size_t size);

/* Destroy an existing xmlrpc_mem_block, and everything it contains. */
void xmlrpc_mem_block_free (xmlrpc_mem_block* block);

/* Initialize the contents of the provided xmlrpc_mem_block. */
void xmlrpc_mem_block_init
    (xmlrpc_env* env, xmlrpc_mem_block* block, size_t size);

/* Deallocate the contents of the provided xmlrpc_mem_block, but not the
** block itself. */
void xmlrpc_mem_block_clean (xmlrpc_mem_block* block);

/* Get the size and contents of the xmlrpc_mem_block. */
size_t 
xmlrpc_mem_block_size(const xmlrpc_mem_block * const block);

void * 
xmlrpc_mem_block_contents(const xmlrpc_mem_block * const block);

/* Resize an xmlrpc_mem_block, preserving as much of the contents as
** possible. */
void xmlrpc_mem_block_resize
    (xmlrpc_env* env, xmlrpc_mem_block* block, size_t size);

/* Append data to an existing xmlrpc_mem_block. */
void xmlrpc_mem_block_append
    (xmlrpc_env* env, xmlrpc_mem_block* block, const void *data, size_t len);

#define XMLRPC_MEMBLOCK_NEW(type,env,size) \
    xmlrpc_mem_block_new((env), sizeof(type) * (size))
#define XMLRPC_MEMBLOCK_FREE(type,block) \
    xmlrpc_mem_block_free(block)
#define XMLRPC_MEMBLOCK_INIT(type,env,block,size) \
    xmlrpc_mem_block_init((env), (block), sizeof(type) * (size))
#define XMLRPC_MEMBLOCK_CLEAN(type,block) \
    xmlrpc_mem_block_clean(block)
#define XMLRPC_MEMBLOCK_SIZE(type,block) \
    (xmlrpc_mem_block_size(block) / sizeof(type))
#define XMLRPC_MEMBLOCK_CONTENTS(type,block) \
    ((type*) xmlrpc_mem_block_contents(block))
#define XMLRPC_MEMBLOCK_RESIZE(type,env,block,size) \
    xmlrpc_mem_block_resize(env, block, sizeof(type) * (size))
#define XMLRPC_MEMBLOCK_APPEND(type,env,block,data,size) \
    xmlrpc_mem_block_append(env, block, data, sizeof(type) * (size))

/* Here are some backward compatibility definitions.  These longer names
   used to be the only ones and typed memory blocks were considered
   special.
*/
#define XMLRPC_TYPED_MEM_BLOCK_NEW(type,env,size) \
    XMLRPC_MEMBLOCK_NEW(type,env,size)
#define XMLRPC_TYPED_MEM_BLOCK_FREE(type,block) \
    XMLRPC_MEMBLOCK_FREE(type,block)
#define XMLRPC_TYPED_MEM_BLOCK_INIT(type,env,block,size) \
    XMLRPC_MEMBLOCK_INIT(type,env,block,size)
#define XMLRPC_TYPED_MEM_BLOCK_CLEAN(type,block) \
    XMLRPC_MEMBLOCK_CLEAN(type,block)
#define XMLRPC_TYPED_MEM_BLOCK_SIZE(type,block) \
    XMLRPC_MEMBLOCK_SIZE(type,block)
#define XMLRPC_TYPED_MEM_BLOCK_CONTENTS(type,block) \
    XMLRPC_MEMBLOCK_CONTENTS(type,block)
#define XMLRPC_TYPED_MEM_BLOCK_RESIZE(type,env,block,size) \
    XMLRPC_MEMBLOCK_RESIZE(type,env,block,size)
#define XMLRPC_TYPED_MEM_BLOCK_APPEND(type,env,block,data,size) \
    XMLRPC_MEMBLOCK_APPEND(type,env,block,data,size)

#ifdef __cplusplus
}
#endif

#endif
