<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Verifikasi Email - Patriot Metric</title>
</head>
<body style="margin: 0; padding: 0; background-color: #f4f7f6; font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;">
    <table role="presentation" width="100%" cellspacing="0" cellpadding="0" border="0" style="background-color: #f4f7f6; padding: 40px 0;">
        <tr>
            <td align="center">
                <table role="presentation" width="600" cellspacing="0" cellpadding="0" border="0" style="background-color: #ffffff; border-radius: 8px; overflow: hidden; box-shadow: 0 2px 8px rgba(0,0,0,0.08);">
                    {{-- Header --}}
                    <tr>
                        <td style="padding: 0; line-height: 0; font-size: 0;">
                            <img src="{{ asset('assets/images/Banner Email Patriot Metric.png') }}"
                                 alt="Patriot Metric"
                                 width="600"
                                 style="display: block; width: 100%; max-width: 600px; height: auto; border: 0;" />
                        </td>
                    </tr>

                    {{-- Body --}}
                    <tr>
                        <td style="padding: 40px;">
                            <h2 style="margin: 0 0 16px; color: #1b5e20; font-size: 20px; font-weight: 600;">Verifikasi Email Anda</h2>

                            <p style="margin: 0 0 12px; color: #333333; font-size: 15px; line-height: 1.6;">
                                Halo,
                            </p>

                            <p style="margin: 0 0 12px; color: #333333; font-size: 15px; line-height: 1.6;">
                                Terima kasih bapak/ibu <strong>{{ $user->name }}</strong> telah mendaftarkan <strong>{{ $institutionName }}</strong> pada UPN Veteran JATIM Patriot Metric University Ranking.
                            </p>

                            <p style="margin: 0 0 24px; color: #333333; font-size: 15px; line-height: 1.6;">
                                Klik tombol di bawah untuk memverifikasi email Anda:
                            </p>

                            {{-- CTA Button --}}
                            <table role="presentation" cellspacing="0" cellpadding="0" border="0" style="margin: 0 auto 24px;">
                                <tr>
                                    <td align="center" style="border-radius: 6px; background-color: #1b5e20;">
                                        <a href="{{ $verificationUrl }}" target="_blank" style="display: inline-block; padding: 14px 32px; color: #ffffff; font-size: 16px; font-weight: 600; text-decoration: none; border-radius: 6px;">
                                            Verifikasi Email Saya
                                        </a>
                                    </td>
                                </tr>
                            </table>

                            {{-- Fallback Link --}}
                            <p style="margin: 0 0 24px; color: #666666; font-size: 13px; line-height: 1.6;">
                                Jika tombol di atas tidak berfungsi, salin dan tempel link berikut di browser Anda:
                            </p>
                            <p style="margin: 0 0 24px; color: #1b5e20; font-size: 13px; line-height: 1.6; word-break: break-all;">
                                <a href="{{ $verificationUrl }}" style="color: #1b5e20; text-decoration: underline;">{{ $verificationUrl }}</a>
                            </p>

                            {{-- Expiry Note --}}
                            <p style="margin: 0; padding: 12px 16px; background-color: #e8f5e9; border-radius: 4px; color: #2e7d32; font-size: 13px; line-height: 1.5;">
                                ⏱ Link ini berlaku selama 60 menit. Setelah itu, Anda perlu meminta link verifikasi baru.
                            </p>
                        </td>
                    </tr>

                    {{-- Footer --}}
                    <tr>
                        <td style="padding: 24px 40px; background-color: #f9faf9; border-top: 1px solid #e0e0e0; text-align: center;">
                            <p style="margin: 0; color: #999999; font-size: 12px; line-height: 1.5;">
                                Email ini dikirim secara otomatis oleh Patriot Metric.<br>
                                Jika Anda tidak merasa mendaftar, abaikan email ini.
                            </p>
                        </td>
                    </tr>
                </table>
            </td>
        </tr>
    </table>
</body>
</html>
