<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Kode OTP Password Reset</title>
</head>
<body style="margin: 0; padding: 0; background-color: #f6f9fc; font-family: Arial, sans-serif;">
    <table border="0" cellpadding="0" cellspacing="0" width="100%" style="padding: 20px 0;">
        <tr>
            <td align="center">
                <table border="0" cellpadding="0" cellspacing="0" width="100%" style="max-width: 500px; background-color: #ffffff; border-radius: 12px; box-shadow: 0 4px 6px rgba(0,0,0,0.1); overflow: hidden;">
                    
                    <tr>
                        <td style="padding: 30px; background-color: #007bff; text-align: center;">
                            <h1 style="margin: 0; color: #ffffff; font-size: 24px;">OTP Reset Password</h1>
                        </td>
                    </tr>

                    <tr>
                        <td style="padding: 40px 30px;">
                            <p style="margin: 0 0 20px 0; font-size: 16px; color: #333333; line-height: 1.5;">
                                Halo, kami menerima permintaan untuk mengatur ulang kata sandi akun Anda. Gunakan kode OTP di bawah ini untuk melanjutkan:
                            </p>

                            <div style="text-align: center; margin: 30px 0;">
                                <div style="display: inline-block; padding: 15px 30px; background-color: #f1f4f9; border: 2px dashed #007bff; border-radius: 8px;">
                                    <span style="font-size: 36px; font-weight: bold; letter-spacing: 8px; color: #007bff;"><?= $otp ?></span>
                                </div>
                                <p style="margin-top: 15px; font-size: 13px; color: #777777;">
                                    *Kode berlaku selama 10 menit
                                </p>
                            </div>

                            <div style="padding: 20px; background-color: #fff4f4; border-left: 4px solid #dc3545; border-radius: 4px;">
                                <p style="margin: 0; font-size: 14px; color: #bd2130; line-height: 1.6;">
                                    <strong>PERINGATAN KEAMANAN:</strong><br>
                                    Jangan berikan kode ini kepada siapa pun. Kami NEXUS <strong>TIDAK PERNAH</strong> meminta kode OTP Anda melalui pesan, telepon, atau media sosial. Jika ada yang meminta, itu dipastikan adalah penipuan.
                                </p>
                            </div>

                            <p style="margin: 30px 0 0 0; font-size: 14px; color: #555555; line-height: 1.5;">
                                Jika Anda tidak merasa melakukan permintaan ini, abaikan saja email ini atau hubungi bantuan jika merasa ada aktivitas mencurigakan.
                            </p>
                        </td>
                    </tr>

                    <tr>
                        <td style="padding: 20px 30px; background-color: #f8f9fa; text-align: center;">
                            <p style="margin: 0; font-size: 12px; color: #999999;">
                                &copy; 2026 Nama Web Kamu. Seluruh hak cipta dilindungi.
                            </p>
                        </td>
                    </tr>
                </table>
            </td>
        </tr>
    </table>
</body>
</html>