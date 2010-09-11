/* fhe_calculator.cc -- FHE Calculator demo (NaCl module) */

/*
 * Copyright © 2008 The Native Client Authors
 * Copyright © 2010 Jan Minář <rdancer@rdancer.org>
 *
 * This program is free software; you can redistribute it and/or modify
 * it under the terms of the GNU General Public License version 2 (two),
 * as published by the Free Software Foundation.
 *
 * This program is distributed in the hope that it will be useful,
 * but WITHOUT ANY WARRANTY; without even the implied warranty of
 * MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
 * GNU General Public License for more details.
 *
 * You should have received a copy of the GNU General Public License along
 * with this program; if not, write to the Free Software Foundation, Inc.,
 * 51 Franklin Street, Fifth Floor, Boston, MA 02110-1301 USA.
 */


#include <stdbool.h>
#include <stdio.h>
#include <stdlib.h>
#include <string.h>
#include <ctype.h>

#if defined(__native_client__)
#include <nacl/nacl_npapi.h>
#else
// Building a develop version for debugging.
#include "third_party/npapi/bindings/npapi.h"
#include "third_party/npapi/bindings/nphostapi.h"
#endif

#define FHE_ERROR -1	// Integer error return value

// These are the method names as JavaScript sees them.
static const char* kPreprocessMethodId = "preprocess";
static const char* kGetNextMethodId = "getNext";

// how many array members are remaining
int32_t remaining = 0;
char *processed_formula = NULL;
char **encrypted_variables = NULL;

// This will never need to be more than MAX(log10(LONG_MAX), log10(DOUBLE_MAX)
#define BUFFER_SIZE 2000

/**
 * Preprocess the formula and store the tokenized formula in an array.
 *
 * @return number of members in the array
 */
int32_t DoPreprocess(char *formula) {
    size_t ptr = 0, size = strlen(formula) + 1, length, variables_count = 0;
    char *s = (char *)malloc(size);
    encrypted_variables = reinterpret_cast<char**>(NPN_MemAlloc(sizeof (char *)
		* strlen(formula)));  // maximum is one variable per char

    for (size_t i = 0; i < strlen(formula); i++) {
	if (isdigit(formula[i])) {
	    /* Substitute the number with a variable in the formula */
	    length = snprintf(&(s[ptr]), 0, "var%u", variables_count);
	    if ((s = (char*)realloc(s, size += length)) == NULL) {
		return FHE_ERROR;
	    }
	    sprintf (&(s[ptr]), "var%u", variables_count);
	    ptr += length;

	    /* todo Get the encrypted number in the variables array */
	    encrypted_variables[variables_count] =
		reinterpret_cast<char*>(NPN_MemAlloc(BUFFER_SIZE));
	    snprintf(encrypted_variables[variables_count], BUFFER_SIZE, "%d",
		atoi(&(formula[i])));
	    variables_count++;
	    // advance the pointer past this number
	    while (isdigit(formula[i + 1])) {
		i++;
	    }
	} else {
	    s[ptr++] = formula[i];
	}
    }
    s[ptr] = '\0';

    /* Copy into the global variable */
    // Can we call NPN_MemFree(NULL)?
    if (processed_formula != NULL) {
	NPN_MemFree(processed_formula);
    }
    processed_formula = reinterpret_cast<char*>(NPN_MemAlloc(strlen(s) + 1));
    strcpy(processed_formula, s);

    return remaining = variables_count + 1;
}


/**
 * Preprocess the formula, replacing numbers with variables.  The processed
 * formula and the variable values are thenceforth available via the GetNext()
 * function.
 *
 * @return number of variables in the formula
 */
// This function creates a string in the browser's memory pool and then returns
// a variable containing a pointer to that string.  The variable is later
// returned back to the browser by the Invoke() function that called this.
static bool Preprocess(const NPVariant *args,
                       uint32_t arg_count,
		       NPVariant *result) {
  if (result) {
    if (arg_count > 0) {
	NPString nps = NPVARIANT_TO_STRING(args[0]);

	// Note: |formula| will be freed later on by the browser, so it needs to
	// be allocated here with NPN_MemAlloc().
	char *formula = reinterpret_cast<char*>(NPN_MemAlloc(nps.UTF8Length + 1));
	memcpy(formula, nps.UTF8Characters, nps.UTF8Length);
	formula[nps.UTF8Length] = '\0';

	int32_t retval = DoPreprocess(formula);

	INT32_TO_NPVARIANT(retval, *result);
    } else {
	INT32_TO_NPVARIANT(FHE_ERROR, *result);
    }
  }
  return true;
}

/**
 * Return the next string, which is either the preprocessed formula, or, if
 * that has been returned, the next encrypted variables in order.
 *
 * @return next string (preprocessed formula or encrypted variable)
 */
static bool GetNext(const NPVariant *args,
                       uint32_t arg_count,
		       NPVariant *result) {
  if (result) {
    char *s = reinterpret_cast<char*>(NPN_MemAlloc(BUFFER_SIZE));

    if (processed_formula != NULL) {
	snprintf (s, BUFFER_SIZE, "%s", processed_formula);
	processed_formula = NULL;
    } else if (--remaining > 0) {
	snprintf (s, BUFFER_SIZE, "%s", *(encrypted_variables++));
    } else {
	snprintf (s, BUFFER_SIZE, "Error: no more variables");
    }

    STRINGN_TO_NPVARIANT(s, strlen(s), *result);
  }
  return true;
}

// Creates the plugin-side instance of NPObject.
// Called by NPN_CreateObject, declared in npruntime.h
// Documentation URL: https://developer.mozilla.org/en/NPClass
static NPObject* Allocate(NPP npp, NPClass* npclass) {
  return new NPObject;
}

// Cleans up the plugin-side instance of an NPObject.
// Called by NPN_ReleaseObject, declared in npruntime.h
// Documentation URL: https://developer.mozilla.org/en/NPClass
static void Deallocate(NPObject* object) {
  delete object;
}

// Returns |true| if |method_name| is a recognized method.
// Called by NPN_HasMethod, declared in npruntime.h
// Documentation URL: https://developer.mozilla.org/en/NPClass
static bool HasMethod(NPObject* obj, NPIdentifier method_name) {
  char *name = NPN_UTF8FromIdentifier(method_name);
  bool is_method = false;
  if (!strcmp((const char*)name, kPreprocessMethodId)) {
    is_method = true;
  } else if (!strcmp((const char*)name, kGetNextMethodId)) {
    is_method = true;
  }
  NPN_MemFree(name);
  return is_method;
}

// Called by the browser to invoke the default method on an NPObject.
// Returns null.
// Apparently the plugin won't load properly if we simply
// tell the browser we don't have this method.
// Called by NPN_InvokeDefault, declared in npruntime.h
// Documentation URL: https://developer.mozilla.org/en/NPClass
static bool InvokeDefault(NPObject *obj, const NPVariant *args,
                          uint32_t argCount, NPVariant *result) {
  if (result) {
    NULL_TO_NPVARIANT(*result);
  }
  return true;
}

// Called by the browser to invoke a function object whose name
// is |method_name|.
// Called by NPN_Invoke, declared in npruntime.h
// Documentation URL: https://developer.mozilla.org/en/NPClass
static bool Invoke(NPObject* obj,
                   NPIdentifier method_name,
                   const NPVariant *args,
                   uint32_t arg_count,
                   NPVariant *result) {
  NULL_TO_NPVARIANT(*result);
  char *name = NPN_UTF8FromIdentifier(method_name);
  if (name == NULL)
    return false;
  bool rval = false;

  // Map the method name to a function call.  |result| is filled in by the
  // called function, then gets returned to the browser when Invoke() returns.
  if (!strcmp((const char*)name, kPreprocessMethodId)) {
    rval = Preprocess(args, arg_count, result);
  } else if (!strcmp((const char*)name, kGetNextMethodId)) {
    rval = GetNext(args, arg_count, result);
  }
  // Since name was allocated above by NPN_UTF8FromIdentifier,
  // it needs to be freed here.
  NPN_MemFree(name);
  return rval;
}

// Represents a class's interface, so that the browser knows what functions it
// can call on this plugin object.  The browser can use the methods in this
// class to discover the rest of the plugin's interface.
// Documentation URL: https://developer.mozilla.org/en/NPClass
static NPClass kFheCalculatorClass = {
  NP_CLASS_STRUCT_VERSION,
  Allocate,
  Deallocate,
  NULL,  // Invalidate is not implemented
  HasMethod,
  Invoke,
  InvokeDefault,
  NULL,  // HasProperty is not implemented
  NULL,  // GetProperty is not implemented
  NULL,  // SetProperty is not implemented
};

// Called by NPP_GetScriptableInstance to get the scripting interface for
// this plugin.
NPClass *GetNPSimpleClass() {
  return &kFheCalculatorClass;
}
