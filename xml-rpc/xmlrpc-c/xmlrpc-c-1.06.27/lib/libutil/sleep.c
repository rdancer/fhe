#include "xmlrpc-c/sleep_int.h"

#ifdef WIN32
#include <process.h>
#else
#include <unistd.h>
#endif


void
xmlrpc_millisecond_sleep(unsigned int const milliseconds) {

#ifdef WIN32
    Sleep(milliseconds);
#else
    usleep(milliseconds * 1000);
#endif
}
