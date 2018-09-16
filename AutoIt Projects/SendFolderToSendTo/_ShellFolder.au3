#include-once

; #AutoIt3Wrapper_Au3Check_Parameters=-d -w 1 -w 2 -w 3 -w 4 -w 5 -w 6 -w 7
; #INDEX# =======================================================================================================================
; Title .........: _ShellFolder
; AutoIt Version : v3.2.12.1 or higher
; Language ......: English
; Description ...: Create an entry in the shell contextmenu when selecting a folder, includes the program icon as well.
; Note ..........:
; Author(s) .....: guinness
; Remarks .......: Special thanks to KaFu for EnumRegKeys2Array() which I used as inspiration for enumerating the Registry Keys.
; ===============================================================================================================================

; #INCLUDES# ====================================================================================================================
; None

; #GLOBAL VARIABLES# ============================================================================================================
; None

; #CURRENT# =====================================================================================================================
; _ShellFolder_Install: Creates an entry in the 'All Users/Current Users' registry for displaying a program entry in the shell contextmenu, but only displays when selecting a folder.
; _ShellFolder_Uninstall: Deletes an entry in the 'All Users/Current Users' registry for displaying a program entry in the shell contextmenu.
; ===============================================================================================================================

; #INTERNAL_USE_ONLY#============================================================================================================
; __ShellFolder_RegistryGet ......; Retrieve an array of registry entries for a specific key.
; ===============================================================================================================================

; #FUNCTION# ====================================================================================================================
; Name ..........: _ShellFolder_Install
; Description ...: Creates an entry in the 'All Users/Current Users' registry for displaying a program entry in the shell contextmenu, but only displays when selecting a file and folder.
; Syntax ........: _ShellFolder_Install($sText[, $sName = @ScriptName[, $sFilePath = @ScriptFullPath[, $sIconPath = @ScriptFullPath[,
;                  $iIcon = 0[, $fAllUsers = False[, $fExtended = False]]]]]])
; Parameters ....: $sText               - Text to be shown in the contextmenu.
;                  $sName               - [optional] Name of the program. Default is @ScriptName.
;                  $sFilePath           - [optional] Location of the program executable. Default is @ScriptFullPath.
;                  $sIconPath           - [optional] Location of the icon e.g. program executable or dll file. Default is @ScriptFullPath.
;                  $iIcon               - [optional] Index of icon to be used. Default is 0.
;                  $fAllUsers           - [optional] Add to Current Users (False) or All Users (True) Default is False.
;                  $fExtended           - [optional] Show in the Extended contextmenu using Shift + Right click. Default is False.
; Return values .: Success - Returns True
;                  Failure - Returns False and sets @error to non-zero.
; Author ........: guinness
; Example .......: Yes
; ===============================================================================================================================
Func _ShellFolder_Install($sText, $sName = @ScriptName, $sFilePath = @ScriptFullPath, $sIconPath = @ScriptFullPath, $iIcon = 0, $fAllUsers = False, $fExtended = False)
	Local $i64Bit = '', $sRegistryKey = ''

	If $iIcon = Default Then
		$iIcon = 0
	EndIf
	If $sFilePath = Default Then
		$sFilePath = @ScriptFullPath
	EndIf
	If $sIconPath = Default Then
		$sIconPath = @ScriptFullPath
	EndIf
	If $sName = Default Then
		$sName = @ScriptName
	EndIf
	If @OSArch = 'X64' Then
		$i64Bit = '64'
	EndIf
	If $fAllUsers Then
		$sRegistryKey = 'HKEY_LOCAL_MACHINE' & $i64Bit & '\SOFTWARE\Classes\Folder\shell\'
	Else
		$sRegistryKey = 'HKEY_CURRENT_USER' & $i64Bit & '\SOFTWARE\Classes\Folder\shell\'
	EndIf

	$sName = StringLower(StringRegExpReplace($sName, '\.[^\.\\/]*$', ''))
	If StringStripWS($sName, 8) = '' Or FileExists($sFilePath) = 0 Then
		Return SetError(1, 0, False)
	EndIf

	_ShellFolder_Uninstall($sName, $fAllUsers)

	Local $iReturn = 0
	$iReturn += RegWrite($sRegistryKey & $sName, '', 'REG_SZ', $sText)
	$iReturn += RegWrite($sRegistryKey & $sName, 'Icon', 'REG_EXPAND_SZ', $sIconPath & ',' & $iIcon)
	$iReturn += RegWrite($sRegistryKey & $sName & '\command', '', 'REG_SZ', '"' & $sFilePath & '" "%1"')
	If $fExtended Then
		$iReturn += RegWrite($sRegistryKey & $sName, 'Extended', 'REG_SZ', '')
	EndIf
	Return $iReturn > 0
EndFunc   ;==>_ShellFolder_Install

; #FUNCTION# ====================================================================================================================
; Name ..........: _ShellFolder_Uninstall
; Description ...: Deletes an entry in the 'All Users/Current Users' registry for displaying a program entry in the shell contextmenu.
; Syntax ........: _ShellFolder_Uninstall([$sName = @ScriptName[, $fAllUsers = False]])
; Parameters ....: $sName               - [optional] Name of the Program. Default is @ScriptName.
;                  $fAllUsers           - [optional] Was it added to Current Users (False) or All Users (True) Default is False.
; Return values .: Success - Returns True
;                  Failure - Returns False and sets @error to non-zero.
; Author ........: guinness
; Example .......: Yes
; ===============================================================================================================================
Func _ShellFolder_Uninstall($sName = @ScriptName, $fAllUsers = False)
	Local $i64Bit = '', $sRegistryKey = ''

	If $sName = Default Then
		$sName = @ScriptName
	EndIf
	If @OSArch = 'X64' Then
		$i64Bit = '64'
	EndIf
	If $fAllUsers Then
		$sRegistryKey = 'HKEY_LOCAL_MACHINE' & $i64Bit & '\SOFTWARE\Classes\Folder\shell\'
	Else
		$sRegistryKey = 'HKEY_CURRENT_USER' & $i64Bit & '\SOFTWARE\Classes\Folder\shell\'
	EndIf

	$sName = StringLower(StringRegExpReplace($sName, '\.[^\.\\/]*$', ''))
	If StringStripWS($sName, 8) = '' Then
		Return SetError(1, 0, 0)
	EndIf

	Local $aReturn = __ShellFolder_RegistryGet($sRegistryKey), $iReturn = 0, $sNameDeleted = ''
	If $aReturn[0][0] Then
		For $i = 1 To $aReturn[0][0]
			If $aReturn[$i][0] = $sName And $sNameDeleted <> $aReturn[$i][1] Then
				$sNameDeleted = $aReturn[$i][1]
				$iReturn += RegDelete($sNameDeleted)
			EndIf
		Next
	EndIf
	$aReturn = 0
	Return $iReturn > 0
EndFunc   ;==>_ShellFolder_Uninstall

; #INTERNAL_USE_ONLY#============================================================================================================
Func __ShellFolder_RegistryGet($sRegistryKey)
	Local $aArray[1][5] = [[0, 5]], $iCount_1 = 0, $iCount_2 = 0, $iDimension = 0, $iError = 0, $sRegistryKey_All = '', $sRegistryKey_Main = '', $sRegistryKey_Name = '', _
			$sRegistryKey_Value = ''

	While 1
		If $iError Then
			ExitLoop
		EndIf
		$sRegistryKey_Main = RegEnumKey($sRegistryKey, $iCount_1 + 1)
		If @error Then
			$sRegistryKey_All = $sRegistryKey
			$iError = 1
		Else
			$sRegistryKey_All = $sRegistryKey & $sRegistryKey_Main
		EndIf

		$iCount_2 = 0
		While 1
			$sRegistryKey_Name = RegEnumVal($sRegistryKey_All, $iCount_2 + 1)
			If @error Then
				ExitLoop
			EndIf

			If ($aArray[0][0] + 1) >= $iDimension Then
				$iDimension = Ceiling(($aArray[0][0] + 1) * 1.5)
				ReDim $aArray[$iDimension][$aArray[0][1]]
			EndIf

			$sRegistryKey_Value = RegRead($sRegistryKey_All, $sRegistryKey_Name)
			$aArray[$aArray[0][0] + 1][0] = $sRegistryKey_Main
			$aArray[$aArray[0][0] + 1][1] = $sRegistryKey_All
			$aArray[$aArray[0][0] + 1][2] = $sRegistryKey & $sRegistryKey_Main & '\' & $sRegistryKey_Name
			$aArray[$aArray[0][0] + 1][3] = $sRegistryKey_Name
			$aArray[$aArray[0][0] + 1][4] = $sRegistryKey_Value
			$aArray[0][0] += 1
			$iCount_2 += 1
		WEnd
		$iCount_1 += 1
	WEnd
	ReDim $aArray[$aArray[0][0] + 1][$aArray[0][1]]
	Return $aArray
EndFunc   ;==>__ShellFolder_RegistryGet