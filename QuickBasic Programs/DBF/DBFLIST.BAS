DECLARE SUB FILTER (X$)
DECLARE SUB HELPme ()
'DECLARE SUB HELP ()
DECLARE FUNCTION BIN2DEC$ (BIN AS STRING)
DECLARE FUNCTION BIN2HEX$ (BIN AS STRING)
DECLARE FUNCTION HEX2DEC$ (HEX AS STRING)
DECLARE FUNCTION HEX2BIN$ (HEX AS STRING)
DECLARE FUNCTION DEC2BIN$ (DEC AS STRING)
DECLARE FUNCTION DEC2HEX$ (DEC AS STRING)
COMMON SHARED X$
DIM FLDARRAY$(50)
COM$ = COMMAND$
'COM$ = "DRGRECS.DBF"
COM$ = UCASE$(LTRIM$(RTRIM$(COM$)))
IF COM$ = "" THEN
        CALL HELPme
END IF
IF INSTR(COM$, "HELP") > 0 THEN
        CALL HELPme
END IF
IF INSTR(COM$, "?") > 0 THEN
        CALL HELPme
END IF
DBFNAME$ = COM$

IF INSTR(DBFNAME$, ".") = 0 THEN
         DBFNAME$ = DBFNAME$ + ".DBF"
END IF
ON ERROR GOTO ERRHANDLER
OPEN DBFNAME$ FOR INPUT AS #1
CLOSE #1

ON ERROR GOTO ERRHANDLER
OPEN DBFNAME$ FOR BINARY AS #1
ON ERROR GOTO 0
HEADER$ = INPUT$(30, 1)
'CHECK FOR VALID DBASE III+ FORMAT
IF LEFT$(HEADER$, 1) <> CHR$(3) AND LEFT$(HEADER$, 1) <> CHR$(83) THEN
        PRINT "[ERR:02] Not a valid dBASE III+ file."
        CLOSE #1
        SYSTEM
END IF
PRINT
PRINT "Listing for database: " + DBFNAME$
PRINT
'PRINT NO OF RECORDS
RECORD$ = MID$(HEADER$, 5, 4)
rec$ = ""
FOR B = 4 TO 1 STEP -1
rec$ = rec$ + RIGHT$("0" + HEX$(ASC(MID$(RECORD$, B, 1))), 2)
NEXT
rec$ = BIN2DEC$(HEX2BIN$(rec$))
'PRINT "Number of data Records: " + RIGHT$("      " + rec$, 6)

'PRINT DATE LAST UPDATE
'yy$ = STR$(ASC(MID$(HEADER$, 2, 1)))        '1 Byte - Year of update
'mm$ = STR$(ASC(MID$(HEADER$, 3, 1)))            '1 Byte - Day of update
'dd$ = STR$(ASC(MID$(HEADER$, 4, 1)))         '1 Byte - Month of update
'yy$ = RIGHT$("00" + LTRIM$(RTRIM$(yy$)), 2)
'mm$ = RIGHT$("00" + LTRIM$(RTRIM$(mm$)), 2)
'dd$ = RIGHT$("00" + LTRIM$(RTRIM$(dd$)), 2)
'PRINT "Date of last update   : " + dd$ + "/" + mm$ + "/" + yy$

BUFFER$ = SPACE$(30)
X = 0
N = 33
WHILE ASC(LEFT$(BUFFER$, 1)) <> ASC(CHR$(13))

GET #1, N, BUFFER$
IF ASC(LEFT$(BUFFER$, 1)) <> ASC(CHR$(13)) THEN
        FLDARRAY$(X) = BUFFER$
        X = X + 1
        N = N + 32
END IF
WEND
datapos = N + 2
XWIDTH = 1
TOTALFIELDS = X
DIM fldwidth$(TOTALFIELDS)
PRINT "Record#  ";
FOR C = 0 TO TOTALFIELDS - 1
        'FLDNAME$ = LEFT$(FLDARRAY$(C), 11)
        'CALL FILTER(FLDNAME$)
        'PRINT FLDNAME$;
        'PRINT " ";
        fldwidth$(C) = MID$(FLDARRAY$(C), 17, 1)
NEXT
'PRINT "Field  Field Name  Type       Width    Dec"

FOR C = 0 TO TOTALFIELDS - 1
         fldname$ = LEFT$(FLDARRAY$(C), 11)
         'CALL FILTER(FLDNAME$)
         IF LEN(fldname$) > ASC(fldwidth$(C)) THEN
                PRINT LEFT$(fldname$, 11);
         ELSE
                PRINT LEFT$(fldname$ + SPACE$(255), ASC(fldwidth$(C)));
                PRINT " ";
              
         END IF
         'PRINT " ";
NEXT
PRINT ""

FOR RRR = 1 TO VAL(rec$)
IF INKEY$ = CHR$(3) OR INKEY$ = CHR$(27) THEN
        PRINT ""
        PRINT "Interrupted."
        CLOSE #1
        SYSTEM
END IF
PRINT " " + RIGHT$("      " + STR$(RRR), 6) + "  ";
FOR X = 0 TO TOTALFIELDS - 1
        BUFFER$ = SPACE$(ASC(fldwidth$(X)))
        
        GET #1, datapos, BUFFER$
        PRINT LEFT$(BUFFER$ + SPACE$(255), ASC(fldwidth$(X)));
        PRINT " ";
       
        'IF X + 1 > TOTALFIELDS - 1 THEN
                'datapos = datapos + ASC(fldwidth$(TOTALFIELDS))
       ' ELSE
                datapos = datapos + ASC(fldwidth$(X))
        'END IF
NEXT
PRINT ""
datapos = datapos + 1
NEXT
CLOSE #1
PRINT
SYSTEM






FOR C = 0 TO TOTALFIELDS - 1
FLDNO = C + 1
PRINT RIGHT$("     " + STR$(FLDNO), 5);
PRINT "  ";

fldname$ = LEFT$(FLDARRAY$(C), 11)
CALL FILTER(fldname$)
PRINT fldname$;
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
SYSTEM
'
'=============================================================================
'                        dBASE III+ Header Structure
'=============================================================================
'VALID$ = CHR$(3)                           '1 Byte - Valid dBASE III+ file 03 = No memo, 83 = Memo
'PRINT #1, VALID$;

'yy$ = CHR$(VAL(RIGHT$(DATE$, 2)))          '1 Byte - Year of update
'dd$ = CHR$(VAL(MID$(DATE$, 4, 2)))         '1 Byte - Month of update
'mm$ = CHR$(VAL(LEFT$(DATE$, 2)))           '1 Byte - Day of update
'PRINT #1, yy$;
'PRINT #1, mm$;
'PRINT #1, dd$;
'
'NOREC$ = STRING$(4, CHR$(0))               '32bit No. = No. of Records, initially 0
'PRINT #1, NOREC$;
'
'NOBYTEHDR$ = STRING$(2, CHR$(0))           '16bit No. = No. of Bytes in Header. Initially padded
'PRINT #1, NOBYTEHDR$;
'
'NOBYTEREC$ = STRING$(2, CHR$(0))           '16bit No. = No. of Bytes in Record. Initially padded
'PRINT #1, NOBYTEREC$;
'
'RESERV1$ = STRING$(3, CHR$(0))             '3 Bytes - Reserved
'PRINT #1, RESERV1$;
'
'RESERV2$ = STRING$(13, CHR$(0))            '13 Bytes - Reserved for dBASE III+ on Network
'PRINT #1, RESERV2$;
'
'RESERV3$ = STRING$(4, CHR$(0))             '4 Bytes - Reserved
'PRINT #1, RESERV3$;
'
'
'FIELDNAME$ = LEFT$(DBFFIELD$(x), 11)     'Field Name ASCII (zero-filled)
'FIELDTYPE$ = MID$(DBFFIELD$(x), 12, 1)   'Field Type (C, N, L, D or M)
'FIELDDADD$ = STRING$(4, CHR$(0))                     'Field Data Address (Memory) Not useful on disk
'FIELDLEN$ = MID$(DBFFIELD$(x), 17, 1)    'Field Length in hex
'XWIDTH = XWIDTH + ASC(FIELDLEN$)           'No. Bytes in the record.
'
'FIELDDEC$ = MID$(DBFFIELD$(x), 18, 1)    'Field Decimal count in hex
'RESERVED1$ = MID$(DBFFIELD$(x), 19, 2)   'Reserved for dBASE III+ on LAN
'WORKAREA$ = MID$(DBFFIELD$(x), 21, 1)    'Work Area I.D.
'RESERVED2$ = MID$(DBFFIELD$(x), 22, 2)   'Reserved for dBASE III+ on LAN
'SETFIELD$ = MID$(DBFFIELD$(x), 24, 1)    'Set Fields Flag
'RESERVED3$ = MID$(DBFFIELD$(x), 25, 8)   'Reserved for dBASE III+ on LAN
'
ERRHANDLER:
SELECT CASE ERR
        CASE IS = 53
                PRINT "[ERR:53] File not found"
        CASE IS = 70
                PRINT "[ERR:70] Permission denied"
        CASE IS = 75
                PRINT "[ERR:75] Path/File access error"
        CASE IS = 76
                PRINT "[ERR:76] Path not found"
        CASE ELSE
                PRINT "[ERR:" + LEFT$("0" + LTRIM$(STR$(ERR)), 2) + "] Unexpected error"
END SELECT
PRINT
SYSTEM

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
N = 1
z$ = ""
DO WHILE N < LEN(X$) + 1
        IF MID$(X$, N, 1) = CHR$(0) THEN     'CHR$(32) OR MID$(X$, n, 1) = CHR$(58)
                z$ = z$ + " "
                MID$(z$, N, 1) = CHR$(32)
        ELSE
                z$ = z$ + MID$(X$, N, 1)
        END IF
N = N + 1
LOOP
X$ = z$
END SUB

SUB HELPme
PRINT "DBFLIST v1.0 - List data stored in a dBASE III+ database."
PRINT
PRINT "DBFLIST dbasefile"
PRINT
PRINT "  dbasefile              dBASE III+ database file to read."
PRINT "  help                   Display this help screen."
PRINT "  /?                     Display this help screen."
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

