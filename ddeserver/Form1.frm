VERSION 5.00
Begin VB.Form Form1 
   Caption         =   "Server"
   ClientHeight    =   1290
   ClientLeft      =   60
   ClientTop       =   510
   ClientWidth     =   6450
   Icon            =   "Form1.frx":0000
   LinkMode        =   1  'Source
   LinkTopic       =   "sliver"
   ScaleHeight     =   1290
   ScaleWidth      =   6450
   StartUpPosition =   2  '屏幕中心
   Begin VB.TextBox Text1 
      Height          =   270
      Left            =   990
      TabIndex        =   6
      Text            =   "2"
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
      Left            =   5160
      Top             =   840
   End
   Begin VB.CommandButton Command1 
      Caption         =   "Start"
      Height          =   375
      Left            =   3360
      TabIndex        =   0
      Top             =   765
      Width           =   1095
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
      Caption         =   "s"
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
Private Const url As String = "http://ls.lzjgold-dg.com/getact.php"
Private Sub Command1_Click()
    On Error Resume Next
    If Command1.Caption = "Start" Then
        Timer1.Enabled = True
        Command1.Caption = "Stop"
        If Val(Text1.Text) < 1 Then
            MsgBox "时间间隔设置过短", vbOKOnly
            Text1.Text = Str(3)
        Else
            Timer1.Interval = 1000 * Val(Text1.Text)
        End If
    Else
        Timer1.Enabled = False
        Command1.Caption = "Start"
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
