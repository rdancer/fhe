#include <assert.h>
#include <sys/types.h>
#include <string.h>
#include <stdio.h>

#include "xmlrpc-c/util_int.h"
#include "xmlrpc-c/string_int.h"
#include "xmlrpc-c/abyss.h"
#include "server.h"
#include "http.h"
#include "conn.h"

#include "session.h"



abyss_bool
SessionRefillBuffer(TSession * const sessionP) {
/*----------------------------------------------------------------------------
   Get the next chunk of HTTP request body from the connection into
   the buffer.

   I.e. read data from the socket.
-----------------------------------------------------------------------------*/
    struct _TServer * const srvP = sessionP->conn->server->srvP;
    abyss_bool failed;

    failed = FALSE;  /* initial value */
            
    /* Reset our read buffer & flush data from previous reads. */
    ConnReadInit(sessionP->conn);

    if (sessionP->continueRequired)
        failed = !HTTPWriteContinue(sessionP);

    if (!failed) {
        sessionP->continueRequired = FALSE;

        /* Read more network data into our buffer.  If we encounter a
           timeout, exit immediately.  We're very forgiving about the
           timeout here.  We allow a full timeout per network read, which
           would allow somebody to keep a connection alive nearly
           indefinitely.  But it's hard to do anything intelligent here
           without very complicated code.
        */
        failed = !ConnRead(sessionP->conn, srvP->timeout);	
    }
    return !failed;
}



size_t
SessionReadDataAvail(TSession * const sessionP) {

    return sessionP->conn->buffersize - sessionP->conn->bufferpos;

}



void
SessionGetReadData(TSession *    const sessionP, 
                   size_t        const max, 
                   const char ** const outStartP, 
                   size_t *      const outLenP) {
/*----------------------------------------------------------------------------
   Extract some HTTP request body which the server has read and
   buffered for the session.  Don't get or wait for any data that has
   not yet arrived.  Do not return more than 'max'.

   We return a pointer to the first byte as *outStartP, and the length in
   bytes as *outLenP.  The memory pointed to belongs to the session.
-----------------------------------------------------------------------------*/
    uint32_t const bufferPos = sessionP->conn->bufferpos;

    *outStartP = &sessionP->conn->buffer[bufferPos];

    assert(bufferPos <= sessionP->conn->buffersize);

    *outLenP = MIN(max, sessionP->conn->buffersize - bufferPos);

    /* move pointer past the bytes we are returning */
    sessionP->conn->bufferpos += *outLenP;

    assert(sessionP->conn->bufferpos <= sessionP->conn->buffersize);
}



void
SessionGetRequestInfo(TSession *            const sessionP,
                      const TRequestInfo ** const requestInfoPP) {
    
    *requestInfoPP = &sessionP->request_info;
}



abyss_bool
SessionLog(TSession * const sessionP) {

    abyss_bool retval;

    if (!sessionP->validRequest)
        retval = FALSE;
    else {
        const char * const user = sessionP->request_info.user;

        const char * logline;
        char date[30];

        DateToLogString(&sessionP->date, date);

        xmlrpc_asprintf(&logline, "%d.%d.%d.%d - %s - [%s] \"%s\" %d %d",
                        IPB1(sessionP->conn->peerip),
                        IPB2(sessionP->conn->peerip),
                        IPB3(sessionP->conn->peerip),
                        IPB4(sessionP->conn->peerip),
                        user ? user : "",
                        date, 
                        sessionP->request_info.requestline,
                        sessionP->status,
                        sessionP->conn->outbytes
            );
        if (logline) {
            LogWrite(sessionP->conn->server, logline);

            xmlrpc_strfree(logline);
        }
        retval = TRUE;
    }
    return retval;
}



