#include <unistd.h>
#include <pthread.h>
#include <string.h>
#include <errno.h>
#include <signal.h>

#include "xmlrpc_config.h"

#include "mallocvar.h"
#include "xmlrpc-c/string_int.h"

#include "xmlrpc-c/abyss.h"

#include "thread.h"



struct abyss_thread {
    pthread_t       thread;
    void *          userHandle;
    TThreadProc *   func;
    TThreadDoneFn * threadDone;
};

/* We used to have THREAD_STACK_SIZE = 16K, which was said to be the
   minimum stack size on Win32.  Scott Kolodzeski found in November
   2005 that this was insufficient for 64 bit Solaris -- we fail
   when creating the first thread.  So we changed to 128K.
*/
#define  THREAD_STACK_SIZE (128*1024L)


typedef void * (pthreadStartRoutine)(void *);



static pthreadStartRoutine pthreadStart;

static void *
pthreadStart(void * const arg) {

    struct abyss_thread * const threadP = arg;
    abyss_bool const executeTrue = true;

    pthread_cleanup_push(threadP->threadDone, threadP->userHandle);

    threadP->func(threadP->userHandle);

    pthread_cleanup_pop(executeTrue);

    return NULL;
}



void
ThreadCreate(TThread **      const threadPP,
             void *          const userHandle,
             TThreadProc   * const func,
             TThreadDoneFn * const threadDone,
             abyss_bool      const useSigchld ATTR_UNUSED,
             const char **   const errorP) {

    TThread * threadP;

    MALLOCVAR(threadP);
    if (threadP == NULL)
        xmlrpc_asprintf(errorP,
                        "Can't allocate memory for thread descriptor.");
    else {
        pthread_attr_t attr;
        int rc;

        pthread_attr_init(&attr);

        pthread_attr_setstacksize(&attr, THREAD_STACK_SIZE);
        
        threadP->userHandle = userHandle;
        threadP->func       = func;
        threadP->threadDone = threadDone;

        rc = pthread_create(&threadP->thread, &attr,
                            pthreadStart, threadP);
        if (rc == 0) {
            *errorP = NULL;
            *threadPP = threadP;
        } else
            xmlrpc_asprintf(
                errorP, "pthread_create() failed, errno = %d (%s)",
                errno, strerror(errno));
        
        pthread_attr_destroy(&attr);

        if (*errorP)
            free(threadP);
    }
}



abyss_bool
ThreadRun(TThread * const threadP ATTR_UNUSED) {
    return TRUE;    
}



abyss_bool
ThreadStop(TThread * const threadP ATTR_UNUSED) {
    return TRUE;
}



abyss_bool
ThreadKill(TThread * const threadP ATTR_UNUSED) {

    return (pthread_kill(threadP->thread, SIGTERM) == 0);
}



void
ThreadWaitAndRelease(TThread * const threadP) {

    void * threadReturn;

    pthread_join(threadP->thread, &threadReturn);

    free(threadP);
}



void
ThreadExit(int const retValue) {

    pthread_exit((void*)&retValue);

    /* Note that the above runs our cleanup routine (which we registered
       with pthread_cleanup_push() before exiting.
    */
}



void
ThreadRelease(TThread * const threadP) {

    pthread_detach(threadP->thread);

    free(threadP);
}



abyss_bool
ThreadForks(void) {

    return FALSE;
}



void
ThreadUpdateStatus(TThread * const threadP ATTR_UNUSED) {

    /* Threads keep their own statuses up to date, so there's nothing
       to do here.
    */
}



void
ThreadHandleSigchld(pid_t const pid ATTR_UNUSED) {

    /* Death of a child signals have nothing to do with pthreads */
}



/*********************************************************************
** Mutex
*********************************************************************/



abyss_bool
MutexCreate(TMutex * const mutexP) {

    return (pthread_mutex_init(mutexP, NULL) == 0);
}



abyss_bool
MutexLock(TMutex * const mutexP) {
    return (pthread_mutex_lock(mutexP) == 0);
}



abyss_bool
MutexUnlock(TMutex * const mutexP) {
    return (pthread_mutex_unlock(mutexP) == 0);
}



abyss_bool
MutexTryLock(TMutex * const mutexP) {
    return (pthread_mutex_trylock(mutexP) == 0);
}



void
MutexFree(TMutex * const mutexP) {
    pthread_mutex_destroy(mutexP);
}
