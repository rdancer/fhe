#include <ctype.h>
#include <string.h>
#include <stdio.h>
#include <time.h>
#include <stdlib.h>


#include <inttypes.h>
#include "date.h"

/*********************************************************************
** Date
*********************************************************************/

static char *_DateDay[7]=
{
    "Sun","Mon","Tue","Wed","Thu","Fri","Sat"
};

static char *_DateMonth[12]=
{
    "Jan","Feb","Mar","Apr","May","Jun","Jul","Aug","Sep","Oct","Nov","Dec"
};

static int32_t _DateTimeBias=0;
static char _DateTimeBiasStr[6]="";

abyss_bool DateToString(TDate *tm,char *s)
{
    if (mktime(tm)==(time_t)(-1))
    {
        *s='\0';
        return FALSE;
    };

    sprintf(s,"%s, %02d %s %04d %02d:%02d:%02d GMT",_DateDay[tm->tm_wday],tm->tm_mday,
                _DateMonth[tm->tm_mon],tm->tm_year+1900,tm->tm_hour,tm->tm_min,tm->tm_sec);

    return TRUE;
}



abyss_bool
DateToLogString(TDate * const tmP,
                char *  const s) {
    time_t t;

    t = mktime(tmP);
    if (t != (time_t)(-1)) {
        TDate d;
        abyss_bool success;
        success = DateFromLocal(&d, t);
        if (success) {
            sprintf(s, "%02d/%s/%04d:%02d:%02d:%02d %s",
                    d.tm_mday, _DateMonth[d.tm_mon],
                    d.tm_year+1900, d.tm_hour, d.tm_min, d.tm_sec,
                    _DateTimeBiasStr);
            return TRUE;
        }
    }
    *s = '\0';
    return FALSE;
}



abyss_bool
DateDecode(const char *  const dateString,
           TDate *       const tmP) {

    int rc;
    const char * s;
    uint32_t n;

    s = &dateString[0];

    /* Ignore spaces, day name and spaces */
    while ((*s==' ') || (*s=='\t'))
        ++s;

    while ((*s!=' ') && (*s!='\t'))
        ++s;

    while ((*s==' ') || (*s=='\t'))
        ++s;

    /* try to recognize the date format */
    rc = sscanf(s, "%*s %d %d:%d:%d %d%*s", &tmP->tm_mday, &tmP->tm_hour,
                &tmP->tm_min, &tmP->tm_sec, &tmP->tm_year);
    if (rc != 5) {
        int rc;
        rc = sscanf(s, "%d %n%*s %d %d:%d:%d GMT%*s",
                    &tmP->tm_mday,&n,&tmP->tm_year,
                    &tmP->tm_hour, &tmP->tm_min, &tmP->tm_sec);
        if (rc != 5) {
            int rc;
            rc = sscanf(s, "%d-%n%*[A-Za-z]-%d %d:%d:%d GMT%*s",
                        &tmP->tm_mday, &n, &tmP->tm_year,
                        &tmP->tm_hour, &tmP->tm_min, &tmP->tm_sec);
            if (rc != 5)
                return FALSE;
        }
    }    
    /* s points now to the month string */
    s += n;
    for (n = 0; n < 12; ++n) {
        char * p;

        p =_DateMonth[n];

        if (tolower(*p++) == tolower(*s))
            if (*p++ == tolower(s[1]))
                if (*p == tolower(s[2]))
                    break;
    }

    if (n == 12)
        return FALSE;

    tmP->tm_mon = n;

    /* finish the work */
    if (tmP->tm_year > 1900)
        tmP->tm_year -= 1900;
    else {
        if (tmP->tm_year < 70)
            tmP->tm_year += 100;
    }
    tmP->tm_isdst = 0;

    return (mktime(tmP) != (time_t)(-1));
}



int32_t DateCompare(TDate *d1,TDate *d2)
{
    int32_t x;

    if ((x=d1->tm_year-d2->tm_year)==0)
        if ((x=d1->tm_mon-d2->tm_mon)==0)
            if ((x=d1->tm_mday-d2->tm_mday)==0)
                if ((x=d1->tm_hour-d2->tm_hour)==0)
                    if ((x=d1->tm_min-d2->tm_min)==0)
                        x=d1->tm_sec-d2->tm_sec;

    return x;
}



abyss_bool
DateFromGMT(TDate *d,time_t t) {
    TDate *dx;

    dx=gmtime(&t);
    if (dx) {
        *d=*dx;
        return TRUE;
    };

    return FALSE;
}

abyss_bool DateFromLocal(TDate *d,time_t t)
{
    return DateFromGMT(d,t+_DateTimeBias*2);
}



abyss_bool
DateInit() {
    time_t t;
    TDate gmt,local,*d;

    time(&t);
    if (DateFromGMT(&gmt,t)) {
        d=localtime(&t);
        if (d) {
            local=*d;
            _DateTimeBias =
                (local.tm_sec-gmt.tm_sec)+(local.tm_min-gmt.tm_min)*60
                +(local.tm_hour-gmt.tm_hour)*3600;
            sprintf(_DateTimeBiasStr, "%+03d%02d",
                    _DateTimeBias/3600,(abs(_DateTimeBias) % 3600)/60);
            return TRUE;
        };
    }
    return FALSE;
}
