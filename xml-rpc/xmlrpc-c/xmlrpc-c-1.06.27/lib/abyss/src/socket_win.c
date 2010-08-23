/*=============================================================================
                                 socket_win.c
===============================================================================
  This is the implementation of TSocket for a Windows Winsock socket.
=============================================================================*/

#include "xmlrpc_config.h"

#include <stdlib.h>
#include <stdio.h>
#include <string.h>
#include <assert.h>
#include <winsock.h>

#include "xmlrpc-c/util_int.h"
#include "mallocvar.h"
#include "trace.h"

#include "socket.h"


struct socketWin {
/*----------------------------------------------------------------------------
   The properties/state of a TSocket unique to a Unix TSocket.
-----------------------------------------------------------------------------*/
    SOCKET fd;
    abyss_bool userSuppliedWinsock;
        /* 'socket' was supplied by the user; it belongs to him */
};



void
SocketWinInit(abyss_bool * const succeededP) {

    WORD wVersionRequested;
    WSADATA wsaData;
    int err;

    wVersionRequested = MAKEWORD(2, 0);

    err = WSAStartup(wVersionRequested, &wsaData);
    *succeededP = (err == 0);
}



void
SocketWinTerm(void) {

    WSACleanup();
}



static SocketDestroyImpl            socketDestroy;
static SocketWriteImpl              socketWrite;
static SocketReadImpl               socketRead;
static SocketConnectImpl            socketConnect;
static SocketBindImpl               socketBind;
static SocketListenImpl             socketListen;
static SocketAcceptImpl             socketAccept;
static SocketErrorImpl              socketError;
static SocketWaitImpl               socketWait;
static SocketAvailableReadBytesImpl socketAvailableReadBytes;
static SocketGetPeerNameImpl        socketGetPeerName;


static struct TSocketVtbl const vtbl = {
    &socketDestroy,
    &socketWrite,
    &socketRead,
    &socketConnect,
    &socketBind,
    &socketListen,
    &socketAccept,
    &socketError,
    &socketWait,
    &socketAvailableReadBytes,
    &socketGetPeerName
};



void
SocketWinCreate(TSocket ** const socketPP) {

    struct socketWin * socketWinP;

    MALLOCVAR(socketWinP);

    if (socketWinP) {
        SOCKET rc;
        rc = socket(AF_INET, SOCK_STREAM, 0);
        if (rc < 0)
            *socketPP = NULL;
        else {
            socketWinP->fd = rc;
            socketWinP->userSuppliedWinsock = FALSE;

            {
                int32_t n = 1;
                int rc;
                rc = setsockopt(socketWinP->fd, SOL_SOCKET, SO_REUSEADDR,
                                (char*)&n, sizeof(n));
                if (rc < 0)
                    *socketPP = NULL;
                else
                    SocketCreate(&vtbl, socketWinP, socketPP);
            }
            if (!*socketPP)
                closesocket(socketWinP->fd);
        }
        if (!*socketPP)
            free(socketWinP);
    } else
        *socketPP = NULL;
}



void
SocketWinCreateWinsock(SOCKET     const winsock,
                       TSocket ** const socketPP) {

    struct socketWin * socketWinP;

    MALLOCVAR(socketWinP);

    if (socketWinP) {
        socketWinP->fd = winsock;
        socketWinP->userSuppliedWinsock = TRUE;

        SocketCreate(&vtbl, socketWinP, socketPP);

        if (!*socketPP)
            free(socketWinP);
    } else
        *socketPP = NULL;
}



void
socketDestroy(TSocket * const socketP) {

    struct socketWin * const socketWinP = socketP->implP;

    if (!socketWinP->userSuppliedWinsock)
        closesocket(socketWinP->fd);

    free(socketWinP);
}



void
socketWrite(TSocket *             const socketP,
            const unsigned char * const buffer,
            uint32_t              const len,
            abyss_bool *          const failedP) {

    struct socketWin * const socketWinP = socketP->implP;

    size_t bytesLeft;
    abyss_bool error;

    assert(sizeof(size_t) >= sizeof(len));

    for (bytesLeft = len, error = FALSE;
         bytesLeft > 0 && !error;
        ) {
        size_t const maxSend = (size_t)(-1) >> 1;

        int rc;

        rc = send(socketWinP->fd, &buffer[len-bytesLeft],
                  MIN(maxSend, bytesLeft), 0);

        if (rc <= 0)
            /* 0 means connection closed; < 0 means severe error */
            error = TRUE;
        else
            bytesLeft -= rc;
    }
    *failedP = error;
}



uint32_t
socketRead(TSocket * const socketP,
           char *    const buffer,
           uint32_t  const len) {

    struct socketWin * const socketWinP = socketP->implP;

    int rc;
    rc = recv(socketWinP->fd, buffer, len, 0);
    return rc;
}



abyss_bool
socketConnect(TSocket * const socketP,
              TIPAddr * const addrP,
              uint16_t  const portNumber) {

    struct socketWin * const socketWinP = socketP->implP;

    struct sockaddr_in name;
    int rc;

    name.sin_family = AF_INET;
    name.sin_port = htons(portNumber);
    name.sin_addr = *addrP;

    rc = connect(socketWinP->fd, (struct sockaddr *)&name, sizeof(name));

    return rc != -1;
}



abyss_bool
socketBind(TSocket * const socketP,
           TIPAddr * const addrP,
           uint16_t  const portNumber) {

    struct socketWin * const socketWinP = socketP->implP;

    struct sockaddr_in name;
    int rc;

    name.sin_family = AF_INET;
    name.sin_port   = htons(portNumber);
    if (addrP)
        name.sin_addr = *addrP;
    else
        name.sin_addr.s_addr = INADDR_ANY;

    rc = bind(socketWinP->fd, (struct sockaddr *)&name, sizeof(name));

    return (rc != -1);
}



abyss_bool
socketListen(TSocket * const socketP,
             uint32_t  const backlog) {

    struct socketWin * const socketWinP = socketP->implP;

    int32_t const minus1 = -1;

    int rc;

    /* Disable the Nagle algorithm to make persistant connections faster */

    setsockopt(socketWinP->fd, IPPROTO_TCP,TCP_NODELAY,
               (const char *)&minus1, sizeof(minus1));

    rc = listen(socketWinP->fd, backlog);

    return (rc != -1);
}



static void
socketAccept(TSocket *    const listenSocketP,
             abyss_bool * const connectedP,
             abyss_bool * const failedP,
             TSocket **   const acceptedSocketPP,
             TIPAddr *    const ipAddrP) {
/*----------------------------------------------------------------------------
   Accept a connection on the listening socket 'listenSocketP'.  Return as
   *acceptedSocketPP the socket for the accepted connection.

   If no connection is waiting on 'listenSocketP', wait until one is.

   If we receive a signal while waiting, return immediately.

   Return *connectedP true iff we accepted a connection.  Return
   *failedP true iff we were unable to accept a connection for some
   reason other than that we were interrupted.  Return both false if
   our wait for a connection was interrupted by a signal.
-----------------------------------------------------------------------------*/
    struct socketWin * const listenSocketWinP = listenSocketP->implP;

    abyss_bool connected, failed, interrupted;

    connected   = FALSE;
    failed      = FALSE;
    interrupted = FALSE;

    while (!connected && !failed && !interrupted) {
        struct sockaddr_in sa;
        socklen_t size = sizeof(sa);
        int rc;
        rc = accept(listenSocketWinP->fd, (struct sockaddr *)&sa, &size);
        if (rc >= 0) {
            SOCKET const acceptedWinsock = rc;
            struct socketWin * acceptedSocketWinP;

            MALLOCVAR(acceptedSocketWinP);

            if (acceptedSocketWinP) {
                acceptedSocketWinP->fd = acceptedWinsock;
                acceptedSocketWinP->userSuppliedWinsock = FALSE;

                SocketCreate(&vtbl, acceptedSocketWinP, acceptedSocketPP);
                if (!*acceptedSocketPP)
                    failed = TRUE;
                else {
                    connected = TRUE;
                    *ipAddrP = sa.sin_addr;
                }
                if (failed)
                    free(acceptedSocketWinP);
            } else
                failed = TRUE;
            if (failed)
                closesocket(acceptedWinsock);
        } else if (socketError(NULL) == WSAEINTR)
            interrupted = TRUE;
        else
            failed = TRUE;
    }
    *failedP    = failed;
    *connectedP = connected;
}



static uint32_t
socketWait(TSocket *  const socketP,
           abyss_bool const rd,
           abyss_bool const wr,
           uint32_t   const timems) {

    struct socketWin * const socketWinP = socketP->implP;

    fd_set rfds, wfds;
    TIMEVAL tv;

    FD_ZERO(&rfds);
    FD_ZERO(&wfds);

    if (rd)
        FD_SET(socketWinP->fd, &rfds);

    if (wr)
        FD_SET(socketWinP->fd, &wfds);

    tv.tv_sec  = timems / 1000;
    tv.tv_usec = timems % 1000;

    for (;;) {
        int rc;

        rc = select(socketWinP->fd + 1, &rfds, &wfds, NULL,
                    (timems == TIME_INFINITE ? NULL : &tv));

        switch(rc) {
        case 0: /* time out */
            return 0;

        case -1:  /* socket error */
            if (socketError(NULL) == WSAEINTR)
                break;

            return 0;

        default:
            if (FD_ISSET(socketWinP->fd, &rfds))
                return 1;
            if (FD_ISSET(socketWinP->fd, &wfds))
                return 2;
            return 0;
        }
    }
}



static uint32_t
socketAvailableReadBytes(TSocket * const socketP) {

    struct socketWin * const socketWinP = socketP->implP;

    uint32_t x;
    int rc;

    rc = ioctlsocket(socketWinP->fd, FIONREAD, &x);

    return rc == 0 ? x : 0;
}



static void
socketGetPeerName(TSocket *    const socketP,
                  TIPAddr *    const ipAddrP,
                  uint16_t *   const portNumberP,
                  abyss_bool * const successP) {

    struct socketWin * const socketWinP = socketP->implP;

    socklen_t addrlen;
    int rc;
    struct sockaddr sockAddr;

    addrlen = sizeof(sockAddr);

    rc = getpeername(socketWinP->fd, &sockAddr, &addrlen);

    if (rc < 0) {
        TraceMsg("getpeername() failed.  errno=%d (%s)",
                 errno, strerror(errno));
        *successP = FALSE;
    } else {
        if (addrlen != sizeof(sockAddr)) {
            TraceMsg("getpeername() returned a socket address of the wrong "
                     "size: %u.  Expected %u", addrlen, sizeof(sockAddr));
            *successP = FALSE;
        } else {
            if (sockAddr.sa_family != AF_INET) {
                TraceMsg("Socket does not use the Inet (IP) address "
                         "family.  Instead it uses family %d",
                         sockAddr.sa_family);
                *successP = FALSE;
            } else {
                struct sockaddr_in * const sockAddrInP = (struct sockaddr_in *)
                    &sockAddr;

                *ipAddrP     = sockAddrInP->sin_addr;
                *portNumberP = sockAddrInP->sin_port;

                *successP = TRUE;
            }
        }
    }
}



static uint32_t
socketError(TSocket * const socketP) {
    return (uint32_t)WSAGetLastError();
}



