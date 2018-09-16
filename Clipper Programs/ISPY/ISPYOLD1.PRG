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
USERINFO=""
declare screens[14]
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

*do reset_flags

clear
if PCOUNT()>0
        if upper(DOS_PAR1)="-R" .or. upper(DOS_PAR1)="/R"
                do log_rest
        endif
endif

**************
* START
**************
*DO HOLDSCREEN
clear
do scrdis
res=0

set color to (c_mainbl)
clear
do p_defvars
do scrdis
select 1

left=24
length=28
set color to (c_mainwh)
   

do while .t.
   
   *screenct=1
   do lightcol
   do cursoff
   do shadowbox with 5,left-1,16,length+left+2
   
   @3, 30 say "         "
   @ 3,30 say MEMORY(0)
   
   @  6, left+2 prompt "Network Stations List     " 
   @  7, left+2 prompt "Network Stations Database " 
   @  8, left+2 prompt "Directory & File Listings "  
   @  9, left+2 prompt "Memory & Hard Disk Stats.."  
   @ 10, left+2 prompt "O/S & Apps Installed..    "
   @ 11, left+2 prompt "Configuration Files..     "
   @ 12, left+2 prompt "Network Station's Log..   "
   @ 13, left+2 prompt "Schedule ICHK operation   "
   @ 14, left+2 prompt "Print Summary / Reports   " 
   @ 15, left+2 prompt "Exit to main menu         " 
   
   menu to res
   set color to (c_mainWH)
   do case
   case res=1
      do DIR_LIST
   case res=2
        SELECT 1
        use ispyuser
        set filter to
        go top
        do MYB
        use
   case res=3
         CHK_F=" .T. .and. at("+'"'+'.   '+'"'+",FILELIST)=0 .and. at("+'"'+'..   '+'"'+",FILELIST)=0 .AND. at("+'"'+'file(s)'+'"'+",FILELIST)=0"
         do file_list
   case res=4
         do diskormem
   case res=5
         do os_menu
   case res=6
         do conf_disp
   case res=7
         do LOG_MENU
   case res=8
         do sched_ichk
   case res=9
         
   case res=10
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
*DO POPSCREEN
return


procedure conf_disp

cf=0

do holdscreen
do while .t.
        DO ShADOWBOX WITH 13,29, 17, 44
        do cursoff
        
        set color to (c_mainwb)
        @ 13, 31 say    "System Files"
        do lightcol
        @ 14, 31 PROMPT "Config.sys  " 
        @ 15, 31 PROMPT "Autoexec.bat"
        @ 16, 31 PROMPT "Win.ini     "

        menu to cf
        
        *set color to (c_mainWH)
        do case
                case cf=1
                        DO cfg_sys
                case cf=2
                        do aut_bat
                case cf=3
                        do win_ini
                case cf=0
                        DO POPSCREEN
                        return
        endcase
enddo
release all
DO POPSCREEN
return



********************************************************************************
procedure cfg_sys
if .not. file("F:\STAFF\BIND\&T_FILE_NAME.\CONFIG.SYS")
        do x_msg with "No Config.sys file found"
        return
endif
do holdscreen

infile=fopen("F:\STAFF\BIND\&T_FILE_NAME.\CONFIG.SYS")
lngth=fseek(infile,1,2)
fseek(infile,0,0)
conf=freadstr(infile,lngth)
*conf=freadstr(infile,6000)
fclose(infile)

do shadowbox with 5,10,20,60
set color to (c_mainwb)
@ 5, 30 say "CONFIG.SYS"
set cursor off
memoedit(conf,6,11,19,59,.f.)

set color to (c_mainwh)
do popscreen
return
*******************************************************************************
procedure aut_bat
if .not. file("F:\STAFF\BIND\&T_FILE_NAME.\AUTOEXEC.BAT")
        do x_msg with "No Autoexec.bat file found"
        return
endif

infile=fopen("F:\STAFF\BIND\&T_FILE_NAME.\AUTOEXEC.BAT")
lngth=fseek(infile,1,2)
fseek(infile,0,0)
conf=freadstr(infile,lngth)
*conf=freadstr(infile,5000)
fclose(infile)
do holdscreen
do shadowbox with 5,10,20,60
set color to (c_mainwb)
@ 5, 30 say "AUTOEXEC.BAT"
set cursor off
memoedit(conf,6,11,19,59,.f.)

set color to (c_mainwh)
do popscreen
return
*******************************************************************************
procedure win_ini
if .not. file("F:\STAFF\BIND\&T_FILE_NAME.\WIN.INI")
        do x_msg with "No Win.ini file found"
        return
endif

infile=fopen("F:\STAFF\BIND\&T_FILE_NAME.\WIN.INI")
lngth=fseek(infile,1,2)
fseek(infile,0,0)
conf=freadstr(infile,lngth)
*conf=freadstr(infile,15000)
fclose(infile)
do holdscreen
do shadowbox with 5,10,20,60
set color to (c_mainwb)
@ 5, 30 say "WIN.INI"
set cursor off
memoedit(conf,6,11,19,59,.f.)
set color to (c_mainwh)
do popscreen
return





*******************************************************************************
procedure sched_ichk
if .not. file("F:\STAFF\BIND\&T_FILE_NAME.\ichk.100")
        do x_msg with "No scheduling information is available"
        return
endif

private  day_aray[7]
day_aray[1]="Monday   "
day_aray[2]="Tuesday  "
day_aray[3]="Wednesday"
day_aray[4]="Thursday "
day_aray[5]="Friday   "
day_aray[6]="Saturday "
day_aray[7]="Sunday   "

do holdscreen
tt=6
ll=16
bb=14
rr=28
do shadowbox with tt,ll,bb,rr
set color to (c_mainwb)
title="Schedule"
lenlu=len(title)

@ tt,((ll-1)+(rr-12)/2)-((lenlu/2)) say title

buffer=space(10)
infile=fopen("F:\STAFF\BIND\&T_FILE_NAME.\ichk.100")
fseek(infile, 9,1)
fread(infile,@buffer,10)

fclose(infile)
*?buffer
buffer=alltrim(memotran(buffer,"",""))

dayst=at(" ",buffer)
*?dayst

lbuf=len(buffer)
*?lbuf
*wait
cur_sched=substr(buffer,dayst,lbuf-dayst)
*?cur_sched
*wait             
do shadowbox with 18, 21, 20, 56
@ 19,23 say "Current Scheduled day:"

set color to (c_mainwh)
@ 19,46 say cur_sched
t_sel=achoice(tt+1,ll+2,bb-1,rr-2,day_aray,.t.,"",1)

if t_sel#0
        go top
        skip t_sel-1
        sched_day=(day_aray[t_sel])
        *@ 23,22 say sched_day
        *?sched_day
        *wait
        buffer=sched_day
        outfile=fopen("F:\STAFF\BIND\&T_FILE_NAME.\ichk.100",2)
        fseek(outfile, 9,1)
        fwrite(outfile,buffer,10)
        fclose(outfile)
endif

do popscreen
return


******************************************************************************
procedure diskormem

dom=0

do holdscreen
do while .t.
        DO SHADOWBOX WITH 14,7, 17, 36
        do cursoff
        
        set color to (c_mainwb)
        @ 14, 12 say   "Memory & Disk Stats"
        do lightcol
        @ 15, 10 PROMPT "Memory Summary & Modules" 
        @ 16, 10 PROMPT "Hard Disk Statistics    "
        
        menu to dom
        
        set color to (c_mainWH)
        do case
                case dom=1
                        DO MEM_MENU
                case dom=2
                        do DISK_STAT
                case dom=0
                        DO POPSCREEN
                        return
        endcase
enddo
release all
DO POPSCREEN
return

* Œ End of PROC diskormem MENU *





******************************************************************************
*                               PROC DIR_LIST
******************************************************************************
PROCEDURE DIR_LIST
public T_FILE_NAME
*RELEASE ALL LIKE FILE_ARAY*
*if file("F:\STAFF\BIND\DIRFILE.TMP")
*        erase F:\STAFF\BIND\DIRFILE.TMP
*endif
*?MEMORY(0)
run DIR F:\STAFF\BIND\*.*>DIRFILE.TMP
SELECT 2
USE DIRFILE
ZAP

APPEND ALL FROM DIRFILE.TMP SDF FOR DIR_NAME="<DIR>"

GO TOP
REPLACE FILE_NAME WITH LEFT(FILE_NAME,8)+"."+RIGHT(FILE_NAME,3) FOR .T.

B_DIR=NPOPUPC(5,5,16,5,"DIRFILE->FILE_NAME",1,"")
if recno()=1 .and. eof()
        DO X_MSG WITH "There Are No Network Stations Available"
        return
endif

IF B_DIR#0 
        go top
        skip B_DIR-1
        T_FILE_NAME = DIRFILE->FILE_NAME
        @ 23, 12 say T_FILE_NAME
        USERINFO=""
        RELEASE ALL
        *do reset_flags
else
        return
ENDIF
RELEASE ALL
return  
* Œ End of PROC DIR_LIST *

******************************************************************************
*                               PROC FILE_LIST
******************************************************************************
PROCEDURE FILE_LIST

*PRIVATE T_S
*private t_ct
*private disk_info
*private vol_info
*private xx
*private tt
*private bb
*private ll
*private rr

if .not. file ("F:\STAFF\BIND\&T_FILE_NAME.\ICHK.600")
        do x_msg with "No directory listing file available"
        return
endif

do holdscreen
do p_note with "Generating file listing, please wait..."

SELECT 3
USE ISPYLIST
ZAP

*APPEND ALL FROM F:\STAFF\BIND\&T_FILE_NAME.\ICHK.650 SDF FOR at("Directory PATH listing",FILELIST)>0
*disk_info=alltrim(FILELIST)
*zap

*APPEND ALL FROM F:\STAFF\BIND\&T_FILE_NAME.\ICHK.650 SDF FOR at("Volume Serial Number",FILELIST)>0
*vol_info=alltrim(FILELIST)
*zap

*APPEND ALL FROM F:\STAFF\BIND\&T_FILE_NAME.\ICHK.650 SDF FOR .T. .and. at("Directory PATH listing",FILELIST)=0 .and. at("Volume Serial Number",FILELIST)=0 
*xx=reccount()
if readkey()=12
        do popscreen
        release all
        return
endif
APPEND ALL FROM F:\STAFF\BIND\&T_FILE_NAME.\ICHK.600 SDF FOR &CHK_F. 
if readkey()=12
        do popscreen
        release all
        return
endif

*PRIVATE File_aray[xx]


*GO TOP
*t_ct=1
*do while .not. eof()
*        file_aray[t_ct]=alltrim(substr(FILELIST,1,42))
*        t_ct=t_ct+1
*        skip
*        if eof()
*                exit
*        endif
*enddo
*USE
do popscreen
SELECT 3
USE ISPYLIST
SET FILTER TO
GO TOP
do holdscreen
DO MYB
RELEASE ALL
do popscreen
RETURN
*do holdscreen
*tt=6
*ll=16
*bb=20
*rr=62
*do shadowbox with tt,ll,bb,rr
*set color to (c_mainwb)
*
*title="File & Directory Listing"
*lenlu=len(title)

*@ tt,((ll-1)+(rr-12)/2)-((lenlu/2)) say title
*@ tt+1,ll+2 say disk_info
*@ tt+2,ll+2 say vol_info
*set color to (c_mainwh)
*
*t_s=achoice(tt+3,ll+2,bb-1,rr-2,file_aray,.t.,"",1)
*IF T_S#0
 *       do popscreen
 *       RELEASE ALL LIKE FILE_ARAY*
 *       *ADEL(FILE_ARAY)
 *       ? MEMORY(0)
 *       WAIT
 *       return
*ELSE
*        do popscreen
*        RELEASE ALL LIKE FILE_ARAY*
*        *ADEL(FILE_ARAY)
*        ? MEMORY(0)
*        WAIT
*        return
*ENDIF
* Œ End of PROC file_LIST *

******************************************************************************
*                           PROCEDURE DISK_STAT
******************************************************************************
PROCEDURE DISK_STAT
private totbytesA, avabytesA, userfilesA, userdirsA,totbyts
private totbytesB, avabytesB, userfilesB, userdirsB
totbyts=0
totbytesA=""
avabytesA=""
userfilesA=""
userdirsA=""
totbytesB=""
avabytesB=""
userfilesB=""
userdirsB=""
do cursoff

DO HOLDSCREEN

if file("F:\STAFF\BIND\&T_FILE_NAME.\ICHK.400")
        DO ICHK4
        
        *DO SHADOWBOX WITH 22,15, 24, 61
        DO panelBOX WITH 22,0, 24, 79
        set color to (c_mainwb)
        *@ 5, 31 SAY "New Disk Stats"
        @ 23, 23 say totbytesA
        set color to (c_mainwh)
        @ 23,17 SAY STR(INT(VAL(TOTBYTS)/(1048576)),4)+"M"
        
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
                *DO SHADOWBOX WITH 22,15, 24, 61
                DO panelBOX WITH 22,0, 24, 79
                set color to (c_mainwb)
                *@ 5, 31 SAY "New Disk Stats"
                @ 23, 23 say totbytesb
                set color to (c_mainwh)
                @ 23,17 SAY STR(INT(VAL(TOTBYTS)/(1048576)),4)+"M"
                *@ 2,17 SAY STR(INT(VAL(TOTBYTS)/(1048576)),4)+"M"
                do ichk3_disp
        ELSE
                DO X_MSG WITH "No Hard Disk Statistics Are Available"
                do popscreen
                return
        ENDIF
ENDIF
do while INKEY()#27 
enddo
set key 27 to


DO POPSCREEN
RETURN
* Œ End of PROC DISKSTAT *

*******************************************************************************
*                               PROC ICHK4
*******************************************************************************
procedure ichk4

SELECT 3
USE ISPYLIST
ZAP

APPEND FROM F:\STAFF\BIND\&T_FILE_NAME.\ICHK.400 SDF FOR AT("bytes total",FILELIST)>0
totbytesA=alltrim(ISPYLIST->FILELIST)
APPEND FROM F:\STAFF\BIND\&T_FILE_NAME.\ICHK.400 SDF FOR AT("bytes available",FILELIST)>0
avabytesA=alltrim(ISPYLIST->FILELIST)
APPEND FROM F:\STAFF\BIND\&T_FILE_NAME.\ICHK.400 SDF FOR AT("user files",FILELIST)>0
userfilesA=alltrim(ISPYLIST->FILELIST)
APPEND FROM F:\STAFF\BIND\&T_FILE_NAME.\ICHK.400 SDF FOR AT("directories",FILELIST)>0
userdirsA=alltrim(ISPYLIST->FILELIST)
        TB=AT("bytes",TOTBYTESA)-1
        TOTB=ALLTRIM(LEFT(TOTBYTESA,TB))
        TOTBYTS=STRTRAN(TOTB,",")
        *DO LIGHTCOL
        *@ 6,17 SAY STR(INT(VAL(TOTBYTS)/(1048576)),4)+"M"

return
* Œ End of PROC ICHK4 *

******************************************************************************
*                            PROC ICHK4_DISP
******************************************************************************
procedure ichk4_disp

IF len(TOTBYTESA)#0 .AND. len(AVABYTESA)#0 .AND. len(USERFILESA)#0 .AND. len(USERDIRSA)#0
        
        DO LIGHTCOL
        DO CURSOFF
        DO SHADOWBOX WITH 7,15, 11, 61
        
        set color to (c_mainwb)
        @ 7, 31 SAY "New Disk Stats"
        *@ 2, 23 say totbytesA
        
        @ 8, 23 say avabytesA
        @ 9, 23 say userfilesA
        @ 10, 23 say userdirsA
        
        
        AV=AT("bytes",AVABYTESA)-1
        AVAB=ALLTRIM(LEFT(AVABYTESA,AV))
        AVABYTS=STRTRAN(AVAB,",")
        DO LIGHTCOL
        @ 8,17 SAY STR(INT(VAL(AVABYTS)/(1048576)),4)+"M"
        
        UF=AT("user files",USERFILESA)-1
        USFI=ALLTRIM(LEFT(USERFILESA,UF))
        USERF=STRTRAN(USFI,",")
        DO LIGHTCOL
        @ 9,17 SAY STR(INT(VAL(USERF)/(1048576)),4)+"M"
        
        UD=AT("directories",USERDIRSA)-1
        USDI=ALLTRIM(LEFT(USERDIRSA,UD))
        USERD=STRTRAN(USDI,",")
        DO LIGHTCOL
        @ 10,17 SAY STR(INT(VAL(USERD)/(1048576)),4)+"M"
        do normcol
else        
        DO X_MSG WITH "There Is No New Data Available "
ENDIF
release all
RETURN
* Œ End of PROC ICHK4_DISP *

********************************************************************************
*                              PROC ICHK3
********************************************************************************
procedure ichk3

SELECT 3
USE ISPYLIST
ZAP

APPEND FROM F:\STAFF\BIND\&T_FILE_NAME.\ICHK.300 SDF FOR AT("bytes total",FILELIST)>0
totbytesB=alltrim(ISPYLIST->FILELIST)
APPEND FROM F:\STAFF\BIND\&T_FILE_NAME.\ICHK.300 SDF FOR AT("bytes available",FILELIST)>0
avabytesB=alltrim(ISPYLIST->FILELIST)
APPEND FROM F:\STAFF\BIND\&T_FILE_NAME.\ICHK.300 SDF FOR AT("user files",FILELIST)>0
userfilesB=alltrim(ISPYLIST->FILELIST)
APPEND FROM F:\STAFF\BIND\&T_FILE_NAME.\ICHK.300 SDF FOR AT("directories",FILELIST)>0
userdirsB=alltrim(ISPYLIST->FILELIST)
        TB=AT("bytes",totbytesB)-1
        TOTB=ALLTRIM(LEFT(totbytesB,TB))
        TOTBYTS=STRTRAN(TOTB,",")
        *DO LIGHTCOL
        *@ 13,17 SAY STR(INT(VAL(TOTBYTS)/(1048576)),4)+"M"


return
* Œ End of PROC ICHK3 *

********************************************************************************
*                             PROC ICHK3_DISP
********************************************************************************
Procedure ichk3_disp

IF len(totbytesB)#0 .AND. len(avabytesB)#0 .AND. len(userfilesB)#0 .AND. len(userdirsB)#0
        
        DO LIGHTCOL
        DO CURSOFF
        DO SHADOWBOX WITH 13,15, 17, 61
        
        set color to (c_mainwb)
        @ 13, 33 SAY "Disk Stats"
        *@ 2, 23 say TOTBYTESB
        
        @ 14, 23 say AVABYTESB
        @ 15, 23 say USERFILESB
        @ 16, 23 say USERDIRSB
        
        
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
release all
RETURN
* Œ End of PROC ICHK3_DISP *

******************************************************************************
*                              PROC MEM_MENU
******************************************************************************
PROCEDURE MEM_MENU
private opt
opt=0

IF .NOT. FILE("F:\STAFF\BIND\&T_FILE_NAME.\ICHK.700")
        do holdscreen
        DO X_MSG WITH "No Memory Statistics Are Available"
        do popscreen
        return
ENDIF
do holdscreen

do while .t.
        DO SHADOWBOX WITH 15,29, 18, 48
        do cursoff
        
        set color to (c_mainwb)
        @ 15, 36 say "Memory"
        do lightcol
        @ 16, 32 PROMPT "Memory Summary" 
        @ 17, 32 PROMPT "Memory Modules"
        
        menu to opt
        
        set color to (c_mainWH)
        do case
                case OPT=1
                        do mem_con
                case OPT=2
                        do mem_mods
                case OPT=0
                        DO POPSCREEN
                        return
        endcase
enddo
release all
DO POPSCREEN
return

* Œ End of PROC MEM_MENU *
***********************************************************************************
*                            procedure mem_con
***********************************************************************************
procedure mem_con
private total_mem, free_mem
private a,a2, a3, b, b2, c, d, d2, e, f, e1, e2, e3, e4, e5, e6, e7, e8, e9, e10, e11
private tt,ll,bb,rr, t_ct
private MOD[20]

SELECT 3
USE ISPYLIST
ZAP

APPEND FROM F:\STAFF\BIND\&T_FILE_NAME.\ICHK.700 SDF FOR AT("Total memory",FILELIST)>0
total_mem=alltrim(substr(FILELIST,1,75))
*?total_mem
a=substr(total_mem,46,9)
A2=substr(total_mem,33,9)

b=ALLTRIM(strtran(a,",","")) 
B2=ALLTRIM(strtran(A2,",","")) 
*?b2
c=54352

d=val(b)+c
D2=val(b2)-c
*?d2
if len(alltrim(str(d)))<7
        e1="  "
        e2=substr(ALLTRIM(STR(d)),1,3)
        e3=","
        e3a=substr(ALLTRIM(STR(d)),4,3)
        e=e1+e2+e3+e3a
else
        e1=substr(ALLTRIM(STR(d)),1,1)
        e2=","
        e3=substr(ALLTRIM(STR(d)),2,3)
        e4=","
        e5=substr(ALLTRIM(STR(d)),5,3)
        e=e1+e2+e3+e4+e5
        
endif
*?e
if len(alltrim(str(d2)))<7
        e4="  "
        E5=substr(ALLTRIM(STR(d2)),1,3)
        E6=","
        E7=substr(ALLTRIM(STR(d2)),4,3)
        E10=E4+E5+E6+e7
else        
        E4=substr(ALLTRIM(STR(d2)),1,1)
        *?e4
        E5=","
        E6=substr(ALLTRIM(STR(d2)),2,3)
        *?e6
        E7=","
        E8=substr(ALLTRIM(STR(d2)),5,3)
        *?e8
        E10=E4+E5+E6+e7+e8
endif
*?e10
*wait
E11=strtran(total_mem,a2,e10)
total_mem=E11

f=strtran(total_mem,a,e)
total_mem=f
*?a
*?e
*?a2
*?e10
*replace all FILELIST with strtran(FILELIST,a,e) for at(a,FILELIST)>0
*replace all FILELIST with strtran(FILELIST,a2,e10) for at(a2,FILELIST)>0
*?total_mem
zap

APPEND FROM F:\STAFF\BIND\&T_FILE_NAME.\ICHK.700 SDF FOR rAT("Conventional      ",FILELIST)>0 
free_mem=alltrim(substr(FILELIST,1,75))

a=substr(free_mem,48,7)

A2=substr(free_mem,35,7)

*A3=SUBSTR(FREE_MEM,12,7)
*A4=SUBSTR(FREE_MEM,22,3)

b=ALLTRIM(strtran(a,",","")) 
B2=ALLTRIM(strtran(A2,",","")) 

c=54352

d=val(b)+c
D2=val(b2)-c

e1=substr(ALLTRIM(STR(d)),1,3)
e2=","
e3=substr(ALLTRIM(STR(d)),4,3)
e=e1+e2+e3


if len(alltrim(str(d2)))<6
        e4=" "
        E5=substr(ALLTRIM(STR(d2)),1,2)
        E6=","
        E7=substr(ALLTRIM(STR(d2)),3,3)
        E10=E4+E5+E6+e7
else
        E5=substr(ALLTRIM(STR(d2)),1,3)
        E6=","
        E7=substr(ALLTRIM(STR(d2)),4,3)
        E10=E5+E6+e7
endif
*?e10
*wait
*E8=strtran(free_mem,a2,e7)
*free_mem=E8
*K=INT(D2/1048)
*K1=ALLTRIM(STR(K))
*K2=strtran(free_mem,a4,K1)
*FREE_MEM=K2

f=strtran(free_mem,a,e)
free_mem=f

replace all FILELIST with strtran(FILELIST,a,e) for at(a,FILELIST)>0

replace all FILELIST with strtran(FILELIST,a2,e10) for at(a2,FILELIST)>0


*g=INT(D/1048)
*H1=ALLTRIM(STR(G))
*H2=strtran(free_mem,a2,H1)
*FREE_MEM=H2

APPEND FROM F:\STAFF\BIND\&T_FILE_NAME.\ICHK.700 SDF FOR AT("Upper     ",FILELIST)>0 .or. AT("Reserved",FILELIST)>0  .or. AT("Extended (XMS)",FILELIST)>0  
APPEND FROM F:\STAFF\BIND\&T_FILE_NAME.\ICHK.700 SDF FOR AT("Total Expanded (EMS)",FILELIST)>0 .or. AT("Free Expanded (EMS)",FILELIST)>0   
APPEND FROM F:\STAFF\BIND\&T_FILE_NAME.\ICHK.700 SDF FOR AT("* EMM386",FILELIST)>0   
replace all FILELIST with strtran(FILELIST," as needed","") for at(" as needed",FILELIST)>0
GO TOP

t_ct=1
do while .not. eof()
        mod[t_ct]=alltrim(substr(FILELIST,1,75))
        t_ct=t_ct+1
        if t_ct=5
                mod[t_ct]=space(75)
                t_ct=t_ct+1
        endif
        skip
        if eof()
                exit
        endif
enddo

do holdscreen
tt=6
ll=9
bb=19
rr=69
do shadowbox with tt,ll,bb,rr
set color to (c_mainwb)

title="Memory Summary"
lenlu=len(title)

@ tt,((ll+2)+(rr-12)/2)-((lenlu/2)) say title
@ tt+1,ll+3 say "Type of Memory        Total  =    Used    +    Free"
@ tt+2,ll+3 say "ÍÍÍÍÍÍÍÍÍÍÍÍÍÍÍÍ   ÍÍÍÍÍÍÍÍÍ   ÍÍÍÍÍÍÍÍÍÍ   ÍÍÍÍÍÍÍÍÍÍ"
@ bb-1,ll+3 say total_mem
set color to (c_mainw2)

t_select=achoice(tt+3,ll+3,bb-2,rr-3,mod,.t.,"",1)
release all
do popscreen
return



******************************************************************************
*                             PROC MEM_MODS
******************************************************************************
procedure mem_mods

private a,a2, a3, b, b2, c, d, d2, e, f, e1, e2, e3, e4, e5, e6, e7, e8, e9, e10, e11
private free_mem, k, k1, k2, h1, h2, g  
private tt,ll,bb,rr, t_ct
private  MOD[30]
        
SELECT 3
USE ISPYLIST
ZAP

APPEND FROM F:\STAFF\BIND\&T_FILE_NAME.\ICHK.700 SDF FOR AT("K)",FILELIST)>0  .and. at("Free   ",FILELIST)>0 
free_mem=alltrim(substr(FILELIST,1,75))

a=substr(free_mem,31,7)
A2=substr(free_mem,41,3)
A3=SUBSTR(FREE_MEM,12,7)
A4=SUBSTR(FREE_MEM,22,3)

b=ALLTRIM(strtran(a,",","")) 
B2=ALLTRIM(strtran(A3,",","")) 

c=54352

d=val(b)+c
D2=val(b2)+c

if len(str(d))<7
        e1=" "
        e2=substr(ALLTRIM(STR(d)),1,2)
        e3=","
        e4=substr(ALLTRIM(STR(d)),3,3)
        e=e1+e2+e3+e4
else
        e1=substr(ALLTRIM(STR(d)),1,3)
        e2=","
        e3=substr(ALLTRIM(STR(d)),4,3)
        e=e1+e2+e3
endif

E4=substr(ALLTRIM(STR(d2)),1,3)
E5=","
E6=substr(ALLTRIM(STR(d2)),4,3)
E7=E4+E5+E6
E8=strtran(free_mem,a3,e7)
free_mem=E8
K=INT(D2/1048)
K1=ALLTRIM(STR(K))
K2=strtran(free_mem,a4,K1)
FREE_MEM=K2

f=strtran(free_mem,a,e)
free_mem=f

g=INT(D/1048)
H1=ALLTRIM(STR(G))
H2=strtran(free_mem,a2,H1)
FREE_MEM=H2


ZAP
APPEND FROM F:\STAFF\BIND\&T_FILE_NAME.\ICHK.700 SDF FOR RAT("K)",FILELIST)>0 .and. at("ICHK",FILELIST)=0 .and. at("Total",FILELIST)=0 .and. at("Free",FILELIST)=0 .and. at("Largest",FILELIST)=0
GO TOP

t_ct=1
do while .not. eof()
        mod[t_ct]=alltrim(substr(FILELIST,1,75))
        t_ct=t_ct+1
        skip
        if eof()
                exit
        endif
enddo

do holdscreen
tt=6
ll=5
bb=20
rr=74
do shadowbox with tt,ll,bb,rr
set color to (c_mainwb)

*t_LOG_user=ALLTRIM(t_log_user)
title="Memory Modules"
lenlu=len(title)
*lenlu=len(t_log_user)

*@ 18,ll+3 say "Log History"
*@ tt,((ll)+(rr-ll)/2)-((lentl/3)) say title

@ tt,((ll+3)+(rr-12)/2)-((lenlu/2)) say title
@ tt+1,ll+3 say "Name             Total     =   Conventional   +   Upper Memory"
@ tt+2,ll+3 say "ÍÍÍÍÍÍÍÍ   ÍÍÍÍÍÍÍÍÍÍÍÍÍÍÍ   ÍÍÍÍÍÍÍÍÍÍÍÍÍÍÍÍ   ÍÍÍÍÍÍÍÍÍÍÍÍÍÍÍÍ"
@ bb-1,ll+3 say free_mem
set color to (c_mainwh)

t_select=achoice(tt+3,ll+3,bb-2,rr-3,mod,.t.,"",1)
release all
do popscreen
return

* Œ End of PROC MEM_MODS *


********************************************************************************
*                               PROC LOG_MENU
********************************************************************************
procedure LOG_MENU
do holdscreen
opt=0

IF .NOT. FILE("F:\STAFF\BIND\&T_FILE_NAME.\ICHK.000")
        DO X_MSG WITH "Station Log Not Available"
        do popscreen
        return
ENDIF

do while .t.

DO SHADOWBOX WITH 12,28, 17, 49
do cursoff

set color to (c_mainwb)
@ 12, 34 say "Station Log"
do lightcol
@ 13, 30 PROMPT "Log History (User)" 
@ 14,30 prompt  "Log History (All) "
@ 15, 30 PROMPT "Last Logged User  " 
@ 16,30 prompt  "Log Maintenance   "
menu to opt
set color to (c_mainWH)
   
   do case
        case OPT=1
                do LOG_USR with "ICHK.000"
        case OPT=2
                t_log_user="All Users"
                do view_all WITH "ICHK.000"
        case OPT=3
                do Last_User with ""
                do l_user_disp
        case OPT=4
                do log_maint
        case OPT=0
                release all
                DO POPSCREEN
                return
        endcase
enddo
DO POPSCREEN
return
* Œ End of PROC LOG_MENU *


******************************************************************************
procedure view_all

PARAMETERS LGFILENAME

private t_ct,tt,ll,bb,rr

private  log_aray[175]
select 4
use log
zap

append all FROM F:\STAFF\BIND\&T_FILE_NAME.\&LGFILENAME. delimited with , 

REPLACE log_user WITH strtran(log_user,'"',"") for .t.
REPLACE log_DATE WITH strtran(log_date,'"',"") for .t.
REPLACE log_TIME WITH strtran(log_TIME,'"',"") for .t.
go top

t_ct=1
do while .not. eof()
        log_aray[t_ct]=substr(log_user,1,16)+"  "+substr(log_DATE,1,10)+"  "+substr(log_TIME,1,8)
        t_ct=t_ct+1
        skip
        if eof()
                exit
        endif
enddo

tt=6
ll=18
bb=18
rr=59

do holdscreen
do shadowbox with tt,ll,bb,rr
set color to (c_mainwb)
*t_log_user="All Users"
t_LOG_user=ALLTRIM(t_log_user)

lenlu=len(t_log_user)

@ bb,ll+2 say "Log History"
@ bb,48 say "Entries:"+alltrim(str(t_ct))
@ tt,((ll)+(rr-ll)/2)-((lenlu/2)) say t_log_user
@ tt+1,ll+2 say "User Name         Date        Time"
@ tt+2,ll+2 say "ÄÄÄÄÄÄÄÄÄÄÄÄÄÄÄÄ  ÄÄÄÄÄÄÄÄÄÄ  ÄÄÄÄÄÄÄÄ"
set color to (c_mainwh)

t_select=achoice(tt+3,ll+2,bb-1,rr-2,log_aray,.t.,"",1)
release all
do popscreen
return


******************************************************************************
*                             PROC LAST_USER
******************************************************************************
procedure Last_User
parameters log_opt
private lu,dt,tm

if log_opt=""
endif

if log_opt="2"
        SELECT 4
        USE log
        ZAP

        APPEND all FROM F:\STAFF\BIND\&T_FILE_NAME.\ICHK.000 delimited with , 
        go bottom
        
        userinfo=alltrim(log_user)+","+alltrim(log_date)+","+alltrim(log_time)
        return
endif

if userinfo=""
        SELECT 4
        USE log
        ZAP

        APPEND all FROM F:\STAFF\BIND\&T_FILE_NAME.\ICHK.000 delimited with , 
        go bottom
        lu=alltrim(strtran(log_user,'"',""))
        dt=strtran(log_date,'"',"")
        tm=strtran(log_time,'"',"")
        userinfo=lu+"  "+dt+" "+tm
endif
release all
return
* Œ End of PROC LAST_USER *

******************************************************************************
procedure l_user_disp
do y_msg with "Last User", userinfo
return
******************************************************************************
*                             PROC LOG_USR
******************************************************************************
procedure LOG_USR
parameters lgfilename

SELECT 4
USE log 
zap
        
APPEND all FROM F:\STAFF\BIND\&T_FILE_NAME.\&lgfilename. delimited with , 

REPLACE log_user WITH strtran(log_user,'"',"") FOR .T.
REPLACE log_DATE WITH strtran(log_DATE,'"',"") FOR .T.
REPLACE log_TIME WITH strtran(log_TIME,'"',"") FOR .T.

*set unique off
*if .not. file("F:\STAFF\BIND\&T_FILE_NAME.\logndx1.tmp")
*        index on log_user+log_date to F:\STAFF\BIND\&T_FILE_NAME.\logndx1.tmp
*else
*        if log1flag=0
*                use log index F:\STAFF\BIND\&T_FILE_NAME.\logndx1.tmp
*                reindex
*                log1flag=1
*        endif
*endif
set unique on        

if .not. file("F:\STAFF\BIND\&T_FILE_NAME.\logndx2.tmp")

        index on log_user to F:\STAFF\BIND\&T_FILE_NAME.\logndx2.tmp
        use log index F:\STAFF\BIND\&T_FILE_NAME.\logndx2.tmp
else
        use log index F:\STAFF\BIND\&T_FILE_NAME.\logndx2.tmp
        reindex
endif


B_DIR=NPOPUPc(7,13,16,26,"log->log_user",1,"Logged Users")
if recno()=1 .and. eof()
        DO X_MSG WITH "There Are No User Logs Available"
        return
endif

IF B_DIR#0 
        go top
        skip B_DIR-1
        T_log_user = log->log_user
        use
        do LOG_HIST WITH LGFILENAME,T_LOG_USER
ENDIF
*release all
return
* Œ End of PROC LOG_USR *


******************************************************************************
*                              PROC LOG_HIST
******************************************************************************
procedure LOG_HIST
PARAMETERS LGFILENAME,t_log_user
private log_aray[255]
private t_ct
*LOG_FILT=T_LOG_USER

select 4
use log
zap

append all FROM F:\STAFF\BIND\&T_FILE_NAME.\&LGFILENAME. delimited with , 
*t_log_user=strtran(log_user,'"',"")
*t_log_DATE=strtran(log_user,'"',"")
*t_log_TIME=strtran(log_TIME,'"',"")

REPLACE log_user WITH strtran(log_user,'"',"") for .t.
REPLACE log_DATE WITH strtran(log_date,'"',"") for .t.
REPLACE log_TIME WITH strtran(log_TIME,'"',"") for .t.
go top
set filter to log->log_user=t_log_user
go top
*recall
*delete all for  while .t.


t_ct=1
do while .not. eof()
        log_aray[t_ct]=substr(log_DATE,1,10)+"  "+substr(log_TIME,1,8)
        t_ct=t_ct+1
        skip
        if eof()
                exit
        endif
enddo

do holdscreen
do shadowbox with 6,26,18,51
set color to (c_mainwb)

t_LOG_user=ALLTRIM(t_log_user)

lenlu=len(t_log_user)

@ 18,34 say "Log History"
*@ tt,((ll)+(rr-ll)/2)-((lentl/3)) say title

@ 6,((29+1)+(49-29)/2)-((lenlu/2)) say t_log_user

set color to (c_mainwh)

t_select=achoice(7,29,17,49,log_aray,.t.,"",1)
*release all
do popscreen
return


******************************************************************************
procedure log_maint

do holdscreen
lmopt=0
do while .t.

DO SHADOWBOX WITH 7,38, 12, 60
do cursoff

set color to (c_mainwb)
@ 7, 42 say "Log Maintenance"
do lightcol
@ 8, 41 PROMPT "Backup Log       " 
@ 9,41 PROMPT  "Restore Last Log " 
@ 10,41 PROMPT "Delete Old Log(s)" 
@ 11,41 prompt "View Old Logs    "

menu to lmopt
set color to (c_mainWH)
   
   do case
        case lmOPT=1
                select 4
                use log
                zap
                append all FROM F:\STAFF\BIND\&T_FILE_NAME.\ICHK.000 delimited with , 
                IF RECCOUNT()>50
                        do LOG_Bkup
                ELSE
                        DO X_MSG WITH "No backup available, Log is less than 50 entries"
                endif
        case lmOPT=2
                select 4
                use log
                zap
                append all FROM F:\STAFF\BIND\&T_FILE_NAME.\ICHK.000 delimited with , 
                IF RECCOUNT()<3
                        do LOG_REST
                ELSE
                        DO X_MSG WITH "Last log not restored, current log has entries"
                endif
                
        case lmOPT=3
                do del_logs
        case lmOPT=4
                do view_logs
        case lmOPT=0
                release all
                DO POPSCREEN
                return
        endcase
enddo
DO POPSCREEN
return


*****************************************************************************
procedure del_logs
private  DEL_ARAY[10]

*x=.t.

*do while .not. x

run DIR F:\STAFF\BIND\&T_FILE_NAME.\ichk.00?>DIRFILE.TMP
*if ferror() .or. doserror()
*        ? doserror()
*        ? ferror()
*        wait
*else
*        x=.t.
*endif
*enddo


SELECT 2
USE DIRFILE
ZAP
APPEND ALL FROM DIRFILE.TMP SDF FOR DIR_NAME#"<DIR>".and. at("ICHK",file_name)>0 .AND. AT("000",FILE_NAME)=0

if reccount()<1
        do x_msg with "There are no logs are available"
        return
endif

GO TOP
*REPLACE FILE_NAME WITH STRTRAN(FILE_NAME,"    ","LOG") FOR .T.
*?FILE_NAME
*?FILE_DATE
*WAIT
t_ct=1
do while .not. eof()
        DEL_ARAY[t_ct]=STRTRAN(FILE_NAME,"    ","LOG")+"  "+ DIRFILE->FILE_DATE
        t_ct=t_ct+1
        skip
        if eof()
                exit
        endif
enddo
*FOR N=1 TO 2
*?DEL_ARAY[N]
*NEXT

ASORT(DEL_ARAY)

do holdscreen

tt=5
ll=5
bb=10
rr=29
do shadowbox with tt,ll,bb,rr
set color to (c_mainwb)

title="Delete Logs"
lenlu=len(title)
@ tt,((ll+3)+(rr-12)/2)-((lenlu/3)) say title
set color to (c_mainwh)
t_sel=achoice(tt+1,ll+2,bb-1,rr-2,DEL_ARAY,.t.,"",1)

if t_sel#0
        go top
        skip t_sel-1
        *?del_aray[t_sel]
        delfile=substr(del_aray[t_sel],1,12)
        delfile=strtran(delfile,"LOG ",".")
        
        *? "F:\STAFF\BIND\&T_FILE_NAME.\"+delfile
        
        erase F:\STAFF\BIND\&T_FILE_NAME.\&delfile. 
        if delfile="ICHK.001"
        else
                release all
                do del_logs
        endif

endif
release all
do popscreen

return

* Œ End of PROC DEL_LOGS *


******************************************************************************
procedure view_logs
public lfile1,lfile2
private  oL_ARAY[10]
*menuflag=0
*x=.t.

*do while .not. x

run DIR F:\STAFF\BIND\&T_FILE_NAME.\ichk.00?>DIRFILE.TMP

*if ferror() .or. doserror()
*        ? doserror()
*        ? ferror()
*        wait
*else
*        x=.t.
*endif

*enddo



SELECT 2
USE DIRFILE
ZAP
APPEND ALL FROM DIRFILE.TMP SDF FOR DIR_NAME#"<DIR>".and. at("ICHK",file_name)>0 .AND. AT("000",FILE_NAME)=0

if reccount()<1
        do x_msg with "There are no logs are available"
        return
endif

GO TOP
*REPLACE FILE_NAME WITH STRTRAN(FILE_NAME,"    ","LOG") FOR .T.
*?FILE_NAME
*?FILE_DATE
*WAIT
t_ct=1
do while .not. eof()
        oL_ARAY[t_ct]=STRTRAN(FILE_NAME,"    ","LOG")+"  "+ DIRFILE->FILE_DATE
        t_ct=t_ct+1
        skip
        if eof()
                exit
        endif
enddo

*FOR N=1 TO 2
*?oL_ARAY[N]
*NEXT
*wait
ASORT(oL_ARAY)

do holdscreen
do while .t.
*DECLARE oL_ARAY[10]
t_log_user=""
tt=5
ll=5
bb=10
rr=29
do shadowbox with tt,ll,bb,rr
set color to (c_mainwb)

title="Old Logs"
lenlu=len(title)
@ tt,((ll+3)+(rr-12)/2)-((lenlu/3)) say title
set color to (c_mainwh)

t_sel=achoice(tt+1,ll+2,bb-1,rr-2,oL_ARAY,.t.,"",1)

if t_sel#0
        go top
        skip t_sel-1
        *?ol_aray[t_sel]
        lfile1=alltrim(substr(ol_aray[t_sel],1,12))
        lfile2=strtran(lfile1,"LOG ",".")
        
        *? "F:\STAFF\BIND\&T_FILE_NAME.\"+lfile2
        release all except ol_aray*
        do view_logs_menu with lfile1, lfile2
        *menuflag=1
else
        exit
endif        
enddo
release all like ol_aray*
do popscreen
return
******************************************************************************
procedure view_logs_menu
parameters lfile1, lfile2

*?lfile2
*wait
*if menuflag=1        
        olgmenu=0
        do holdscreen
        
        do while .t.
        do shadowbox with 9,20,12,41
        do cursoff
        set color to (c_mainwb)
        @ 9, 26 say lfile1
        
        set color to (c_mainWH)
        @ 10, 22 prompt "Log History (User)"
        @ 11, 22 prompt "Log History (All) "
        
        
        menu to olgmenu
        
        do case
                case olgmenu=1
                        *do holdscreen
                        do log_usr with "&lfile2."
                        *do popscreen
                
                case olgmenu=2
                        t_log_user="&lfile1."
                        LGFILENAME="&lfile2."
                        do view_all WITH LGFILENAME
                case olgmenu=0
                
                do popscreen
                return
        endcase
        enddo
*endif        
do popscreen
return
 


******************************************************************************
procedure log_rest

private  N[10]
N=9
*chkfile="F:\STAFF\BIND\&T_FILE_NAME.\ICHK.00&T_N"

*IF FILE ("F:\STAFF\BIND\&T_FILE_NAME.\ICHK.000")
        
        do while .T.
                T_N=chr(48+N)
                chkfile="F:\STAFF\BIND\&T_FILE_NAME.\ICHK.00&T_N."
                
                if chkfile="F:\STAFF\BIND\&T_FILE_NAME.\ICHK.000"
                        do x_msg with "No Backup log was found"
                        exit
                endif
                
                IF FILE (chkfile)
                        do x_msg with "Restoring backup file "+chkfile
                        erase  F:\STAFF\BIND\&T_FILE_NAME.\ICHK.000
                        RENAME  &chkfile. TO  F:\STAFF\BIND\&T_FILE_NAME.\ICHK.000
                        exit
                ELSE
                        IF N=0
                               do x_msg with "No Backup log was found"
                               exit
                        ELSE
                               N=N-1 
                        ENDIF
                ENDIF
        ENDDO
        release all
        return
        
        *do x_msg with "Error, No Log File Found To Restore"

* Œ End of PROC log_rest*

******************************************************************************
procedure log_bkup

private  N[10]
N=1
*chkfile="F:\STAFF\BIND\&T_FILE_NAME.\ICHK.00&T_N"

IF FILE ("F:\STAFF\BIND\&T_FILE_NAME.\ICHK.000")
        
        do while .T.
                T_N=chr(48+N)
                chkfile="F:\STAFF\BIND\&T_FILE_NAME.\ICHK.00&T_N."
                
                IF FILE (chkfile)
                        IF N#9
                               N=N+1
                        ELSE
                               yn=1
                               do x_aresure with yn,"Backup logs full, Delete an older log ","Yes","No"
                               if yn#1
                                        do x_msg with "No log can be generated until an older one is cleared"
                                        
                               else
                                        DO DEL_LOGs 
                               endif
                               return
                        ENDIF
                ELSE
                        EXIT
                ENDIF
        ENDDO
        do x_msg with "Generating backup file "+chkfile
        chkfile="F:\STAFF\BIND\&T_FILE_NAME.\ICHK.00&T_N."
        
        do last_user with "2"
        RENAME F:\STAFF\BIND\&T_FILE_NAME.\ICHK.000 TO &chkfile.
        
        buffer= userinfo
        outfile=fcreate("F:\STAFF\BIND\&T_FILE_NAME.\ichk.000")
        
        fwrite(outfile,buffer)
        fclose(outfile)
        
        if .not. file("F:\STAFF\BIND\&T_FILE_NAME.\ICHK.00&T_N.")
                do x_msg with "Error, Log File Not Generated"
                return
        endif
        
ELSE
        do x_msg with "Error, No Log File Found To Backup"
ENDIF
release all
return
* Œ End of PROC log_bkup*

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

private  N[10]
N=1
*chkfile="F:\STAFF\BIND\&T_FILE_NAME.\ICHK.30&T_N"

IF FILE ("F:\STAFF\BIND\&T_FILE_NAME.\ICHK.300")
        
        do while .T.
                T_N=chr(48+N)
                chkfile="F:\STAFF\BIND\&T_FILE_NAME.\ICHK.30&T_N"
                
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
        
        RENAME F:\STAFF\BIND\&T_FILE_NAME.\ICHK.300 TO &CHKFILE.
        RENAME F:\STAFF\BIND\&T_FILE_NAME.\ICHK.400 TO F:\STAFF\BIND\&T_FILE_NAME.\ICHK.300
ELSE

ENDIF
return
* Œ End of PROC VERIFY *

*********************************************************************************************************
procedure os_menu

opt=0
do holdscreen
IF .NOT. FILE("F:\STAFF\BIND\&T_FILE_NAME.\ichk.900")
        DO X_MSG WITH "No O/S Statistics Are Available"
        do popscreen
        return
ENDIF

select 3
use ISPYLIST
zap
append from F:\STAFF\BIND\&T_FILE_NAME.\ichk.900 sdf for at("Windows",FILELIST)>0
Winver=alltrim(ISPYLIST->FILELIST)
append from F:\STAFF\BIND\&T_FILE_NAME.\ichk.900 sdf for at("DOS",FILELIST)>0
Dosver=alltrim(ISPYLIST->FILELIST)

do while .t.
        DO SHADOWBOX WITH 8,22, 15, 55
        do cursoff
        set color to (c_mainwb)
        @ 11,23 to 11,54
        @ 8, 27 say "Operating Systems & Apps"
        set color to (c_mainwh)
        x=9
        if len(Winver)#0
                @ x,25 say "Located "+winver
                x=x+1
        else
                @ x,25 say "No Local Version of Windows"
                x=x+1
        endif
        
        if len(dosver)#0
                @ x,25 say "Running "+dosver
        else
                @ x,25 say "No Version of Dos found"
        endif
        do lightcol
        
        @ 12, 27 PROMPT "Extentions Database  "
        @ 13, 27 PROMPT "Applications Database" 
        @ 14, 27 PROMPT "Begin Search         " 
        menu to opt
        
        set color to (c_mainWH)
        do case
                case OPT=1
                        select 5 
                        use ap
                        set filter to
                        do myb
                        use
                case OPT=2
                        select 5 
                        use ap2
                        set filter to
                        do myb
                        use
                case OPT=3
                        CHK_F=".f. "
                        REP_CHK=SPACE(3)
                        do apsearch
                        if readkey()=12 .OR. REP_CHK=SPACE(3)
                                
                        ELSE
                                DO FILE_LIST
                        ENDIF

                case OPT=0
                        release all
                        DO POPSCREEN
                        return
        endcase
enddo
* Œ End of PROC OS_MENU *

******************************************************************

procedure scrdis
@ 3, 0 SAY REPLICATE(CHR(176),1520)

DO panelbox WITH 0,0,3,79
DO PANELBOX WITH 22,0,24,79
set color to (c_mainwb)
@ 1,18 say "ISPY 1.2  LAN MONITORING UTILITY  (C) 1996"
@ 2,3 say "USER:          "
@ 23,3 SAY "ADDRESS:                    "

user=gete("WHOIAM")
user=substr(user,1,10)
set color to (c_mainwh)
@ 2,9 say user
@ 2,35 say "THERMAL"
@ 2,55 say "NetWare v3.12 (50 user)"
RETURN



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
public c_mainbl,c_mainwh,c_mainwb,c_mainno,c_whbl,c_blwh,c_pass,C_MAINXX,c_mainw2
c_mainbl="G/B+,W/B,B,B,N/BG"
*c_mainx2="G/B+,B+/W,B,B,N/BG"
*c_mainbl="GR+/B,W/BG,B,B,N/BG"
c_mainXX="GR+/B,W/B,B,B,N/BG"
*c_mainwh="GR+/W,W/RB,W,B,N/BG"
c_mainwh="GR+/W,W/B,W,B,N/BG"
c_mainw2="GR+/W,GR+/W,W,B,N/BG"
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


******************************************************************************
*                         procedure x_aresure
******************************************************************************
procedure x_aresure
parameters vv,msg,msgy,msgn
do holdscreen
t_len=len(msg)
if t_len>74
   msg=substr(msg,1,74)
   t_len=len(msg)
endif
t_left=32-int(t_len/2)
t_right=t_left+t_len+2+7    &&  +2 is for " ?"

do shadowbox with 19,t_left,21,t_right+2
c_temp=setcolor()

set color to (c_mainwh)
@ 20,t_left+2 say msg+"?"
@ 20,t_left+2+t_len+2 prompt "Yes" &&     message substr(msgy+space(80),1,80)
@ 20,t_left+2+t_len+6 prompt "No"  &&     message substr(msgn+space(80),1,80)

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
lt=len(titles)
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

@ 13,44-(lt/2)-4 say titles
set color to (c_mainwh)
@ 14,t_left+2 say t_text

do while inkey()#27 
enddo 

set key 27 to

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


procedure apsearch
*yn=1
*DO X_ARESURE WITH yn,"Select Report Filter" ,"Yes","No"
*if YN=1
        DO HOLDSCREEN
        do curson
        do lightcol
        do shadowbox with 18,29,20,49
        @ 19,30 say "  Apps Filter: "
        @ 19,45 get REP_CHK picture '!!!'
        clear gets
        *read
        
        SELECT 5
        use ap
        
        T_CC=0
        T_CC=NPOPUPC(19,45,23,48,"ap->rep",1,"")

        IF T_CC#0 
                        go top
                        skip T_CC-1
                        rep_Chk = ap->rep
                        @  19, 45  GET  rep_Chk
                        READ
        else
                do popscreen
                return
        ENDIF   
                
        
        DO POPSCREEN
        DO CURSOFF        
        if readkey()=12 .OR. REP_CHK=SPACE(3)
                return
        ENDIF
        
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
                if ap->ap1#space(3)
                        NUMAC=NUMAC+" .OR. AT("+'" '+ap->ap1+'"'+",FILELIST)>0"
                                *"COST_CENT='"+ap->ap1+"'"
                        *?NUMAC
                ENDIF
                if ap->ap2#space(3)
                        NUMAC=NUMAC+" .OR. AT("+'" '+ap->ap2+'"'+",FILELIST)>0"
                        *?NUMAC        
                ENDIF
                if ap->ap3#space(3)
                         NUMAC=NUMAC+" .OR. AT("+'" '+ap->ap3+'"'+",FILELIST)>0"
                        *NUMAC=NUMAC+" .OR. COST_CENT='"+ap->ap3+"'"
                        *?NUMAC
                ENDIF
                if ap->ap4#space(3)
                        NUMAC=NUMAC+" .OR. AT("+'" '+ap->ap4+'"'+",FILELIST)>0"
                        *?NUMAC
                ENDIF
                if ap->ap5#space(3)
                        NUMAC=NUMAC+" .OR. AT("+'" '+ap->ap5+'"'+",FILELIST)>0"
                        *?NUMAC
                ENDIF
                if ap->ap6#space(3)
                        NUMAC=NUMAC+" .OR. AT("+'" '+ap->ap6+'"'+",FILELIST)>0"
                        *?NUMAC
                ENDIF
                if ap->ap7#space(3)
                        NUMAC=NUMAC+" .OR. AT("+'" '+ap->ap7+'"'+",FILELIST)>0"
                        *?NUMAC
                ENDIF
                if ap->ap8#space(3)
                        NUMAC=NUMAC+" .OR. AT("+'" '+ap->ap8+'"'+",FILELIST)>0"
                        *?NUMAC
                ENDIF
                if ap->ap9#space(3)
                        NUMAC=NUMAC+" .OR. AT("+'" '+ap->ap9+'"'+",FILELIST)>0"
                        *?NUMAC
                ENDIF
                *WAIT
                IF LEN(NUMAC)#0   && i.e. somebody has created a bogus report
                        NUMAC=SUBSTR(NUMAC,7,LEN(NUMAC))    && REMOVE FIRST " .OR. "
                        NUMAC=" .OR. "+NUMAC+" .OR. AT("+'"'+"Directory of"+'"'+",FILELIST)>0"
                        CHK_F=CHK_F+NUMAC    && ADD CONDITION TO MAIN CHK_F CHECK
                        *?CHK_F
                ELSE
                        DO P_MSG WITH "ERROR: Check Report filter setup !"
                        do popscreen
                        return
                ENDIF
        ELSE        
                DO CURSOFF
                do p_msg with "Report filter '"+t_REP_CHK+"' does not exist!"
                do popscreen
                return
        endif
return

