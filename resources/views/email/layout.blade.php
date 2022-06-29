<html>
<head>
    <meta http-equiv="Content-Type" content="text/html; charset=utf-8"/>
    <style type="text/css">
    </style>
</head>
<body id="mimemail-body" class="project-completed-notification-for-followers" style="background-color:#f0f0f0;">
<table width="600" cellspacing="0" cellpadding="0"
       style="font-family: Helvetica,sans-serif;font-size: 14px;background:#fff;padding: 30px 0px 30px 0px; margin:0 auto;border-collapse:collapse;">
    <tbody>
    <tr>
        <td style="color: #6B6B6B;width: 560px;margin: auto;background:#fff;padding: 10px 20px 20px 20px;margin0;">
            <div
                style="display:block !important;padding:20px 0 0 10px;color:#fff;font-size:26px;font-weight:bold;text-align:center;">
            </div>
            <div style="padding:0px 30px 0px 30px;color:#444444;">
                @if (isset($email_title))
                    <h1 style="color: #6f6f6f; font-family: Arial; font-weight: normal; font-size: 16px; padding-bottom: 24px; text-align: center;">{!!$email_title!!}</h1>
                @endif
                <div style="color:#444444;padding-top:20px;padding-bottom:20px;line-height:1.4em;">
                    @yield('content')
                </div>
            </div>
        </td>
    </tr>
    </tbody>
</table>
</body>
</html>
