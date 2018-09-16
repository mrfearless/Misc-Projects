DECLARE SUB help ()
com$ = COMMAND$
com$ = LTRIM$(UCASE$(com$))

com$ = "f:\staff\operator\allbats.doc /S:doc /R:555"


IF com$ = "" THEN
PRINT
ok = 0

DO WHILE ok = 0
LINE INPUT "Enter name of file to perform Search & Replace on: ", filename$

IF filename$ = "" THEN
        ok = 0
ELSE
        IF LEN(LTRIM$(RTRIM$(filename$))) > 12 THEN
                ok = 0
        ELSE
                IF LTRIM$(RTRIM$(filename$)) = CHR$(3) THEN
                        ok = 0
                ELSE
                        ok = 1
                END IF
        END IF
      
END IF
LOOP
'PRINT filename$


ok = 0
DO WHILE ok = 0
LINE INPUT "Search for   : ", search$

IF search$ = "" THEN
        ok = 0
ELSE
        IF LTRIM$(RTRIM$(search$)) = CHR$(3) THEN
                ok = 0
        ELSE
                ok = 1
        END IF
END IF
LOOP
'PRINT search$


ok = 0
DO WHILE ok = 0
LINE INPUT "Replace with : ", replace$

IF replace$ = "" THEN
        ok = 0
ELSE
        IF LTRIM$(RTRIM$(replace$)) = CHR$(3) THEN
                ok = 0
        ELSE
                ok = 1
        END IF
  
END IF
LOOP
'PRINT replace$

ON ERROR GOTO errhand1
OPEN filename$ FOR INPUT AS #1
ON ERROR GOTO 0
'****************************

ELSE
        IF INSTR(com$, "/?") > 0 THEN
                CALL help
        END IF
      
        IF INSTR(com$, "/S:") > 0 THEN
                'CALL searchfor
        END IF
       
        IF INSTR(com$, "/R:") > 0 THEN
                'CALL replacewith
        END IF
END IF

SYSTEM



errhand1:

IF ERR = 53 THEN
        PRINT
        PRINT "[53] Error: file not found"
        PRINT
        SYSTEM
ELSE
        PRINT
        PRINT "["; : PRINT ERR; : PRINT "] Error: unexpected or unknown"
        PRINT
        SYSTEM
END IF

SUB help
PRINT
PRINT "[S&R 1.0] Search & Replace Utility for Binary & Text files."
PRINT
PRINT " S&R [drive:] [path] filename  /S:searchfor /R:[replacewith]"
PRINT
PRINT "/S:searchfor     Search for specified string search for"
PRINT "/R:replace with  Replace with specified string replacewith"
PRINT "/?               Shows this help screen"
PRINT
SYSTEM
END SUB

