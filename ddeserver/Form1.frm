VERSION 5.00
Begin VB.Form Form1 
   Caption         =   "Server"
   ClientHeight    =   1560
   ClientLeft      =   60
   ClientTop       =   510
   ClientWidth     =   6450
   Icon            =   "Form1.frx":0000
   LinkMode        =   1  'Source
   LinkTopic       =   "sliver"
   ScaleHeight     =   1560
   ScaleWidth      =   6450
   StartUpPosition =   2  '屏幕中心
   Begin VB.TextBox Text2 
      Height          =   285
      Left            =   990
      TabIndex        =   11
      Text            =   "http://ls.lzjgold-dg.com/getact.php"
      Top             =   1170
      Width           =   3795
   End
   Begin VB.TextBox Text1 
      Height          =   270
      Left            =   990
      TabIndex        =   6
      Text            =   "1000"
      Top             =   765
      Width           =   585
   End
   Begin VB.TextBox data4 
      Height          =   375
      Left            =   4920
      TabIndex        =   4
      Text            =   "0"
      Top             =   240
      Width           =   1455
   End
   Begin VB.TextBox data3 
      Height          =   375
      Left            =   3320
      TabIndex        =   3
      Text            =   "0"
      Top             =   240
      Width           =   1455
   End
   Begin VB.TextBox data2 
      Height          =   375
      Left            =   1720
      TabIndex        =   2
      Text            =   "0"
      Top             =   240
      Width           =   1455
   End
   Begin VB.TextBox data1 
      Height          =   375
      Left            =   120
      TabIndex        =   1
      Text            =   "0"
      Top             =   240
      Width           =   1455
   End
   Begin VB.Timer Timer1 
      Enabled         =   0   'False
      Interval        =   2000
      Left            =   2925
      Top             =   720
   End
   Begin VB.CommandButton Command1 
      Caption         =   "Start"
      Height          =   375
      Left            =   5265
      TabIndex        =   0
      Top             =   1170
      Width           =   1095
   End
   Begin VB.Label Label6 
      Caption         =   "server status"
      Height          =   240
      Left            =   3330
      TabIndex        =   12
      Top             =   810
      Width           =   3075
   End
   Begin VB.Label Label5 
      Caption         =   "Address:"
      Height          =   285
      Left            =   135
      TabIndex        =   10
      Top             =   1215
      Width           =   735
   End
   Begin VB.Label Label4 
      Height          =   255
      Left            =   2700
      TabIndex        =   9
      Top             =   810
      Width           =   465
   End
   Begin VB.Label Label3 
      Caption         =   "Type:"
      Height          =   255
      Left            =   2160
      TabIndex        =   8
      Top             =   810
      Width           =   465
   End
   Begin VB.Label Label2 
      Caption         =   "ms"
      Height          =   255
      Left            =   1710
      TabIndex        =   7
      Top             =   810
      Width           =   255
   End
   Begin VB.Label Label1 
      Caption         =   "Interval:"
      Height          =   255
      Left            =   120
      TabIndex        =   5
      Top             =   810
      Width           =   825
   End
End
Attribute VB_Name = "Form1"
Attribute VB_GlobalNameSpace = False
Attribute VB_Creatable = False
Attribute VB_PredeclaredId = True
Attribute VB_Exposed = False
Option Explicit
Private url As String
Private Sub Command1_Click()
    On Error Resume Next
    If Command1.Caption = "Start" Then
        Label6.Caption = "server is running..."
        Command1.Caption = "Stop"
        url = Trim(Text2.Text)
        If Val(Text1.Text) < 100 Or Val(Text1.Text) > 100000 Then
            MsgBox "时间间隔设置过短或过长", vbOKOnly
            Text1.Text = Trim(Str(1000))
        End If
        Timer1.Interval = Val(Text1.Text)
        Timer1.Enabled = True
    Else
        Timer1.Enabled = False
        Command1.Caption = "Start"
        Label6.Caption = "server stoped"
    End If
End Sub

Private Sub Timer1_Timer()
    handleResponse XMLHttpRequest("GET", url, "")
End Sub

Private Function XMLHttpRequest(ByVal XmlHttpMode, ByVal XmlHttpUrl, ByVal XmlHttpData)
    Dim MyXmlHttp As Object
    On Error Resume Next
    Set MyXmlHttp = CreateObject("Msxml2.ServerXMLHTTP.6.0")
    With MyXmlHttp
        .setTimeouts 5000, 5000, 5000, 5000
        If XmlHttpMode = "POST" Then
            .Open "POST", XmlHttpUrl, True
        Else
            .Open "GET", XmlHttpUrl, True
        End If
        .send XmlHttpData
        .waitForResponse
        If MyXmlHttp.Status = 200 Then
            XMLHttpRequest = .responseText
            'Debug.Print .responseText
        Else
            XMLHttpRequest = ""
        End If
    End With
    Set MyXmlHttp = Nothing
End Function

Private Function handleResponse(ByVal strResult)
    Dim res() As String
    Dim first() As String
    On Error Resume Next
    If strResult = "" Then
        Exit Function
    Else
        strResult = Replace(strResult, """", "")
    End If
    res() = Split(strResult, ",")
    first() = Split(res(0), ":")
    data1.Text = first(1)
    data2.Text = res(1)
    data3.Text = res(2)
    data4.Text = res(3)
    Label4.Caption = first(0)
End Function
