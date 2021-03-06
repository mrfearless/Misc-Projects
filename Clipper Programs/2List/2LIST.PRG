PUBLIC totfiles:= 0
PUBLIC totdir:=0
PUBLIC totfsize:= 0
PUBLIC PCM:=13         && PC Magazine Header Info
PUBLIC DSDIRSTR:=32     && DOS Directory String Length
PUBLIC bsROOTSECT 
PUBLIC bsSECTSIZE
PUBLIC bsCLUSTSIZE
PUBLIC bsFATCNT
PUBLIC bsFATSIZE
PUBLIC bsRESSECT

PARAMETER param1, param2
IF EMPTY(PARAM1) .AND. EMPTY(PARAM2)
        DO HELP
        RETURN
ENDIF
IF FILE(param1)
        IMGFILE=param1
        *IMGFILE="C:\UTILS\MISC\BOOTDISK.IMG"
ELSE
        ? "2List - Lists The Contents Of An Diskette Image File Created By 2FILE"
        ?
        ? "2List Error - Image File Does Not Exist: "+param1
        ?
        QUIT
ENDIF
IF EMPTY(param2)
        set alternate to 2list.lst
ELSE
        set alternate to param2
ENDIF

NHANDLE:=FOPEN(IMGFILE,0)
IF FERROR()!=0
        ? "2List - Lists The Contents Of An Diskette Image File Created By 2FILE"
        ?
        ? "2List Error - Image Open Error: "+IMGFILE
        ?
        set alternate off
        set alternate to
        QUIT
ENDIF
set alternate on
? "2LIST v1.0 - Lists The Contents Of An Diskette Image File Created By 2FILE.EXE"
? "Keith S. Robertson 09/11/1998"
DO BOOTSECT
?
?"Searching: "+IMGFILE
?
?"File Name     File Size     File Date   File Time  Attrib  Cluster  Sector"
?"============  ============  ==========  =========  ======  =======  ======"
ROOT=19
?
DIRINFO(nhandle,ROOT,"")
*?"============  ============  ==========  =========  ======  =======  ======"
?"Total files listed:"
?padl(alltrim(str(totfiles)),6,SPACE(1))+IIF(totfiles!=1," Files "," File ")+padl(totfsize,13,space(1))+" bytes"
?padl(alltrim(str(totdir)),6,SPACE(1))+IIF(totdir!=1," Directories "," Directory ")
?
?"Image Name: "+IMGFILE
?
set alternate off
set alternate to
RETURN

FUNCTION DUMMY(nhandle)
FSEEK(NHANDLE,9741,0)   && Start
DSDIRSTR=32     && DOS Directory String Length
BUFFER=SPACE(DSDIRSTR)
FREAD(NHANDLE,@BUFFER,DSDIRSTR)
IF NAMEVALID(BUFFER)
        totfiles:=totfiles+1
        ?DOSFNAME(BUFFER)
        ??SPACE(2)
        IF !FT_ISBIT(SUBSTR(BUFFER,12,1),4)    && Directory
                ??DOSFSIZE(BUFFER)
        ELSE
                ??"<DIR>"+SPACE(7)
        ENDIF
        ??SPACE(2)
        ??DOSFDATE(BUFFER)
        ??SPACE(2)
        ??DOSFTIME(BUFFER)
        ??SPACE(3)
        ??DOSFATTR(BUFFER)
        ??SPACE(2)
        ??DOSFCLUST(BUFFER)
ENDIF
CHK=LEFT(BUFFER,1)
DO WHILE CHK!=CHR(0)
        FREAD(NHANDLE,@BUFFER,DSDIRSTR)
        CHK=LEFT(BUFFER,1)
        IF CHK!=CHR(0)
                IF NAMEVALID(BUFFER)
                        totfiles:=totfiles+1
                        ?DOSFNAME(BUFFER)
                        ??SPACE(2)
                        IF !FT_ISBIT(SUBSTR(BUFFER,12,1),4)    && Directory
                                ??DOSFSIZE(BUFFER)
                        ELSE
                                ??"<DIR>"+SPACE(7)
                        ENDIF
                        ??SPACE(2)
                        ??DOSFDATE(BUFFER)
                        ??SPACE(2)
                        ??DOSFTIME(BUFFER)
                        ??SPACE(3)
                        ??DOSFATTR(BUFFER)
                        ??SPACE(2)
                        ??DOSFCLUST(BUFFER)

                ENDIF
        ENDIF
ENDDO
?"============  ============  ==========  =========  ======  =========== =========="
? "Total: "+padl(alltrim(str(totfiles)),6,SPACE(1))+IIF(totfiles!=1," Files: ","File: ")+padl(totfsize,12,space(1))+" bytes"
*?"Total: "+padr(alltrim(str(totfiles))+IIF(totfiles!=1," Files:","File:"),14,space(1))+padl(totfsize,12,space(1))
?
?"Image Name: "+IMGFILE
?
set alternate off
set alternate to
RETURN

FUNCTION DOSFNAME(STRING)
LOCAL DOSFNAME:=LEFT(STRING,8)+" "+SUBSTR(STRING,9,3)
RETURN DOSFNAME

FUNCTION DOSFSIZE(STRING)
LOCAL DOSFSIZE:=PADL(BIN2L(SUBSTR(STRING,29,4)),12,SPACE(1))
RETURN DOSFSIZE

FUNCTION DOSFCLUST(STRING)
LOCAL DOSFCLUST
LOCAL BIT26:=SUBSTR(STRING,27,1)
LOCAL BIT27:=SUBSTR(STRING,28,1)
DOSFCLUST:=PADL(BIN2I(SUBSTR(STRING,27,2)),6,SPACE(1))
RETURN DOSFCLUST

FUNCTION DOSFDATE(STRING)
LOCAL DOSFDATE:=""
LOCAL BIT24:=SUBSTR(STRING,25,1)     && Byte 24 of 32 Byte DOS File & Directory Information Record
LOCAL BIT25:=SUBSTR(STRING,26,1)     && Byte 25 of 32 Byte DOS File & Directory Information Record
LOCAL X
X := 0
FOR N=1 TO 5    && Bits 0-4 of Byte 24 contain Days
        IF FT_ISBIT(BIT24,N-1)
                X := X + (2^ (N-1))
        ENDIF
NEXT
DD := RIGHT("0"+ALLTRIM(STR(INT(X))),2)
X := 0
FOR N=1 TO 3    && Bits 5-7 of Byte 24 & Bit 1 Of Byte 25 contain Month
        IF FT_ISBIT(BIT24,N+4)
                X := X + (2^ (N-1))
        ENDIF
NEXT
IF FT_ISBIT(BIT25,0)
        X := X + (2^3)
ENDIF
MM := RIGHT("0"+ALLTRIM(STR(INT(X))),2)
X := 0
FOR N=1 TO 7    && Bits 1-7 of Byte 25 contain years (+1980 to make current)
        IF FT_ISBIT(BIT25,N)
                X := X + (2^ (N-1))
        ENDIF
NEXT
YY := RIGHT("0"+ALLTRIM(STR(1980+INT(X))),4)
DOSFDATE:=DD+"/"+MM+"/"+YY
RETURN DOSFDATE

FUNCTION DOSFTIME(STRING)
LOCAL DOSFTIME:=""
LOCAL BIT22:=SUBSTR(STRING,23,1)
LOCAL BIT23:=SUBSTR(STRING,24,1)
LOCAL X
X := 0
FOR N=1 TO 5    && Bits 0-4 of Byte 22 Contains Seconds (/2 for current)
        IF FT_ISBIT(BIT22,N-1)
                X := X + (2^ (N-1))
        ENDIF
NEXT
Secs := RIGHT("0"+ALLTRIM(STR(INT(X))),2)
X := 0
FOR N=1 TO 3
        IF FT_ISBIT(BIT22,N+4)
                X := X + (2^ (N-1))
        ENDIF
NEXT
FOR N=1 TO 3
        IF FT_ISBIT(BIT23,N-1)
                X := X + (2^ (N+2))
        ENDIF
NEXT
Mins := RIGHT("0"+ALLTRIM(STR(INT(X))),2)
X := 0
FOR N=3 TO 7
        IF FT_ISBIT(BIT23,N)
                X := X + (2^ (N-3))
        ENDIF
NEXT
Hrs := RIGHT("0"+ALLTRIM(STR(INT(X))),2)
DOSFTIME:=Hrs+":"+Mins+":"+Secs
RETURN DOSFTIME

FUNCTION DOSFATTR(STRING)
LOCAL DOSFATTR:=""
LOCAL BIT11:=SUBSTR(STRING,12,1)
IF FT_ISBIT(BIT11,0)    && Read Only
        DOSFATTR := DOSFATTR + "R"
ELSE
        DOSFATTR := DOSFATTR + " "
ENDIF
IF FT_ISBIT(BIT11,1)    && Hidden
        DOSFATTR := DOSFATTR + "H"
ELSE
        DOSFATTR := DOSFATTR + " "
ENDIF
IF FT_ISBIT(BIT11,2)    && System
        DOSFATTR := DOSFATTR + "S"
ELSE
        DOSFATTR := DOSFATTR + " "
ENDIF
IF FT_ISBIT(BIT11,5)    && Archive
        DOSFATTR := DOSFATTR + "A"
ELSE
        DOSFATTR := DOSFATTR + " "
ENDIF
IF FT_ISBIT(BIT11,3)    && Volume
        DOSFATTR := DOSFATTR + "V"
ELSE
        DOSFATTR := DOSFATTR + " "
ENDIF
IF FT_ISBIT(BIT11,4)    && Directory
        DOSFATTR := DOSFATTR + "D"
ELSE
        DOSFATTR := DOSFATTR + " "
ENDIF

RETURN DOSFATTR

FUNCTION NAMEVALID(String)
IF FT_ISBIT(SUBSTR(STRING,12,1),3)    && Volume
        RETURN .F.
ENDIF
IF LEFT(STRING,1)=CHR(5) .OR. LEFT(STRING,1)=CHR(229) .OR. LEFT(STRING,1)=CHR(46)
        RETURN .F.
ENDIF
RETURN .T.

PROCEDURE HELP
? "2LIST v1.0 - Lists The Contents Of An Diskette Image File Created By 2FILE.EXE"
? "Keith S. Robertson 09/11/1998"
?
? "2LIST ImageFile [listfile]"
?
? " ImageFile   The Image File To List Contents For. Created By 2FILE.EXE"
? " listfile    Optional File To Redirect Output To."
?
QUIT


PROCEDURE BOOTSECT
FSEEK(NHANDLE,13,0)     && Start of PC Magazine Image OF Diskette
* Start BOOT SECTOR
BUFFER:=SPACE(3)
bsJMP:=FREAD(NHANDLE,@BUFFER,3)
BUFFER:=SPACE(8)
FREAD(NHANDLE,@BUFFER,8)
bsOEM:=BUFFER
BUFFER:=SPACE(2)
FREAD(NHANDLE,@BUFFER,2)          && Start of BIOS PARAMETER BLOCK (BPB)
bsSECTSIZE:=BIN2I(BUFFER)
BUFFER:=SPACE(1)
FREAD(NHANDLE,@BUFFER,1)
bsCLUSTSIZE:=BIN2I(BUFFER)
BUFFER:=SPACE(2)
FREAD(NHANDLE,@BUFFER,2)
bsRESSECT:=BIN2I(BUFFER)
BUFFER:=SPACE(1)
FREAD(NHANDLE,@BUFFER,1)
bsFATCNT:=BIN2I(BUFFER)
BUFFER:=SPACE(2)
FREAD(NHANDLE,@BUFFER,2)
bsROOTSIZE:=BIN2I(BUFFER)
BUFFER:=SPACE(2)
FREAD(NHANDLE,@BUFFER,2)
bsTOTALSECT:=BIN2I(BUFFER)
BUFFER:=SPACE(1)
FREAD(NHANDLE,@BUFFER,1)
bsMEDIA:=FT_BYT2HEX(BUFFER)
BUFFER:=SPACE(2)
FREAD(NHANDLE,@BUFFER,2)
bsFATSIZE:=BIN2i(BUFFER)
BUFFER:=SPACE(2)
FREAD(NHANDLE,@BUFFER,2)
bsTRACKSECT:=BIN2i(BUFFER)
BUFFER:=SPACE(2)
FREAD(NHANDLE,@BUFFER,2)
bsHEADCNT:=BIN2i(BUFFER)
BUFFER:=SPACE(4)
FREAD(NHANDLE,@BUFFER,4)
bsHIDENSECT:=BIN2L(BUFFER)
BUFFER:=SPACE(4)
FREAD(NHANDLE,@BUFFER,4)            && End of BIOS PARAMETER BLOCK (BPB)
bsHUGESECT:=BIN2L(BUFFER)
BUFFER:=SPACE(1)
FREAD(NHANDLE,@BUFFER,1)
bsDRIVENUM:=FT_BYT2HEX(BUFFER)
BUFFER:=SPACE(1)
FREAD(NHANDLE,@BUFFER,1)
bsRESERV:=BUFFER
BUFFER:=SPACE(1)
FREAD(NHANDLE,@BUFFER,1)
bsBOOTSIGN:=FT_BYT2HEX(BUFFER)
BUFFER:=SPACE(4)
FREAD(NHANDLE,@BUFFER,4)
bsVOLID:=LEFT(FT_BYT2HEX(RIGHT(BUFFER,1)),2) + LEFT(FT_BYT2HEX(SUBSTR(BUFFER,3,1)),2) + "-" + LEFT(FT_BYT2HEX(SUBSTR(BUFFER,2,1)),2) + LEFT(FT_BYT2HEX(LEFT(BUFFER,1)),2)
BUFFER:=SPACE(11)
FREAD(NHANDLE,@BUFFER,11)
bsVOLABEL:=BUFFER
BUFFER:=SPACE(8)
FREAD(NHANDLE,@BUFFER,8)
bsFILESTYPE:=BUFFER

bsROOTSECT:=(bsROOTSIZE * 32) / bsSECTSIZE 

* End BOOT SECTOR
*?
*?"OEM: "+bsOEM
*?"Sector Size: "+ALLTRIM(STR(bsSECTSIZE))
*?"Cluster Size: "+ALLTRIM(STR(bsCLUSTSIZE))
*?"Reserved Sectors: "+ALLTRIM(STR(bsRESSECT))
*?"File Allocation Tables: "+ALLTRIM(STR(bsFATCNT))
*?"Root Size: "+ALLTRIM(STR(bsROOTSIZE))
*?"Total Sectors: "+ALLTRIM(STR(bsTOTALSECT))
*?"Media: "+bsMEDIA
*?"File Allocation Table Size: "+ALLTRIM(STR(bsFATSIZE))
*?"Sectors Per Track: "+ALLTRIM(STR(bsTRACKSECT))
*?"No of Heads: "+ALLTRIM(STR(bsHEADCNT))
*?"No of Hidden Sectors: "+ALLTRIM(STR(bsHIDENSECT))
*?"Total Sectors (Huge): "+ALLTRIM(STR(bsHUGESECT))
*?"Active Partition: "+ALLTRIM(STR(bsDRIVENUM))
*?"Boot Signature: "+bsBOOTSIGN
*?"Volume Serial Number: "+bsVOLID
*?"Volume Label: "+bsVOLABEL
*?"File Type: "+bsFILESTYPE
?
? "Diskette Image Technical Information"
? "=============================================================================="
? "Volume Label : "+bsVOLABEL+SPACE(1)
??"Serial Number : "+bsVOLID+SPACE(2)
??"OEM Signature : "+bsOEM
? "Media Byte   : "+bsMEDIA+SPACE(9)
??"Boot Signature: "+bsBOOTSIGN+SPACE(8)
??"Partition     : "+bsDRIVENUM
? "File Type    : "+bsFILESTYPE+SPACE(4)
??"Root Size     : "+PADR(ALLTRIM(STR(bsROOTSIZE)),5,SPACE(1))+SPACE(6)
??"No of Heads   : "+ALLTRIM(STR(bsHEADCNT))
?
? "File Allocation Tables    : "+STR(bsFATCNT)
? "File Allocation Table Size: "+STR(bsFATSIZE)
?
? "Cluster Size              : "+STR(bsCLUSTSIZE)
? "Sector Size               : "+STR(bsSECTSIZE)
? "Sectors Per Track         : "+STR(bsTRACKSECT)
? "Number of Reserved Sectors: "+STR(bsRESSECT)
? "Number of Hidden Sectors  : "+STR(bsHIDENSECT)
?
? "Total Sectors             : "+STR(bsTOTALSECT)
? "Total Sectors (Huge)      : "+STR(bsHUGESECT)
? "=============================================================================="
RETURN

FUNCTION DIRINFO(Fhandle, Sector, CurrentDIR)

        LOCAL Offset := (Sector * (bsSECTSIZE * bsCLUSTSIZE) + PCM )-32
        LOCAL Buffer := SPACE(DSDIRSTR)
        LOCAL ValidEntry := .T.
        LOCAL subtotfiles:= 0
        LOCAL subtotfsize:= 0
        LOCAL aDirList[1,2]     && DIRNAME & START SECTOR

        FSEEK(Fhandle,Offset,0)

        ?"Directory of "+IIF(EMPTY(CurrentDIR),"\",ALLTRIM(CurrentDIR))
        ?

        DO WHILE ValidEntry
                Offset := Offset + 32
                FSEEK(Fhandle,Offset,0)
                FREAD(Fhandle,@Buffer,DSDIRSTR)

                CHK=LEFT(BUFFER,1)
                IF CHK!=CHR(0)
                        ValidEntry := .T.

                        IF NAMEVALID(BUFFER)
                                IF !FT_ISBIT(SUBSTR(BUFFER,12,1),4)
                                        subtotfiles:=subtotfiles+1
                                        totfiles:=totfiles+1
                                        ?DOSFNAME(BUFFER)
                                        ??SPACE(2)
                                        ltFileSize:=DOSFSIZE(BUFFER)
                                        ??ltFileSize
                                        subtotfsize:=subtotfsize+val(ltFileSize)
                                        totfsize:=totfsize+val(ltFileSize)
                                        ??SPACE(2)
                                        ??DOSFDATE(BUFFER)
                                        ??SPACE(2)
                                        ??DOSFTIME(BUFFER)
                                        ??SPACE(3)
                                        ??DOSFATTR(BUFFER)
                                        ??SPACE(3)
                                        ??DOSFCLUST(BUFFER)
                                        ??SPACE(2)
                                        ??DOSFSECT(BUFFER)

                                ELSE    && Directory
                                        totdir := totdir + 1
                                        Dirname:=DOSFNAME(BUFFER)
                                        ?Dirname
                                        ??SPACE(2)
                                        ASIZE(aDirList,LEN(aDirList)+1)
                                        *?LEN(aDirList)
                                        aDirList[LEN(aDirList)-1,1]:=Dirname
                                        ??"<DIR>"+SPACE(7)
                                        ??SPACE(2)
                                        ??DOSFDATE(BUFFER)
                                        ??SPACE(2)
                                        ??DOSFTIME(BUFFER)
                                        ??SPACE(3)
                                        ??DOSFATTR(BUFFER)
                                        ??SPACE(3)
                                        ??DOSFCLUST(BUFFER)
                                        ??SPACE(2)
                                        dirfsect:=DOSFSECT(BUFFER)
                                        ??dirfsect
                                        aDirList[LEN(aDirList)-1,2]:=VAL(dirfsect)

                                ENDIF
                        ENDIF
                ELSE
                        ValidEntry := .F.
                        EXIT
                ENDIF
        ENDDO
        ?padl(alltrim(str(subtotfiles)),6,SPACE(1))+IIF(subtotfiles!=1," Files "," File ")+padl(subtotfsize,13,space(1))+" bytes"
        *padr(alltrim(str(subtotfiles))+IIF(subtotfiles!=1," Files:","File:"),14,space(1))+padl(subtotfsize,12,space(1))
        ?
        IF LEN(aDirList)>1
                FOR N=1 TO LEN(aDirList)-1
                        DIRINFO(nhandle,VAL(dirfsect),ALLTRIM(CurrentDIR)+"\"+DIRNAME)
                NEXT
        ENDIF
RETURN

FUNCTION CLST2SECT(Cluster)
        LOCAL Sector := INT((VAL(Cluster) / bsCLUSTSIZE) + (bsROOTSECT) + (bsFATCNT * bsFATSIZE) + bsRESSECT -2)
RETURN Sector

FUNCTION DOSFSECT(BUFFER)
        LOCAL DOSFSECT := PADL(ALLTRIM(STR(CLST2SECT(DOSFCLUST(BUFFER)))),6,SPACE(6))
RETURN DOSFSECT
