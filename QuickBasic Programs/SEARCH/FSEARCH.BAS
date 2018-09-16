DECLARE FUNCTION COMDEL$ (REPLACE$)
DECLARE SUB RECURSE (filespec$, TEXTSTRING$)
DECLARE SUB FILTER (X$)
DECLARE SUB SEARCH (filename$, TEXTSTRING$)
DECLARE SUB HELP ()

COMMON SHARED COM$                                   'COMMAND LINE
COMMON SHARED filename$                              'filename to search
COMMON SHARED filespec$                              'file specification
COMMON SHARED TEXTSTRING$                            'string to search for
COMMON SHARED LOTS                                   'Length of text string
COMMON SHARED LOS                                    'Length of string (COM$)
COMMON SHARED FILESTRING$                            'string to search in
COMMON SHARED APPENDFLAG                             'if option /A to append
COMMON SHARED recurseflag                            'if option /R to recurse
COMMON SHARED TOTALMATCH
COMMON SHARED MATCHFLAG                              'IF OPTION /M To write matches only
MATCHFLAG = 0
APPENDFLAG = 0
recurseflag = 0

COM$ = COMMAND$                                      'command line parameters
'--------------------------------- TESTING -----------------------------------
'COM$ = "'(C)' /A *.EXE /R"
'COM$ = "c:\windows\system\user.exe 'Keith'"
'COM$ = "           'this is a test'                testfile "
'-----------------------------------------------------------------------------
COM$ = LTRIM$(RTRIM$(UCASE$(COM$)))
IF COM$ = "" THEN
        GOTO ERRHAND1
ELSE
        IF INSTR(COM$, "?") > 0 THEN
                CALL HELP
        END IF
END IF
       
SELECT CASE INKEY$
        CASE IS = CHR$(3)
                PRINT "Search aborted by user."
                SYSTEM
        CASE IS = CHR$(27)
                PRINT "Search aborted by user."
                SYSTEM
END SELECT

LOS = LEN(COM$)
       
IF INSTR(COM$, "/A") THEN
        APPENDFLAG = 1
        APPENDPOS = INSTR(COM$, "/A")
        COM$ = COMDEL$("/A")
        'NEWCOM$ = ""
        'SPOS = 1
        'DO WHILE SPOS < (LOS + 1)
        '        VAL$ = MID$(COM$, SPOS, 1)
        '        IF SPOS = APPENDPOS THEN
        '                VAL$ = ""
        '                SPOS = SPOS + 1
        '        END IF
        '        NEWCOM$ = NEWCOM$ + VAL$
        '        SPOS = SPOS + 1
        'LOOP
'COM$ = LTRIM$(RTRIM$(NEWCOM$))
END IF

LOS = LEN(COM$)

IF INSTR(COM$, "/R") > 0 THEN
        recurseflag = 1
        RECURSEPOS = INSTR(COM$, "/R")
        COM$ = COMDEL$("/R")
        'NEWCOM$ = ""
        'SPOS = 1
        'DO WHILE SPOS < (LOS + 1)
        '        VAL$ = MID$(COM$, SPOS, 1)
        '        IF SPOS = RECURSEPOS THEN
         '               VAL$ = ""
        '                SPOS = SPOS + 1
        '        END IF
        '        NEWCOM$ = NEWCOM$ + VAL$
        '        SPOS = SPOS + 1
        'LOOP
       ' COM$ = LTRIM$(RTRIM$(NEWCOM$))
ELSE
        IF INSTR(COM$, "*") > 0 OR INSTR(COM$, "?") > 0 THEN
                recurseflag = 2
        END IF
END IF

LOS = LEN(COM$)
       
IF INSTR(COM$, "/M") > 0 THEN
        MATCHFLAG = 1
        MATCHPOS = INSTR(COM$, "/M")
        COM$ = COMDEL$("/M")
        'NEWCOM$ = ""
        'SPOS = 1
        'DO WHILE SPOS < (LOS + 1)
        '        VAL$ = MID$(COM$, SPOS, 1)
        '        IF SPOS = RECURSEPOS THEN
         '               VAL$ = ""
        '                SPOS = SPOS + 1
        '        END IF
        '        NEWCOM$ = NEWCOM$ + VAL$
        '        SPOS = SPOS + 1
        'LOOP
       ' COM$ = LTRIM$(RTRIM$(NEWCOM$))
END IF



FIRSTQUOTE = INSTR(COM$, "'")
IF FIRSTQUOTE = 0 THEN
        GOTO ERRHAND2
END IF
       
SECONDQUOTE = INSTR(FIRSTQUOTE + 1, COM$, "'")
IF SECONDQUOTE = 0 THEN
        GOTO ERRHAND3
END IF
       
TEXTSTRING$ = MID$(COM$, FIRSTQUOTE + 1, (SECONDQUOTE - (FIRSTQUOTE + 1)))
LOTS = LEN(TEXTSTRING$)

SP = INSTR(SECONDQUOTE, COM$, " ")
SELECT CASE SP
        CASE IS > SECONDQUOTE
                filename$ = MID$(COM$, SP + 1, (LOS - (SP)))
        CASE ELSE
                IF INSTR(COM$, " ") < FIRSTQUOTE THEN
                        filename$ = MID$(COM$, 1, (FIRSTQUOTE - 1))
                ELSE
                        GOTO ERRHAND4
                END IF
END SELECT

filename$ = RTRIM$(LTRIM$(filename$))
       
IF recurseflag = 1 OR recurseflag = 2 THEN
        filespec$ = filename$
        TOTALMATCH = 0
        CALL RECURSE(filespec$, TEXTSTRING$)
ELSE
        TOTALMATCH = 0
        CALL SEARCH(filename$, TEXTSTRING$)
END IF

SYSTEM

OK:
RESUME NEXT

ERRHAND0:
PRINT "FileSEARCH v1.05"
PRINT
PRINT "[ERR:";
PRINT RIGHT$("  " + STR$(ERR), 2);
PRINT "] Line ";
PRINT ERL;
PRINT " Unexpected Error. Please refer to error codes."
PRINT
SYSTEM

ERRHAND1:
PRINT "FileSEARCH v1.05"
PRINT
PRINT "[ERR:02] No filename or text-string has been specified."
PRINT
SYSTEM

ERRHAND2:
PRINT "FileSEARCH v1.05"
PRINT
PRINT "[ERR:40] No text-string has been specified."
PRINT
SYSTEM

ERRHAND3:
PRINT "FileSEARCH v1.05"
PRINT
PRINT "[ERR:02] Missing quote-mark (') from string."
PRINT
SYSTEM

ERRHAND4:
PRINT "FileSEARCH v1.05"
PRINT
PRINT "[ERR:53] No filename has been specified."
PRINT
SYSTEM

ERRHAND5:
PRINT "FileSEARCH v1.05"
PRINT
PRINT "[ERR:53] File not found."
PRINT
SYSTEM

FUNCTION COMDEL$ (REPLACE$)
        REPLACEPOS = INSTR(COM$, REPLACE$)
        NEW$ = ""
        SPOS = 1
        DO WHILE SPOS < (LOS + 1)
                VAL$ = MID$(COM$, SPOS, 1)
                IF SPOS = REPLACEPOS THEN
                        VAL$ = ""
                        SPOS = SPOS + 1
                END IF
                NEW$ = NEW$ + VAL$
                SPOS = SPOS + 1
        LOOP
        NEW$ = LTRIM$(RTRIM$(NEW$))
        COMDEL$ = NEW$
END FUNCTION

SUB FILTER (X$)
        'filter command$ of LINEFEED (chr$(10) CARRAIGE (chr$(13) ESCAPE (chr$(27)
        N = 1
        Z$ = ""
        DO WHILE N < LEN(X$) + 1
                IF MID$(X$, N, 1) = CHR$(8) OR MID$(X$, N, 1) = CHR$(9) OR MID$(X$, N, 1) = CHR$(127) THEN
                        MID$(X$, N, 1) = CHR$(32)
                ELSE
                        Z$ = Z$ + MID$(X$, N, 1)
                END IF
                N = N + 1
        LOOP
        X$ = Z$
END SUB

SUB HELP
        PRINT "FileSEARCH v1.05 - Search for a text-string in a specified file."
        PRINT "KSR 11/06/97"
        PRINT
        PRINT "  To search a file for a text-string:"
        PRINT "  FSEARCH filename 'text-string' [/A] [/M]"
        PRINT
        PRINT "  filename                  Specified file to search."
        PRINT "  'text-string'             Specified text-string to search for in quotes ('')"
        PRINT "  /A                        Appends output to FS.OUT"
        PRINT "  /M                        Print Matches only."
        PRINT
        PRINT "  To recurse files for a text-string:"
        PRINT "  FSEARCH filespec 'text-string' [/R] [/M]"
        PRINT
        PRINT "  filespec                  Files to search for. Use of wildcards is allowed."
        PRINT "  /R                        Recurse files with filespec for 'text-string'."
        PRINT "                            /R, by default deletes FS.OUT before searches begin,"
        PRINT "                            use with /A to not delete FS.OUT."
        PRINT
        PRINT "Filesearch will write details of any matches to FS.OUT, in the current dir."
        SYSTEM
END SUB

SUB RECURSE (filespec$, TEXTSTRING$)
        PRINT "FileSEARCH v1.05 - Starting search of " + filespec$ + " for '" + TEXTSTRING$ + "'"
        PRINT "Compiling filespec data...";
        IF recurseflag = 1 THEN
                SHELL "DIR " + filespec$ + " /S /B /O-S>FS.DAT"
        ELSE
                SHELL "DIR " + filespec$ + " >FS.DAT"
        END IF
        PRINT "done."
        PRINT "Searching, please wait..."
        PRINT

        OPEN "FS.DAT" FOR INPUT AS #9
        IF recurseflag = 2 THEN
                HEADERLENGTH = 0
                DO
                        LINE INPUT #9, DATALINE$
                        HEADERLENGTH = HEADERLENGTH + 1
                LOOP WHILE LTRIM$(LEFT$(DATALINE$, 1)) = ""
                HEADERLENGTH = HEADERLENGTH - 1
                CLOSE #9
                      
                OPEN "FS.DAT" FOR INPUT AS #9
                FOR lcount = 1 TO HEADERLENGTH
                        LINE INPUT #9, DATALINE$
                NEXT
        END IF

        ON ERROR GOTO OK
        IF APPENDFLAG = 0 THEN
                KILL "FS.OUT"
                APPENDFLAG = 1
        END IF
       
        DO UNTIL EOF(9)
                SELECT CASE INKEY$
                        CASE IS = CHR$(3)
                                CLOSE #9
                                KILL "FS.DAT"
                                PRINT "Search aborted by user."
                                SYSTEM
                        CASE IS = CHR$(27)
                                CLOSE #9
                                KILL "FS.DAT"
                                PRINT "Search aborted by user."
                                SYSTEM
                END SELECT
       
                IF recurseflag = 1 THEN
                        LINE INPUT #9, filename$
                        filename$ = LTRIM$(RTRIM$(filename$))
                        IF filename$ <> "FS.OUT" AND filename$ <> "FS.DAT" THEN
                                CALL SEARCH(filename$, TEXTSTRING$)
                        END IF
                ELSE
                        LINE INPUT #9, DATALINE$
                        IF recurseflag = 2 THEN
                                IF LEFT$(DATALINE$, 1) = " " THEN
                                        EXIT DO
                                END IF
                        END IF
                        DIR$ = RTRIM$(MID$(DATALINE$, 14, 5))
                        IF DIR$ <> "<DIR>" THEN
                                IF RTRIM$(MID$(DATALINE$, 10, 3)) <> "" THEN
                                        filename$ = RTRIM$(LTRIM$(LEFT$(DATALINE$, 8))) + "." + RTRIM$(MID$(DATALINE$, 10, 3))
                                ELSE
                                        filename$ = RTRIM$(LTRIM$(LEFT$(DATALINE$, 8)))
                                END IF
                                IF filename$ <> "FS.OUT" AND filename$ <> "FS.DAT" THEN
                                       
                                        CALL SEARCH(filename$, TEXTSTRING$)
                                END IF
                        END IF
                        
                END IF
                
                
        LOOP
       
        CLOSE #9
        PRINT "FileSEARCH v1.05 - Finished search of " + filespec$ + " for '" + TEXTSTRING$ + "'"
        PRINT "Total Matches:" + STR$(TOTALMATCH)
        IF TOTALMATCH <> 0 THEN
                PRINT "Please refer to FS.OUT for details of any search."
        END IF
END SUB

SUB SEARCH (filename$, TEXTSTRING$)
        SELECT CASE INKEY$
                CASE IS = CHR$(3)
                        ON ERROR GOTO OK
                        CLOSE #9
                        KILL "FS.DAT"
                        PRINT "Search aborted by user."
                        SYSTEM
                CASE IS = CHR$(27)
                        ON ERROR GOTO OK
                        CLOSE #9
                        KILL "FS.DAT"
                        PRINT "Search aborted by user."
                        SYSTEM
        END SELECT
      
        ON ERROR GOTO ERRHAND5
        OPEN filename$ FOR INPUT AS #1
        CLOSE #1
       
        OPEN filename$ FOR BINARY AS #1
       
        ON ERROR GOTO OK
        IF APPENDFLAG = 0 THEN
                KILL "FS.OUT"
        END IF
       
        OPEN "FS.OUT" FOR APPEND AS #2
       
        ON ERROR GOTO ERRHAND0
        FILESIZE = LOF(1)
       
        IF MATCHFLAG = 0 THEN
        PRINT #2, "FileSEARCH v1.05 " + DATE$ + " " + TIME$
        PRINT #2,
        PRINT #2, "- Searching " + filename$ + " Filesize:" + STR$(FILESIZE) + " for '" + TEXTSTRING$ + "'"
        END IF
        IF recurseflag = 0 THEN
                PRINT "FileSEARCH v1.05 - Starting search of " + filename$ + " for '" + TEXTSTRING$ + "'"
                PRINT
        END IF
       
        IF MATCHFLAG = 0 THEN
        PRINT "- Searching " + filename$ + " Filesize:" + STR$(FILESIZE)
        PRINT
        END IF
        FOUND = 0
        FILESTRING$ = SPACE$(255)
        SEEKPOS = 1
       
        DO WHILE NOT EOF(1)
                SELECT CASE INKEY$
                        CASE IS = CHR$(3)
                                ON ERROR GOTO OK
                                CLOSE #9
                                KILL "FS.DAT"
                                PRINT "Search aborted by user."
                                SYSTEM
                        CASE IS = CHR$(27)
                                ON ERROR GOTO OK
                                CLOSE #9
                                KILL "FS.DAT"
                                PRINT "Search aborted by user."
                                SYSTEM
                END SELECT
              
                GET #1, SEEKPOS, FILESTRING$
               
                FILESTRING$ = UCASE$(FILESTRING$)
                Position = SEEKPOS
               
                IF INSTR(FILESTRING$, TEXTSTRING$) > 0 THEN
                        TOTALMATCH = TOTALMATCH + 1
                        TextPOS = (Position + INSTR(FILESTRING$, TEXTSTRING$)) - 1
                        COLOR 11, 0
                        PRINT "  û";
                        COLOR 7, 0
                        PRINT " Match in " + filename$ + " [Position:" + STR$(TextPOS) + "]"
                        PRINT #2, "  û Match in " + filename$ + " [Position:" + STR$(TextPOS) + "] for '" + TEXTSTRING$ + "'"
                        CALL FILTER(FILESTRING$)
                        PRINT #2, FILESTRING$
                       
                        FOUND = -1
                END IF
                SEEKPOS = SEEKPOS + 255
        LOOP
      
        IF NOT FOUND THEN
                IF MATCHFLAG = 0 THEN
                PRINT #2, "  X No match found in " + filename$ + ""
                PRINT #2,
                COLOR 12, 0
                PRINT "  X";
                COLOR 7, 0
                PRINT " No match found in " + filename$ + ""
                END IF
               
                IF recurseflag = 0 THEN
                        'PRINT
                END IF
        END IF
        CLOSE #1
        IF MATCHFLAG = 0 THEN
        PRINT #2, "--------------------------------------------------------------------------------------------------------------------------"
        CLOSE #2
        PRINT
        END IF
END SUB

