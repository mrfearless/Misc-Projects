#NoTrayIcon
MsgN := DllCall( "RegisterWindowMessage", Str,"TaskbarCreated" )
OnMessage( MsgN, "WM_TASKBAR_CREATED" )
Global T

#noenv
#singleinstance, force
DetectHiddenWindows, On
onexit, Exit

Gui, +lastfound
hwnd_gui1 := winexist()


;~ Gui, Color, CC0000 ; There is no need for this line because we are changing the background color anyway.


;hInst := DllCall("GetModuleHandle", "str", "z:\windows\system32\mmres.dll")
;hIcon := DllCall("LoadImage", "Uint", hInst, "Uint", 1, "Uint", 1, "int", 48, "int", 48, "Uint", 0x8000)

global tray_icon_headset := ".\headset.ico"
global tray_icon_speaker := ".\speaker.ico"

Gui, Font, s22 bold, Segoe UI
Gui, Add, Text, w700 vUpdateText,
Gui, -Border +AlwaysOnTop


; Creating NOTIFYICONDATA : http://msdn.microsoft.com/en-us/library/aa930660.aspx
; Thanks Lexikos : http://www.autohotkey.com/forum/viewtopic.php?p=162175#162175
PID := DllCall("GetCurrentProcessId"), VarSetCapacity( NID,444,0 ), NumPut( 444,NID )
DetectHiddenWindows, On
NumPut( WinExist( A_ScriptFullPath " ahk_class AutoHotkey ahk_pid " PID),NID,4 )
;DetectHiddenWindows, Off
NumPut( 1028,NID,8 ), NumPut( 2,NID,12 ), NumPut( hIcon,NID,20 )
Menu, Tray, Icon                                           ;   Shows the default Tray icon
DllCall( "shell32\Shell_NotifyIcon", UInt,0x1, UInt,&NID ) ; and we immediately modify it.
Menu, Tray, Icon, %tray_icon_speaker%
Menu, Tray, Tip, ToggleSound: ALT + `` To Toggle Between Headset & Speaker
;ChangeSound(2)
;T := !!T ; Toggle variable
return





!`:: ; ` Changes between devices
T := !T ; Toggle variable
If (T)
{
   ChangeSound(1)
   Menu, Tray, Icon, %tray_icon_headset%
   Menu, Tray, Tip, ToggleSound: Logitech USB headset
   ;DllCall( "shell32\Shell_NotifyIcon", UInt,0x1, UInt,&NID )
   ;Font color Change
   Gui, Font, cBlue  ; Use a new line for font information
   GuiControl, Font, UpdateText  ; Put the above font into effect for a control.
   
   Gui, Color, 999999 ;CC0000
   
   GuiControl,, UpdateText, Default audio device set to Logitech USB headset
   Gui, Show, Autosize NoActivate
}
else
{
   ChangeSound(2)
   Menu, Tray, Icon, %tray_icon_speaker%
   Menu, Tray, Tip, ToggleSound: Realtek Speakers
   ;DllCall( "shell32\Shell_NotifyIcon", UInt,0x1, UInt,&NID )
   ;Font color Change
   Gui, Font, cGreen  ; Use a new line for font information
   GuiControl, Font, UpdateText  ; Put the above font into effect for a control.
   
   Gui, Color, 999999 ;0000CC
   
   GuiControl,, UpdateText, Default audio device set to Realtek Speakers
   Gui, Show, Autosize NoActivate
}
   
   Settimer, GuiOff, -1000 ; Turns off the tooltip in 3 seconds by executing the ToolTipOff label once (once because the number of milliseconds on the timer is negative)
return

GuiOff: ; Turns off the gui when executed by the settimer
Gui, Hide
return

ChangeSound(DeviceNum)
{
	;Run rundll32.exe shell32.dll`,Control_RunDLL "mmsys.cpl",, Hide, PID
	;winwait, ahk_pid %pid%
	;hwnd := WinExist("ahk_pid " . PID)
	;Set_Parent(hwnd_gui1, hwnd)
	;Return

	Run, mmsys.cpl,,hide ; Run the sound device window
	WinWaitActive, Sound ahk_class #32770 ; wait for it to be active
	ControlSend, SysListView321,{Down %DeviceNum%}, Sound ahk_class #32770 ; Send the down key to highlight our device
	ControlClick, Button2, Sound ahk_class #32770 ; Click button 2 which is the 'Set Default' button
	WinClose, Sound ahk_class #32770 ; Close the window
}


WM_TASKBAR_CREATED() {
 Global NID
 WinWait ahk_class Shell_TrayWnd
 DllCall( "shell32\Shell_NotifyIcon", UInt,0x1, UInt,&NID )
 ;ChangeSound(2)
}

Exit:
	Process, Close, %pid%
ExitApp


Set_Parent(Parent_hwmd, Child_hwnd)
{
   Return DllCall("SetParent", "uint", Child_hwnd, "uint", Parent_hwmd)
} 