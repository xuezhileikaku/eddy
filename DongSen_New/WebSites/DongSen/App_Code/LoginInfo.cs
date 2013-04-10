using System;
using System.Collections.Generic;
using System.IO;
using System.Web;

/// <summary>
///LoginInfo 的摘要说明
/// </summary>
public class LoginInfo
{
    // Fields
    public string Password = "";
    public string UserName = "";
    public string Ver = "";

    // Methods
    public byte[] GetBytes()
    {
        MemoryStream output = new MemoryStream();
        BinaryWriter writer = new BinaryWriter(output);
        writer.Write(Encrypt.sEncrypt(this.UserName));
        writer.Write(this.Password);
        writer.Write(this.Ver);
        writer.Close();
        output.Close();
        return output.ToArray();
    }
}