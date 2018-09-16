REM $DYNAMIC
DECLARE FUNCTION REPLACE$ (REP$)
DECLARE SUB VERSION ()
DECLARE SUB HELP2 ()
DECLARE SUB MULTIPLE (FILESPEC$)
DECLARE SUB STRUCTUR (DBFNAME$)
DECLARE SUB FILTER (X$)
DECLARE SUB HELPme ()
DECLARE FUNCTION BIN2DEC$ (BIN AS STRING)
DECLARE FUNCTION BIN2HEX$ (BIN AS STRING)
DECLARE FUNCTION HEX2DEC$ (HEX AS STRING)
DECLARE FUNCTION HEX2BIN$ (HEX AS STRING)
DECLARE FUNCTION DEC2BIN$ (DEC AS STRING)
DECLARE FUNCTION DEC2HEX$ (DEC AS STRING)
COMMON SHARED COM$
COMMON SHARED HEADERLENGTH
COMMON SHARED X$
COMMON SHARED DBFNAME$
COMMON SHARED FILESPEC$
COMMON SHARED REP$
COMMON SHARED MULTIDBF
COMMON SHARED SUMMARY
SUMMARY = 0
MULTIDBF = 0

DIM SHARED FLDARRAY$(400)

COM$ = COMMAND$
'COM$ = "DRGRECS.DBF"

COM$ = UCASE$(LTRIM$(RTRIM$(COM$)))
IF COM$ = "" THEN
        CALL HELPme
END IF

IF INSTR(COM$, "/") THEN
        IF INSTR(COM$, "/S") > 0 THEN
                COM$ = REPLACE$("/S")
                SUMMARY = 1
                DBFNAME$ = COM$
        END IF

        IF INSTR(COM$, "/HELP") > 0 THEN
                CALL HELPme
        END IF
        IF INSTR(COM$, "/INFO") > 0 OR INSTR(COM$, "/I") > 0 THEN
                CALL HELP2
        END IF
        IF INSTR(COM$, "?") > 0 THEN
                CALL HELPme
        END IF
        IF INSTR(COM$, "/V") > 0 THEN
                CALL VERSION
        END IF
        IF INSTR(COM$, "/") > 0 THEN
                CALL HELPme
        END IF

ELSE
        DBFNAME$ = COM$
END IF
IF INSTR(DBFNAME$, ".") = 0 AND INSTR(DBFNAME$, "*") = 0 THEN
        DBFNAME$ = DBFNAME$ + ".DBF"
        CALL STRUCTUR(DBFNAME$)
ELSE
        IF INSTR(DBFNAME$, "*") > 0 THEN
                FILESPEC$ = COM$
                PRINT "Compiling file list, please wait...";
                
                IF INSTR(FILESPEC$, ".") = 0 THEN
                                FILESPEC$ = FILESPEC$ + ".DBF"
                END IF
                MULTIDBF = 1
                CALL MULTIPLE(FILESPEC$)
                PRINT ""
                PRINT
                OPEN "DBFSTRUC.TMP" FOR INPUT AS #2
                FOR lcount = 1 TO HEADERLENGTH
                        LINE INPUT #2, fdesc$
                NEXT

                DO UNTIL EOF(2)
TRYNEXT1:
                        LINE INPUT #2, fdesc$
                        IF LEFT$(fdesc$, 1) = " " THEN
                                EXIT DO
                        END IF
                        dir$ = RTRIM$(MID$(fdesc$, 14, 5))
                       
                        IF RTRIM$(MID$(fdesc$, 10, 3)) <> "" THEN
                                DBFNAME$ = RTRIM$(LTRIM$(LEFT$(fdesc$, 8))) + "." + RTRIM$(MID$(fdesc$, 10, 3))
                        ELSE
                                DBFNAME$ = RTRIM$(LTRIM$(LEFT$(fdesc$, 8)))
                        END IF
                       
                        CALL STRUCTUR(DBFNAME$)
                 LOOP
                 CLOSE #2
                 ON ERROR GOTO IGNORE
                 KILL "DBFSTRUC.TMP"

        ELSE
                CALL STRUCTUR(DBFNAME$)
        END IF
END IF


SYSTEM
'=============================================================================
IGNORE:
RESUME NEXT


ERRHANDLER:
SELECT CASE ERR
        CASE IS = 9
                PRINT "[ERR:09] Field Descriptor Array out of range."
        CASE IS = 53
                PRINT "[ERR:53] '" + DBFNAME$ + "' File not found"
        CASE IS = 70
                PRINT "[ERR:70] '" + DBFNAME$ + "' Permission denied"
                IF MULTIDBF = 1 THEN
                        PRINT
                        GOTO TRYNEXT1
                END IF
        CASE IS = 75
                PRINT "[ERR:75] Path/File access error"
        CASE IS = 76
                PRINT "[ERR:76] Path not found"
        CASE ELSE
                PRINT "[ERR:" + LEFT$("0" + LTRIM$(STR$(ERR)), 2) + "] Unexpected error"
END SELECT
SYSTEM

'IDEAS
'summary info only switch?
'

REM $STATIC
FUNCTION BIN2DEC$ (BIN AS STRING) STATIC
        BIN = UCASE$(LTRIM$(RTRIM$(BIN)))
        LOS = LEN(BIN)
        POSCT = 1
        DECVAL = 0
        DO WHILE POSCT < LOS + 1
     
                VAL$ = MID$(BIN, (LOS - (POSCT - 1)), 1)
                CALCVAL = (2 ^ (POSCT - 1)) * VAL(VAL$)
                DECVAL = DECVAL + CALCVAL
                POSCT = POSCT + 1

        LOOP
        BIN2DEC$ = LTRIM$(RTRIM$(STR$(DECVAL)))
END FUNCTION

FUNCTION BIN2HEX$ (BIN AS STRING)
        B2D$ = BIN2DEC$(BIN)            'Bin-to-Dec
        BIN2HEX$ = HEX$(VAL(B2D$))      'Dec-to-Hex
END FUNCTION

FUNCTION DEC2BIN$ (DEC AS STRING)
        D2B$ = HEX$(VAL(DEC))           'Dec-to-Hex
        DEC2BIN$ = HEX2BIN$(D2B$)       'Hex-to-Bin
END FUNCTION

FUNCTION DEC2HEX$ (DEC AS STRING)
        DEC2HEX$ = HEX$(VAL(DEC))
END FUNCTION

SUB FILTER (X$) STATIC

'filter command$ of spaces (chr$(32)) and full-colons (:)(chr$(58)
n = 1
z$ = ""
DO WHILE n < LEN(X$) + 1
        IF MID$(X$, n, 1) = CHR$(0) THEN     'CHR$(32) OR MID$(X$, n, 1) = CHR$(58)
                z$ = z$ + " "
                MID$(z$, n, 1) = CHR$(32)
        ELSE
                z$ = z$ + MID$(X$, n, 1)
        END IF
n = n + 1
LOOP
X$ = z$
END SUB

SUB HELP2
CLS
PRINT "================== dBASE III+ Header Structure =============================="
PRINT "1 Byte           - Valid dBASE III+ file 03h = No memo, 83h = Memo"
PRINT "1 Byte           - Year of update"
PRINT "1 Byte           - Month of update"
PRINT "1 Byte           - Day of update"
PRINT "32-bit No.       - No. of Records, initially 0"
PRINT "16-bit No.       - No. of Bytes in Header."
PRINT "16-bit No.       - No. of Bytes in Record."
PRINT "3 Bytes          - Reserved"
PRINT "13 Bytes         - Reserved for dBASE III+ on Network"
PRINT "4 Bytes          - Reserved"
PRINT "================== dBASE III+ Field Descriptor Array ========================"
PRINT "11 Bytes         - Field Name ASCII (zero-filled)"
PRINT "1 Byte           - Field Type (C, N, L, D or M)"
PRINT "32-bit No.       - Field Data Address (Memory) Not useful on disk"
PRINT "1 Byte           - Field Length in hex"
PRINT "1 Byte           - Field Decimal count in hex"
PRINT "2 Bytes          - Reserved for dBASE III+ on LAN"
PRINT "1 Byte           - Work Area I.D."
PRINT "2 Bytes          - Reserved for dBASE III+ on LAN"
PRINT "1 Byte           - Set Fields Flag"
PRINT "8 Bytes          - Reserved for dBASE III+ on LAN"
PRINT "================== 0Dh as the field terminator before data records =========="
SYSTEM
END SUB

SUB HELPme
PRINT "DBFSTRUC v1.5 - Read & Display dBASE III+ Database Structure."
PRINT
PRINT "DBFSTRUC dbasefile"
PRINT
PRINT "  dbasefile        dBASE III+ database file to read (.DBF extention optional)"
PRINT "                   use of * wildcard is supported. (* on its own = *.DBF)"
PRINT "  /help            Display this help screen."
PRINT "  /info            Info on dBASE III+ structure (Header, Field Descriptor Array)"
PRINT "  /?               Display this help screen."
PRINT "  /i               see /info."
PRINT "  /s               Summary information only."
PRINT
PRINT
SYSTEM
END SUB

FUNCTION HEX2BIN$ (HEX AS STRING) STATIC
HEX = UCASE$(LTRIM$(RTRIM$(HEX)))
LOS = LEN(HEX)
POSCT = 1
BIN$ = ""
DO WHILE POSCT < LOS + 1
    
        VAL$ = MID$(HEX, (LOS - (POSCT - 1)), 1)

        SELECT CASE VAL$
                CASE IS = "0"
                        VAL$ = "0000"
                CASE IS = "1"
                        VAL$ = "0001"
                CASE IS = "2"
                        VAL$ = "0010"
                CASE IS = "3"
                        VAL$ = "0011"
                CASE IS = "4"
                        VAL$ = "0100"
                CASE IS = "5"
                        VAL$ = "0101"
                CASE IS = "6"
                        VAL$ = "0110"
                CASE IS = "7"
                        VAL$ = "0111"
                CASE IS = "8"
                        VAL$ = "1000"
                CASE IS = "9"
                        VAL$ = "1001"
                CASE IS = "A"
                        VAL$ = "1010"
                CASE IS = "B"
                        VAL$ = "1011"
                CASE IS = "C"
                        VAL$ = "1100"
                CASE IS = "D"
                        VAL$ = "1101"
                CASE IS = "E"
                        VAL$ = "1110"
                CASE IS = "F"
                        VAL$ = "1111"
        END SELECT

        BIN$ = VAL$ + BIN$
        POSCT = POSCT + 1

LOOP
HEX2BIN$ = BIN$
END FUNCTION

FUNCTION HEX2DEC$ (HEX AS STRING)
HEX = LTRIM$(RTRIM$(HEX))
LOS = LEN(HEX)
POSCT = 1
DECVAL = 0
DO WHILE POSCT < LOS + 1
VAL$ = MID$(HEX, (LOS - (POSCT - 1)), 1)

SELECT CASE VAL$
        CASE IS = "A"
                VAL$ = "10"
        CASE IS = "B"
                VAL$ = "11"
        CASE IS = "C"
                VAL$ = "12"
        CASE IS = "D"
                VAL$ = "13"
        CASE IS = "E"
                VAL$ = "14"
        CASE IS = "F"
                VAL$ = "15"
END SELECT
CALCVAL = (16 ^ (POSCT - 1)) * VAL(VAL$)
DECVAL = DECVAL + CALCVAL
POSCT = POSCT + 1
LOOP
HEX2DEC$ = STR$(DECVAL)
END FUNCTION

SUB MULTIPLE (FILESPEC$)

SHELL "DIR " + FILESPEC$ + ">DBFSTRUC.TMP"

OPEN "DBFSTRUC.TMP" FOR INPUT AS #2

IF LOF(2) < 140 THEN
        PRINT
        PRINT "[ERR:53] Bad filespec or file not found"
        CLOSE (2)
        SHELL "DEL DBFSTRUC.TMP>NUL"
        SYSTEM
END IF

HEADERLENGTH = 0

DO
        LINE INPUT #2, fdesc$
        HEADERLENGTH = HEADERLENGTH + 1
LOOP WHILE LTRIM$(LEFT$(fdesc$, 1)) = ""

HEADERLENGTH = HEADERLENGTH - 1
'IF INSTR(FILESPEC$, "/S") > 0 THEN
'        HEADERLENGTH = HEADERLENGTH + 2
'END IF
'DO UNTIL EOF(2)
'        IF LEFT$(fdesc$, 1) = " " THEN
'                EXIT DO
'        END IF
'
'        reclen = LEN(fdesc$)
'        'FILENAME$ = LEFT$(fdesc$, 8)
'        dir$ = RTRIM$(MID$(fdesc$, 14, 5))
'        FTIME$ = RIGHT$(RTRIM$(fdesc$), 6)
'        'fdesc$ = LEFT$(LTRIM$(fdesc$), 6)
'        fdesc$ = LTRIM$(LEFT$(fdesc$, reclen - 6))
'        tt$ = RTRIM$(fdesc$)
'        FDATE$ = RIGHT$(tt$, 8)
'        mt1 = VAL(LEFT$(FDATE$, 2))
'        mt2 = VAL(MID$(FDATE$, 4, 2))
'
'        LINE INPUT #2, fdesc$
'LOOP

CLOSE 2

END SUB

FUNCTION REPLACE$ (REP$)
        REPLACEPOS = INSTR(COM$, REP$)
        LOS = LEN(COM$)
        NEW$ = ""
        SPOS = 1
        DO WHILE SPOS < (LOS + 1)
                VAL$ = MID$(COM$, SPOS, 1)
                IF SPOS = REPLACEPOS THEN
                        VAL$ = ""
                        SPOS = SPOS + 1
                END IF
                NEW$ = RTRIM$(NEW$ + VAL$)
                SPOS = SPOS + 1
        LOOP
        NEW$ = LTRIM$(RTRIM$(NEW$))
        REPLACE$ = NEW$
END FUNCTION

SUB STRUCTUR (DBFNAME$)
ON ERROR GOTO ERRHANDLER
OPEN DBFNAME$ FOR INPUT AS #1
CLOSE #1

ON ERROR GOTO ERRHANDLER
OPEN DBFNAME$ FOR BINARY AS #1
ON ERROR GOTO ERRHANDLER
HEADER$ = INPUT$(30, 1)
'CHECK FOR VALID DBASE III+ FORMAT
IF LEFT$(HEADER$, 1) <> CHR$(3) AND LEFT$(HEADER$, 1) <> CHR$(131) THEN 'HEX 03 OR HEX 83
        PRINT "[ERR:64] '" + DBFNAME$ + "' Not a valid dBASE III+ file or bad file name."
        CLOSE #1
        SYSTEM
END IF
PRINT
PRINT "Structure for database: " + DBFNAME$

'PRINT NO OF RECORDS
RECORD$ = MID$(HEADER$, 5, 4)
rec$ = ""
FOR B = 4 TO 1 STEP -1
rec$ = rec$ + RIGHT$("0" + HEX$(ASC(MID$(RECORD$, B, 1))), 2)
NEXT
rec$ = BIN2DEC$(HEX2BIN$(rec$))
PRINT "Number of data Records: " + RIGHT$("      " + rec$, 6)

'PRINT DATE LAST UPDATE
YY$ = STR$(ASC(MID$(HEADER$, 2, 1)))        '1 Byte - Year of update
MM$ = STR$(ASC(MID$(HEADER$, 3, 1)))            '1 Byte - Day of update
DD$ = STR$(ASC(MID$(HEADER$, 4, 1)))         '1 Byte - Month of update
YY$ = RIGHT$("00" + LTRIM$(RTRIM$(YY$)), 2)
MM$ = RIGHT$("00" + LTRIM$(RTRIM$(MM$)), 2)
DD$ = RIGHT$("00" + LTRIM$(RTRIM$(DD$)), 2)
PRINT "Date of last update   : " + DD$ + "/" + MM$ + "/" + YY$

buffer$ = SPACE$(30)
X = 0
n = 33
WHILE ASC(LEFT$(buffer$, 1)) <> ASC(CHR$(13))
IF INKEY$ = CHR$(3) OR INKEY$ = CHR$(27) THEN
        SYSTEM
END IF

GET #1, n, buffer$
IF ASC(LEFT$(buffer$, 1)) <> ASC(CHR$(13)) THEN
        ON ERROR GOTO ERRHANDLER
        FLDARRAY$(X) = buffer$
        X = X + 1
        n = n + 32
END IF
WEND
CLOSE #1

TOTALFIELDS = X
IF SUMMARY = 1 THEN
        PRINT "Total Fields          :" + STR$(X)
        GOTO ENDING
END IF

PRINT "Field  Field Name  Type       Width    Dec"

XWIDTH = 1

FOR C = 0 TO TOTALFIELDS - 1
IF INKEY$ = CHR$(3) OR INKEY$ = CHR$(27) THEN
        SYSTEM
END IF

FLDNO = C + 1
PRINT RIGHT$("     " + STR$(FLDNO), 5);
PRINT "  ";

FLDNAME$ = LEFT$(FLDARRAY$(C), 11)
CALL FILTER(FLDNAME$)
PRINT FLDNAME$;
PRINT " ";
fldtype$ = MID$(FLDARRAY$(C), 12, 1)
SELECT CASE fldtype$
        CASE IS = "C"
                PRINT "Character  ";

        CASE IS = "D"
                PRINT "Date       ";

        CASE IS = "L"
                PRINT "Logical    ";

        CASE IS = "N"
                PRINT "Numeric    ";

        CASE IS = "M"
                PRINT "Memo       ";
END SELECT

PRINT "  ";
FLDLEN$ = STR$(ASC(MID$(FLDARRAY$(C), 17, 1)))
PRINT RIGHT$("   " + FLDLEN$, 3);

PRINT "     ";
XWIDTH = XWIDTH + VAL(FLDLEN$)
FLDDEC$ = STR$(ASC(MID$(FLDARRAY$(C), 18, 1)))
IF VAL(FLDDEC$) <> 0 THEN
        PRINT RIGHT$("  " + FLDDEC$, 2)
ELSE
        PRINT "  "
END IF

NEXT
PRINT "** Total **                     " + RIGHT$("   " + LTRIM$(STR$(XWIDTH)), 3)
PRINT
ENDING:
END SUB

SUB VERSION
PRINT "DBFSTRUC v1.5 - Read & Display Xbase Database Structure."
PRINT "Designed & Programmed by K. Robertson (C) 25/04/97"
PRINT
PRINT "VERSION    NOTES"
PRINT "อออออออ    ออออออออออออออออออออออออออออออออออออออออออออออออออออออออออออออออ"
PRINT "1.0        First version."
PRINT "1.1        Change to allow HELP.DBF to be dispalyed. Help switch to include"
PRINT "           forward slash, for example: /help"
PRINT "1.2        Add wildcard support for *.DBF, automatically add .DBF to"
PRINT "           filename if it doesnt include it, use . to specify no extention."
PRINT "1.2b       Check for valid .DBF & .DBT files, warn if not a valid file."
PRINT "1.2c       Modified Field Array Length to read Field Descriptor Array"
PRINT "           containing up to 128 fields. Add /v switch for version info."
PRINT "1.3        Add Summary info switch /S and replace function."
PRINT "1.4        Add Extended field support. 128 or more Fields."
PRINT "1.4b       Removed Extended field support. Default is for <=400 Fields."
PRINT "1.4c       Fixed bug. Delete temporary file when finished."
PRINT "1.4d       Fixed bug. Skip permission denied and try next file."
PRINT "1.5        Update recognition for other valid Xbase Tables."
SYSTEM
END SUB

