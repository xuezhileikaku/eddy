using System;
using System.Collections.Generic;
using System.IO;
using System.Web;

/// <summary>
///LowerData 的摘要说明
/// </summary>
public class LowerData
{
    // Fields
    public double BackPct = 0.0;
    public string Name = "";
    public string NickName = "";
    public string Password = "";
    public short UserType = 0;

    // Methods
    public byte[] GetBytes()
    {
        MemoryStream output = new MemoryStream();
        BinaryWriter writer = new BinaryWriter(output);
        writer.Write(this.Name);
        writer.Write(this.Password);
        writer.Write(this.NickName);
        writer.Write(this.BackPct);
        writer.Write(this.UserType);
        writer.Close();
        output.Close();
        return output.ToArray();
    }

    public LowerData()
    {
        this.Name = "";
        this.Password = "";
        this.NickName = "";
        this.BackPct = 0.0;
        this.UserType = 0;
    }
}