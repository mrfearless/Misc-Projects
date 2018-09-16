PARAMETERS DRIVEPATH, OPTION

IF PCOUNT()=0
        
                        ?? "Creates a directory and all subdirectories in the path."
                        ?
                        ? "  MAKEDIR [ [drive:]path ] | [ /S | [/?] ]"
                        ? 
                        ? "  drive      Drive to start creating directory path."
                        ? "  path       Directory path structure to create."
                        ? "  /?         Shows this help.  "
                        ? "  /S         Silent mode - no screen output."
                        ?
        RETURN

ELSE
        
        IF PCOUNT()>0
                if AT("?",DRIVEPATH)>0
                
                        ?? "Creates a directory and all subdirectories in the path."
                        ?
                        ? "  MAKEDIR [ [drive:]path ] | [ /S | [/?] ]"
                        ?
                        ? "  drive      Drive to start creating directory path."
                        ? "  path       Directory path structure to create. "
                        ? "  /?         Shows this help.  "
                        ? "  /S         Silent mode - no screen output."
                        ?
                        RETURN
                else
                        if PCOUNT()>1
                               if AT("/s",OPTION)>0 .or. AT("/S",OPTION)>0
                                        drivepath=strtran(drivepath,"/S","")
                                        silent=1        && silent on
                               else
                                        silent=0        && silent off
                               endif
                        ELSE
                                if AT("/s",DRIVEPATH)>0 .or. AT("/S",DRIVEPATH)>0
                                        drivepath=strtran(drivepath,"/S","")
                                        silent=1
                                else
                                        silent=0        && silent off
                                endif
                        ENDIF

                ENDIF

        endif

ENDIF

Drivepath=alltrim(drivepath)
if at(":\",drivepath)#2
        
        IF at(".\",drivepath)#1
                drivepath=".\"+drivepath
        ENDIF
        
        drive=left(drivepath,2)
        root=left(drivepath,3)
        eo_last=at(".\",drivepath)+1
        eo_root=at(".\",drivepath)+1

ELSE
        drive=left(drivepath,2)
        root=left(drivepath,3)
        eo_last=at(":\",drivepath)+1
        eo_root=at(":\",drivepath)+1
endif

eo_dir=at("\",substr(drivepath,eo_last+1,len(drivepath)))

declare test[30]
n=1
                
do while eo_dir<len(drivepath)                
                        
        eo_dir=at("\",substr(drivepath,eo_last+1,len(drivepath)))
                        
        if eo_dir=0
                test[n]=substr(drivepath,1,len(drivepath))
                exit
        else
                test[n]=substr(drivepath,1,((eo_last+eo_dir)-1))
                eo_last=eo_last+eo_dir+1
        endif
        n=n+1
enddo

endns=n

if silent=0
        ?"Creating Directory Structure..."
        ?
endif
HPOS=ROW()
VPOS=1

for n=1 to endns
        if silent=0
                @ HPOS, 1 SAY Test[n]
        endif
        OK=FT_MKDIR(Test[n])

        IF OK#0
        IF OK=3 .OR. OK=99
                if silent=0
                        ?
                        ?"[ERR:01] Could not succesfully create directory structure."
                        ?"         Access denied, invalid drive and/or directory path."
                        ?
                endif
                RETURN
        ELSE
                IF n=endns
                        IF OK=5
                                if silent=0
                                        ?
                                        ?"[ERR:02] directory already exists in structure."
                                        ?         
                                        ?
                                endif
                                RETURN
                        ENDIF

                ENDIF
        ENDIF
        ENDIF
next n
if silent=0
        ?
endif
return
