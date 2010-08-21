/* evaluate.h -- evaluate algebraic strings (header file) */

/*
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

#include <stddef.h>
#include <stdlib.h>

/* private memory header for tracked memory allocation */
struct memh {
  struct memh *next;
  void *ptr;
};

/* creates a new memory header for allocating memory */
struct memh *create_mem();

/* allocates memory using a particular header */
void *mem_alloc(struct memh *mh, size_t len);

/* frees all memory for a particular header */
void free_mem(struct memh *mh);


#define T_INT    0
#define T_REAL   1

/* value */
struct val {
  long   ival; /* if type = T_INT, this is the result */
  double rval; /* if type = T_REAL, this is the result */
  char   type; /* either T_INT or T_REAL */
};

/* variable */
struct var {
  struct var *next; /* next variable in table or NULL */
  struct val val;   /* value of variable */
  char   *name;     /* name of variable */
};

/* variable table */
struct vartable {
  struct var *first; /* first entry in variable table */
  struct memh *mh;
};

/* creates a new variable table (NULL if no memory) */
extern struct vartable *create_vartable();

/* frees a variable table */
void free_vartable(struct vartable *vt);

/* gets a variable from a variable table (NULL if not found) */
struct var *get_var(struct vartable *vt, const char *name);

/* puts a variable into a variable table (NULL if no memory) */
struct var *put_var(struct vartable *vt, const char *name, struct val *value);


/* THE FUNCTION YOU WANT TO CALL */

/* given a string to evaluate (not NULL), a result to put the answer in
 * (not NULL) and optionally your own variable table (NULL for 'internal
 * only' vartable), will return an error code (and result, etc)
 */
int evaluate(char *eval, struct val *result, struct vartable *variables);

/* errors */
#define RESULT_OK               0       /* all OK                       */
#define ERROR_SYNTAX            2       /* invalid expression           */
#define ERROR_VARNOTFOUND       3       /* variable not found           */
#define ERROR_NOMEM             8       /* not enough memory available  */
#define ERROR_DIV0              9       /* division by zero             */
