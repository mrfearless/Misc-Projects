;#include '_SendTo.au3'
#include "_ShellFolder.au3"

If $CmdLine[0] = 1 Then
   If $CmdLine[1] = "/install" Then
	  DoInstall()
   ElseIf $CmdLine[1] = "/uninstall" Then
	  DoUninstall()
	  ;MsgBox(4096, '', 'SendFolderToSendTo Uninstalled.')
   Else
	  ;$Dat = $CmdLine[1] & '\'
	  ;$sFullPath1 = "\folder1\folder2\folder3\foldern\" 
	 ; $sLastFolder1 = StringRegExpReplace($Dat, ".*\\(.*)\\\z", "$1") 
	 ; MsgBox(0, "Last Folder", $sLastFolder1)
	  
	  DoSendToShortCut($CmdLine[1] & '\')
   EndIf
Else
   ;MsgBox(4096, '', 'SendFolderToSendTo Installed.')
   DoInstall()
EndIf


Func DoInstall()
   _ShellFolder_Install('Send Folder To SendTo') ; Add the running EXE to the Shell ContextMenu. This will only display when selecting a drive and folder.
EndFunc

Func DoUninstall()
   _ShellFolder_Uninstall() ; Remove the running EXE from the Shell ContextMenu.
EndFunc

Func DoSendToShortCut($sFilePath)
   Local Const $bCSIDL_SENDTO = 0x0009
   Local $sSendTo = __SendTo_ShellGetSpecialFolderPath($bCSIDL_SENDTO)
   Local $sCmdLine = ''
   ;Local $sName = $sFilePath
   Local $sName = StringRegExpReplace($sFilePath, ".*\\(.*)\\\z", "$1") 
   
   
   ;MsgBox(0, "Last Folder", $sLastFolder)   
   ;MsgBox(0, "File Path", $sFilePath)
   DeleteExistingShortCutIfExists($sFilePath)
   MsgBox(0, 'SendFolderToSendTo', 'Shortcut To Folder Created: ' & $sName)
   Return FileCreateShortcut($sFilePath, $sSendTo & '\' & $sName & '.lnk', $sSendTo, $sCmdLine)
   ;MsgBox(4096, '', $CmdLine[1]) ; Retrieve the first file.
  ; _SendTo_Install($CmdLine[1], $CmdLine[1],) ; Add the current folder to the SendTo Folder.
EndFunc


Func DeleteExistingShortCutIfExists($sFilePath)
	Local Const $bCSIDL_SENDTO = 0x0009
	Local $aFileGetShortcut = 0, $sFileName = ''
	Local $sName = StringRegExpReplace($sFilePath, ".*\\(.*)\\\z", "$1") 
	
	If FileExists($sFilePath) = 0 Then
	   Return SetError(1, 0, 0)
	EndIf
	
	Local $iStringLen = StringLen($sName), $sSendTo = __SendTo_ShellGetSpecialFolderPath($bCSIDL_SENDTO)
	Local $hSearch = FileFindFirstFile($sSendTo & '\' & '*.lnk')
	If $hSearch = -1 Then
		Return SetError(1, 0, 0)
	EndIf

	While 1
		$sFileName = FileFindNextFile($hSearch)
		If @error Then
			ExitLoop
		EndIf
		If StringLeft($sFileName, $iStringLen) = $sName Then
			$aFileGetShortcut = FileGetShortcut($sSendTo & '\' & $sFileName)
			If @error Then
				ContinueLoop
			EndIf
			If $aFileGetShortcut[0] = $sFilePath Then
				FileDelete($sSendTo & '\' & $sFileName)
			EndIf
		EndIf
	WEnd
	Return FileClose($hSearch)
EndFunc



; #FUNCTION# ====================================================================================================================
; Name...........: __SendTo_ShellGetSpecialFolderPath renamed from _WinAPI_ShellGetSpecialFolderPath
; Description....: Retrieves the path of a special folder.
; Syntax.........: __SendTo_ShellGetSpecialFolderPath($CSIDL [, $fCreate = 0])
; Parameters.....: $CSIDL   - The CSIDL ($CSIDL_*) that identifies the folder of interest.
;                  $fCreate - Specifies whether the folder should be created if it does not already exist, valid values:
;                  |TRUE    - The folder is created.
;                  |FALSE   - The folder is not created. (Default)
; Return values..: Success  - The full path of a special folder.
;                  Failure  - Empty string and sets the @error flag to non-zero.
; Author.........: Yashied
; Modified.......:
; Remarks........: WinAPIEx.au3 - http://www.autoitscript.com/forum/topic/98712-winapiex-udf
; Related........:
; Link...........: @@MsdnLink@@ SHGetSpecialFolderPath
; Example........: Yes
; ===============================================================================================================================
Func __SendTo_ShellGetSpecialFolderPath($CSIDL, $fCreate = 0)
	Local $tPath = DllStructCreate('wchar[1024]')
	Local $aReturn = DllCall('shell32.dll', 'int', 'SHGetSpecialFolderPathW', 'hwnd', 0, 'ptr', DllStructGetPtr($tPath), 'int', $CSIDL, 'int', $fCreate)

	If (@error) Or (Not $aReturn[0]) Then
		Return SetError(1, 0, '')
	EndIf
	Return DllStructGetData($tPath, 1)
EndFunc   ;==>__SendTo_ShellGetSpecialFolderPath