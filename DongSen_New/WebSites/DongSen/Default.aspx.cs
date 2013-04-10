using System;
using System.Collections.Generic;
using System.Linq;
using System.Web;
using System.Web.UI;
using System.Web.UI.WebControls;

using System.Text;
using System.IO;
using System.Net;
using System.Net.Sockets;
using System.Net.Mail;
using System.Configuration;
using System.Net.NetworkInformation;

public partial class _Default : System.Web.UI.Page
{
    protected void Page_Load(object sender, EventArgs e)
    {
        
    }

    protected void triggerButton_Click(object sender, EventArgs e)
    {
        if(Page.IsPostBack)
        {
            LabelInfo.Text = "正在处理中，请稍等。。。";
            triggerButton.Enabled = false;

            //服务器IP
            List<ServerInfo> list = new List<ServerInfo>();
            ServerInfo info1 = new ServerInfo();
            info1.Port = 0xca4d;
            info1.Ip = "112.132.215.30";
            list.Add(info1);
            ServerInfo info2 = new ServerInfo();
            info2.Port = 0x6bb0;
            info2.Ip = "112.213.122.141";
            list.Add(info2);
            ServerInfo info3 = new ServerInfo();
            info3.Port = 0xdba6;
            info3.Ip = "117.25.147.236";
            list.Add(info3);
            ServerInfo info4 = new ServerInfo();
            info4.Port = 0xd1c3;
            info4.Ip = "112.132.212.59";
            list.Add(info4);
            ServerInfo info5 = new ServerInfo();
            info5.Port = 0x4ec0;
            info5.Ip = "206.161.218.27";
            list.Add(info5);
            ServerInfo info6 = new ServerInfo();
            info6.Port = 0xccb1;
            info6.Ip = "211.55.29.30";
            list.Add(info6);

            string curIp = "117.25.147.236";
            int curPort = 0xdba6;
            Ping curP = new Ping();
            if (curP.Send(curIp).Status != IPStatus.Success)
            {
                foreach (ServerInfo si in list){
                    Ping p = new Ping();
                    if (p.Send(si.Ip).Status == IPStatus.Success)
                    {
                        curIp = si.Ip;
                        curPort = si.Port;
                    }
                }
                if (curIp == "211.55.29.30")
                {
                    LabelInfo.Text = "网络不通，连接服务器失败";
                    triggerButton.Enabled = true;
                    return;
                }
            }

            short UserType = 0;
            string errMsg = string.Empty;
            if (RadioButton1.Checked)
            {
                UserType = 1;
            }

            Socket s = new Socket(AddressFamily.InterNetwork, SocketType.Stream, ProtocolType.Tcp);
            //EndPoint point = new IPEndPoint(IPAddress.Parse("117.25.147.236"), 0xdba6);
            EndPoint point = new IPEndPoint(IPAddress.Parse(curIp), curPort);
            s.Connect(point);

            if (!s.Connected)
            {
                // Connection failed, try next IPaddress.
                s = null;
                LabelInfo.Text = "网络不通，连接服务器失败";
                triggerButton.Enabled = true;
                return;
            }
            else
            {
                /********************登陆********************/
                LoginInfo li = new LoginInfo();
                li.UserName = ConfigurationManager.AppSettings["user"];
                li.Password = Encrypt.sEncrypt(ConfigurationManager.AppSettings["pwd"]);
                li.Ver = "2.0.1";

                //Send(0x80001, li.GetBytes())
                byte[] datal = li.GetBytes();
                int sizel = datal.Length;
                int offsetl = 0;
                int msgl = 0x80001;

                byte[] destinationArrayl = new byte[sizel + 8];
                Array.Copy(BitConverter.GetBytes(msgl), 0, destinationArrayl, 0, 4);
                Array.Copy(BitConverter.GetBytes(sizel), 0, destinationArrayl, 4, 4);
                if ((datal != null) && (datal.Length >= (offsetl + sizel)))
                {
                    Array.Copy(datal, offsetl, destinationArrayl, 8, sizel);
                }
                try
                {
                    s.Send(destinationArrayl, destinationArrayl.Length, 0);//发包
                    byte[] buf = new byte[1024];
                    s.Receive(buf);//同步
                    //Response.Write(Encoding.ASCII.GetString(buf) + li.UserName);
                    //Console.WriteLine(Encoding.ASCII.GetString(buf));//调试登陆
                    if (Encoding.ASCII.GetString(buf)=="")
                    {
                        LabelInfo.Text = "内部错误，注册失败！";
                        triggerButton.Enabled = true;
                        return;
                    }
                }
                catch (SocketException err)
                {
                    errMsg = err.ErrorCode + ":" + err.Message;
                    //LabelInfo.Text = errMsg;
                    LabelInfo.Text = "网络超时，请稍后再试";
                    triggerButton.Enabled = true;
                    s.Close();
                    return;
                }
                

                /********************添加会员********************/
                LowerData ald = new LowerData();
                //Send(0x80060, ald.GetBytes());
                ald.Name = username.Value.Trim();
                ald.Password = password.Value.Trim();
                ald.NickName = nickname.Value.Trim();
                ald.BackPct = Convert.ToDouble(ConfigurationManager.AppSettings["fandian"]) / 100.0;
                ald.UserType = UserType;

                byte[] data = ald.GetBytes();
                int size = data.Length;
                int offset = 0;
                int msg = 0x80060;
                byte[] destinationArray = new byte[size + 8];
                Array.Copy(BitConverter.GetBytes(msg), 0, destinationArray, 0, 4);
                Array.Copy(BitConverter.GetBytes(size), 0, destinationArray, 4, 4);
                if ((data != null) && (data.Length >= (offset + size)))
                {
                    Array.Copy(data, offset, destinationArray, 8, size);
                }
                try
                {
                    s.Send(destinationArray, destinationArray.Length, 0);
                    byte[] buf2 = new byte[1024];
                    s.Receive(buf2);
                    if (Encoding.ASCII.GetString(buf2) == "")
                    {
                        LabelInfo.Text = "内部错误，注册失败！";
                        triggerButton.Enabled = true;
                        return;
                    }
                    //Console.WriteLine(Encoding.ASCII.GetString(buf2));//调试
                    /*
                    byte[] codeAry = new byte[4];
                    byte[] lenAry = new byte[4];
                    Array.Copy(buf2,0,codeAry,0,4);
                    Array.Copy(buf2, 4, lenAry, 0, 4);
                    int code = BitConverter.ToInt32(codeAry,0);
                    int len = BitConverter.ToInt32(lenAry, 0);
                    Response.Write("msg:" + string.Format("{0:x}", code));
                    Response.Write("msg:" + string.Format("{0:x}", len));*/
                    //Response.Write(BitConverter.ToString(buf2));
                    /*
                    if (code == 0x80006 && len ==0x1)
                    {
                        Response.Write("注册成功");
                    }
                    else
                    {
                        Response.Write("注册失败");
                    }*/
                }
                catch (SocketException err)
                {
                    errMsg = err.ErrorCode + ":" + err.Message;
                    //LabelInfo.Text = errMsg;
                    LabelInfo.Text = "网络超时，请稍后再试";
                    triggerButton.Enabled = true;
                    s.Close();
                    return;
                }
                /*
                 * 异步发送模式
                SocketAsyncEventArgs test = new SocketAsyncEventArgs();
                test.SetBuffer(destinationArray, 0, destinationArray.Length);
                s.SendAsync(test);

                test.Completed += new EventHandler<SocketAsyncEventArgs>(test_Completed);
                Byte[] RecvBytes = new Byte[1024];
                String strRetPage = null;
                Int32 bytes = s.Receive(RecvBytes, RecvBytes.Length, 0);
                strRetPage = Encoding.Unicode.GetString(RecvBytes, 0, bytes);

                while (bytes > 0)
                {
                    bytes = s.Receive(RecvBytes, RecvBytes.Length, 0);
                    strRetPage = strRetPage + Encoding.UTF8.GetString(RecvBytes, 0, bytes);
                }
                Console.WriteLine(strRetPage);*/

                //发送邮件
                
                SmtpClient client = new SmtpClient("smtp.163.com", 25);
                MailMessage msgMail =
                new MailMessage("dongsen8608@163.com", ConfigurationManager.AppSettings["to_mail"], "注册通知", "新用户注册：\r\n帐号：" + username.Value + "\r\n密码：" + password.Value + "\r\n昵称：" + nickname.Value + "\r\nQQ：" + qqnum.Value);
                //new MailMessage("dongsen8608@163.com", "eddy@rrgod.com", "注册通知", "新用户注册：\r\n帐号：" + username.Value + "\r\n密码：" + password.Value + "\r\n昵称：" + nickname.Value);
                client.UseDefaultCredentials = false;
                System.Net.NetworkCredential basicAuthenticationInfo = new System.Net.NetworkCredential(ConfigurationManager.AppSettings["mail_user"], ConfigurationManager.AppSettings["mail_pwd"]);
                client.Credentials = basicAuthenticationInfo;
                client.EnableSsl = false;
                client.Send(msgMail);

                LabelInfo.Text = "注册完成！请登陆检测是否注册成功。若失败，尝试更换帐号和昵称重新注册。";
                triggerButton.Enabled = true;
                username.Value = "";
                nickname.Value = "";
                qqnum.Value = "";
            }
        }
    }
}