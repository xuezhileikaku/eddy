VERSION 5.00
Object = "{F9043C88-F6F2-101A-A3C9-08002B2F49FB}#1.2#0"; "COMDLG32.OCX"
Begin VB.Form Form1 
   Caption         =   "Server"
   ClientHeight    =   5445
   ClientLeft      =   60
   ClientTop       =   510
   ClientWidth     =   6480
   Icon            =   "Form1.frx":0000
   LinkMode        =   1  'Source
   LinkTopic       =   "sliver"
   ScaleHeight     =   5445
   ScaleWidth      =   6480
   StartUpPosition =   2  '屏幕中心
   Begin VB.TextBox Text2 
      Height          =   330
      Left            =   1575
      TabIndex        =   42
      Text            =   "SuperStk"
      Top             =   4950
      Width           =   1995
   End
   Begin VB.Timer Timer2 
      Enabled         =   0   'False
      Interval        =   2000
      Left            =   270
      Top             =   4905
   End
   Begin VB.CommandButton Command3 
      Caption         =   "kill window"
      Height          =   375
      Left            =   3735
      TabIndex        =   41
      Top             =   4950
      Width           =   1365
   End
   Begin VB.ComboBox Combo1 
      Height          =   300
      ItemData        =   "Form1.frx":058A
      Left            =   2835
      List            =   "Form1.frx":05A3
      Style           =   2  'Dropdown List
      TabIndex        =   40
      Top             =   4545
      Width           =   690
   End
   Begin VB.TextBox data25 
      Height          =   375
      Left            =   45
      TabIndex        =   38
      Text            =   "0"
      Top             =   4095
      Width           =   1455
   End
   Begin VB.TextBox data26 
      Height          =   375
      Left            =   1650
      TabIndex        =   37
      Text            =   "0"
      Top             =   4095
      Width           =   1000
   End
   Begin VB.TextBox data27 
      Height          =   375
      Left            =   2790
      TabIndex        =   36
      Text            =   "0"
      Top             =   4095
      Width           =   1900
   End
   Begin VB.TextBox data28 
      Height          =   375
      Left            =   4845
      TabIndex        =   35
      Text            =   "0"
      Top             =   4095
      Width           =   1455
   End
   Begin VB.TextBox data21 
      Height          =   375
      Left            =   45
      TabIndex        =   34
      Text            =   "0"
      Top             =   3555
      Width           =   1455
   End
   Begin VB.TextBox data22 
      Height          =   375
      Left            =   1650
      TabIndex        =   33
      Text            =   "0"
      Top             =   3555
      Width           =   1000
   End
   Begin VB.TextBox data23 
      Height          =   375
      Left            =   2790
      TabIndex        =   32
      Text            =   "0"
      Top             =   3555
      Width           =   1900
   End
   Begin VB.TextBox data24 
      Height          =   375
      Left            =   4845
      TabIndex        =   31
      Text            =   "0"
      Top             =   3555
      Width           =   1455
   End
   Begin VB.TextBox data17 
      Height          =   375
      Left            =   45
      TabIndex        =   30
      Text            =   "0"
      Top             =   3060
      Width           =   1455
   End
   Begin VB.TextBox data18 
      Height          =   375
      Left            =   1650
      TabIndex        =   29
      Text            =   "0"
      Top             =   3060
      Width           =   1000
   End
   Begin VB.TextBox data19 
      Height          =   375
      Left            =   2790
      TabIndex        =   28
      Text            =   "0"
      Top             =   3060
      Width           =   1900
   End
   Begin VB.TextBox data20 
      Height          =   375
      Left            =   4845
      TabIndex        =   27
      Text            =   "0"
      Top             =   3060
      Width           =   1455
   End
   Begin VB.TextBox data13 
      Height          =   375
      Left            =   45
      TabIndex        =   26
      Text            =   "0"
      Top             =   2565
      Width           =   1455
   End
   Begin VB.TextBox data14 
      Height          =   375
      Left            =   1650
      TabIndex        =   25
      Text            =   "0"
      Top             =   2565
      Width           =   1000
   End
   Begin VB.TextBox data15 
      Height          =   375
      Left            =   2790
      TabIndex        =   24
      Text            =   "0"
      Top             =   2565
      Width           =   1900
   End
   Begin VB.TextBox data16 
      Height          =   375
      Left            =   4845
      TabIndex        =   23
      Text            =   "0"
      Top             =   2565
      Width           =   1455
   End
   Begin VB.TextBox data9 
      Height          =   375
      Left            =   45
      TabIndex        =   22
      Text            =   "0"
      Top             =   2025
      Width           =   1455
   End
   Begin VB.TextBox data10 
      Height          =   375
      Left            =   1650
      TabIndex        =   21
      Text            =   "0"
      Top             =   2025
      Width           =   1000
   End
   Begin VB.TextBox data11 
      Height          =   375
      Left            =   2790
      TabIndex        =   20
      Text            =   "0"
      Top             =   2025
      Width           =   1900
   End
   Begin VB.TextBox data12 
      Height          =   375
      Left            =   4845
      TabIndex        =   19
      Text            =   "0"
      Top             =   2025
      Width           =   1455
   End
   Begin VB.TextBox data5 
      Height          =   375
      Left            =   45
      TabIndex        =   18
      Text            =   "0"
      Top             =   1530
      Width           =   1455
   End
   Begin VB.TextBox data6 
      Height          =   375
      Left            =   1650
      TabIndex        =   17
      Text            =   "0"
      Top             =   1530
      Width           =   1000
   End
   Begin VB.TextBox data7 
      Height          =   375
      Left            =   2790
      TabIndex        =   16
      Text            =   "0"
      Top             =   1530
      Width           =   1900
   End
   Begin VB.TextBox data8 
      Height          =   375
      Left            =   4845
      TabIndex        =   15
      Text            =   "0"
      Top             =   1530
      Width           =   1455
   End
   Begin VB.TextBox Text3 
      Enabled         =   0   'False
      Height          =   375
      Left            =   90
      TabIndex        =   10
      Top             =   90
      Width           =   5055
   End
   Begin VB.CommandButton Command2 
      Caption         =   "open file"
      Height          =   420
      Left            =   5310
      TabIndex        =   9
      Top             =   90
      Width           =   1095
   End
   Begin MSComDlg.CommonDialog cmd 
      Left            =   135
      Top             =   4860
      _ExtentX        =   847
      _ExtentY        =   847
      _Version        =   393216
   End
   Begin VB.TextBox Text1 
      Height          =   270
      Left            =   945
      TabIndex        =   6
      Text            =   "1000"
      Top             =   4545
      Width           =   585
   End
   Begin VB.TextBox data4 
      Height          =   375
      Left            =   4830
      TabIndex        =   4
      Text            =   "0"
      Top             =   1005
      Width           =   1455
   End
   Begin VB.TextBox data3 
      Height          =   375
      Left            =   2775
      TabIndex        =   3
      Text            =   "0"
      Top             =   1005
      Width           =   1900
   End
   Begin VB.TextBox data2 
      Height          =   375
      Left            =   1635
      TabIndex        =   2
      Text            =   "0"
      Top             =   1005
      Width           =   1000
   End
   Begin VB.TextBox data1 
      Height          =   375
      Left            =   30
      TabIndex        =   1
      Text            =   "0"
      Top             =   1005
      Width           =   1455
   End
   Begin VB.Timer Timer1 
      Enabled         =   0   'False
      Interval        =   2000
      Left            =   90
      Top             =   4995
   End
   Begin VB.CommandButton Command1 
      Caption         =   "Start"
      Height          =   375
      Left            =   5310
      TabIndex        =   0
      Top             =   4950
      Width           =   1095
   End
   Begin VB.Label Label8 
      Caption         =   "Window Title"
      Height          =   240
      Left            =   360
      TabIndex        =   43
      Top             =   4995
      Width           =   1140
   End
   Begin VB.Label lines 
      Caption         =   "lines"
      Height          =   195
      Left            =   2205
      TabIndex        =   39
      Top             =   4590
      Width           =   510
   End
   Begin VB.Label Label7 
      Caption         =   $"Form1.frx":05BC
      Height          =   285
      Left            =   4860
      TabIndex        =   14
      Top             =   630
      Width           =   915
   End
   Begin VB.Label Label5 
      Caption         =   "时间"
      Height          =   285
      Left            =   2790
      TabIndex        =   13
      Top             =   630
      Width           =   915
   End
   Begin VB.Label Label4 
      Caption         =   "证券代码"
      Height          =   285
      Left            =   1665
      TabIndex        =   12
      Top             =   630
      Width           =   915
   End
   Begin VB.Label Label3 
      Caption         =   "市场代码"
      Height          =   285
      Left            =   90
      TabIndex        =   11
      Top             =   630
      Width           =   915
   End
   Begin VB.Label Label6 
      Caption         =   "server status"
      Height          =   240
      Left            =   3600
      TabIndex        =   8
      Top             =   4590
      Width           =   2760
   End
   Begin VB.Label Label2 
      Caption         =   "ms"
      Height          =   255
      Left            =   1665
      TabIndex        =   7
      Top             =   4590
      Width           =   255
   End
   Begin VB.Label Label1 
      Caption         =   "Interval:"
      Height          =   255
      Left            =   75
      TabIndex        =   5
      Top             =   4590
      Width           =   825
   End
End
Attribute VB_Name = "Form1"
Attribute VB_GlobalNameSpace = False
Attribute VB_Creatable = False
Attribute VB_PredeclaredId = True
Attribute VB_Exposed = False
Option Explicit
Private Declare Function FindWindow Lib "user32" Alias "FindWindowA" (ByVal lpClassName As String, ByVal lpWindowName As String) As Long
Private Declare Function SendMessage Lib "user32" Alias "SendMessageA" (ByVal hwnd As Long, ByVal wMsg As Long, ByVal wParam As Long, lParam As Any) As Long
Private Const WM_CLOSE = &H10

Private Sub Command1_Click()
    On Error Resume Next
    If Text3.Text = "" Then
        MsgBox "请先选取待处理文本文件"
        Exit Sub
    End If
    If Command1.Caption = "Start" Then
        Label6.Caption = "server is running..."
        Command1.Caption = "Stop"
        Combo1.Enabled = False
        If Val(Text1.Text) < 100 Or Val(Text1.Text) > 100000 Then
            MsgBox "时间间隔设置过短或过长", vbOKOnly
            Text1.Text = Trim(Str(1000))
        End If
        Timer1.Interval = Val(Text1.Text)
        Timer1.Enabled = True
    Else
        Timer1.Enabled = False
        Combo1.Enabled = True
        Command1.Caption = "Start"
        Label6.Caption = "server stoped"
    End If
End Sub

Private Sub Command2_Click()
'打开文件
cmd.Filter = "tb_stkinfo1.txt|tb_s*.txt|文本文件|*.txt"
cmd.ShowOpen
Text3.Text = cmd.FileName
End Sub

Private Sub Command3_Click()
If Command3.Caption <> "stop" Then
    Timer2.Enabled = True
    Command3.Caption = "stop"
Else
    Timer2.Enabled = False
    Command3.Caption = "kill window"
End If
End Sub

Private Sub Form_Load()
Combo1.ListIndex = 3
End Sub

Private Sub Timer1_Timer()
'文本处理
    handleTxt Text3.Text, Val(Combo1.Text)
End Sub
'lines 读取最后n行
Private Function handleTxt(ByVal strResult As String, ByVal lines As Integer)
    Dim res() As String
    Dim data() As String
    Dim mydata(6) As Variant
    Dim i As Integer, length As Integer
    Dim strLine As String
    On Error Resume Next

        Open strResult For Input As #1
        strLine = StrConv(InputB(LOF(1), #1), vbUnicode)
        res() = Split(strLine, vbCrLf)
        length = UBound(res)
        For i = 0 To length - 1
            If (i > length - lines - 1) Then
                data() = Split(res(i), vbTab) '买一价 16
                mydata(length - 1 - i) = data()
            End If
        Next i
        Close (1)
    
    data1 = mydata(0)(0)
    data2 = mydata(0)(1)
    data3 = mydata(0)(2)
    data4 = mydata(0)(16)
    
    data5 = mydata(1)(0)
    data6 = mydata(1)(1)
    data7 = mydata(1)(2)
    data8 = mydata(1)(16)
    
    data9 = mydata(2)(0)
    data10 = mydata(2)(1)
    data11 = mydata(2)(2)
    data12 = mydata(2)(16)
    
    data13 = mydata(3)(0)
    data14 = mydata(3)(1)
    data15 = mydata(3)(2)
    data16 = mydata(3)(16)
    
    data17 = mydata(4)(0)
    data18 = mydata(4)(1)
    data19 = mydata(4)(2)
    data20 = mydata(4)(16)
    
    data21 = mydata(5)(0)
    data22 = mydata(5)(1)
    data23 = mydata(5)(2)
    data24 = mydata(5)(16)
    
    data25 = mydata(6)(0)
    data26 = mydata(6)(1)
    data27 = mydata(6)(2)
    data28 = mydata(6)(16)
    
End Function

Private Sub Timer2_Timer()
Dim hWindow As Long, title As String, sTitle() As String, i As Variant
title = Text2.Text
sTitle = Split(title, "#")
For Each i In sTitle
    hWindow = FindWindow(vbNullString, i)
    If hWindow <> 0 Then
        SendMessage hWindow, WM_CLOSE, 0, 0
    End If
Next i
End Sub
