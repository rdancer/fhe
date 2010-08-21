/* eval.c -- algebraic strings evaluation demo */

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

extern "C" {

#include <stdio.h>
#include <stdlib.h>
#include <math.h>
#include "evaluate.h"

int main(int argc, char **argv) {
  struct vartable *vt = create_vartable();
  struct val p, e, result;
  char buffer[512];

  e.type = T_REAL; e.rval = exp(1.0);
  p.type = T_REAL; p.rval = 4.0 * atan(1.0);

  if (!vt || !put_var(vt, (char *)"e", &e) || !put_var(vt, (char *)"pi", &p))
    return EXIT_FAILURE;

  //printf("evaluate.c example demo. please enter expressions\n");
  while (fgets(buffer, 512, stdin)) {
    switch (evaluate(buffer, &result, vt)) {
    case ERROR_SYNTAX:      printf("syntax error\n");       break;
    case ERROR_VARNOTFOUND: printf("variable not found\n"); break;
    case ERROR_NOMEM:       printf("not enough memory\n");  break;
    case ERROR_DIV0:        printf("division by zero\n");   break;
    case RESULT_OK: 
      if (result.type == T_INT) printf("%ld\n", result.ival);
      else printf("%g\n", result.rval);
      
    }
  }
  free_vartable(vt);
  return EXIT_SUCCESS;
}
} // extern "C"
