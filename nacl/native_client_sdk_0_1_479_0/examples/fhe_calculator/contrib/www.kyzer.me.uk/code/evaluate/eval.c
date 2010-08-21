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

  if (!vt || !put_var(vt, "e", &e) || !put_var(vt, "pi", &p))
    return EXIT_FAILURE;

  printf("evaluate.c example demo. please enter expressions\n");
  while (fgets(buffer, 512, stdin)) {
    switch (evaluate(buffer, &result, vt)) {
    case ERROR_SYNTAX:      printf("syntax error\n");       break;
    case ERROR_VARNOTFOUND: printf("variable not found\n"); break;
    case ERROR_NOMEM:       printf("not enough memory\n");  break;
    case ERROR_DIV0:        printf("division by zero\n");   break;
    case RESULT_OK: 
      if (result.type == T_INT) printf("result = %ld\n", result.ival);
      else printf("result = %g\n", result.rval);
      
    }
  }
  free_vartable(vt);
  return EXIT_SUCCESS;
}
