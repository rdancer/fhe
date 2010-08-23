#ifndef GIRSTRING_H_INCLUDED
#define GIRSTRING_H_INCLUDED

#include <string.h>
#include "bool.h"

char
stripeq(const char * const comparand, const char * const comparator);

char
stripcaseeq(const char * const comparand, const char * const comparator);

void
stripcpy(char * dest, const char * source);

void
stripcasecpy(char * dest, const char * source);

char *
stripdup(const char * const input);

char *
strcasedup(const char input[]);

static __inline__ bool
streq(const char * const comparator,
      const char * const comparand) {

    return (strcmp(comparand, comparator) == 0);
}

static __inline__ const char *
sdup(const char * const input) {
    return (const char *) strdup(input);
}

/* Copy string pointed by B to array A with size checking.  */
#define SSTRCPY(A,B) \
	(strncpy((A), (B), sizeof(A)), *((A)+sizeof(A)-1) = '\0')
#define SSTRCMP(A,B) \
	(strncmp((A), (B), sizeof(A)))

/* Concatenate string B onto string in array A with size checking */
#define STRSCAT(A,B) \
    (strncat((A), (B), sizeof(A)-strlen(A)), *((A)+sizeof(A)-1) = '\0')

#endif
