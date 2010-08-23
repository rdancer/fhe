#ifndef CASPRINTF_H_INCLUDED
#define CASPRINTF_H_INCLUDED

#include <stdarg.h>

#include "c_util.h"

extern const char * const strsol;

void
cvasprintf(const char ** const retvalP,
           const char *  const fmt,
           va_list             varargs);

void GNU_PRINTF_ATTR(2,3)
casprintf(const char ** const retvalP, const char * const fmt, ...);

void
strfree(const char * const string);

#endif
