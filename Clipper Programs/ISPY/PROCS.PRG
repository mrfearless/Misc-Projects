*:*****************************************************************************
*:
*: Procedure file: C:\LANGS\DBASE\PROGRAMS\COSTING\COILCOS2\PROCS.PRG
*:
*:         System: Commercial Costing Program
*:         Author: Barry O'Shea
*:      Copyright (c) 1995, Thermal Heat Exchangers (Irl) Ltd.
*:  Last modified: 05/18/95      9:14
*:
*:  Procs & Fncts: LEFT_ARROW
*:               : RIGHT_ARROW
*:               : ESC_KEY
*:               : CTRL_W
*:               : SHADOWBOX
*:               : HOLDSCREEN
*:               : POPSCREEN
*:               : P_ARESURE
*:               : P_MSG
*:               : P_NOTE
*:               : RIGHT()
*:               : PADOUT()
*:               : LINES()
*:               : P_INTRO
*:               : P_BIGLETT
*:               : P_BIGMSG
*:               : READVAR
*:               : MYSTUFF()
*:               : MYBROWSE()
*:               : XMYBROWSE()
*:               : STATLINE()
*:               : GET_PIC()
*:               : PAD()
*:               : CLEAR_GETS
*:               : AMPM()
*:               : STRZERO()
*:               : DAYS()
*:               : DBF()
*:               : ELAPTIME()
*:               : FKLABEL()
*:               : FKMAX()
*:               : LENNUM()
*:               : MOD()
*:               : OS()
*:               : READKEY()
*:               : SECS()
*:               : TSTRING()
*:               : VERSION()
*:               : COPYFIELD
*:               : RENFIELD()
*:               : LIGHTCOL
*:               : NORMCOL
*:               : P_COLVARS
*:               : CURSON
*:               : CURSOFF
*:               : MYB
*:               : P_REPDISP
*:               : P_PGBRKCSV
*:               : P_READREP
*:               : PRTLINE()
*:               : P_SETPATH
*:
*:      Documented 05/22/95 at 16:21                SNAP!  version 5.02
*:*****************************************************************************

*******************************************************************
* left_arrow
*!*****************************************************************************
*!
*!      Procedure: LEFT_ARROW
*!
*!          Calls: CHR()              (function  in ?)
*!
*!*****************************************************************************
procedure left_arrow

parameter pn, pl, rv

* return, up arrow, return
keyboard chr(27) + chr(5) + chr(13)

return
*EOP left_arrow

*******************************************************************
* right_arrow
*!*****************************************************************************
*!
*!      Procedure: RIGHT_ARROW
*!
*!          Calls: CHR()              (function  in ?)
*!
*!*****************************************************************************
procedure       right_arrow

parameter       pn, pl, rv

* return, right arrow, return
keyboard chr(27) + chr(4) + chr(13)

return
* EOP right_arrow

* EOF MENU.PRG
*******************************************************************
*!*****************************************************************************
*!
*!      Procedure: ESC_KEY
*!
*!      Called by: P_MSG              (procedure in PROCS.PRG)
*!
*!          Calls: CHR()              (function  in ?)
*!
*!*****************************************************************************
procedure       esc_key
parameter       pn, pl, rv
keyboard chr(27)
return
*******************************************************************
*!*****************************************************************************
*!
*!      Procedure: CTRL_W
*!
*!          Calls: CHR()              (function  in ?)
*!
*!*****************************************************************************
procedure       ctrl_w
parameter       pn, pl, rv
keyboard chr(23)       && ACSII 23 ->     ^W
return
*******************************************************************

*!*****************************************************************************
*!
*!      Procedure: SHADOWBOX
*!
*!      Called by: CCOST.PRG                         
*!               : INTRATE            (procedure in CCOST.PRG)
*!               : COSTC              (procedure in CCOST.PRG)
*!               : AGPRICES           (procedure in CCOST.PRG)
*!               : P_ARESURE          (procedure in PROCS.PRG)
*!               : P_MSG              (procedure in PROCS.PRG)
*!               : P_NOTE             (procedure in PROCS.PRG)
*!               : READVAR            (procedure in PROCS.PRG)
*!               : MYB                (procedure in PROCS.PRG)
*!               : P_REPDISP          (procedure in PROCS.PRG)
*!               : P_SETPATH          (procedure in PROCS.PRG)
*!
*!          Calls: SETCOLOR()         (function  in ?)
*!               : SPACE()            (function  in ?)
*!
*!*****************************************************************************
procedure shadowbox
parameters y1,x1,y2,x2
c_temp=setcolor()
set color to (c_whbl)

vert = SAVESCREEN(y1+1,x2+1,y2+1,x2+2)
v = TRANSFORM(vert,REPLICATE("X"+chr(07),LEN(vert)))

horiz = SAVESCREEN(y2+1,x1+2,y2+1,x2+2)
h = TRANSFORM(horiz,REPLICATE("X"+chr(07),LEN(horiz)))

RESTSCREEN(y1+1,x2+1,y2+1,x2+2,v)
RESTSCREEN(y2+1,x1+2,y2+1,x2+2,h)

set color to (c_mainwb)
@ y1,x1 to y2,x2
for n=1 to (y2-y1)-1
   @ y1+n,x1+1 say space((x2-x1)-1)
next

set color to (c_whbl)

*for n=1 to (y2-y1)+1
*   @ y1+n,x2+1 say space(2)
*next
*@ y2+1,x1+2 say space (x2-x1)

set color to (c_temp)
return
* EOP SHADOWBOX

****************************************************************
*!*****************************************************************************
*!
*!      Procedure: HOLDSCREEN
*!
*!      Called by: INTRATE            (procedure in CCOST.PRG)
*!               : COSTC              (procedure in CCOST.PRG)
*!               : AGPRICES           (procedure in CCOST.PRG)
*!               : CRTCODE            (procedure in CCOST1.PRG)
*!               : MODCODE            (procedure in CCOST3.PRG)
*!               : EXPORTDRG          (procedure in CCOST7.PRG)
*!               : P_ARESURE          (procedure in PROCS.PRG)
*!               : P_MSG              (procedure in PROCS.PRG)
*!               : P_NOTE             (procedure in PROCS.PRG)
*!               : READVAR            (procedure in PROCS.PRG)
*!               : MYB                (procedure in PROCS.PRG)
*!               : P_REPDISP          (procedure in PROCS.PRG)
*!               : P_SETPATH          (procedure in PROCS.PRG)
*!
*!*****************************************************************************
procedure holdscreen
screenct=screenct+1
save    screen to   screens[SCREENCT]
return

****************************************************************
*!*****************************************************************************
*!
*!      Procedure: POPSCREEN
*!
*!      Called by: INTRATE            (procedure in CCOST.PRG)
*!               : COSTC              (procedure in CCOST.PRG)
*!               : AGPRICES           (procedure in CCOST.PRG)
*!               : CRTCODE            (procedure in CCOST1.PRG)
*!               : MODCODE            (procedure in CCOST3.PRG)
*!               : EXPORTDRG          (procedure in CCOST7.PRG)
*!               : P_ARESURE          (procedure in PROCS.PRG)
*!               : P_MSG              (procedure in PROCS.PRG)
*!               : READVAR            (procedure in PROCS.PRG)
*!               : MYB                (procedure in PROCS.PRG)
*!               : P_REPDISP          (procedure in PROCS.PRG)
*!               : P_SETPATH          (procedure in PROCS.PRG)
*!
*!*****************************************************************************
procedure popscreen
restore screen from screens[SCREENCT]
screens[SCREENCT]=""
screenct=screenct-1
return

****************************************************************

*code procedures
****************************************************************
*!*****************************************************************************
*!
*!      Procedure: P_ARESURE
*!
*!      Called by: ACCPT              (procedure in CCOST.PRG)
*!               : CRTCODE            (procedure in CCOST1.PRG)
*!               : MODCODE            (procedure in CCOST3.PRG)
*!               : EXPORTDRG          (procedure in CCOST7.PRG)
*!               : P_REPDISP          (procedure in PROCS.PRG)
*!
*!          Calls: HOLDSCREEN         (procedure in PROCS.PRG)
*!               : LEN()              (function  in ?)
*!               : SUBSTR()           (function  in ?)
*!               : INT()              (function  in ?)
*!               : SHADOWBOX          (procedure in PROCS.PRG)
*!               : SETCOLOR()         (function  in ?)
*!               : SPACE()            (function  in ?)
*!               : POPSCREEN          (procedure in PROCS.PRG)
*!
*!*****************************************************************************
procedure p_aresure
parameters vv,msg,msgy,msgn
do holdscreen
t_len=len(msg)
if t_len>74
   msg=substr(msg,1,74)
   t_len=len(msg)
endif
t_left=37-int(t_len/2)
t_right=t_left+t_len+2+1    &&  +2 is for " ?"

do shadowbox with 12,t_left,15,t_right
c_temp=setcolor()
set color to (c_mainwh)
@ 13,t_left+1 say msg+" ?"
@ 14,36 prompt "Yes"      message substr(msgy+space(80),1,80)
@ 14,40 prompt "No"       message substr(msgn+space(80),1,80)
menu to vv
set color to (c_temp)
do popscreen
return
******************************************************************

*!*****************************************************************************
*!
*!      Procedure: P_MSG
*!
*!      Called by: CCOST.PRG                         
*!               : AGPRICES           (procedure in CCOST.PRG)
*!               : CRTCODE            (procedure in CCOST1.PRG)
*!               : DELCODE            (procedure in CCOST2.PRG)
*!               : MODCODE            (procedure in CCOST3.PRG)
*!               : DISCODE            (procedure in CCOST4.PRG)
*!               : RDX                (procedure in CCOST5.PRG)
*!               : IMPORT             (procedure in CCOST6.PRG)
*!               : EXPORTDRG          (procedure in CCOST7.PRG)
*!               : P_REPDISP          (procedure in PROCS.PRG)
*!
*!          Calls: SETCOLOR()         (function  in ?)
*!               : HOLDSCREEN         (procedure in PROCS.PRG)
*!               : LTRIM()            (function  in ?)
*!               : RTRIM()            (function  in ?)
*!               : LEN()              (function  in ?)
*!               : SHADOWBOX          (procedure in PROCS.PRG)
*!               : ESC_KEY            (procedure in PROCS.PRG)
*!               : INKEY()            (function  in ?)
*!               : POPSCREEN          (procedure in PROCS.PRG)
*!
*!*****************************************************************************
procedure p_msg
parameters t_msg

private c_temp
c_temp=setcolor()
do holdscreen
e_msg=ltrim(rtrim(t_msg))

t_len=len(e_msg)

t_left=(39-(t_len/2))-3
t_right=(t_left+t_len)+3

*T_lines=MLCOUNT(E_MSG,((T_right-1)-(T_left+1)))
t_top=12
*-T_lines/2
t_bot=15
*T_top+T_lines+1
do shadowbox with t_top,t_left,t_bot+1,t_right
set color to (c_mainwh)
@ t_bot,26 say "Press       to Continue"
set color to (c_mainbl)
@ t_bot,32 say v_enter
set color to (c_mainwh)

t_text=e_msg
set key 13 to esc_key
*T_text=MEMOEDIT(T_text,T_top+1,T_left+1,T_bot-1,T_right-1,.f.)
@ 13,t_left+2 say t_text
do while inkey()!=13
enddo
set key 13 to
do popscreen
set color to (c_temp)
return
**************************************************************
*!*****************************************************************************
*!
*!      Procedure: P_NOTE
*!
*!      Called by: P_REPDISP          (procedure in PROCS.PRG)
*!
*!          Calls: SETCOLOR()         (function  in ?)
*!               : HOLDSCREEN         (procedure in PROCS.PRG)
*!               : LTRIM()            (function  in ?)
*!               : RTRIM()            (function  in ?)
*!               : LEN()              (function  in ?)
*!               : SHADOWBOX          (procedure in PROCS.PRG)
*!
*!*****************************************************************************
procedure p_note


*
* this leaves a note on the screen - stores the screen but does not pop
* Call the POPSCREEN function after.
*
parameters t_msg

private c_temp
c_temp=setcolor()
do holdscreen
e_msg=ltrim(rtrim(t_msg))

t_len=len(e_msg)

t_left=(39-(t_len/2))-3
t_right=(t_left+t_len)+3

*T_lines=MLCOUNT(E_MSG,((T_right-1)-(T_left+1)))
t_top=12
*-T_lines/2
t_bot=14
*T_top+T_lines+1
do shadowbox with t_top,t_left,t_bot,t_right
set color to (c_mainwh)
t_text=e_msg
@ 13,t_left+2 say t_text
set color to (c_temp)
return
**************************************************************

*!*****************************************************************************
*!
*!       Function: RIGHT()
*!
*!      Called by: CRTCODE            (procedure in CCOST1.PRG)
*!               : MODCODE            (procedure in CCOST3.PRG)
*!
*!          Calls: SUBSTR()           (function  in ?)
*!               : LEN()              (function  in ?)
*!
*!*****************************************************************************
function right
parameters t_string, t_len
return substr(t_string,len(t_string)-t_len+1,t_len)
**************************************************************

*!*****************************************************************************
*!
*!       Function: PADOUT()
*!
*!          Calls: SUBSTR()           (function  in ?)
*!               : SPACE()            (function  in ?)
*!
*!*****************************************************************************
function padout
parameters t_string, t_len
return substr(t_string+space(255),1,t_len)
**************************************************************
*!*****************************************************************************
*!
*!       Function: LINES()
*!
*!          Calls: LEN()              (function  in ?)
*!               : SUBSTR()           (function  in ?)
*!
*!*****************************************************************************
function lines
parameters t_string
t_string2=""
for n=1 to len(t_string)
   if substr(t_string,n,1)=" "
      t_string2=t_string2+" "
   else
      t_string2=t_string2+v_linedrw
   endif
next
return t_string2

**************************************************************
*!*****************************************************************************
*!
*!      Procedure: P_INTRO
*!
*!          Calls: REPLICATE()        (function  in ?)
*!               : P_BIGMSG           (procedure in PROCS.PRG)
*!
*!*****************************************************************************
procedure p_intro
*t_COLOR=SETCOLOR()
set color to (c_mainbl)
clear
for n=1 to 24
   @ n,0 say replicate("°",80)
next n
@ 22,73 say  "F1:HELP"
@ 23,72 say "ESC:EXIT"
*SET COLOR TO (t_COLOR)
do p_bigmsg with "THERMAL",8 ,7
*DO P_BIGMSG with "",15,7
return
************************************************************
*!*****************************************************************************
*!
*!      Procedure: P_BIGLETT
*!
*!      Called by: P_BIGMSG           (procedure in PROCS.PRG)
*!
*!          Calls: SUBSTR()           (function  in ?)
*!
*!*****************************************************************************
procedure p_biglett
parameters t_let,yyy,xxx
private letline[5]
do case
case t_let="A"
   letline[1]=" ²²²²²² "
   letline[2]="²²    ²²"
   letline[3]="²²²²²²²²"
   letline[4]="²²    ²²"
   letline[5]="²²    ²²"
case t_let="B"
   letline[1]="²²²²²²² "
   letline[2]="²²    ²²"
   letline[3]="²²²²²²² "
   letline[4]="²²    ²²"
   letline[5]="²²²²²²² "
case t_let="C"
   letline[1]=" ²²²²²² "
   letline[2]="²²    ²²"
   letline[3]="²²      "
   letline[4]="²²    ²²"
   letline[5]=" ²²²²²² "
case t_let="D"
   letline[1]="²²²²²²  "
   letline[2]="²²   ²² "
   letline[3]="²²    ²²"
   letline[4]="²²   ²² "
   letline[5]="²²²²²²  "
case t_let="E"
   letline[1]="²²²²²²²²"
   letline[2]="²²      "
   letline[3]="²²²²²²² "
   letline[4]="²²      "
   letline[5]="²²²²²²²²"
case t_let="F"
   letline[1]="²²²²²²²²"
   letline[2]="²²      "
   letline[3]="²²²²²²² "
   letline[4]="²²      "
   letline[5]="²²      "
case t_let="G"
   letline[1]=" ²²²²²² "
   letline[2]="²²     ²"
   letline[3]="²²      "
   letline[4]="²²   ²²²"
   letline[5]=" ²²²²²² "
case t_let="H"
   letline[1]="²²    ²²"
   letline[2]="²²    ²²"
   letline[3]="²²²²²²²²"
   letline[4]="²²    ²²"
   letline[5]="²²    ²²"
case t_let="I"
   letline[1]=" ²²²²²² "
   letline[2]="   ²²   "
   letline[3]="   ²²   "
   letline[4]="   ²²   "
   letline[5]=" ²²²²²² "
case t_let="J"
   letline[1]="²²²²²²²²"
   letline[2]="    ²²  "
   letline[3]="    ²²  "
   letline[4]="²²  ²²  "
   letline[5]="²²²²²²  "
case t_let="K"
   letline[1]="²²    ²²"
   letline[2]="²²   ²² "
   letline[3]="²²²²²²  "
   letline[4]="²²   ²² "
   letline[5]="²²    ²²"
case t_let="L"
   letline[1]="²²      "
   letline[2]="²²      "
   letline[3]="²²      "
   letline[4]="²²      "
   letline[5]="²²²²²²²²"
case t_let="M"
   letline[1]="²²    ²²"
   letline[2]="²²²  ²²²"
   letline[3]="²²²²²²²²"
   letline[4]="²² ²² ²²"
   letline[5]="²²    ²²"
case t_let="N"
   letline[1]="²²    ²²"
   letline[2]="²²²²  ²²"
   letline[3]="²² ²² ²²"
   letline[4]="²²   ²²²"
   letline[5]="²²    ²²"
case t_let="O"
   letline[1]=" ²²²²²² "
   letline[2]="²²    ²²"
   letline[3]="²²    ²²"
   letline[4]="²²    ²²"
   letline[5]=" ²²²²²² "
case t_let="P"
   letline[1]="²²²²²²² "
   letline[2]="²²    ²²"
   letline[3]="²²²²²²² "
   letline[4]="²²      "
   letline[5]="²²      "
case t_let="Q"
   letline[1]=" ²²²²²² "
   letline[2]="²²    ²²"
   letline[3]="²²    ²²"
   letline[4]="²²   ²² "
   letline[5]=" ²²²²² ²"
case t_let="R"
   letline[1]="²²²²²²² "
   letline[2]="²²    ²²"
   letline[3]="²²²²²²  "
   letline[4]="²²   ²² "
   letline[5]="²²    ²²"
case t_let="S"
   letline[1]=" ²²²²²²²"
   letline[2]="²²      "
   letline[3]="²²²²²²²²"
   letline[4]="      ²²"
   letline[5]="²²²²²²² "
case t_let="T"
   letline[1]="²²²²²²²²"
   letline[2]="   ²²   "
   letline[3]="   ²²   "
   letline[4]="   ²²   "
   letline[5]="   ²²   "
case t_let="U"
   letline[1]="²²    ²²"
   letline[2]="²²    ²²"
   letline[3]="²²    ²²"
   letline[4]="²²    ²²"
   letline[5]=" ²²²²²² "
case t_let="V"
   letline[1]="²²    ²²"
   letline[2]="²²²  ²²²"
   letline[3]=" ²²  ²² "
   letline[4]="  ²²²²  "
   letline[5]="   ²²   "
case t_let="W"
   letline[1]="²²    ²²"
   letline[2]="²² ²² ²²"
   letline[3]="²²²²²²²²"
   letline[4]="²²²  ²²²"
   letline[5]=" ²²  ²² "
case t_let="X"
   letline[1]="²²    ²²"
   letline[2]=" ²²  ²² "
   letline[3]="  ²²²²  "
   letline[4]=" ²²  ²² "
   letline[5]="²²    ²²"
case t_let="Y"
   letline[1]="²²    ²²"
   letline[2]=" ²²  ²² "
   letline[3]="  ²²²²  "
   letline[4]="   ²²   "
   letline[5]="   ²²   "
case t_let="Z"
   letline[1]="²²²²²²²²"
   letline[2]="    ²²² "
   letline[3]="   ²²²  "
   letline[4]=" ²²²    "
   letline[5]="²²²²²²²²"
endcase
set color to (c_whbl)
for n=1 to 5
   for m=1 to 8
      if substr(letline[n],m,1)!=" "
         @ yyy+(n-1)+1,xxx+(m-1)+2 say " "
      endif
   next m
   
next n
set color to (c_mainbl)
for n=1 to 5
   for m=1 to 8
      if substr(letline[n],m,1)!=" "
         @ yyy+(n-1),xxx+(m-1) say substr(letline[n],m,1)
      endif
   next m
next n
return
************************************************************
*!*****************************************************************************
*!
*!      Procedure: P_BIGMSG
*!
*!      Called by: P_INTRO            (procedure in PROCS.PRG)
*!
*!          Calls: LEN()              (function  in ?)
*!               : SUBSTR()           (function  in ?)
*!               : P_BIGLETT          (procedure in PROCS.PRG)
*!
*!*****************************************************************************
procedure p_bigmsg
parameters t_text,t_y,t_x
for t_n=1 to len(t_text)
   do p_biglett with substr(t_text,t_n,1),t_y,t_x+((t_n-1)*11)
next t_n
return
************************************************************
*!*****************************************************************************
*!
*!      Procedure: READVAR
*!
*!          Calls: HOLDSCREEN         (procedure in PROCS.PRG)
*!               : LEN()              (function  in ?)
*!               : INT()              (function  in ?)
*!               : SHADOWBOX          (procedure in PROCS.PRG)
*!               : SUBSTR()           (function  in ?)
*!               : SPACE()            (function  in ?)
*!               : REPLICATE()        (function  in ?)
*!               : READKEY()          (function  in PROCS.PRG)
*!               : POPSCREEN          (procedure in PROCS.PRG)
*!
*!*****************************************************************************
procedure readvar
parameters msg, vv,t_len
do holdscreen
boxlen=len(msg)+t_len
left=38-(int(boxlen/2))-1
right=left+boxlen+2
t_vv=vv
do shadowbox with 9,left-1,11,right+1
vv=substr(vv+space(t_len),1,t_len)
@ 10,left+1 say msg get vv picture replicate("!",t_len)
read
if readkey()=12
   vv=t_vv
endif
do popscreen
return
****************************************************************

*!*****************************************************************************
*!
*!       Function: MYSTUFF()
*!
*!      Called by: AGPRICES           (procedure in CCOST.PRG)
*!
*!          Calls: SUBSTR()           (function  in ?)
*!
*!*****************************************************************************
function mystuff
parameters t_string1, t_st,t_len,t_string2
return substr(t_string1,1,t_st-1)+t_string2+substr(t_string1,t_st+t_len)
**************************************************************


**************************************************************
******
*       MYBROWSE([t, l, b, r])
*
*       Dennis L. Dias
*       MOD: B. O'Shea
*
******
*!*****************************************************************************
*!
*!       Function: MYBROWSE()
*!
*!      Called by: MYB                (procedure in PROCS.PRG)
*!
*!          Calls: EMPTY()            (function  in ?)
*!               : FIELDNAME()        (function  in ?)
*!               : PCOUNT()           (function  in ?)
*!               : FCOUNT()           (function  in ?)
*!               : CHR()              (function  in ?)
*!               : EOF()              (function  in ?)
*!               : LASTREC()          (function  in ?)
*!               : SPACE()            (function  in ?)
*!               : DBEDIT()           (function  in ?)
*!
*!*****************************************************************************
function mybrowse

parameters t, l, b, r
private n,move_chrs,force_rec,ntx_expr,ntx_eval,prev_rec,;
   is_append,any_append,keystroke,e_field;,get_data

if empty(fieldname(1))
   * no database in use
   return .f.
   
endif

save screen

if pcount() < 4
   t = 1
   l = 0
   b = 23
   r = 79
   
else
   * window specified
   @ m->t, m->l, m->b, m->r box "ÕÍ¸³¾ÍÔ³"
   @ m->t + 3, m->l say "Æ"
   @ m->t + 3, m->r say "µ"
   
   * shrink
   t = m->t + 1
   l = m->l + 1
   b = m->b - 1
   r = m->r - 1
   
endif

declare field_list[FCOUNT()]

for n = 1 to fcount()
   * each element contains one field name
   field_list[M->n] = fieldname(m->n)
   
next

* record movement keystroke values
move_chrs = chr(24) + chr(5) + chr(3) + chr(18) + chr(31) + chr(30)

if eof()
   go top
   
endif

* init
force_rec = .t.
is_append = .f.
any_append = .f.
prev_rec = 0

if lastrec() = 0
   * empty file..force append mode
   keyboard chr(24)
   
endif

@ m->t, m->l say space(m->r - m->l + 1)
dbedit(m->t + 1, m->l, m->b, m->r, m->field_list, "xmybrowse", 0, 0, " Í", " ")

restore screen
return .t.







************************************************************************
************************************************************************
************************************************************************
************************************************************************
************************************************************************
************************************************************************
************************************************************************
************************************************************************
************************************************************************
************************************************************************
************************************************************************
************************************************************************
************************************************************************
************************************************************************
************************************************************************
************************************************************************
************************************************************************
************************************************************************
************************************************************************
************************************************************************
************************************************************************
************************************************************************
************************************************************************
************************************************************************
************************************************************************
************************************************************************
************************************************************************
************************************************************************
************************************************************************
************************************************************************
************************************************************************
************************************************************************
************************************************************************
************************************************************************
************************************************************************
************************************************************************
************************************************************************
************************************************************************
************************************************************************
************************************************************************
************************************************************************
************************************************************************
************************************************************************
************************************************************************
************************************************************************
************************************************************************
************************************************************************
************************************************************************

**************************************************************
******
*       xmybrowse()
*
*       handle special keys and displays during dbedit
******
*!*****************************************************************************
*!
*!       Function: XMYBROWSE()
*!
*!          Calls: LASTKEY()          (function  in ?)
*!               : RECNO()            (function  in ?)
*!               : CHR()              (function  in ?)
*!               : STATLINE()         (function  in PROCS.PRG)
*!               : IF()               (function  in ?)
*!               : EOF()              (function  in ?)
*!               : PAD()              (function  in PROCS.PRG)
*!               : LTRIM()            (function  in ?)
*!               : STR()              (function  in ?)
*!               : LASTREC()          (function  in ?)
*!               : COPYFIELD          (procedure in PROCS.PRG)
*!               : DELETED()          (function  in ?)
*!               : INDEXKEY()         (function  in ?)
*!               : EMPTY()            (function  in ?)
*!               : CLEAR_GETS         (procedure in PROCS.PRG)
*!               : ROW()              (function  in ?)
*!               : COL()              (function  in ?)
*!               : GET_PIC()          (function  in PROCS.PRG)
*!
*!*****************************************************************************
function xmybrowse

parameters mode, f_script
private ret_val

* assume normal return
ret_val = 1

* save last keystroke
keystroke = lastkey()

* get fieldspec into normal variable
e_field = field_list[M->f_script]

if m->prev_rec <> recno()
   * record pointer has been moved
   prev_rec = recno()
   force_rec = .t.
   
endif

do case
   
case m->mode = 0
   * idle
   
   if chr(m->keystroke) $ m->move_chrs .or. m->force_rec
      * need to update status line
      statline()
      
   endif
   
case m->mode = 1
   * bof bang
   
   if m->is_append
      * no more append mode..refresh if any new records
      ret_val = if(m->any_append, 2, 3)
      is_append = .f.
      any_append = .f.
      
   else
      
      if .not. eof()
         * just display "bof bang" status
         @ m->t, m->r - 20 say pad(ltrim(str(recno())) + "/" +;
            ltrim(str(lastrec())), 15) + " <bof>"
         
      endif
   endif
   
   force_rec = .t.
   
case m->mode = 2
   * eof bang
   
   if m->is_append
      * append mode active
      
      if m->keystroke = 24 .and. .not. eof()
         * down arrow...do it again
         ret_val = 3
         
      else
         
         if m->keystroke = 30
            * ^PgDn..no more append mode..refresh if any new records
            ret_val = if(m->any_append, 2, 3)
            is_append = .f.
            any_append = .f.
            
         else
            * someone is standing on the down arrow
            
            if m->force_rec
               * need to update status line
               statline()
               
            endif
         endif
      endif
      
   else
      
      if m->keystroke = 24
         * enter append mode
         ret_val = 3
         is_append = .t.
         
      else
         
         if .not. eof()
            * just display "eof bang" status
            @ m->t, m->r - 20 say pad(ltrim(str(recno())) + "/" +;
               ltrim(str(lastrec())), 15) + " <eof>"
            
         endif
      endif
   endif
   
   force_rec = .t.
   
case m->mode = 3
   * file is empty
   
   if m->keystroke = 24
      * enter append mode
      ret_val = 3
      is_append = .t.
      force_rec = .t.
      
   else
      * just display status
      statline()
      
   endif
   
case m->mode = 4
   * keystroke exception
   *@1,1 say M->keystroke
   *
   *
   *  CODE ADDED BY BOS for F1 press to copy field from above.
   *  as at 25/01/95
   *
   *
   do case
   case m->keystroke = 28
      * copy contents from field above ...
      do copyfield
      *
      * BOS
      *
   case m->keystroke = 27
      * exit requested..quit dbedit
      ret_val = 0
      
   case m->keystroke = 7 .and. .not. eof() .and. lastrec() <> 0
      * delete key..toggle deleted() flag
      
      if deleted()
         recall
         
      else
         delete
         
      endif
      
      * show the new deleted status
      statline()
      
   case m->keystroke = 13 .and.;
         (m->is_append .or. (.not. eof() .and. lastrec() <> 0))
      * edit the current field
      ntx_expr = indexkey(0)          && get the controlling index key
      
      if .not. empty(m->ntx_expr)
         * expand key for comparison after edit
         ntx_eval = &ntx_expr
         
      endif
      
      set cursor on
      
      * use memory variable for data entry
      get_data = &e_field
      
      * up and down arrows will exit read
      set key 5 to clear_gets
      set key 24 to clear_gets
      
      * data entry
      @ row(),col() get get_data picture get_pic(m->e_field)
      read
      keystroke = lastkey()           && save exit key
      
      * release keys
      set key 5 to
      set key 24 to
      
      if m->keystroke <> 27
         *  .AND. UPDATED()
         
         
         * new data confirmed
         
         if m->is_append .and. eof()
            * first data in new record
            append blank
            any_append = .t.
            
            * update status line
            statline()
            
         endif
         
         * put it there

         if rlock()
                replace &e_field with m->get_data
         endif         

      endif
      
      set cursor off
      
      if .not. empty(m->ntx_expr) .and. .not. m->is_append
         * file indexed..check for altered key field
         
         if m->ntx_eval <> (&ntx_expr)
            * key field altered..re-draw screen
            ret_val = 2
            
         endif
      endif
      
      if m->ret_val <> 2
         * certain keys move cursor after edit if no refresh
         
         do case
            
         case m->keystroke = 5
            * up arrow
            
            if m->is_append
               * no more append mode..refresh if new records
               ret_val = if(m->any_append, 2, 3)
               is_append = .f.
               any_append = .f.
               
            else
               * move up one row
               keyboard chr(5)
               
            endif
            
         case m->keystroke = 18
            * PgUp
            
            if m->is_append
               * no more append mode..refresh if new records
               ret_val = if(m->any_append, 2, 3)
               is_append = .f.
               any_append = .f.
               
            else
               * return up arrow
               keyboard chr(5)
               
            endif
            
         case m->keystroke = 24
            * down arrow
            keyboard chr(24)
            
         case m->keystroke = 3 .and. .not. m->is_append
            * PgDn..return down arrow if not append mode
            keyboard chr(24)
            
         case m->keystroke = 13
            * return..move right
            keyboard chr(4)
            
         endcase
      endif
   endcase
endcase

return m->ret_val

******
*       statline()
*
*       update the status line in the browse window
******
*!*****************************************************************************
*!
*!       Function: STATLINE()
*!
*!      Called by: XMYBROWSE()        (function  in PROCS.PRG)
*!
*!          Calls: LASTREC()          (function  in ?)
*!               : EOF()              (function  in ?)
*!               : IF()               (function  in ?)
*!               : DELETED()          (function  in ?)
*!               : PAD()              (function  in PROCS.PRG)
*!               : LTRIM()            (function  in ?)
*!               : STR()              (function  in ?)
*!               : RECNO()            (function  in ?)
*!
*!*****************************************************************************
function statline

* display record pointer information
@ m->t, m->r - 27 say "Record "

if lastrec() = 0 .and. .not. m->is_append
   * file is empty
   @ m->t, m->r - 20 say "<none>               "
   
else
   
   if eof()
      * no record number if eof
      @ m->t, m->r - 40 say "         "
      @ m->t, m->r - 20 say "                " +;
         if(m->is_append, "<new>", "<eof>")
      
   else
      * normal record..display recno()/lastrec() and deleted()
      @ m->t, m->r - 40 say if(deleted(), "<Deleted>", "         ")
      @ m->t, m->r - 20 say pad(ltrim(str(recno())) + "/" +;
         ltrim(str(lastrec())), 21)
      
   endif
endif

* status line is current
force_rec = .f.
return 0


******
*       get_pic()
*
*       return matching picture string for specified field
******
*!*****************************************************************************
*!
*!       Function: GET_PIC()
*!
*!      Called by: XMYBROWSE()        (function  in PROCS.PRG)
*!
*!          Calls: TYPE()             (function  in ?)
*!               : LTRIM()            (function  in ?)
*!               : STR()              (function  in ?)
*!               : MIN()              (function  in ?)
*!               : LEN()              (function  in ?)
*!               : REPLICATE()        (function  in ?)
*!               : AT()               (function  in ?)
*!
*!*****************************************************************************
function get_pic

parameters field
private pstring, s

do case
   
case type(m->field) = "C"
   * character field is bounded by window width
   pstring = "@KS" + ltrim(str(min(len(&field), 78)))
   
case type(m->field) = "N"
   * convert to character to help format picture string
   s = str(&field)
   
   if "." $ m->s
      * decimals in numeric...use the form "9999.99"
      pstring = replicate("9", at(".", m->s) - 1) + "."
      pstring = pstring + replicate("9", len(m->s) - len(m->pstring))
      
   else
      * no decimals...only need the correct length
      pstring = replicate("9", len(m->s))
      
   endif
   
otherwise
   * no picture
   pstring = ""
   
endcase

return pstring


****
*       pad()
*
*       pad with spaces
****
*!*****************************************************************************
*!
*!       Function: PAD()
*!
*!      Called by: XMYBROWSE()        (function  in PROCS.PRG)
*!               : STATLINE()         (function  in PROCS.PRG)
*!
*!          Calls: SUBSTR()           (function  in ?)
*!               : SPACE()            (function  in ?)
*!
*!*****************************************************************************
function pad

parameters string, length
return substr(string + space(length), 1, length)


******
*       clear_gets
*
*       set keystrokes to this procedure to exit a READ
******
*!*****************************************************************************
*!
*!      Procedure: CLEAR_GETS
*!
*!      Called by: XMYBROWSE()        (function  in PROCS.PRG)
*!
*!*****************************************************************************
procedure clear_gets
parameters dummy1,dummy2,dummy3

clear gets
return


***
*   AMPM()
*   Tom Rettig
*   11/01/85, 09/01/86
*
*   Returns an 11 byte character string with the time in
*       a 12-hour am/pm format.
*
*   time_ampm = AMPM(time_24)
*
*       time_ampm   -   character string.
*       time_24     -   character string.
*
*   Placed in the public domain by Tom Rettig Associates.
*
*

*!*****************************************************************************
*!
*!       Function: AMPM()
*!
*!          Calls: IF()               (function  in ?)
*!               : VAL()              (function  in ?)
*!               : STR()              (function  in ?)
*!               : SUBSTR()           (function  in ?)
*!
*!*****************************************************************************
function ampm

parameters cl_time

return if(        val(cl_time)<12, cl_time + " am",;
   if(    val(cl_time)=12, cl_time + " pm",;
   str(val(cl_time)-12,2) + substr(cl_time,3) + " pm" ) )

*
*
** eofunc AMPM()


****
*   STRZERO()
*   Tom Rettig
*   11/01/85, 09/01/86
*
*   The STR() of <expN> with leading zeros instead of blanks.
*
*   string = STRZERO( <expN>, [<length> [,<decimals>]] )
*
*       string      -   character string.
*       expN        -   numeric.
*       length      -   numeric.
*       decimals    -   numeric.
*
*   Placed in the public domain by Tom Rettig Associates.
*

*!*****************************************************************************
*!
*!       Function: STRZERO()
*!
*!      Called by: TSTRING()          (function  in PROCS.PRG)
*!
*!          Calls: PCOUNT()           (function  in ?)
*!               : STR()              (function  in ?)
*!               : REPLICATE()        (function  in ?)
*!               : LEN()              (function  in ?)
*!               : LTRIM()            (function  in ?)
*!               : SUBSTR()           (function  in ?)
*!               : AT()               (function  in ?)
*!
*!*****************************************************************************
function strzero

parameters cl_num, cl_len, cl_dec

private cl_str

do case
case pcount()=3
   cl_str = str(cl_num,cl_len,cl_dec)
   
case pcount()=2
   cl_str = str(cl_num,cl_len)
   
otherwise
   cl_str = str(cl_num)
   
endcase

if "-" $ cl_str    && negative number
   * Move the minus sign in front of zeros.
   return "-" + replicate( "0", len(cl_str)-len(ltrim(cl_str)) ) +;
      substr( cl_str, at("-",cl_str)+1 )
else   && positive number
   return replicate( "0", len(cl_str)-len(ltrim(cl_str)) ) + ltrim(cl_str)
endif

*
*
** eofunc STRZERO()


****
*
*       DAYS()
*
***


*!*****************************************************************************
*!
*!       Function: DAYS()
*!
*!          Calls: INT()              (function  in ?)
*!
*!*****************************************************************************
function days
* Syntax: DAYS( <seconds> )
* Return: Integer number of days from numeric seconds
* Note..: The remainder under 24 hours is returned by the TSTRING() function.
*
parameters cl_secs
return int( cl_secs / 86400 )


****
*
* Syntax: DBF()
* Return: The alias of the currently selected database.
* Note..: Supposed to return the name of the currently selected database file.
*
***

*!*****************************************************************************
*!
*!       Function: DBF()
*!
*!          Calls: ALIAS()            (function  in ?)
*!
*!*****************************************************************************
function dbf
return alias()


****
* Syntax: ELAPTIME( <start time>, <end time> )
* Return: A time string showing the difference between start and end times
* Note..: If start time is greater than end time, this algorithm assumes
*         that the day changed at midnight.  Only for timings under 24 hours.
*         86400 is the number of seconds in 24 hours.
***

*!*****************************************************************************
*!
*!       Function: ELAPTIME()
*!
*!          Calls: TSTRING()          (function  in PROCS.PRG)
*!               : IF()               (function  in ?)
*!               : SECS()             (function  in PROCS.PRG)
*!
*!*****************************************************************************
function elaptime
parameters cl_start, cl_end

return tstring( if(cl_end<cl_start,86400,0)+secs(cl_end)-secs(cl_start) )


****
* Syntax: FKLABEL( <expN> )
* Return: The name of the <expN>th programmable function key
***
*!*****************************************************************************
*!
*!       Function: FKLABEL()
*!
*!          Calls: IF()               (function  in ?)
*!               : LTRIM()            (function  in ?)
*!               : STR()              (function  in ?)
*!
*!*****************************************************************************
function fklabel
parameters cl_1

return if(cl_1<=40 .and. cl_1 > 0, "F"+ltrim(str(cl_1)), [])


****
*
* Syntax: FKMAX()
* Return: The maximum number of programmable function keys on the computer
* Note..: This is specific to IBM PC/XT/AT and clones.
*
***
*!*****************************************************************************
*!
*!       Function: FKMAX()
*!
*!*****************************************************************************
function fkmax

return 40          && IBM specific


****
* Syntax: LENNUM( <expN> )
* Return: The length of <expN>
*
***
*!*****************************************************************************
*!
*!       Function: LENNUM()
*!
*!          Calls: LEN()              (function  in ?)
*!               : LTRIM()            (function  in ?)
*!               : STR()              (function  in ?)
*!
*!*****************************************************************************
function lennum
parameters cl_number

return len(ltrim(str(cl_number)))


****
* Syntax: MOD( <expN1>, <expN2> )
* Return: The remainder of <expN1> divided by <expN2>
* Note..: Difference between the dBASE modulus function and the Clipper
*         modulus operator is indicated by an arrow <-->:
*
*         Clipper operator:        dBASE function:
*         -----------------        -----------------
*          3 %  3 ::=  0.00        MOD( 3, 3) ::=  0
*          3 %  2 ::=  1.00        MOD( 3, 2) ::=  1
*          3 %  1 ::=  0.00        MOD( 3, 1) ::=  0
*          3 %  0 ::=  0.00  <-->  MOD( 3, 0) ::=  3
*          3 % -1 ::=  0.00        MOD( 3,-1) ::=  0
*          3 % -2 ::=  1.00  <-->  MOD( 3,-2) ::= -1
*          3 % -3 ::=  0.00        MOD( 3,-3) ::=  0
*
*         -3 %  3 ::=  0.00        MOD(-3, 3) ::=  0
*         -3 %  2 ::= -1.00  <-->  MOD(-3, 2) ::=  1
*         -3 %  1 ::=  0.00        MOD(-3, 1) ::=  0
*         -3 %  0 ::=  0.00  <-->  MOD(-3, 0) ::= -3
*         -3 % -1 ::=  0.00        MOD(-3,-1) ::=  0
*         -3 % -2 ::= -1.00        MOD(-3,-2) ::= -1
*         -3 % -3 ::=  0.00        MOD(-3,-3) ::=  0
*
*          3 %  3 ::=  0.00        MOD( 3, 3) ::=  0
*          2 %  3 ::=  2.00        MOD( 2, 3) ::=  2
*          1 %  3 ::=  1.00        MOD( 1, 3) ::=  1
*          0 %  3 ::=  0.00        MOD( 0, 3) ::=  0
*         -1 %  3 ::= -1.00  <-->  MOD(-1, 3) ::=  2
*         -2 %  3 ::= -2.00  <-->  MOD(-2, 3) ::=  1
*         -3 %  3 ::=  0.00        MOD(-3, 3) ::=  0
*
*          3 % -3 ::=  0.00        MOD( 3,-3) ::=  0
*          2 % -3 ::=  2.00  <-->  MOD( 2,-3) ::= -1
*          1 % -3 ::=  1.00  <-->  MOD( 1,-3) ::= -2
*          0 % -3 ::=  0.00        MOD( 0,-3) ::=  0
*         -1 % -3 ::= -1.00        MOD(-1,-3) ::= -1
*         -2 % -3 ::= -2.00        MOD(-2,-3) ::= -2
*         -3 % -3 ::=  0.00        MOD(-3,-3) ::=  0
*
***
*!*****************************************************************************
*!
*!       Function: MOD()
*!
*!      Called by: TSTRING()          (function  in PROCS.PRG)
*!
*!          Calls: IF()               (function  in ?)
*!
*!*****************************************************************************
function mod
parameters cl_num, cl_base

private cl_result
cl_result = cl_num % cl_base
return if( cl_base = 0, cl_num,;
   if(cl_result * cl_base < 0, cl_result + cl_base, cl_result) )


****
* Syntax OS()
* Return: The name of the operating system
*
***
*!*****************************************************************************
*!
*!       Function: OS()
*!
*!*****************************************************************************
function os

return "MS/PC-DOS"    && DOS specific, MicroSoft or IBM


****
* Syntax: READKEY()
* Return: A number representing the key pressed to exit from full-screen mode
* Note..: Differences between dBASE's READKEY() and Clipper's LASTKEY():
*
*         Exit Key:      dBASE:      Clipper:
*         ---------      ------      --------
*         Backspace         0        no exit
*         ^D, ^L            1        no exit
*         Lt arrow          2        no exit
*         Rt arrow          3        no exit
*         Up arrow          4        no exit
*         Dn arrow          5        no exit
*         PgUp              6          18
*         PgDn              7           3
*         Esc, ^Q          12          27 (Esc only)
*         ^End, ^W         14          23 (^W only)
*         type past end    15        ascii of last char typed
*         Enter            15          13
*         ^Home            33        no exit
*         ^PgUp            34        no exit
*         ^PgDn            35        no exit
*         F1               36        no exit
*
*         dBASE III adds 256 to the exit code if the user changed anything.
*         Clipper uses its UPDATED() function to determine if anything changed.
*
***
*!*****************************************************************************
*!
*!       Function: READKEY()
*!
*!      Called by: CCOST.PRG                         
*!               : ENTERCODE          (procedure in CCOST.PRG)
*!               : COSTC              (procedure in CCOST.PRG)
*!               : DELCODE            (procedure in CCOST2.PRG)
*!               : MODCODE            (procedure in CCOST3.PRG)
*!               : DISCODE            (procedure in CCOST4.PRG)
*!               : EXPORTDRG          (procedure in CCOST7.PRG)
*!               : CALCCOIL           (procedure in CALCC.PRG)
*!               : READVAR            (procedure in PROCS.PRG)
*!               : P_SETPATH          (procedure in PROCS.PRG)
*!
*!          Calls: LASTKEY()          (function  in ?)
*!               : IF()               (function  in ?)
*!               : UPDATED()          (function  in ?)
*!
*!*****************************************************************************
function readkey

do case
case lastkey() = 18                           && PgUp
   return  6 + if(updated(),256,0)
case lastkey() =  3                           && PgDn
   return  7 + if(updated(),256,0)
case lastkey() = 27                           && Esc
   return 12 + if(updated(),256,0)
case lastkey() = 23                           && ^W
   return 14 + if(updated(),256,0)
case lastkey() = 13                           && Enter
   return 15 + if(updated(),256,0)
case lastkey() = 31                           && ^PgUp
   return 34 + if(updated(),256,0)
case lastkey() = 30                           && ^PgDn
   return 35 + if(updated(),256,0)
case lastkey() >= 32                          && type past end
   return 15 + if(updated(),256,0)
otherwise
   return 0
endcase


****
* Syntax: SECS( <time string> )
* Return: Numeric seconds as a quantity of the time string
* Note..: Seconds in time period
*         -------    -----------
*              60    1 minute
*            3600    1 hour
*           86400    1 day
*
***
*!*****************************************************************************
*!
*!       Function: SECS()
*!
*!      Called by: ELAPTIME()         (function  in PROCS.PRG)
*!
*!          Calls: VAL()              (function  in ?)
*!               : SUBSTR()           (function  in ?)
*!
*!*****************************************************************************
function secs
parameters cl_time

return val(       cl_time   ) * 3600 +;
   val(substr(cl_time,4)) *   60 +;
   val(substr(cl_time,7))







****
* Syntax: TSTRING( <seconds> )
* Return: A 24-hour time string from numeric seconds
* Note..: Time quantities over 24 hours are returned by the DAYS() function.
*
***
*!*****************************************************************************
*!
*!       Function: TSTRING()
*!
*!      Called by: ELAPTIME()         (function  in PROCS.PRG)
*!
*!          Calls: STRZERO()          (function  in PROCS.PRG)
*!               : INT()              (function  in ?)
*!               : MOD()              (function  in PROCS.PRG)
*!
*!*****************************************************************************
function tstring
parameters cl_secs

return strzero( int(mod(cl_secs/3600, 24)), 2, 0 ) +':'+;
   strzero( int(mod(cl_secs/  60, 60)), 2, 0 ) +':'+;
   strzero( int(mod(cl_secs     , 60)), 2, 0 )


****
* Syntax: VERSION()
* Return: The name of current dBASE III or Clipper version
* Note..: Remember to change this when you update your Clipper version.
***
*!*****************************************************************************
*!
*!       Function: VERSION()
*!
*!*****************************************************************************
function version

return "Clipper, Summer '87"


*
* by BOS 25/01/95
*
*!*****************************************************************************
*!
*!      Procedure: COPYFIELD
*!
*!      Called by: XMYBROWSE()        (function  in PROCS.PRG)
*!
*!          Calls: BOF()              (function  in ?)
*!
*!*****************************************************************************
procedure copyfield
private tmp
if .not. bof()
   skip -1
   tmp=&e_field
   skip 1
   if rlock()
        replace &e_field with tmp
   endif
endif
return
*
*
*

************************************************************************
*!*****************************************************************************
*!
*!       Function: RENFIELD()
*!
*!          Calls: REPLICATE()        (function  in ?)
*!               : CHR()              (function  in ?)
*!               : LTRIM()            (function  in ?)
*!               : RTRIM()            (function  in ?)
*!               : UPPER()            (function  in ?)
*!               : SUBSTR()           (function  in ?)
*!               : SELECT()           (function  in ?)
*!               : FILE()             (function  in ?)
*!               : FCOUNT()           (function  in ?)
*!               : FIELD()            (function  in ?)
*!               : FOPEN()            (function  in ?)
*!               : FERROR()           (function  in ?)
*!               : FSEEK()            (function  in ?)
*!               : FWRITE()           (function  in ?)
*!               : FCLOSE()           (function  in ?)
*!
*!           Uses: &DBFFILE           
*!
*!*****************************************************************************
function renfield
parameters dbffile, frm, tto
private nn
private ok
private flds
private ttfile
private oldarea
private renfile
private buffer
private errcd
private foundat
private nulls

nulls=replicate(chr(0),10)
frm=ltrim(rtrim(upper(frm)))
tto=substr(tto+nulls,1,10)
oldarea=select()
select 0
ok=.f.
if file("&DBFfile..dbf")
   use &dbffile.
   flds=fcount()
   for nn=1 to flds
      if field(nn)=frm
         ok=.t.
         foundat=nn
         use
         exit
      endif
   next nn
   use
   select (oldarea)
else
   use
   select (oldarea)
   return -1
endif
if .not. ok
   use
   select (oldarea)
   return -1
endif
errcd=0
ttfile=dbffile+".dbf"
renfile=fopen(ttfile,2)
if ferror()=0
   fseek(renfile,foundat*32)
   fwrite(renfile,tto)
else
   errcd=-1
endif
fclose(renfile)
return errcd

************************************************************
*!*****************************************************************************
*!
*!      Procedure: LIGHTCOL
*!
*!      Called by: CCOST.PRG                         
*!               : INTRATE            (procedure in CCOST.PRG)
*!               : COSTC              (procedure in CCOST.PRG)
*!               : AGPRICES           (procedure in CCOST.PRG)
*!               : MYB                (procedure in PROCS.PRG)
*!               : P_REPDISP          (procedure in PROCS.PRG)
*!
*!*****************************************************************************
procedure lightcol
set color to (c_mainwh)
return
************************************************************
*!*****************************************************************************
*!
*!      Procedure: NORMCOL
*!
*!      Called by: CCOST.PRG                         
*!               : COSTC              (procedure in CCOST.PRG)
*!               : AGPRICES           (procedure in CCOST.PRG)
*!
*!*****************************************************************************
procedure normcol
set color to (c_mainbl)
return

*!*****************************************************************************
*!
*!      Procedure: P_COLVARS
*!
*!      Called by: CCOST.PRG                         
*!
*!          Calls: CHR()              (function  in ?)
*!
*!*****************************************************************************
procedure p_colvars
public c_mainbl,c_mainwh,c_mainwb,c_mainno,c_whbl,c_blwh,c_pass
public v_crlf,v_arrows,v_enter,v_dot

c_mainbl="GR+/B,W/RB,B,B,N/BG"
c_mainwh="GR+/W,W/RB,W,B,N/BG"
c_mainwb="N/W,BG/RB,W,B,N/BG"
c_mainno="W/W,N/W,W,B,N/RB"
c_whbl="W/N"
c_blwh="N/W"
c_pass="RB/RB,RB/RB"
v_crlf=chr(13)+chr(10)              && Cr&Lf
v_arrows=chr(24)+chr(25)+chr(27)+chr(26)
v_enter=" "+chr(17)+chr(196)+chr(217)+" "    && " <-Ù "
v_dot=chr(7)
return




************************************************************

*!*****************************************************************************
*!
*!      Procedure: CURSON
*!
*!      Called by: CCOST.PRG                         
*!               : ENTERCODE          (procedure in CCOST.PRG)
*!               : ACCPT              (procedure in CCOST.PRG)
*!               : AGPRICES           (procedure in CCOST.PRG)
*!               : CRTCODE            (procedure in CCOST1.PRG)
*!               : MODCODE            (procedure in CCOST3.PRG)
*!               : CALCCOIL           (procedure in CALCC.PRG)
*!
*!*****************************************************************************
procedure curson
set cursor on
return
******************************************
*!*****************************************************************************
*!
*!      Procedure: CURSOFF
*!
*!      Called by: CCOST.PRG                         
*!               : ENTERCODE          (procedure in CCOST.PRG)
*!               : ACCPT              (procedure in CCOST.PRG)
*!               : WAITKEY            (procedure in CCOST.PRG)
*!               : AGPRICES           (procedure in CCOST.PRG)
*!               : CRTCODE            (procedure in CCOST1.PRG)
*!               : MODCODE            (procedure in CCOST3.PRG)
*!               : EXPORTDRG          (procedure in CCOST7.PRG)
*!               : CALCCOIL           (procedure in CALCC.PRG)
*!
*!*****************************************************************************
procedure cursoff
set cursor off
return
******************************************
*!*****************************************************************************
*!
*!      Procedure: MYB
*!
*!      Called by: CCOST.PRG                         
*!
*!          Calls: HOLDSCREEN         (procedure in PROCS.PRG)
*!               : SHADOWBOX          (procedure in PROCS.PRG)
*!               : LIGHTCOL           (procedure in PROCS.PRG)
*!               : MYBROWSE()         (function  in PROCS.PRG)
*!               : POPSCREEN          (procedure in PROCS.PRG)
*!
*!*****************************************************************************
procedure myb

do holdscreen
do shadowbox with 4,4,23,73
do lightcol
@ 22,5 say "Keys: PgUp, PgDn, &V_Arrows, &V_enter to Modify. Ctrl+G to Delete   F1=COPY "
go top
mybrowse(5,5,21,72)
do popscreen
return

******************************************************************


******************************************************************
* example do P_repdisp with reportfile,7,"X020.X030.X010.X010."
**************************************************************


**************************************************************
* Needs to have the following variables defined before starting this procedure.
* PGBRK  : "Y","N"  = page breakes
* PGTOP  : numeric 1->9 top report lines
* PGBOT  : numeric 1->9 bottom report lines
* PGFT   : "Yes,"No" = pg footer ( note: this takes away another 2 lines from page )
* PGFTTXT: string for text at footer
* PGLEN  : Guess what this is !
**************************************************************

*!*****************************************************************************
*!
*!      Procedure: P_REPDISP
*!
*!      Called by: CCOST.PRG                         
*!               : CALCCOIL           (procedure in CALCC.PRG)
*!
*!          Calls: HOLDSCREEN         (procedure in PROCS.PRG)
*!               : SHADOWBOX          (procedure in PROCS.PRG)
*!               : SETCOLOR()         (function  in ?)
*!               : LIGHTCOL           (procedure in PROCS.PRG)
*!               : MEMOREAD()         (function  in ?)
*!               : MEMOEDIT()         (function  in ?)
*!               : P_ARESURE          (procedure in PROCS.PRG)
*!               : POPSCREEN          (procedure in PROCS.PRG)
*!               : P_SETPATH          (procedure in PROCS.PRG)
*!               : P_NOTE             (procedure in PROCS.PRG)
*!               : P_READREP          (procedure in PROCS.PRG)
*!               : P_PGBRKCSV         (procedure in PROCS.PRG)
*!               : EOF()              (function  in ?)
*!               : PRTLINE()          (function  in PROCS.PRG)
*!               : IIF()              (function  in ?)
*!               : P_MSG              (procedure in PROCS.PRG)
*!               : FILE()             (function  in ?)
*!
*!*****************************************************************************
procedure p_repdisp
parameter reportfile,titlelines,layout

* TITLELINES = number of lines not to include in format string
* LAYOUT= format string see function PRTLINE()
do holdscreen
do shadowbox with 3,2,21,77
c_temp=setcolor()
do lightcol
*
*
*
* make sure to add code to check for over 64K read !
* switch to other lister LIST.COM
*
*
*
@ 20,6 say "PgUp, PgDn, Home, End to view report                  ESC to exit"
set color to (c_mainwb)
t_report=memoread("&reportfile")
t_report=memoedit(t_report,4,3,19,76,.f.,"",200)
temp="REPORT.TMP"
&& TEMP TO DELETE !

set color to (c_temp)
yn=1
do p_aresure with yn,"Print report","Send report to printer, CSV file, TXT file.","No report."
if yn=1
   do holdscreen
   do shadowbox with 9,20,13,54
   @ 10,25 say "Select output destination"
   @ 12,23 prompt "Printer" message "Print report on printer"
   @ 12,32 prompt "CSV file" message "Export file in CSV format, ( LOTUS import file )"
   @ 12,43 prompt "TXT file" message "Export file in TXT format, ( ASCII import file )"
   menu to t_output
   do popscreen
   if t_output!=0
      if t_output=2
         
         t_csv="C:\REPORT.CSV"
         do p_setpath with "Enter CSV export filename : ", t_csv
         do p_note with "Exporting CSV file. Please wait ..."
         do p_readrep with reportfile
         set console off
         set alternate to (t_csv)
         set alternate on
         
         if pgbrk="Y"
            do p_pgbrkcsv
         endif
         
         t_linct=titlelines+pgtop+1
         goto t_linct
         do while .not. eof()
            ? t_linct
            ?? prtline(textline,layout)
            t_linct=t_linct+1
            if t_linct=((pglen-(pgtop+pgbot))-iif(pgft="Yes",2,0))  && 2 LINES FOR FOOTER
               if pgbrk="Y"
                  if pgft="Yes"
                     ? '""'
                     ? '"'+pgfttxt+'"'
                     for t_n=1 to pgbot
                        ? '""'
                     next
                     
                     
                  endif
                  do p_pgbrkcsv
               endif
               t_linct=titlelines+pgtop+1
            endif
            skip
         enddo
         
         if pgft="Yes"
            for n=t_linct to (pglen-(pgtop+pgbot))
               ? '""'
            next
            ? '""'
            ? "footer text"
            for t_n=1 to pgbot
               ? '""'
            next
         endif
         set alternate off
         set alternate to
         set console on
         set print off
         
         do popscreen  && FOR p_note
         do p_msg with "Finished exporting CSV file"
      endif
      
   endif
endif
t_cmd="&temp"
if file(t_cmd)
   delete file (t_cmd)
endif
do popscreen
return
**************************************************************
*!*****************************************************************************
*!
*!      Procedure: P_PGBRKCSV
*!
*!      Called by: P_REPDISP          (procedure in PROCS.PRG)
*!
*!          Calls: RECNO()            (function  in ?)
*!               : LTRIM()            (function  in ?)
*!               : RTRIM()            (function  in ?)
*!               : PRTLINE()          (function  in PROCS.PRG)
*!
*!*****************************************************************************
procedure p_pgbrkcsv
if pgtit="Yes"
   ? '""'
   for t_n=1 to pgtop
      ? '""'
   next
   
   t_rec=recno()
   go top
   for t_n=1 to titlelines-3
      ? '"'+ltrim(rtrim(textline))+'"'
      skip
   next
   
   for t_n=1 to 3
      ? prtline(textline,layout)
      skip
   next
   
   goto t_rec
else
   titlelines=0
   
endif
return
**************************************************************
*!*****************************************************************************
*!
*!      Procedure: P_READREP
*!
*!      Called by: P_REPDISP          (procedure in PROCS.PRG)
*!
*!           Uses: DBFREPORT.DBF      
*!
*!    Other Files: &REPORTFILE
*!
*!*****************************************************************************
procedure p_readrep
parameters reportfile
select 0
use dbfreport exclusive

*
*
* Format of report FIELD NAME ==== >   "TEXTLINE"  this is needed for other procedures.
*
*
*
*

zap
append from &reportfile sdf
go top
return
**************************************************************
*!*****************************************************************************
*!
*!       Function: PRTLINE()
*!
*!      Called by: P_REPDISP          (procedure in PROCS.PRG)
*!               : P_PGBRKCSV         (procedure in PROCS.PRG)
*!
*!          Calls: LEN()              (function  in ?)
*!               : SUBSTR()           (function  in ?)
*!               : VAL()              (function  in ?)
*!               : LTRIM()            (function  in ?)
*!               : RTRIM()            (function  in ?)
*!
*!*****************************************************************************
function prtline
parameters t_txt, t_lay
t_out=""
t_tot=1
for n=0 to (len(t_lay)/5)-1
   op=substr(t_lay,n*5+1,4)
   t_item=substr(t_txt,t_tot,val(substr(op,2,3)))
   t_tot=t_tot+val(substr(op,2,3))
   do case
   case substr(op,1,1)="X"      && X=ASCII
      t_out=t_out+'"'+ltrim(rtrim(t_item))+'"'+","
   case substr(op,1,1)="N"      && N=NUMERIC
      t_out=t_out+ltrim(rtrim(t_item))+","
      && B=BLANK
   endcase
next
t_out=substr(t_out,1,(len(t_out))-1)
return t_out
**************************************************************
*!*****************************************************************************
*!
*!      Procedure: P_SETPATH
*!
*!      Called by: P_REPDISP          (procedure in PROCS.PRG)
*!
*!          Calls: HOLDSCREEN         (procedure in PROCS.PRG)
*!               : SHADOWBOX          (procedure in PROCS.PRG)
*!               : SUBSTR()           (function  in ?)
*!               : SPACE()            (function  in ?)
*!               : READKEY()          (function  in PROCS.PRG)
*!               : LTRIM()            (function  in ?)
*!               : RTRIM()            (function  in ?)
*!               : UPPER()            (function  in ?)
*!               : POPSCREEN          (procedure in PROCS.PRG)
*!
*!*****************************************************************************
procedure p_setpath
parameters msg, vv
do holdscreen
t_vv=vv
do shadowbox with 9,3,11,76
vv=substr(vv+space(40),1,40)
@ 10,6 say msg get vv picture "!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!"
read
if readkey()=12
   vv=t_vv
endif
vv=ltrim(rtrim(upper(vv)))
do popscreen
return
*****************************************************************









************************************************************************
************************************************************************
************************************************************************
************************************************************************
************************************************************************
************************************************************************
************************************************************************
************************************************************************
************************************************************************
************************************************************************
************************************************************************
************************************************************************
************************************************************************
************************************************************************
************************************************************************
************************************************************************
************************************************************************
************************************************************************
************************************************************************
************************************************************************
************************************************************************
************************************************************************
************************************************************************
************************************************************************
************************************************************************
************************************************************************
************************************************************************
************************************************************************
************************************************************************
************************************************************************
************************************************************************
************************************************************************
************************************************************************
************************************************************************
************************************************************************
************************************************************************
************************************************************************
************************************************************************
************************************************************************
************************************************************************
************************************************************************
************************************************************************
************************************************************************
************************************************************************
************************************************************************
************************************************************************
************************************************************************
*: EOF: PROCS.PRG


procedure panelbox
parameters y1,x1,y2,x2
c_temp=setcolor()
set color to (c_blwh)
@ y1,x1 to y2,x2
set color to (c_mainwh)
for n=1 to (y2-y1)-1
   @ y1+n,x1+1 say space((x2-x1)-1)
next

return

PROCEDURE MODULEINFO
PARAMETERS dir_mlist, t_mod_name

SELECT (0)
USE &dir_mlist.modlist.dbf
GO TOP
DO WHILE .NOT. EOF()
        IF UPPER(modulename) = t_mod_name
                EXIT
        ENDIF
        SKIP
ENDDO

IF .NOT. EOF()
        DO lightcol
        DO panelbox WITH 0,0,2,79
        banner = LTRIM(TRIM(moddescr))
        b_len = LEN(banner)
        IF b_len > 1
                l_pos = 40 - (b_len / 2)
        ELSE
                l_pos = 40
        ENDIF
        @ 1,l_pos SAY banner

        DO panelbox WITH 20,0,24,79
        @ 21,  1 SAY modulename +" Ver. "+modversion
        @ 22,  1 SAY "Written " + DTOC(datewrit)
        @ 23,  1 SAY "Updated " + DTOC(datemod)
        @ 21, 44 SAY "Thermal Heat Exchangers (Irl.) Ltd."
        @ 22, 71 SAY "Drogheda"
        @ 23, 61 SAY "Copyright (C) 1995"
ENDIF
USE

RETURN
************************************************************************

function NPOPUPC
parameters TT, LL, BB, RR, POPUPFIELD, default, title 

private c_temp
private r_temp
private t_ct
private T_PREV
PRIVATE popupf[255]

* max array size
title=alltrim(title)
lentl=len(title)
c_temp=setcolor()
do holdscreen
r_temp=recno()
go top
t_ct=1
t_lfld=len(&POPUPFIELD.)
do while .not. eof()
        popupf[t_ct]=&POPUPFIELD
        t_ct=t_ct+1
        skip
        if eof()
                exit
        endif
enddo

items=t_ct-1
if (BB-TT) > items
     bb=tt+items+1
endif


rr=ll+t_lfld+1

do shadowbox with TT, LL, BB, RR
*@ tt,(((rr)/2)-(lentl/2)-5) say title
set color to (C_mainwb)
@ tt,((ll-1)+(rr-ll)/2)-((lentl/3)) say title
do lightcol
T_select = achoice(TT+1,LL+1,BB-1,RR-1,PopupF,.t.,"",default)
goto r_temp
if T_ct=1
    T_select=-9999
endif

do popscreen
set color to (c_temp)
return t_select


