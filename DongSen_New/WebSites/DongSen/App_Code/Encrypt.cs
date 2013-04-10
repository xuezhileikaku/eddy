using System;
using System.Collections.Generic;
using System.Security;
using System.Security.Cryptography;
using System.Text;
using System.IO;
using System.Web;

/// <summary>
///Encrypt 的摘要说明
/// </summary>
public class Encrypt
{
	public Encrypt()
	{
		//
		//TODO: 在此处添加构造函数逻辑
		//
	}

    // Methods
    public static string sEncrypt(string input)
    {
        try
        {
            byte[] buffer = Encoding.UTF8.GetBytes(input);
            byte[] salt = Encoding.UTF8.GetBytes("JvghbGSkkss");
            AesManaged managed = new AesManaged();
            Rfc2898DeriveBytes bytes = new Rfc2898DeriveBytes("eegclub", salt);
            managed.BlockSize = managed.LegalBlockSizes[0].MaxSize;
            managed.KeySize = managed.LegalKeySizes[0].MaxSize;
            managed.Key = bytes.GetBytes(managed.KeySize / 8);
            managed.IV = bytes.GetBytes(managed.BlockSize / 8);
            ICryptoTransform transform = managed.CreateEncryptor();
            MemoryStream stream = new MemoryStream();
            CryptoStream stream2 = new CryptoStream(stream, transform, CryptoStreamMode.Write);
            stream2.Write(buffer, 0, buffer.Length);
            stream2.Close();
            return Convert.ToBase64String(stream.ToArray());
        }
        catch
        {
            return "";
        }
    }
}