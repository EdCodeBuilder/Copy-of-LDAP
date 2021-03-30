<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">

<head>
    <meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
    <!--[if !mso]><!-->
    <meta http-equiv="X-UA-Compatible" content="IE=edge" />
    <!--<![endif]-->
    <meta name="viewport" content="width=device-width,initial-scale=1.0,maximum-scale=1">
    <title></title>
    <style type="text/css">
        .ReadMsgBody { width: 100%; background-color: #ffffff; }
        .ExternalClass { width: 100%; background-color: #ffffff; }
        .ExternalClass, .ExternalClass p, .ExternalClass span, .ExternalClass font, .ExternalClass td, .ExternalClass div { line-height: 100%; }
        html { width: 100%; }
        body { -webkit-text-size-adjust: none; -ms-text-size-adjust: none; margin: 0; padding: 0; }
        table { border-spacing: 0; table-layout: auto; margin: 0 auto; }
        .yshortcuts a { border-bottom: none !important; }
        img:hover { opacity: 0.9 !important; }
        a { color: #594d95; text-decoration: none; }
        .textbutton a { font-family: 'open sans', arial, sans-serif !important; }
        .btn-link a { color: #FFFFFF !important; }

        @media only screen and (max-width: 479px) {
            body { width: auto !important; font-family: 'Open Sans', Arial, Sans-serif !important;}
            .table-inner{ width: 90% !important; text-align: center !important;}
            .table-full { width: 100%!important; max-width: 100%!important; text-align: center !important;}
            /*gmail*/
            u + .body .full { width:100% !important; width:100vw !important;}
        }
    </style>
</head>

<body class="body">
<table class="full" width="100%" border="0" align="center" cellpadding="0" cellspacing="0">
    <tr>
        <td background="{{ asset('images/email-bg.jpg') }}" bgcolor="#494c50" valign="top" style="background-size: cover; background-position: center;">
            <table class="table-inner" align="center" width="600" style="max-width: 600px;" border="0" cellspacing="0" cellpadding="0">
                <tr>
                    <td height="40"></td>
                </tr>
                <tr>
                    <td bgcolor="#FFFFFF" style="border-top-left-radius: 4px;border-top-right-radius: 4px;" align="center">
                        <table width="90%" border="0" align="center" cellpadding="0" cellspacing="0">
                            <tr>
                                <td height="50"></td>
                            </tr>
                            <!-- logo -->
                            <tr>
                                <td align="center" style="line-height: 0px;"><img style="display:block; line-height:0px; font-size:0px; border:0px; height: 60%;" src="{{ asset('images/logo-color.png') }}" alt="I.D.R.D." /></td>
                            </tr>
                            <!-- end logo -->
                            <tr>
                                <td height="15"></td>
                            </tr>
                            <!-- slogan -->
                            <tr>
                                <td align="center" style="font-family: 'Open Sans', Arial, sans-serif; font-size:12px; color:#3b3b3b; text-transform:uppercase; letter-spacing:2px; font-weight: normal;"> {{'Notificación de Registro IDRD'}}</td>
                            </tr>
                            <!-- end slogan -->
                            <tr>
                                <td height="40"></td>
                            </tr>
                        </table>
                    </td>
                </tr>
                <tr>
                    <td align="center" bgcolor="#f3f3f3">
                        <table width="90%" border="0" align="center" cellpadding="0" cellspacing="0">
                            <tr>
                                <td height="50"></td>
                            </tr>
                            <!-- title -->
                            <tr>
                                <td align="center" style="font-family: 'Open Sans', Arial, sans-serif; font-size:36px; color:#3b3b3b; font-weight: bold; letter-spacing:4px;">{{ isset( $header ) ? $header : 'Gracias por Inscribirte' }}</td>
                            </tr>
                            <!-- end title -->
                            <tr>
                                <td align="center">
                                    <table width="25" border="0" cellspacing="0" cellpadding="0">
                                        <tr>
                                            <td height="15" style="border-bottom:2px solid #594d95;"></td>
                                        </tr>
                                    </table>
                                </td>
                            </tr>
                            <tr>
                                <td height="20"></td>
                            </tr>
                            <!-- content -->
                            <tr>
                                <td align="center" style="font-family: 'Open Sans', Arial, sans-serif; font-size:14px; color:#7f8c8d; line-height:29px;">
                                    {{ isset( $content ) ? $content : '¡Hola! hemos registrado tus datos satisfactoriamente' }}
                                </td>
                            </tr>
                            @if( isset( $remember ) && !isset($hide_btn) || (isset($hide_btn) && $hide_btn == false)  )
                            <tr>
                                <td style="border-bottom-left-radius: 4px;border-bottom-right-radius: 4px;" align="center">
                                    <table width="90%" border="0" align="center" cellpadding="0" cellspacing="0">
                                        <tr>
                                            <td height="40"></td>
                                        </tr>
                                        <!-- button -->
                                        <tr>
                                            <td align="center">
                                                <table class="textbutton" align="center" border="0" cellspacing="0" cellpadding="0">
                                                    <tr>
                                                        <td class="btn-link" bgcolor="#594d95" height="55" align="center" style="font-family: 'Open Sans', Arial, sans-serif; font-size:16px; color:#FFFFFF;font-weight: bold;padding-left: 25px;padding-right: 25px;border-radius:4px;">
                                                            <a href="{{ isset( $url ) ? $url : "https://www.idrd.gov.co/" }}">
                                                                {{ isset($btn_text) ? $btn_text : 'Ver Formulario' }}
                                                            </a>
                                                        </td>
                                                    </tr>
                                                </table>
                                            </td>
                                        </tr>
                                        <!-- end button -->
                                        <tr>
                                            <td height="30"></td>
                                        </tr>
                                    </table>
                                </td>
                            </tr>
                            @endif
                            <tr>
                                <td align="center" style="font-family: 'Open Sans', Arial, sans-serif; font-size:14px; color:#594d95; font-weight: bold; line-height:29px;">
                                    {{ isset( $title ) ? $title : '' }}
                                </td>
                            </tr>
                            <tr>
                                <td align="center">
                                    <table width="90%" border="0" cellspacing="0" cellpadding="0">
                                        <tr>
                                            <td height="15" style="border-bottom:2px solid #594d95;"></td>
                                        </tr>
                                    </table>
                                </td>
                            </tr>
                            <tr>
                                <td align="center">
                                    <table width="90%" border="0" cellspacing="0" cellpadding="0">
                                        <tr>
                                            <td align="left" style="font-family: 'Open Sans', Arial, sans-serif; font-size:14px; color:#7f8c8d; line-height:29px;">
                                                {!!  isset($details) ? $details : '' !!}
                                            </td>
                                        </tr>
                                    </table>
                                </td>
                            </tr>
                            <tr>
                                <td align="center">
                                    <table width="90%" border="0" cellspacing="0" cellpadding="0">
                                        <tr>
                                            <td height="15" style="border-bottom:2px solid #594d95;"></td>
                                        </tr>
                                    </table>
                                </td>
                            </tr>
                            <!-- end content -->
                            <tr>
                                <td height="20"></td>
                            </tr>
                            <tr>
                                <td align="center" style="font-family: 'Open Sans', Arial, sans-serif; font-size:14px; color:#594d95; font-weight: bold; line-height:29px;">
                                    {!!  isset( $info ) ? $info : '' !!}
                                </td>
                            </tr>
                            @if(isset( $remember ))
                            <tr>
                                <td height="20"></td>
                            </tr>
                            <tr>
                                <td align="center" style="font-family: 'Open Sans', Arial, sans-serif; font-size:14px; color:#3b3b3b; font-weight: bold; line-height:29px;">
                                    {!!  isset( $remember ) ? $remember : '' !!}
                                </td>
                            </tr>
                            <tr>
                                <td height="20"></td>
                            </tr>
                            <tr>
                                <td align="center" style="line-height: 0px;"><img style="display:block; line-height:0px; font-size:0px; border:0px; height: 60%;" src="{{ asset('images/remember.png') }}" alt="Recuerde" /></td>
                            </tr>
                            @endif
                            <tr>
                                <td height="50"></td>
                            </tr>
                        </table>
                    </td>
                </tr>
                <tr>
                    <td bgcolor="#FFFFFF" style="border-bottom-left-radius: 4px;border-bottom-right-radius: 4px;" align="center">
                        <table width="90%" border="0" align="center" cellpadding="0" cellspacing="0">
                            <tr>
                                <td height="40"></td>
                            </tr>
                            <!-- button -->
                            <tr>
                                <td align="center">
                                    <table class="textbutton" align="center" border="0" cellspacing="0" cellpadding="0">
                                        <tr>
                                            @if( !isset($hide_btn) || (isset($hide_btn) && $hide_btn == false)  )
                                                <td class="btn-link" bgcolor="#594d95" height="55" align="center" style="font-family: 'Open Sans', Arial, sans-serif; font-size:16px; color:#FFFFFF;font-weight: bold;padding-left: 25px;padding-right: 25px;border-radius:4px;">
                                                    <a href="{{ isset( $url ) ? $url : "https://www.idrd.gov.co/" }}">
                                                        {{ isset($btn_text) ? $btn_text : 'Ver Formulario' }}
                                                    </a>
                                                </td>
                                            @endif
                                        </tr>
                                    </table>
                                </td>
                            </tr>
                            <!-- end button -->
                            <tr>
                                <td height="30"></td>
                            </tr>
                        </table>
                    </td>
                </tr>
                <tr>
                    <td height="25"></td>
                </tr>
                <!-- copyright -->
                <tr>
                    <td align="center" style="font-family: 'Open Sans', Arial, sans-serif; font-size:13px; color:#ffffff;"> © {{ isset( $date ) ? $date : '2020' }} - Instituto Distrital de Recreación y Deporte. </td>
                </tr>
                <!-- end copyright -->
                <tr>
                    <td height="25"></td>
                </tr>
            </table>
        </td>
    </tr>
</table>
</body>

</html>
