/* fhe_calculator.cc -- FHE Calculator demo (NaCl module) */

/*
 * Copyright © 2008 The Native Client Authors
 * Copyright © 2000-2002 Kyzer/CSG
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

#if defined(__native_client__)
#include <nacl/nacl_npapi.h>
#else
// Building a develop version for debugging.
#include "third_party/npapi/bindings/npapi.h"
#include "third_party/npapi/bindings/nphostapi.h"
#endif

// These are the method names as JavaScript sees them.
static const char* kEvaluateMethodId = "evaluate";
static const char* kFortyTwoMethodId = "fortytwo";

/*
 * Adapted from <http://www.kyzer.me.uk/code/evaluate/eval.c>
 * retrieved on 2010-08-21
 * Copyright © 2000-2002 Kyzer/CSG
 *
 * XXX Handle errors properly
 */

extern "C" {

#include <stdio.h>
#include <stdlib.h>
#include <math.h>
#include "evaluate.h"
} // extern "C"

// This will never need to be more than MAX(log10(LONG_MAX), log10(DOUBLE_MAX)
#define BUFFER_SIZE 2000

char * EvaluateLocally(char *formula) {
  struct vartable *vt = create_vartable();
  struct val p, e, result;
  char *s = reinterpret_cast<char*>(NPN_MemAlloc(BUFFER_SIZE));
  const char *error = NULL;

  e.type = T_REAL; e.rval = exp(1.0);
  p.type = T_REAL; p.rval = 4.0 * atan(1.0);

  if (!vt || !put_var(vt, (char *)"e", &e) || !put_var(vt, (char *)"pi", &p)) {
    error = "unspecified error";

  } else {
    switch (evaluate(formula, &result, vt)) {
      case ERROR_SYNTAX:      error = "syntax error";       break;
      case ERROR_VARNOTFOUND: error = "variable not found"; break;
      case ERROR_NOMEM:       error = "not enough memory";  break;
      case ERROR_DIV0:        error = "division by zero";   break;
      case RESULT_OK: 
        if (result.type == T_INT) snprintf(s, BUFFER_SIZE, "%ld", result.ival);
        else snprintf(s, BUFFER_SIZE, "%g", result.rval);
    }
  }
  free_vartable(vt);

  if (error == NULL) {
    return s;
  } else {
    strncpy(s, error, BUFFER_SIZE);
    return s;
  }
}


/** Evaluate the formula */
char * DoEvaluate(char *formula) {
//#define BUFFER_SIZE 2000
//    char *s = reinterpret_cast<char*>(NPN_MemAlloc(BUFFER_SIZE));
//
//    snprintf (s, BUFFER_SIZE, "%lld", atoll(formula) + 2);
//
//    return s;
    return EvaluateLocally(formula);
}

/** Increment number by one */
char * AddOne(char *number) {
#define BUFFER_SIZE 2000
    char *s = reinterpret_cast<char*>(NPN_MemAlloc(BUFFER_SIZE));

    snprintf (s, BUFFER_SIZE, "%lld", atoll(number) + 1);

    return s;
}

// This is the module's function that does the work to set the value of the
// result variable to '43'.  The Invoke() function that called this function
// then returns the result back to the browser as a JavaScript value.
static bool FortyTwo(NPVariant *result) {
  if (result) {
    INT32_TO_NPVARIANT(43, *result);
  }
  return true;
}

// This function creates a string in the browser's memory pool and then returns
// a variable containing a pointer to that string.  The variable is later
// returned back to the browser by the Invoke() function that called this.
static bool Evaluate(const NPVariant *args,
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

	char *s = DoEvaluate(formula);

	STRINGN_TO_NPVARIANT(s, strlen(s), *result);
    } else {
	// XXX Signal error
	STRINGN_TO_NPVARIANT("", 0, *result);
    }
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
  if (!strcmp((const char *)name, kEvaluateMethodId)) {
    is_method = true;
  } else if (!strcmp((const char*)name, kFortyTwoMethodId)) {
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
  if (!strcmp((const char *)name, kEvaluateMethodId)) {
    rval = Evaluate(args, arg_count, result);
  } else if (!strcmp((const char*)name, kFortyTwoMethodId)) {
    rval = FortyTwo(result);
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
