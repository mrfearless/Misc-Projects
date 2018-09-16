parameters DOS_PAR1

set scoreboard off
set talk off
set bell off
set heading off
set menu off
set safety off
set status off
set procedure to ispy
set procedure to procs        && f:\clipprog\prg\procs\procs.prg
SET UNIQUE OFF
set date british
set message to 25
set deleted on
set confirm on
set exclusive on

* INITIALIZE NEW VARIABLES

*-----


OPENFILEFLAG=0
BINDPATH="F:\STAFF\BIND\"
declare screens[10]
screenct=1
v_crlf=chr(13)+chr(10)              && Cr&Lf
v_arrows=chr(24)+chr(25)+chr(27)+chr(26)
v_enter=" "+chr(17)+chr(196)+chr(217)+" "    && " <-Ù "
v_dot=chr(7)
v_linedrw="-"
do p_defvars
*DO SETHPCODES
leftmarg=SPACE(2)
*set color to (c_mainbl)

*********************************
*setup disk data variables
ichk4flag=0
ichk3flag=0
log1flag=0
log2flag=0
userinfo=""
totbytesA=""
avabytesA=""
userfilesA=""
userdirsA=""
totbytesB=""
avabytesB=""
userfilesB=""
userdirsB=""
*********************************

clear
*if PCOUNT()>0
*        if upper(DOS_PAR1)="-R" .or. upper(DOS_PAR1)="/R"
*                do RDX
*        endif
*endif

*if file("DAILYOPS.USR")
*        do P_MSG with "ERROR: Single user operating only"
*endif
*save to DAILYOPS.USR

*if file("DAILYOPS.MEM")
*        restore from DAILYOPS.MEM additive
*        RI_CT=RI_CT+1

*        if RI_CT=75
*                do rdx
*        else
*                save to DAILYOPS.MEM all like RI_*
*        endif
*else
*        do Rdx
*endif


*do loadup
*do clearreps

**************
* START
**************

SELECT 1
use ispyuser
clear
do scrdis
res=0

set color to (c_mainbl)
clear
do p_defvars
select 1
do scrdis

left=24
length=28
set color to (c_mainwh)
   

do while .t.
   
   screenct=1
   do lightcol
   do cursoff
   do shadowbox with 5,left-1,15,length+left+2
   
   @ 3,33 say MEMORY(0)
   
   @  6, left+2 prompt "Network Stations List     " 
   @  7, left+2 prompt "Network Stations Database " 
   @  8, left+2 prompt "Directory & File Listings "  
   @  9, left+2 prompt "Hard Disk Statistics      "  
   @ 10, left+2 prompt "Memory & Drivers Installed"  
   @ 11, left+2 prompt "O/S & Applications Details"
   @ 12, left+2 prompt "Configuration Files       "
   @ 13, left+2 prompt "Network Station's Log     "
   @ 14, left+2 prompt "Exit to main menu         " 
   
   menu to res
   set color to (c_mainWH)
   WKNO="00"
   do case
   case res=1
      do DIR_LIST
   case res=2
         select 1
         set filter to
         go top
         do MYB
   case res=3
         *do moduser
   case res=4
         do DISK_STAT
   case res=5
        DO MEM_MENU
   case res=6

   case res=7
   case res=8
        do LOG_MENU
        
   case res=9
        exit
   case res=0
        exit
   endcase
enddo

* RESET WORKING ENVIRONMENT

   select 1
   use
   select 2
   use

set color to
clear
close databases
set talk on
set escape on
set bell on
set heading on
*SET HELP ON
set menu on
set safety on
set status on
set scoreboard on
do curson
return



******************************************************************************
*                               PROC DIR_LIST
******************************************************************************
PROCEDURE DIR_LIST
PUBLIC T_FILE_NAME

RUN DIR F:\STAFF\BIND\*.*>DIRFILE.TMP

SELECT 2
USE DIRFILE
ZAP
APPEND ALL FROM DIRFILE.TMP SDF FOR DIR_NAME="<DIR>"

GO TOP
REPLACE FILE_NAME WITH LEFT(FILE_NAME,8)+"."+RIGHT(FILE_NAME,3) FOR .T.

B_DIR=NPOPUPC(5,5,16,5,"DIRFILE->FILE_NAME",1)
if recno()=1 .and. eof()
        DO X_MSG WITH "There Are No Network Stations Available"
        return
endif

IF B_DIR#0 
        go top
        skip B_DIR-1
        T_FILE_NAME = DIRFILE->FILE_NAME
        
        log1flag=0
        log2flag=0
        ichk3flag=0
        ichk4flag=0
        
        @  22, 12 say T_FILE_NAME
else
        return
ENDIF
return  
* Œ End of PROC DIR_LIST *


******************************************************************
PROCEDURE DISK_STAT
public totbytesA, avabytesA, userfilesA, userdirsA
public totbytesB, avabytesB, userfilesB, userdirsB
do cursoff
DO HOLDSCREEN

if file("F:\STAFF\BIND\&T_FILE_NAME.\ICHK.400")
        DO ICHK4
        do ichk4_disp
        if file("F:\STAFF\BIND\&T_FILE_NAME.\ICHK.300")
                DO ICHK3
                do ichk3_disp
                do verify
                do popscreen
                return
        ELSE
                DO SHADOWBOX WITH 13,15, 15, 61
                do lightcol
                @ 14,21 say "No Default Statistics Are Available"
        ENDIF

ELSE
        if file("F:\STAFF\BIND\&T_FILE_NAME.\ICHK.300")
                DO ICHK3
        ELSE
                DO X_MSG WITH "No Hard Disk Statistics Are Available"
                do popscreen
                return
        ENDIF
ENDIF
do while INKEY()#13 
enddo
set key 13 to

DO POPSCREEN
RETURN
* Œ End of PROC DISKSTAT *


*******************************************************************************
*                               PROC ICHK4
*******************************************************************************
procedure ichk4

if ichk4flag=0
        if TOTBYTESA="" .AND. AVABYTESA="" .AND. USERFILESA="" .AND. USERDIRSA=""
                SELECT 3
                USE ROUGHWOR
                ZAP

                APPEND FROM F:\STAFF\BIND\&T_FILE_NAME.\ICHK.400 SDF FOR AT("bytes total",BIGSTRING)>0
                totbytesA=alltrim(roughwor->bigstring)
                APPEND FROM F:\STAFF\BIND\&T_FILE_NAME.\ICHK.400 SDF FOR AT("bytes available",BIGSTRING)>0
                avabytesA=alltrim(roughwor->bigstring)
                APPEND FROM F:\STAFF\BIND\&T_FILE_NAME.\ICHK.400 SDF FOR AT("user files",BIGSTRING)>0
                userfilesA=alltrim(roughwor->bigstring)
                APPEND FROM F:\STAFF\BIND\&T_FILE_NAME.\ICHK.400 SDF FOR AT("directories",BIGSTRING)>0
                userdirsA=alltrim(roughwor->bigstring)
                ichk4flag=1
        endif
ENDIF
return
* Œ End of PROC ICHK4 *


******************************************************************************
*                            PROC ICHK4_DISP
******************************************************************************
procedure ichk4_disp

IF len(TOTBYTESA)#0 .AND. len(AVABYTESA)#0 .AND. len(USERFILESA)#0 .AND. len(USERDIRSA)#0
        
        DO LIGHTCOL
        DO CURSOFF
        DO SHADOWBOX WITH 5,15, 10, 61
        
        set color to (c_mainwb)
        @ 5, 31 SAY "New Disk Stats"
        @ 6, 23 say totbytesA
        @ 7, 23 say avabytesA
        @ 8, 23 say userfilesA
        @ 9, 23 say userdirsA
        
        TB=AT("bytes",TOTBYTESA)-1
        TOTB=ALLTRIM(LEFT(TOTBYTESA,TB))
        TOTBYTS=STRTRAN(TOTB,",")
        DO LIGHTCOL
        @ 6,17 SAY STR(INT(VAL(TOTBYTS)/(1048576)),4)+"M"
        
        AV=AT("bytes",AVABYTESA)-1
        AVAB=ALLTRIM(LEFT(AVABYTESA,AV))
        AVABYTS=STRTRAN(AVAB,",")
        DO LIGHTCOL
        @ 7,17 SAY STR(INT(VAL(AVABYTS)/(1048576)),4)+"M"
        
        UF=AT("user files",USERFILESA)-1
        USFI=ALLTRIM(LEFT(USERFILESA,UF))
        USERF=STRTRAN(USFI,",")
        DO LIGHTCOL
        @ 8,17 SAY STR(INT(VAL(USERF)/(1048576)),4)+"M"
        
        UD=AT("directories",USERDIRSA)-1
        USDI=ALLTRIM(LEFT(USERDIRSA,UD))
        USERD=STRTRAN(USDI,",")
        DO LIGHTCOL
        @ 9,17 SAY STR(INT(VAL(USERD)/(1048576)),4)+"M"
        do normcol
else        
        DO X_MSG WITH "There Is No New Data Available "
ENDIF
RETURN
* Œ End of PROC ICHK4_DISP *


********************************************************************************
*                              PROC ICHK3
********************************************************************************
procedure ichk3

if ichk3flag=0
        IF totbytesB="" .AND. avabytesB="" .AND. userfilesB="" .AND. userdirsB=""
                SELECT 3
                USE ROUGHWOR
                ZAP

                APPEND FROM F:\STAFF\BIND\&T_FILE_NAME.\ICHK.300 SDF FOR AT("bytes total",BIGSTRING)>0
                totbytesB=alltrim(roughwor->bigstring)
                APPEND FROM F:\STAFF\BIND\&T_FILE_NAME.\ICHK.300 SDF FOR AT("bytes available",BIGSTRING)>0
                avabytesB=alltrim(roughwor->bigstring)
                APPEND FROM F:\STAFF\BIND\&T_FILE_NAME.\ICHK.300 SDF FOR AT("user files",BIGSTRING)>0
                userfilesB=alltrim(roughwor->bigstring)
                APPEND FROM F:\STAFF\BIND\&T_FILE_NAME.\ICHK.300 SDF FOR AT("directories",BIGSTRING)>0
                userdirsB=alltrim(roughwor->bigstring)
        endif
ENDIF
return
* Œ End of PROC ICHK3 *


********************************************************************************
*                             PROC ICHK3_DISP
********************************************************************************
Procedure ichk3_disp

IF len(totbytesB)#0 .AND. len(avabytesB)#0 .AND. len(userfilesB)#0 .AND. len(userdirsB)#0
        
        DO LIGHTCOL
        DO CURSOFF
        DO SHADOWBOX WITH 12,15, 17, 61
        
        set color to (c_mainwb)
        @ 12, 30 SAY "Default Disk Stats"
        @ 13, 23 say TOTBYTESB
        @ 14, 23 say AVABYTESB
        @ 15, 23 say USERFILESB
        @ 16, 23 say USERDIRSB
        
        TB=AT("bytes",totbytesB)-1
        TOTB=ALLTRIM(LEFT(totbytesB,TB))
        TOTBYTS=STRTRAN(TOTB,",")
        DO LIGHTCOL
        @ 13,17 SAY STR(INT(VAL(TOTBYTS)/(1048576)),4)+"M"
        
        AV=AT("bytes",avabytesB)-1
        AVAB=ALLTRIM(LEFT(avabytesB,AV))
        AVABYTS=STRTRAN(AVAB,",")
        DO LIGHTCOL
        @ 14,17 SAY STR(INT(VAL(AVABYTS)/(1048576)),4)+"M"
        
        UF=AT("user files",userfilesB)-1
        USFI=ALLTRIM(LEFT(userfilesB,UF))
        USERF=STRTRAN(USFI,",")
        DO LIGHTCOL
        @ 15,17 SAY STR(INT(VAL(USERF)/(1048576)),4)+"M"
        
        UD=AT("directories",userdirsB)-1
        USDI=ALLTRIM(LEFT(userdirsB,UD))
        USERD=STRTRAN(USDI,",")
        DO LIGHTCOL
        @ 16,17 SAY STR(INT(VAL(USERD)/(1048576)),4)+"M"
        do normcol
else        
        DO X_MSG WITH "There Is No New Data Available "
ENDIF
RETURN
* Œ End of PROC ICHK3_DISP *



******************************************************************************
*                              PROC MEM_MENU
******************************************************************************
PROCEDURE MEM_MENU

opt=0
do holdscreen
IF .NOT. FILE("F:\STAFF\BIND\&T_FILE_NAME.\ICHK.MEM")
        DO X_MSG WITH "No Memory Statistics Are Available"
        do popscreen
        return
ENDIF

do while .t.

DO SHADOWBOX WITH 13,21, 17, 56
do cursoff

set color to (c_mainwb)
@ 13, 36 say "Memory"
do lightcol
@ 14, 24 PROMPT "Conventional, Upper & Reserved" 
@ 15, 24 PROMPT "Modules, Drivers & TSR's      " 
@ 16, 24 PROMPT "Extended(XMS) & Expanded(EMS) " 

menu to opt
set color to (c_mainWH)
   do case
        case OPT=1
                *do 
        
        case OPT=2
                do mem_mods
        case OPT=3
        case OPT=0
                DO POPSCREEN
                return
   endcase
enddo
* Œ End of PROC MEM_MENU *


******************************************************************************
*                             PROC MEM_MODS
******************************************************************************
procedure mem_mods
DO HOLDSCREEN
DECLARE MOD[20]
        
        SELECT 3
        USE ROUGHWOR
        ZAP

        APPEND FROM F:\STAFF\BIND\&T_FILE_NAME.\ICHK.MEM SDF FOR AT("K)",BIGSTRING)>0 .and. at("ICHK",BIGSTRING)=0
        GO TOP
        TOTMOD=0
        N=1
        DO WHILE .NOT. EOF()
                MOD[N]=ALLTRIM(BIGSTRING)
                N=N+1
                SKIP
        ENDDO
        TOTMOD=N
        WAIT
        
        FOR N=1 TO TOTMOD-1
        ? MOD[N]
        NEXT
        ?
        ? TOTMOD
        WAIT
        DO POPSCREEN
        RETURN
* Œ End of PROC MEM_MODS *


********************************************************************************
*                               PROC LOG_MENU
********************************************************************************
procedure LOG_MENU

opt=0
do holdscreen
IF .NOT. FILE("F:\STAFF\BIND\&T_FILE_NAME.\ICHK.LOG")
        DO X_MSG WITH "Station Log Not Available"
        do popscreen
        return
ENDIF

do while .t.

DO SHADOWBOX WITH 12,28, 16, 49
do cursoff

set color to (c_mainwb)
@ 12, 34 say "Station Log"
do lightcol
@ 13, 31 PROMPT "User Log History" 
@ 14, 31 PROMPT "View Last User  " 
@ 15,31 prompt  "Delete Log      "

menu to opt
set color to (c_mainWH)
   
   do case
        case OPT=1
                do loghist
        case OPT=2
                do LastUser
        case OPT=3
        
        case OPT=0
                DO POPSCREEN
                return
   endcase
enddo

******************************************************************************
procedure LastUser
public userinfo
if userinfo=""
        SELECT 4
        USE log
        ZAP

        APPEND all FROM F:\STAFF\BIND\&T_FILE_NAME.\ICHK.LOG delimited with , 
        go bottom
        lu=alltrim(strtran(log_user,'"',""))
        dt=strtran(log_date,'"',"")
        tm=strtran(log_time,'"',"")
        userinfo=lu+"  "+dt+" "+tm
endif
        do y_msg with "Last User", userinfo
return
******************************************************************
procedure loghist
public t_log_user

        SELECT 4
        USE log 
        zap
        

        APPEND all FROM F:\STAFF\BIND\&T_FILE_NAME.\ICHK.LOG delimited with , 
        go top
        
        *REPLACE FILE_NAME WITH LEFT(FILE_NAME,8)+"."+RIGHT(FILE_NAME,3) FOR .T.
        *len_loguser=len(log->log_user)
        t_log_user=strtran(log_user,'"',"")
        t_log_DATE=strtran(log_DATE,'"',"")
        t_log_TIME=strtran(log_TIME,'"',"")
        
        REPLACE log_user WITH t_log_user FOR .T.
        REPLACE log_DATE WITH t_log_DATE FOR .T.
        REPLACE log_TIME WITH t_log_TIME FOR .T.
        
        set unique off
        if .not. file("F:\STAFF\BIND\&T_FILE_NAME.\logndx1.tmp")
                
                index on log_user+log_date to F:\STAFF\BIND\&T_FILE_NAME.\logndx1.tmp
        else
                if log1flag=0
                        use log index F:\STAFF\BIND\&T_FILE_NAME.\logndx1.tmp
                        reindex
                        log1flag=1
                endif
        endif
        
        *use log index F:\STAFF\BIND\&T_FILE_NAME.\logndx1.tmp
        
        set unique on
        if .not. file("F:\STAFF\BIND\&T_FILE_NAME.\logndx2.tmp")
                
                index on log_user to F:\STAFF\BIND\&T_FILE_NAME.\logndx2.tmp
        else
                if log2flag=0
                        use log index F:\STAFF\BIND\&T_FILE_NAME.\logndx2.tmp
                        reindex
                        log2flag=1
                endif
        endif
        use log index F:\STAFF\BIND\&T_FILE_NAME.\logndx2.tmp

B_DIR=NPOPUPC(7,15,16,25,"log->log_user",1)
if recno()=1 .and. eof()
        DO X_MSG WITH "There Are No User Logs Available"
        return
endif

IF B_DIR#0 
        *go top
        *skip B_DIR-1
        T_log_user = log->log_user
        *do normcol
        *@  22, 32 say T_log_user
        *clear gets
        *go top
        *select 6
        use log index f:\staff\bind\&T_FILE_NAME.\logndx1.tmp
        set unique off
        go top
        set filter to log_user=t_log_user
        
        LOGLIST=1
        GO TOP
        set filter to log_user=t_log_user
        DO WHILE .NOT. EOF()
                SKIP
                LOGLIST=LOGLIST+1
        ENDDO
        
        go top
        set filter to log_user=t_log_user
        n=1
        
                TOP=INT(LOGLIST-(loglist+3))
                do holdscreen
                DO SHADOWBOX WITH TOP,15,TOP+LOGLIST+1,59
                set color to (c_mainwb)
                if loglist>22
                        @ TOP+loglist+1, 30 SAY "More"
                endif
                @ TOP, 30 SAY "User Log History"
                @ top+1,19 say "User Name"
                @ top+1,38 say "Login Date"
                @ top+1,49 say "& Time"
                set color to (c_mainwh)
                
                ?TOP
                ?LOGLIST
                ?TOP+LOGLIST
                TOP=TOP+2
                *@  TOP,8 SAY ""
        
        do while .not. eof()
                @ TOP,19 SAY log_user
                @ TOP,38 SAY log_DATE
                @ TOP,49 SAY log_TIME
                *? log_user+"  "
                *?? log_date+"  "
                *?? log_time+"  "
                TOP=TOP+1
                SKIP
        enddo
        
                *if top=23 .and. loglist>22       
                
                *? log_user+"  "
                *?? log_date+"  "
                *?? log_time+"  "
                *n=n+1
                *if n=20
                        *do while inkey()!=13
                        *enddo
                        *set key 13 to
                        *clear
                        *n=1
                *endif
                *skip
        
       
        do while inkey()!=13
        enddo
        set key 13 to
        do popscreen
        return
else
      return
ENDIF
return


******************************************************************************
*                              PROC VERIFY
******************************************************************************
PROCEDURE VERIFY
do cursoff
yn=1
DO x_ARESURE WITH yn,"Verify New statistics and make as Default","Yes","No"
if YN#1
        return
endif

DECLARE N[10]
N=1
chkfile="F:\STAFF\BIND\&T_FILE_NAME.\ICHK.30&T_N"

IF FILE ("F:\STAFF\BIND\&T_FILE_NAME.\ICHK.300")
        
        do while .T.
                T_N=chr(48+N)
                chkfile="F:\STAFF\BIND\&T_FILE_NAME.\ICHK.3B&T_N"
                
                IF FILE (chkfile)
                        IF N#9
                               N=N+1
                        ELSE
                               *DO DEL BACK LOG Y/N
                               n=1
                               exit
                        ENDIF
                ELSE
                        EXIT
                ENDIF
        ENDDO
        do x_msg with "Generating backup file "+chkfile
        *? t_n
        *? chkfile
        
        RENAME F:\STAFF\BIND\&T_FILE_NAME.\ICHK.300 TO CHKFILE
        RENAME F:\STAFF\BIND\&T_FILE_NAME.\ICHK.400 TO F:\STAFF\BIND\&T_FILE_NAME.\ICHK.300
ELSE

ENDIF
return
* Œ End of PROC VERIFY *

*********************
B_DIR=NPOPUPC(5,5,16,5,"ROUGHWOR->BIGSTRING",1)
if recno()=1 .and. eof()
        DO X_MSG WITH "There Is No Data Available "
        return
endif

RETURN



******************************************************************
*
* Procedure to select & load .dbf files, .NT? index files
*

procedure loadup


select 1
use
if .not. file("dailyops.NT1")   
        do rdx
endif
if .not. file("dailyops.NT2")   
        do rdx
endif
if .not. file("dailyops.NT3")   
        do rdx
endif
if .not. file("dailyops.NT4")   
        do rdx
endif

if .not. file("dailyops.NT5")   
        do rdx
endif
if .not. file("dailyops.NT6")   
        do rdx
endif

use dailyops index dailyops.NT1, dailyops.NT2, dailyops.NT3, dailyops.NT4, dailyops.NT5, dailyops.NT6


SELECT 2
use
if .not. file("dailyccs.NTX")   
        do rdx
endif

USE dailyccs index dailyccs

SELECT 4
use
USE dailyMAC

SELECT 5
if .not. file("DAILYEMP.NTX")   
        do rdx
endif
USE dailyEMP INDEX DAILYEMP


SELECT 8
USE dailyRCC

return
******************************************************************
procedure clearreps

*
* clear up old report files
*

SELECT 7


IF FILE("DAILYRE1.DBF")
    USE DAILYRE1
    zap
ENDIF

IF FILE("DAILYRE2.DBF")
    USE DAILYRE2
    zap
ENDIF
IF FILE("DAILYRE3.DBF")
    USE DAILYRE3
    zap
ENDIF
IF FILE("DAILYRE4.DBF")
    USE DAILYRE4
    zap
ENDIF
IF FILE("DAILYREP.DBF")
    USE DAILYREP
    zap
ENDIF


use


return
******************************************************************
*procedure MYB
*
*do holdscreen
*do shadowbox with 4,4,23,73
*
*DO LIGHTCOL
*@ 22,5 say "Keys: PgUp, PgDn, &V_Arrows, &V_enter to Modify. Ctrl+G to Delete   F1=COPY "
*go top
*
*MYBROWSE(5,5,21,72)
*do popscreen
*return

******************************************************************
procedure printdor
do holdscreen
do shadowbox with 10,25,15,76
DO LIGHTCOL
REPNUM=1
DO WHILE .T.
        @  11, 26 prompt "Generate Daily \ Weekly Operating Report        "
        @  12, 26 prompt "Generate Daily \ Weekly Machine Activity Report "
        @  13, 26 prompt "Generate Weekly Activity Report by Clock Number "
        @  14, 26 prompt "Modify Report Filter                            "
        menu to repnum
        if repnum=0 
                exit
        endif

        do case
                case repnum=1
                        
                        do PRINTREP1
                case repnum=2
                        
                        do PRINTREP2
                case repnum=3
                        
                        do PRINTREP3
                case repnum=4
                        select 8
                        do myb
        endcase
enddo
do popscreen
return



******************************************************************
PROCEDURE printrep1
do holdscreen

select 1

do lightcol
do shadowbox with 1,4,5,45
T_FINISH_WK="00"
T_FINISH_DAY="1"
T_COST_CENT="   "
T_SHIFT="1"

@  2,  5  SAY "Finished Wk   :"
@  2, 21  GET  T_FINISH_WK
@  2, 27  SAY "Day (1-7) :"
@  2, 39  GET  T_FINISH_DAY
@  4,  5  SAY "Cost Centre   :"
@  4, 21  GET  T_COST_CENT
@  4, 26  SAY "Shift (1-3):"
@  4, 39  GET  T_SHIFT


CLEAR GETS

do curson

@  2, 21  GET  T_FINISH_WK
@  2, 39  GET  T_FINISH_DAY
READ
IF READKEY()=12
                do popscreen
                return
endif

T_CC=0
SELECT 2
T_CC=NPOPUPC(4,21,20,26,"DAILYCCS->COST_CENT",1)

IF T_CC#0 
                        go top
                        skip T_CC-1
                        T_COST_CENT = DAILYCCS->COST_CENT
                        @  4, 21  GET  T_COST_CENT
                        read
        else
                do popscreen
                return
ENDIF


SELECT 1


T_SHIFT=getshift(1)
@  4, 39  GET  T_SHIFT
read

do cursoff
IF READKEY()=12
                do popscreen
                return
endif

T_FINISH_WK=ltrim(T_FINISH_WK)

chk_f="FINISH_WK=T_FINISH_WK"


T_FINISH_DAY=alltrim(T_FINISH_DAY)
if len(T_FINISH_DAY)#0
                chk_f=chk_f+" .and. FINISH_DAY=T_FINISH_DAY"
endif

T_SHIFT=ltrim(T_SHIFT)
if len(T_SHIFT)#0
                chk_f=chk_f+" .and. SHIFT=T_SHIFT"
endif

T_COST_CENT=ltrim(T_COST_CENT)

if len(T_COST_CENT)#0
                chk_f=chk_f+" .and. COST_CENT=T_COST_CENT"
endif
REP_CHK=SPACE(3)
do customrep
***************
* REPORT START
***************


* blank old report files
* do clearreps


SELECT 1
GO TOP

if len(T_FINISH_DAY)=0
        set order to 4
        * WEEKLY
ELSE
        set order to 2
        * DAILY
ENDIF


IND_F=indexkey(0)

IF FILE("Dailyrep.DBF")
        ERASE Dailyrep.DBF
ENDIF




********
total ON &IND_F. fields finqty, setup, prodn, WAITING,CLEANING,;
         QUALPROC,MAINT,REWORK to DailyREP for &chk_f.
********


select 7
use dailyrep

select 6
set order to 1

select 7

if len(T_FINISH_DAY)=0
        USE DAILYRE3
        FIL="DAILYRE3"
ELSE
        USE DAILYRE1
        FIL="DAILYRE1"
ENDIF

zap

append from dailyREP

***************************************************
yn=1
do p_aresure with YN,"Print report","",""
if yn=1
        if len(T_FINISH_DAY)=0
                do PRDAILOP with 3    && weekly output 
        else
                do PRDAILOP with 1    && daily output 
        endif
endif
***************************************************


* CLOSE TEMP AREA 7

USE
select 1
set order to 1

*************
* REPORT END
*************

do popscreen


* call dbase setup
do loadup
return



******************************************************************
PROCEDURE printrep2
do holdscreen

select 1

do lightcol
do shadowbox with 1,2,7,45
T_FINISH_WK="00"
T_FINISH_DAY="1"
T_COST_CENT="   "
T_MACHINE_NO="   "
T_SHIFT="1"

@  2,  5  SAY "Finished Wk   :"
@  2, 21  GET  T_FINISH_WK
@  2, 27  SAY "Day (1-7) :"
@  2, 39  GET  T_FINISH_DAY
@  4,  5  SAY "Cost Centre   :"
@  4, 21  GET  T_COST_CENT
@  4, 26  SAY "Shift (1-3):"
@  4, 39  GET  T_SHIFT
@  6,  5  SAY "Machine Number :"
@  6, 22  GET  T_MACHINE_NO


CLEAR GETS

do curson

@  2, 21  GET  T_FINISH_WK
@  2, 39  GET  T_FINISH_DAY
READ
IF READKEY()=12
                do popscreen
                return
endif

T_CC=0
SELECT 2
T_CC=NPOPUPC(4,21,20,26,"DAILYCCS->COST_CENT",1)

IF T_CC#0 
                        go top
                        skip T_CC-1
                        T_COST_CENT = DAILYCCS->COST_CENT
                        @  4, 21  GET  T_COST_CENT
                        read
        else
                do popscreen
                return
ENDIF


SELECT 1


T_SHIFT=getshift(1)
@  4, 39  GET  T_SHIFT
read
do cursoff
IF READKEY()=12
                do popscreen
                return
endif

*if T_COST_CENT#""
        select 4
        set filter to DAILYMAC->COST_CENT=T_COST_CENT
        go top
        IF EOF() 
                DO p_msg WITH "No machine list to pick from"
        endif
        T_MAC=NPOPUPC(4,31,10,36,"DAILYMAC->MACHINE_NO",1)
        IF T_MAC#0 
                GO Top
                skip T_MAC-1
                T_MACHINE_NO=DAILYMAC->MACHINE_NO 
        ENDIF
        do curson
        @ 6, 22  GET  T_MACHINE_NO
        read

        set filter to 
        SELECT 1
*endif


T_FINISH_WK=ltrim(T_FINISH_WK)

chk_f="DAILYOPS->FINISH_WK=T_FINISH_WK"


T_FINISH_DAY=ltrim(T_FINISH_DAY)
if len(T_FINISH_DAY)#0
                chk_f=chk_f+" .and. DAILYOPS->FINISH_DAY=T_FINISH_DAY"
endif

T_SHIFT=ltrim(T_SHIFT)
if len(T_SHIFT)#0
                chk_f=chk_f+" .and. DAILYOPS->SHIFT=T_SHIFT"
endif

T_COST_CENT=ltrim(T_COST_CENT)

if len(T_COST_CENT)#0
                chk_f=chk_f+" .and. DAILYOPS->COST_CENT=T_COST_CENT"
endif

T_MACHINE_NO=ltrim(T_MACHINE_NO)
if len(T_MACHINE_NO)#0
                chk_f=chk_f+" .and. DAILYOPS->MACHINE_NO=T_MACHINE_NO"
endif


REP_CHK=SPACE(3)
do customrep
***************
* REPORT START
***************

* blank old report files
* do clearreps

SELECT 1
GO TOP

if len(T_FINISH_DAY)=0
        set order to 3
        * WEEKLY
ELSE
        set order to 1
        * DAILY
ENDIF


IND_F=indexkey(0)

IF FILE("Dailyrep.DBF")
        ERASE Dailyrep.DBF
ENDIF

total ON &IND_F. fields finqty, setup, prodn, ;
     WAITING,CLEANING,QUALPROC,MAINT,REWORK to Dailyrep for &chk_f.
select 7
if len(T_FINISH_DAY)=0
        USE DAILYRE4
        FIL="DAILYRE4"
ELSE
        USE DAILYRE2
        FIL="DAILYRE2"
ENDIF

zap
append from dailyrep
***************************************************
yn=1
do p_aresure with YN,"Print report","",""
if yn=1
        if len(T_FINISH_DAY)=0
                do PRDAILOP with 4     && weekly machine output 
        else
                do PRDAILOP with 2     && daily machine output 
        endif
endif
***************************************************

* CLOSE TEMP AREA 7

USE
select 1
set order to 1


*************
* REPORT END
*************
* call dbase setup
do loadup

do popscreen
return


******************************************************************
PROCEDURE printrep3
do holdscreen

select 1

do lightcol
do shadowbox with 1,4,5,45
T_FINISH_WK="00"
T_FINISH_DAY="1"
T_COST_CENT="   "
T_SHIFT="1"

@  2,  5  SAY "Finished Wk   :"
@  2, 21  GET  T_FINISH_WK
@  2, 27  SAY "Day (1-7) :"
@  2, 39  GET  T_FINISH_DAY
@  4,  5  SAY "Cost Centre   :"
@  4, 21  GET  T_COST_CENT
@  4, 26  SAY "Shift (1-3):"
@  4, 39  GET  T_SHIFT


CLEAR GETS

do curson

@  2, 21  GET  T_FINISH_WK
@  2, 39  GET  T_FINISH_DAY
READ
IF READKEY()=12
                do popscreen
                return
endif

T_CC=0
SELECT 2
T_CC=NPOPUPC(4,21,20,26,"DAILYCCS->COST_CENT",1)

IF T_CC#0 
                        go top
                        skip T_CC-1
                        T_COST_CENT = DAILYCCS->COST_CENT
                        @  4, 21  GET  T_COST_CENT
                        read
        else
                do popscreen
                return
ENDIF


SELECT 1


T_SHIFT=getshift(1)
@  4, 39  GET  T_SHIFT
read

do cursoff
IF READKEY()=12
                do popscreen
                return
endif

T_FINISH_WK=ltrim(T_FINISH_WK)

chk_f="FINISH_WK=T_FINISH_WK"


T_FINISH_DAY=ltrim(T_FINISH_DAY)
if len(T_FINISH_DAY)#0
                chk_f=chk_f+" .and. FINISH_DAY=T_FINISH_DAY"
endif

T_SHIFT=ltrim(T_SHIFT)
if len(T_SHIFT)#0
                chk_f=chk_f+" .and. SHIFT=T_SHIFT"
endif

T_COST_CENT=ltrim(T_COST_CENT)

if len(T_COST_CENT)#0
                chk_f=chk_f+" .and. COST_CENT=T_COST_CENT"
endif
REP_CHK=SPACE(3)
do customrep
***************
* REPORT START
***************


* blank old report files
* do clearreps


SELECT 1
GO TOP

if len(T_FINISH_DAY)=0
        set order to 6
        * WEEKLY
ELSE
        set order to 5
        * DAILY
ENDIF


IND_F=indexkey(0)

IF FILE("Dailyrep.DBF")
        ERASE Dailyrep.DBF
ENDIF



********
total ON &IND_F. fields finqty, setup, prodn, WAITING,CLEANING,;
         QUALPROC,MAINT,REWORK to DailyREP for &chk_f.
********


select 7
use dailyrep

select 6
set order to 1

select 7

if len(T_FINISH_DAY)=0
        USE DAILYRE6
        FIL="DAILYRE6"
ELSE
        USE DAILYRE5
        FIL="DAILYRE5"
ENDIF

zap

append from dailyREP


***************************************************
*yn=1  && dont ask now
*do p_aresure with YN,"Print report","",""
*if yn=1
        if len(T_FINISH_DAY)=0
                do PRDAILOP with 6    && weekly output 
        else
                do PRDAILOP with 5    && daily output 
        endif
*endif
***************************************************


* CLOSE TEMP AREA 7

USE
select 1
set order to 1

*************
* REPORT END
*************

do popscreen


* call dbase setup
do loadup
return

******************************************************************





******************************************************************
procedure PRDAILOP       && print daily output report 
para reportnum
go top
pg=1
linect=0
TOT_FINQTY=  0.00
TOT_SETUP=   0.00
TOT_WAITING= 0.00
TOT_CLEANING=0.00
TOT_QUALPROC=0.00
TOT_MAINT=   0.00
TOT_REWORK=  0.00
TOT_BREAKS=  0.00
TOT_PRODN=   0.00

if .not. eof()
        do p_note with "Printing report, please wait ..."
        set console off
        SET PRINT ON
        do prhead with reportnum
        linect=5
        do while .not. eof()
                do case
                        case reportnum=1 .OR. reportnum=3
                                ?  LEFTMARG
                                ?? LASCON1
                                ?? COST_CENT        +SPACE(5)
                                ?? SHIFT            +SPACE(5)
                                ?? STR(SETUP+WAITING+CLEANING+QUALPROC+MAINT+REWORK+BREAKS+PRODN,8,2)+SPACE(2)  && CLOCK HOURS
                                ?? STR(FINQTY ,10,2)+SPACE(2)
                                ?? STR(SETUP   ,7,2)+SPACE(2)
                                ?? STR(WAITING ,7,2)+SPACE(2)
                                ?? STR(CLEANING,7,2)+SPACE(2)
                                ?? STR(QUALPROC,7,2)+SPACE(2)
                                ?? STR(MAINT   ,7,2)+SPACE(2)
                                ?? STR(REWORK  ,7,2)+SPACE(2)  
                                ?? STR(BREAKS  ,7,2)+SPACE(2)
                                ?? STR(SETUP+WAITING+CLEANING+QUALPROC+MAINT+REWORK+BREAKS,8,2)+SPACE(2)  && TOTAL LOST
                                ?? STR(PRODN   ,7,2)+SPACE(2)
                                ?? LASNORM
                                
                        case reportnum=2 .OR. reportnum=4
                                ?  LEFTMARG
                                ?? LASCON1
                                ?? COST_CENT        +SPACE(5)
                                ?? MACHINE_NO       +SPACE(4)
                                ?? SHIFT            +SPACE(3)
                                ?? STR(SETUP+WAITING+CLEANING+QUALPROC+MAINT+REWORK+BREAKS+PRODN,8,2)+SPACE(3)  && CLOCK HOURS
                                ?? STR(FINQTY ,10,2)+SPACE(2)
                                ?? STR(SETUP   ,7,2)+SPACE(2)
                                ?? STR(WAITING ,7,2)+SPACE(2)
                                ?? STR(CLEANING,7,2)+SPACE(2)
                                ?? STR(QUALPROC,7,2)+SPACE(2)
                                ?? STR(MAINT   ,7,2)+SPACE(2)
                                ?? STR(REWORK  ,7,2)+SPACE(1)  
                                ?? STR(BREAKS  ,7,2)+SPACE(3)
                                ?? STR(SETUP+WAITING+CLEANING+QUALPROC+MAINT+REWORK+BREAKS,8,2)+SPACE(2)  && TOTAL LOST
                                ?? STR(PRODN   ,7,2)+SPACE(2)
                                ?? LASNORM
                        
                        
                        case reportnum=5 .OR. reportnum=6
                                ?  LEFTMARG
                                ?? LASCON1
                                ?? EMPLOYEE
                                ?? STR(CLOCKNO ,5,0)+SPACE(2)
                                ?? STR(SETUP+WAITING+CLEANING+QUALPROC+MAINT+REWORK+BREAKS+PRODN,8,2)+SPACE(2)  && CLOCK HOURS
                                ?? STR(FINQTY ,10,2)+SPACE(2)
                                ?? STR(SETUP   ,7,2)+SPACE(2)
                                ?? STR(WAITING ,7,2)+SPACE(2)
                                ?? STR(CLEANING,7,2)+SPACE(2)
                                ?? STR(QUALPROC,7,2)+SPACE(2)
                                ?? STR(MAINT   ,7,2)+SPACE(2)
                                ?? STR(REWORK  ,7,2)+SPACE(2)  
                                ?? STR(BREAKS  ,7,2)+SPACE(2)
                                ?? STR(SETUP+WAITING+CLEANING+QUALPROC+MAINT+REWORK+BREAKS,8,2)+SPACE(2)  && TOTAL LOST
                                ?? STR(PRODN   ,7,2)+SPACE(2)
                                ?? LASNORM
                endcase
                TOT_FINQTY=TOT_FINQTY  +FINQTY
                TOT_SETUP=TOT_SETUP   +SETUP
                TOT_WAITING=TOT_WAITING +WAITING
                TOT_CLEANING=TOT_CLEANING+CLEANING
                TOT_QUALPROC=TOT_QUALPROC+QUALPROC
                TOT_MAINT=TOT_MAINT   +MAINT
                TOT_REWORK=TOT_REWORK  +REWORK
                TOT_BREAKS=TOT_BREAKS  +BREAKS
                TOT_PRODN=TOT_PRODN   +PRODN
                skip            
                                
                linect=linect+1
                IF LINECT>39
                        ? FORMFEED
                        PG=PG+1
                        do prhead with reportnum
                        linect=5
                endif
        enddo
        do case
                case reportnum=1 .OR. reportnum=3
                                ?
                                ?  LEFTMARG
                                ?? LASCON1
                                ?? BOLD
                                ?? "TOTAL"+SPACE(9)
                                ?? STR(TOT_SETUP+TOT_WAITING+TOT_CLEANING+TOT_QUALPROC+TOT_MAINT+TOT_REWORK+TOT_BREAKS+TOT_PRODN,8,2)+SPACE(2)  && CLOCK HOURS
                                ?? STR(TOT_FINQTY ,10,2)+SPACE(2)
                                ?? STR(TOT_SETUP   ,7,2)+SPACE(2)
                                ?? STR(TOT_WAITING ,7,2)+SPACE(2)
                                ?? STR(TOT_CLEANING,7,2)+SPACE(2)
                                ?? STR(TOT_QUALPROC,7,2)+SPACE(2)
                                ?? STR(TOT_MAINT   ,7,2)+SPACE(2)
                                ?? STR(TOT_REWORK  ,7,2)+SPACE(2)  
                                ?? STR(TOT_BREAKS  ,7,2)+SPACE(2)
                                ?? STR(TOT_SETUP+TOT_WAITING+TOT_CLEANING+TOT_QUALPROC+TOT_MAINT+TOT_REWORK+TOT_BREAKS,8,2)+SPACE(2)  && TOTAL LOST
                                ?? STR(TOT_PRODN   ,7,2)+SPACE(2)
                                ?? LASNORM
                                ?? NOBOLD
                
                case reportnum=2 .OR. reportnum=4
                                ?
                                ?  LEFTMARG
                                ?? LASCON1
                                ?? BOLD
                                ?? "TOTAL"+SPACE(17)
                                ?? STR(TOT_SETUP+TOT_WAITING+TOT_CLEANING+TOT_QUALPROC+TOT_MAINT+TOT_REWORK+TOT_BREAKS+TOT_PRODN,8,2)+SPACE(3)  && CLOCK HOURS
                                ?? STR(TOT_FINQTY ,10,2)+SPACE(2)
                                ?? STR(TOT_SETUP   ,7,2)+SPACE(2)
                                ?? STR(TOT_WAITING ,7,2)+SPACE(2)
                                ?? STR(TOT_CLEANING,7,2)+SPACE(2)
                                ?? STR(TOT_QUALPROC,7,2)+SPACE(2)
                                ?? STR(TOT_MAINT   ,7,2)+SPACE(2)
                                ?? STR(TOT_REWORK  ,7,2)+SPACE(1)  
                                ?? STR(TOT_BREAKS  ,7,2)+SPACE(3)
                                ?? STR(TOT_SETUP+TOT_WAITING+TOT_CLEANING+TOT_QUALPROC+TOT_MAINT+TOT_REWORK+TOT_BREAKS,8,2)+SPACE(2)  && TOTAL LOST
                                ?? STR(TOT_PRODN   ,7,2)+SPACE(2)
                                ?? LASNORM
                                ?? NOBOLD
                
                case reportnum=5 .OR. reportnum=6
                                ?
                                ?  LEFTMARG
                                ?? LASCON1
                                ?? BOLD
                                ?? "TOTAL"+SPACE(15)
                                ?? SPACE(5)+SPACE(2)
                                ?? STR(TOT_SETUP+TOT_WAITING+TOT_CLEANING+TOT_QUALPROC+TOT_MAINT+TOT_REWORK+TOT_BREAKS+TOT_PRODN,8,2)+SPACE(2)  && CLOCK HOURS
                                ?? STR(TOT_FINQTY ,10,2)+SPACE(2)
                                ?? STR(TOT_SETUP   ,7,2)+SPACE(2)
                                ?? STR(TOT_WAITING ,7,2)+SPACE(2)
                                ?? STR(TOT_CLEANING,7,2)+SPACE(2)
                                ?? STR(TOT_QUALPROC,7,2)+SPACE(2)
                                ?? STR(TOT_MAINT   ,7,2)+SPACE(2)
                                ?? STR(TOT_REWORK  ,7,2)+SPACE(2)  
                                ?? STR(TOT_BREAKS  ,7,2)+SPACE(2)
                                ?? STR(TOT_SETUP+TOT_WAITING+TOT_CLEANING+TOT_QUALPROC+TOT_MAINT+TOT_REWORK+TOT_BREAKS,8,2)+SPACE(2)  && TOTAL LOST
                                ?? STR(TOT_PRODN   ,7,2)+SPACE(2)
                                ?? LASNORM
                                ?? NOBOLD
                endcase

        ? PORTRAIT
*       ? FORMFEED
        SET PRINT OFF
        set console on
        do popscreen
        do p_msg with "Analysis Report printed"
else
        do p_msg with "Nothing to print !"
endif
return
******************************************************************
procedure prhead
para headtype
?? LANDSCAPE+LASNORM+BOLD+LEFTMARG+"THERMAL HEAT EXCHANGERS (IRL.) LIMITED"
?? space(45)
IF REP_CHK#SPACE(3)
        ?? "REPORT:"+REP_CHK+SPACE(5)
ELSE
        ?? SPACE(15)
ENDIF
?? dtoc(date())
do case
        case headtype=1
                ?  LEFTMARG+"DAILY OUTPUT REPORT BY COST CENTRE         "
                ?? "WK"+FINISH_WK+"\"+FINISH_DAY
        case headtype=3
                ?  LEFTMARG+"WEEKLY OUTPUT REPORT BY COST CENTRE        "
                ?? "WK"+FINISH_WK+"  "
        case headtype=2
                ?  LEFTMARG+"DAILY MACHINE OUTPUT REPORT BY COST CENTRE "
                ?? "WK"+FINISH_WK+"\"+FINISH_DAY
        case headtype=4
                ?  LEFTMARG+"WEEKLY MACHINE OUTPUT REPORT BY COST CENTRE"
                ?? "WK"+FINISH_WK+"  "
        case headtype=5
                ?  LEFTMARG+"DAILY OUTPUT REPORT BY CLOCK NUMBER        "
                ?? "WK"+FINISH_WK+"\"+FINISH_DAY
        case headtype=6
                ?  LEFTMARG+"WEEKLY OUTPUT REPORT BY CLOCK NUMBER        "
                ?? "WK"+FINISH_WK+"  "
endcase   

?? SPACE(48)+"PAGE:"+str(pg,2,0)
?? NOBOLD

do case
        case headtype=1 .OR. headtype=3
                                ?
                                ? LEFTMARG+BOLD
                                ?? LASCON1
                                ?? 'COST   SHIFT  '
                                ?? '  CLOCK   '
                                ?? '    FINISH  '
                                ?? '  SETUP  '
                                ?? 'WAITING  '
                                ?? 'CLEANING '
                                ?? 'QUALITY  '
                                ?? 'MAINTNC  '
                                ?? ' REWORK  '
                                ?? ' OTHER   '
                                ?? ' TOT LOST '
                                ?? ' PRODUCTN'

                                ? LEFTMARG
                                ?? 'CENTRE   NO   '
                                ?? '  HOURS   '
                                ?? '       QTY  '
                                ?? '   TIME  '
                                ?? 'FOR WORK '
                                ?? 'WORKAREA '
                                ?? '  PROBS  '
                                ?? '   TIME  '
                                ?? '   TIME  '
                                ?? '  HOURS  '
                                ?? '  TIME    '
                                ?? '    HOURS'+NOBOLD
                                ? LASNORM
                                ?
        
        case headtype=2 .OR. headtype=4
                                ?
                                ? LEFTMARG+BOLD
                                ?? LASCON1
                                ?? 'COST   MACHINE  '
                                ?? 'SHIFT  '
                                ?? '  CLOCK   '
                                ?? '    FINISH  '
                                ?? '  SETUP  '
                                ?? 'WAITING  '
                                ?? 'CLEANING '
                                ?? 'QUALITY  '
                                ?? 'MAINTNC  '
                                ?? ' REWORK  '
                                ?? ' OTHER   '
                                ?? ' TOT LOST '
                                ?? ' PRODUCTN'
                                
                                ? LEFTMARG
                                ?? 'CENTRE  NUMBER    '
                                ?? 'NO  '              
                                ?? '   HOURS   '
                                ?? '       QTY  '
                                ?? '   TIME  '
                                ?? 'FOR WORK '
                                ?? 'WORKAREA '
                                ?? '  PROBS  '
                                ?? '   TIME  '
                                ?? '   TIME  '
                                ?? '  HOURS  '
                                ?? '  TIME    '
                                ?? '    HOURS'+NOBOLD
                                ? LASNORM
                                ?
        
        
        
        
        case headtype=5 .OR. headtype=6
                                ?
                                ? LEFTMARG+BOLD
                                ?? LASCON1
                                ?? 'EMPLOYEE           '
                                ?? 'CLOCK  '
                                ?? '  CLOCK   '
                                ?? '    FINISH  '
                                ?? '  SETUP  '
                                ?? 'WAITING  '
                                ?? 'CLEANING '
                                ?? 'QUALITY  '
                                ?? 'MAINTNC  '
                                ?? ' REWORK  '
                                ?? ' OTHER   '
                                ?? ' TOT LOST '
                                ?? ' PRODUCTN'
                                ? LEFTMARG
                                ?? 'NAME               '
                                ?? 'NUMBER '
                                ?? '  HOURS   '
                                ?? '       QTY  '
                                ?? '   TIME  '
                                ?? 'FOR WORK '
                                ?? 'WORKAREA '
                                ?? '  PROBS  '
                                ?? '   TIME  '
                                ?? '   TIME  '
                                ?? '  HOURS  '
                                ?? '  TIME    '
                                ?? '    HOURS'+NOBOLD
                                ? LASNORM
                                ?
                                
ENDCASE                                
                                



return
******************************************************************
procedure WEEKFILT
PUBLIC V_WKNO

*
* V_WKNO - Needs to be seen in public - because of the FILTER command
*

do holdscreen
do lightcol
do curson
V_WKNO="  "

select 1
do shadowbox with 6,19,8,54
do lightcol
@ 7, 25 say "ENTER WEEK TO FILTER   : " get V_WKNO
read
if len(ltrim(rtrim(V_WKNO)))#0
        set filter to FINISH_WK=V_WKNO
endif

do popscreen
return



******************************************************************
procedure deletewk

do holdscreen
do lightcol
do curson
V_WKNO="  "

select 1
do shadowbox with 6,23,8,54
do lightcol
@ 7, 25 say "ENTER WEEK TO DELETE   : " get V_WKNO
read

D_WKNO=val(V_WKNO)

do cursoff
   
   yn=1
   do p_aresure with yn,"Delete all entries for WK. "+ltrim(rtrim(str(D_WKNO,2,0)))+" ","Yes, delete all these records","No, don't delete"
   if yn=1
      go top
      delete for val(DAILYOPS->FINISH_WK)=D_WKNO
      pack
   endif

do popscreen
return

******************************************************************
function clockcheck
PARA IND
OLDAREA=SELECT()
SELECT DAILYEMP
GO TOP
SEEK IND

IF FOUND()
        replace DAILYOPS->Employee with employee
        SELECT (OLDAREA)
        @  7, 21  SAY  DAILYOPS->Employee
        return .T.
 ELSE
        SELECT (OLDAREA)
        do cursoff
        DO P_msg WITH "Invalid Clock Card Number"
        do curson
        return .f.
ENDIF


******************************************************************
function costcheck
PARA IND
OLDAREA=SELECT()
SELECT DAILYCCS
GO TOP
SEEK IND
IF FOUND()
        SELECT (OLDAREA)
        return .T.
 ELSE
        SELECT (OLDAREA)
        do cursoff
        DO P_msg WITH "Invalid Cost Centre Number"
        do curson
        return .f.
ENDIF


******************************************************************

procedure enterdos

*
*
* Set up variables to be used in popups
*
*

LASTCC=0
LASTMAC=0
LASTEMP=0

t_FINISH_WK="00"
t_FINISH_DAY="1"
t_SHIFT="1"
t_employee="   "
t_COST_CENT="   "
t_clockno=0000

do holdscreen
DO WHILE .T.
        
        select 1
        append blank
        do lightcol
        do shadowbox with 1,2,8,45
        
        replace DAILYOPS->FINISH_WK  with t_FINISH_WK
        replace DAILYOPS->FINISH_DAY with t_FINISH_DAY
        replace DAILYOPS->Shift with t_SHIFT
        replace DAILYOPS->employee   with t_employee
        replace DAILYOPS->clockno   with t_clockno
        replace DAILYOPS->DATE_INPUT   with DATE()
        replace DAILYOPS->COST_CENT WITH t_COST_CENT
        do curson
        @  7, 21  GET  DAILYOPS->Employee
        clear gets
   YN=2
  do while YN=2
        @  2,  5  SAY "Finished Wk   :"
        @  2, 21  GET  DAILYOPS->FINISH_WK
        @  2, 27  SAY "Day (1-7) :"
        @  2, 39  GET  DAILYOPS->FINISH_DAY
        @  4,  5  SAY "Cost Centre   :"
        @  4, 21  GET  DAILYOPS->COST_CENT valid(costcheck(DAILYOPS->COST_CENT))
        @  4, 26  SAY "Shift (1-3):"
        @  4, 39  GET  DAILYOPS->SHIFT valid(DAILYOPS->SHIFT>"0" .and. DAILYOPS->SHIFT<"4")
        @  6,  5  SAY "Clock Number  :"
        @  6, 21  GET  DAILYOPS->clockno picture "9999" valid(clockcheck(DAILYOPS->clockno)) 
        @  7,  5  SAY "Employee Name :"
        @  3,  3  TO  8, 44  
        read
        IF READKEY()=12
                EXIT
        endif

        yn=1
        do p_aresure with YN,"Is this Correct","",""
        IF YN=0
                EXIT
        ENDIF
   enddo

   IF READKEY()=12 .or. YN=0
                delete
                EXIT
   endif

        *******************************************
        * SCREEN 2
        *******************************************

        do holdscreen
        do curson
        DO shadowbox with 2,16,23,58
        @  4, 26  SAY "Machine Number :"
        @  6, 18  SAY "Time on    :"
        @  7, 39  SAY "Hours : "
        @  8, 18  SAY "Time off   :"
        @ 10, 18  SAY "Drawing No :"
        @ 10, 40  SAY "Week Pos :"
        @ 12, 18  SAY "Finished Quantity :"
        @ 14, 18  SAY "Setup :"
        @ 16, 20  SAY "Time off \ Breaks :"
        @ 17, 20  SAY "Waiting for work..:"
        @ 18, 20  SAY "Cleaning          :"
        @ 19, 20  SAY "Quality Procedures:"
        @ 20, 20  SAY "Maintenance       :"
        @ 21, 20  SAY "Rework Time       :"
        
        @  6, 37  TO  8, 55  
        @ 15, 18  TO 22, 56  
        @  3, 18  TO  5, 55  

        @  6, 31  GET  DAILYOPS->TIME_ON
        @  8, 31  GET  DAILYOPS->TIME_OFF
        @ 10, 31  GET  DAILYOPS->DRAWING_NO
        @ 10, 51  GET  DAILYOPS->WKPOS 
        @ 12, 39  GET  DAILYOPS->FINQTY
        @ 14, 26  GET  DAILYOPS->SETUP
        @ 16, 40  GET  DAILYOPS->BREAKS
        @ 17, 40  GET  DAILYOPS->WAITING
        @ 18, 40  GET  DAILYOPS->CLEANING
        @ 19, 40  GET  DAILYOPS->QUALPROC
        @ 20, 40  GET  DAILYOPS->MAINT
        @ 21, 40  GET  DAILYOPS->REWORK
        
        CLEAR GETS

        select 4
        set filter to DAILYMAC->COST_CENT=DAILYOPS->COST_CENT
        go top
        IF EOF() 
                DO p_msg WITH "No machine codes available for this section"
        endif
        T_MAC=NPOPUPC(4,31,10,36,"DAILYMAC->MACHINE_NO",LASTMAC)
        
        IF T_MAC#0 
                LASTMAC=T_MAC
                go top
                skip T_MAC-1
                REPLACE DAILYOPS->MACHINE_NO WITH DAILYMAC->MACHINE_NO
                @ 4, 43  GET  DAILYOPS->MACHINE_NO
                CLEAR GETS
        ENDIF

        set filter to 
        SELECT 1
       
        if T_MAC=0
                do cursoff
                DO POPSCREEN
                exit
        endif

        ERR=99
        do while err#0
                        ERR=99
                        @  6, 31  GET  DAILYOPS->TIME_ON
                        @  8, 31  GET  DAILYOPS->TIME_OFF
                        read
                        if readkey()=12
                                delete
                                do cursoff
                                DO POPSCREEN
                                exit
                        endif

                        err=0                        
                        if (DAILYOPS->TIME_ON)-int(DAILYOPS->TIME_ON)> .59 .or. int(DAILYOPS->TIME_ON)>23
                                do P_MSG with "ERROR: Invalid Time On !"
                                err=1
                        endif

                        if (DAILYOPS->TIME_oFF)-int(DAILYOPS->TIME_off)> .59 .or. int(DAILYOPS->TIME_off)>23
                                do P_MSG with "ERROR: Invalid Time Off !"
                                err=2
                        endif

                        REALTIME=RT(DAILYOPS->TIME_oFF)-RT(DAILYOPS->TIME_ON)

                        IF REALTIME<0
                                REALTIME=REALTIME+24.00
                        ENDIF

                        IF REALTIME<0.01 .or. REALTIME>10.00
                                do P_MSG with "ERROR: Invalid Hours input !"
                                err=3
                        ENDIF
        enddo

        if err#0
                exit
        endif

        @  7, 39  SAY "Hours : "
        @  7, 47  SAY STR(REALTIME,5,2)

        YN=2        
        do while YN=2
                
                @ 10, 31  GET  DAILYOPS->DRAWING_NO
                @ 10, 51  GET  DAILYOPS->WKPOS 
                @ 12, 39  GET  DAILYOPS->FINQTY
                @ 14, 26  GET  DAILYOPS->SETUP

                @ 16, 40  GET  DAILYOPS->BREAKS   valid DAILYOPS->BREAKS<10.00 
                @ 17, 40  GET  DAILYOPS->WAITING  valid DAILYOPS->WAITING<10.00 
                @ 18, 40  GET  DAILYOPS->CLEANING valid DAILYOPS->CLEANING<10.00
                @ 19, 40  GET  DAILYOPS->QUALPROC valid DAILYOPS->QUALPROC<10.00
                @ 20, 40  GET  DAILYOPS->MAINT    valid DAILYOPS->MAINT<10.00
                @ 21, 40  GET  DAILYOPS->REWORK   valid DAILYOPS->REWORK<10.00
                read
                replace DAILYOPS->PRODN with ;
                        REALTIME-( rt(DAILYOPS->SETUP)+rt(WAITING)+rt(CLEANING)+;
                           rt(QUALPROC)+rt(MAINT)+rt(REWORK)+rt(BREAKS) )
                replace DAILYOPS->BREAKS with rt(DAILYOPS->BREAKS)
                replace DAILYOPS->SETUP with rt(DAILYOPS->SETUP)
                replace DAILYOPS->WAITING with rt(DAILYOPS->WAITING)
                replace DAILYOPS->CLEANING with rt(DAILYOPS->CLEANING)
                replace DAILYOPS->QUALPROC with rt(DAILYOPS->QUALPROC)
                replace DAILYOPS->MAINT with rt(DAILYOPS->MAINT)
                replace DAILYOPS->REWORK with rt(DAILYOPS->REWORK)
                do cursoff
                YN=1            
                hrs=ltrim(str(DAILYOPS->PRODN,5,2))
                DO p_ARESURE WITH yn,"Produced Hours = &hrs.. Is this Correct","",""
                if YN=0
                        delete
                        exit
                endif
                do cursoN
        enddo
        if YN=0
                exit
        endif
        
        
        select 1


        DO POPSCREEN
          *
          *

        YN=1
        DO p_ARESURE WITH yn,"Add more records","",""
        if YN#1
                exit
        endif
        t_FINISH_WK  = DAILYOPS->FINISH_WK
        t_FINISH_DAY = DAILYOPS->FINISH_DAY
        t_employee   = DAILYOPS->employee
        t_COST_CENT  = DAILYOPS->COST_CENT
ENDDO

do popscreen
return

******************************************************************

function getshift
parameters ppp
do holdscreen
c_temp=setcolor()

do lightcol

if ppp<0 
        mx=4
        do shadowbox with 4,30,11,50
else
        mx=3
        do shadowbox with 4,30,10,50
endif

s=ABS(ppp)

@  5,32  say    "Pick Shift Number:"
@  7,32  prompt "Shift 1 (default)"
@  8,32  prompt "Shift 2          "
@  9,32  prompt "Shift 3          "
if ppp<0 
        @ 10, 32  prompt "ALL              "
endif
menu to s

if s<1
       s=ABS(ppp)
endif

do popscreen
set color to (c_temp)
return str(s,1,0)

******************************************************************

FUNCTION rt
PARAMETER TT1

*REALTIME RETURNS REALTIME FROM NUMBER ( I.E. 17.30 = 17.50 )
RT1=INT(TT1)
RT2=(TT1-INT(TT1))*100/60
TT=RT1+RT2
RETURN TT
******************************************************************
procedure rdx

do holdscreen
clear
do scrdis
@ 5,5 say  "Re-index & Pack the system files - "
acc="N"
do accpt
if acc="Y"
   @ 7, 5 say "Please Wait, while re-indexing takes place.."
   select 1

   use dailyops
   pack

   * 
   * NOTE: CHANGING THE INDEX STRUCTURE WILL ALTER THE WAY IN WHICH 
   * THE PROGRAM CALCULATES REPORT TOTALS.  { WALK WITH CARE }
   *


         index on FINISH_WK+FINISH_DAY+SHIFT+      COST_CENT+MACHINE_NO to dailyops.NT1
         index on FINISH_WK+FINISH_DAY+SHIFT+      COST_CENT            to dailyops.NT2
         index on FINISH_WK+COST_CENT+SHIFT+MACHINE_NO to dailyops.NT3
         index on FINISH_WK+COST_CENT+SHIFT            to dailyops.NT4

         index on FINISH_WK+FINISH_DAY+EMPLOYEE           to dailyops.NT5
         index on FINISH_WK+           EMPLOYEE           to dailyops.NT6
   use dailyops index dailyops.NT1, dailyops.NT2, dailyops.NT3, dailyops.NT4,dailyops.NT5, dailyops.NT6

   select 2
   use dailyccs
   pack
   index on cost_cent to dailyccs
   

   SELECT 4

   USE dailyMAC
   SORT on COST_CENT /A, MACHINE_NO /A to dailytmp
   use
   erase dailyMAC.dbf
   rename dailytmp.dbf to dailyMAC.dbf
   USE dailyMAC

   SELECT 5
   USE dailyEMP
   INDEX ON CLOCKNO TO dailyEmp
   USE dailyEMP INDEX DAILYEMP
   select 1

   do p_msg with "File reindexing complete"
   RI_CT=0
   save to DAILYOPS.MEM all like RI_*
endif
do popscreen

return

******************************************************************

procedure scrdis
@ 3, 0 SAY REPLICATE(CHR(176),1440)
do lightcol
DO panelbox WITH 0,0,2,79
@ 1,15 say "ISPY 1.2  LAN MONITORING UTILITY  (C) THERMAL 1996"
DO PANELBOX WITH 21,0,24,79

@ 22,3 SAY "ADDRESS:                    "
RETURN


******************************************************************

procedure accpt
do curson
fl=1
do while fl=1 .or. acc#"Y" .and. acc#"N"
   fl=0
   yn=1
   do p_aresure with yn,"Accept this ?","Continue with operation","Abort operation"
   if yn=1
      acc="Y"
   endif
   *@ 23,30 say "Accept this Y/N ? " get acc picture "!"
   *read
enddo
*@ 23,30
do cursoff
return

******************************************************************
procedure waitkey
do cursoff
@ 23,25 say "Press {SPACE BAR} to Continue"
ky=0
do while ky#32
   ky=inkey()
enddo
@ 23,25
return
******************************************************************
*!*****************************************************************************
*!
*!      Procedure: CURSON
*!
*!      Called by: DAILYOPS.PRG                      
*!               : ACCPT              (procedure in DAILYOPS.PRG)
*!
*!*****************************************************************************
*procedure curson
*set cursor on
*return
******************************************
*!*****************************************************************************
*!
*!      Procedure: CURSOFF
*!
*!      Called by: DAILYOPS.PRG                      
*!               : ACCPT              (procedure in DAILYOPS.PRG)
*!               : WAITKEY            (procedure in DAILYOPS.PRG)
*!
*!*****************************************************************************
*procedure cursoff
*set cursor off
*return
******************************************

*!*****************************************************************************
*!
*!      Procedure: P_DEFVARS
*!
*!      Called by: DAILYOPS.PRG                      
*!
*!*****************************************************************************
procedure p_defvars
public c_mainbl,c_mainwh,c_mainwb,c_mainno,c_whbl,c_blwh,c_pass,C_MAINXX
c_mainbl="G/B+,W/B,B,B,N/BG"
*c_mainbl="GR+/B,W/BG,B,B,N/BG"
c_mainXX="GR+/B,W/B,B,B,N/BG"
*c_mainwh="GR+/W,W/RB,W,B,N/BG"
c_mainwh="GR+/W,W/B,W,B,N/BG"
c_mainwb="B/W,BG/RB,W,B,N/BG"
c_mainno="W/W,N/W,W,B,N/RB"
c_whbl="W/N"
c_blwh="N/W"
c_pass="RB/RB,RB/RB"
return

************************************************************
*check YN input  (p_aresure)
*if y
*        holdscreen
*        shadowbox
*        input repchk variable
*        select rcs file
*       go top; yippee=0
*       do while .not. eof()
*               search file for repchk=REP  if found yippee=1 and then exit do
*               skip
*       enddo
*        print error if at eof() and still no found
*       else
*            !!  build up macro
*             chk_f=chk_f+".and. (.or.  .....)"
*             popscreen
*endif
*return

*******************************************************************************
procedure customrep
yn=1
DO P_ARESURE WITH yn,"Select Report Filter" ,"Yes","No"
if YN=1
        DO HOLDSCREEN
        do curson
        do lightcol
        do shadowbox with 14,29,16,49
        @ 15,30 say " Report Filter: "
        @ 15,45 get REP_CHK picture '!!!'
        READ
        DO POPSCREEN
        DO CURSOFF        
        if readkey()=12 .OR. REP_CHK=SPACE(3)
                return
        ENDIF
        select DAILYRCC
        go top
        REPCKFLAG=0
        do while .not. eof()
                 IF UPPER(REP)=REP_CHK  && search file for repchk=REP  if found yippee=1 and then exit do
                      REPCKFLAG=1
                      EXIT
                 ENDIF
                 SKIP
        ENDDO
        if REPCKFLAG=1
                NUMAC=""
                if DAILYRCC->RC1#space(3)
                        NUMAC=NUMAC+" .OR. COST_CENT='"+DAILYRCC->RC1+"'"
                ENDIF
                if DAILYRCC->RC2#space(3)
                        NUMAC=NUMAC+" .OR. COST_CENT='"+DAILYRCC->RC2+"'"
                ENDIF
                if DAILYRCC->RC3#space(3)
                        NUMAC=NUMAC+" .OR. COST_CENT='"+DAILYRCC->RC3+"'"
                ENDIF
                if DAILYRCC->RC4#space(3)
                        NUMAC=NUMAC+" .OR. COST_CENT='"+DAILYRCC->RC4+"'"
                ENDIF
                if DAILYRCC->RC5#space(3)
                        NUMAC=NUMAC+" .OR. COST_CENT='"+DAILYRCC->RC5+"'"
                ENDIF
                if DAILYRCC->RC6#space(3)
                        NUMAC=NUMAC+" .OR. COST_CENT='"+DAILYRCC->RC6+"'"
                ENDIF
                if DAILYRCC->RC7#space(3)
                        NUMAC=NUMAC+" .OR. COST_CENT='"+DAILYRCC->RC7+"'"
                ENDIF
                if DAILYRCC->RC8#space(3)
                        NUMAC=NUMAC+" .OR. COST_CENT='"+DAILYRCC->RC8+"'"
                ENDIF
                if DAILYRCC->RC9#space(3)
                        NUMAC=NUMAC+" .OR. COST_CENT='"+DAILYRCC->RC9+"'"
                ENDIF
                IF NUMAC#""   && i.e. somebody has created a bogus report
                        NUMAC=SUBSTR(NUMAC,7,LEN(NUMAC))    && REMOVE FIRST " .OR. "
                        NUMAC=" .AND. ("+NUMAC+")"
                        CHK_F=CHK_F+NUMAC    && ADD CONDITION TO MAIN CHK_F CHECK
                ELSE
                        DO P_MSG WITH "ERROR: Check Report filter setup !"
                        return
                ENDIF
        ELSE        
                DO CURSOFF
                do p_msg with "Report filter '"+REP_CHK+"' does not exist!"
                return
        endif
endif
RETURN
**************************************************************************
*                         procedure x_aresure
**************************************************************************
procedure x_aresure
parameters vv,msg,msgy,msgn
do holdscreen
t_len=len(msg)
if t_len>74
   msg=substr(msg,1,74)
   t_len=len(msg)
endif
t_left=33-int(t_len/2)
t_right=t_left+t_len+2+7    &&  +2 is for " ?"

do shadowbox with 19,t_left,21,t_right
c_temp=setcolor()

set color to (c_mainwh)
@ 20,t_left+1 say msg+"?"
@ 20,t_len+16 prompt "Yes" &&     message substr(msgy+space(80),1,80)
@ 20,t_len+20 prompt "No"  &&     message substr(msgn+space(80),1,80)

menu to vv
set color to (c_temp)
do popscreen
return

*******************************************************************************
procedure y_msg
parameters titles, t_msg

private c_temp
c_temp=setcolor()
do holdscreen
e_msg=ltrim(rtrim(t_msg))
t_len=len(e_msg)
titles=ltrim(rtrim(titles))

t_left=(40-(t_len/2))-3
t_right=(t_left+t_len)+3

*T_lines=MLCOUNT(E_MSG,((T_right-1)-(T_left+1)))
t_top=13
*-T_lines/2
t_bot=15
*T_top+T_lines+1
do shadowbox with t_top,t_left,t_bot,t_right
set color to (c_mainwh)
*@ t_bot,28 say "Press       to Continue"
*set color to (c_mainXX)
*@ t_bot,34 say v_enter
set color to (c_mainwh)

t_text=e_msg

*set key 13 to esc_key
*T_text=MEMOEDIT(T_text,T_top+1,T_left+1,T_bot-1,T_right-1,.f.)
set color to (c_mainwb)

@ 13,t_left+16 say titles
set color to (c_mainwh)
@ 14,t_left+2 say t_text

do while inkey()#13 
enddo 

set key 13 to

do popscreen
set color to (c_temp)
return


**************************************************************************
*                            PROCEDURE X_MSG
**************************************************************************
procedure x_msg
parameters t_msg

private c_temp
c_temp=setcolor()
do holdscreen
e_msg=ltrim(rtrim(t_msg))

t_len=len(e_msg)


t_left=(40-(t_len/2))-3
t_right=(t_left+t_len)+3

*T_lines=MLCOUNT(E_MSG,((T_right-1)-(T_left+1)))
t_top=14
*-T_lines/2
t_bot=17
*T_top+T_lines+1
do shadowbox with t_top,t_left,t_bot+1,t_right
set color to (c_mainwh)
@ t_bot,28 say "Press       to Continue"
set color to (c_mainXX)
@ t_bot,34 say v_enter
set color to (c_mainwh)

t_text=e_msg
set key 13 to esc_key
*T_text=MEMOEDIT(T_text,T_top+1,T_left+1,T_bot-1,T_right-1,.f.)
@ 15,t_left+2 say t_text
do while inkey()!=13
enddo
set key 13 to
do popscreen
set color to (c_temp)
return


*: EOF: DAILYOPS.PRG



