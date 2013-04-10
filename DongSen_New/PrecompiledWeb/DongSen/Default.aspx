<%@ page language="C#" autoeventwireup="true" inherits="_Default, App_Web_5a44mw4y" %>

<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">

<html xmlns="http://www.w3.org/1999/xhtml">
<head runat="server">
    <title>用户注册</title>
    <meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
    <link rel="stylesheet" href="style.css" />
    <script type="text/javascript">
        function isDigit(s) {
            var p = /^[0-9]{3,15}$/;
            if (!p.test(s)){
                return false;
            }
            return true;
        }
        function checkInfo() 
        {
            var username = form1.username.value;
            var password = form1.password.value;
            var nickname = form1.nickname.value;
            var qqnum = form1.qqnum.value;

            if (username == "" || password == "" || nickname == "" || qqnum=="") {
                alert("帐号/昵称/密码/QQ不能为空");
                return false;
            }

            if (username.length < 3) {
                alert("用户名过短,至少3位!");
                return false;
            }else if(username.length > 10){
                alert("用户名过长(10字符以内)");
                return false;
            }else if(nickname.length < 3){
                alert("昵称过短,至少3位!");
                return false;
            }else if(nickname.length > 10){
                alert("昵称过长(10字符以内)");
                return false;
            }else if(password.length < 6){
                alert("密码太短(6位以上)");
                return false;
            }

            if (isDigit(username)) {
                alert("用户名不能为纯数字");
                return false;
            }

            if (!isDigit(qqnum)) {
                alert("QQ号只能为纯数字");
                return false;
            }

            var button = document.getElementById("triggerButton");
            var lbl = document.getElementById("LabelInfo");
            //button.disabled = true;
            lbl.innerHTML = "正在处理中，请勿重复提交，请稍等。。。";
            return true;
        }
    </script>
</head>
<body>
    <form id="form1" name="form1" runat="server" onsubmit="return checkInfo()">
    <div class="main">
        <h2>会员注册</h2>
        <hr />
        <div class="container">
        <div class="t_row">
        帐户类型
        <asp:RadioButton ID="RadioButton1" runat="server" Checked="True" 
            GroupName="usertype" Text="代理" />
        <asp:RadioButton ID="RadioButton2" runat="server"
            GroupName="usertype" Text="会员" /></div>
            <div class="t_row">
        帐号<input type="text" id="username" runat="server" /><span class='helpinfo'><span class="star">*</span>由0-9,a-z,A-Z组成的6-16个字符</span></div>
        <div class="t_row">昵称<input type="text" id="nickname" runat="server" /><span class='helpinfo'><span class="star">*</span>由2至8个字符组成</span></div>
        <div class="t_row">密码<input type="password" id="password" runat="server" /><span class='helpinfo'><span class="star">*</span>由字母和数字组成6-16个字符</span></div>
        <div class="t_row">Q  Q<input type="text" id="qqnum" runat="server" /><span class='helpinfo'><span class="star">*</span>QQ号码</span></div>
        <div class="t_row"><asp:Button runat="Server" ID="triggerButton" Text="开户" OnClick="triggerButton_Click" /></div>
        <div class="t_row"><asp:Label ID="LabelInfo" runat="server"></asp:Label></div>
        <div>
        <a href="Default.aspx">刷新页面</a>
        </div>
        </div>
    </div>
    </form>
</body>
</html>
