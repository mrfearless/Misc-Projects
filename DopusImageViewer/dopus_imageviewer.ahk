;-----------------------------------------------------------------------
; Directory Opus Arrow Key Navigation & MouseWheel Zoom In Image Viewer
;
; fearless 2013 @ www.LetTheLight.In
;-----------------------------------------------------------------------
#IfWinActive, ahk_class dopus.viewpicframe
	Left::Send {PgUp}
	Up::Send {PgUp}
	Right::Send {PgDn}
	Down::Send {PgDn}
	WheelUp::Send {NumpadAdd}
	WheelDown::Send {NumpadSub}
#IfWinActive