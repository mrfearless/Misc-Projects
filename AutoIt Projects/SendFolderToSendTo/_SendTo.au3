#include-once

; #AutoIt3Wrapper_Au3Check_Parameters=-d -w 1 -w 2 -w 3 -w 4 -w 5 -w 6 -w 7
; #INDEX# =======================================================================================================================
; Title .........: _SendTo
; AutoIt Version : v3.2.12.1 or higher
; Language ......: English
; Description ...: Create a shortcut in the SendTo folder.
; Note ..........:
; Author(s) .....: guinness
; Remarks .......: Special thanks to Yashied for _WinAPI_ShellGetSpecialFolderPath, which can be found from WinAPIEx.au3 - http://www.autoitscript.com/forum/topic/98712-winapiex-udf
; ===============================================================================================================================

; #INCLUDES# =========================================================================================================
; None

; #GLOBAL VARIABLES# =================================================================================================
; None

; #CURRENT# =====================================================================================================================
; _SendToFolder_Install: Creates a Shortcut in the SendTo folder.
; _SendToFolder_Uninstall: Deletes the Shortcut in the SendTo folder.
; ===============================================================================================================================

; #INTERNAL_USE_ONLY#============================================================================================================
; __SendTo_ShellGetSpecialFolderPath ......; Retrieves the path of a special folder.
; ===============================================================================================================================

; #FUNCTION# ====================================================================================================================
; Name ..........: _SendTo_Install
; Description ...: Creates a Shortcut in the SendTo folder.
; Syntax ........: _SendTo_Install([$sName = @ScriptName[, $sFilePath = @ScriptFullPath[, $sCmdLine = '']]])
; Parameters ....: $sName               - [optional] Name of the program. Default is @ScriptName.
;                  $sFilePath           - [optional] Location of the program executable. Default is @ScriptFullPath.
;                  $sCmdLine            - [optional] Additional file arguments.  Default is ''.
; Return values .: Success - FileCreateShortcut() Return code.
;                  Failure - Returns 0 & sets @error to non-zero.
; Author ........: guinness
; Example .......: Yes
; ===============================================================================================================================
Func _SendTo_Install($sName = @ScriptName, $sFilePath = @ScriptFullPath, $sCmdLine = '')
	Local Const $bCSIDL_SENDTO = 0x0009

	If $sFilePath = Default Then
		$sFilePath = @ScriptFullPath
	EndIf
	If $sName = Default Then
		$sName = @ScriptName
	EndIf
	$sName = StringRegExpReplace($sName, '\.[^\.\\/]*$', '')
	If StringStripWS($sName, 8) = '' Or FileExists($sFilePath) = 0 Then
		Return SetError(1, 0, 0)
	EndIf

	_SendTo_Uninstall($sName, $sFilePath) ; Deletes The Shortcut In The SendTo folder.

	Local $sSendTo = __SendTo_ShellGetSpecialFolderPath($bCSIDL_SENDTO)
	Return FileCreateShortcut($sFilePath, $sSendTo & '\' & $sName & '.lnk', $sSendTo, $sCmdLine)
EndFunc   ;==>_SendTo_Install

; #FUNCTION# ====================================================================================================================
; Name ..........: _SendTo_Uninstall
; Description ...: Deletes the Shortcut in the SendTo folder.
; Syntax ........: _SendTo_Uninstall([$sName = @ScriptName[, $sFilePath = @ScriptFullPath]])
; Parameters ....: $sName               - [optional] Name of the program. Default is @ScriptName.
;                  $sFilePath           - [optional] Location of the program executable. Default is @ScriptFullPath.
; Return values .: Success - FileClose() Return code
;                  Failure - Returns 0 & sets @error to non-zero.
; Author ........: guinness
; Example .......: Yes
; ===============================================================================================================================
Func _SendTo_Uninstall($sName = @ScriptName, $sFilePath = @ScriptFullPath)
	Local Const $bCSIDL_SENDTO = 0x0009
	Local $aFileGetShortcut = 0, $sFileName = ''

	If $sFilePath = Default Then
		$sFilePath = @ScriptFullPath
	EndIf
	If $sName = Default Then
		$sName = @ScriptName
	EndIf
	$sName = StringRegExpReplace($sName, '\.[^\.\\/]*$', '')
	If StringStripWS($sName, 8) = '' Or FileExists($sFilePath) = 0 Then
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
EndFunc   ;==>_SendTo_Uninstall

; #INTERNAL_USE_ONLY#============================================================================================================

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