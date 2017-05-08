<?php 
include "..functions/emailer.php";

    $html = "<!DOCTYPE html>
    <html>
        <head>
    <meta http-equiv='Content-Type' content='text/html; charset=utf-8'>
            <meta charset='utf-8'>
            <meta http-equiv='x-ua-compatible' content='ie=edge'>
            <meta name='viewport' content='width=device-width, initial-scale=1'>


        </head>
        <body style='background-color: #677979; color: black; font-family: sans-serif; display: table; table-layout: auto; width: 100%; margin: 0; padding: 0; padding-bottom: 25px' bgcolor='#677979';>
            <div style='display: table-cell; width: 100%;'>
                <img src='images/LOGO1.png' alt='PTR logo' height='80' width='200' style='padding-top: 25px; padding-bottom: 25px; display: block; margin: 0 auto'>
                <div style='display: block; max-width: 700px; background-color: white; height: 100%; border-bottom-style: solid; border-bottom-width: 5px; border-bottom-color: #d4d4d4; font-size: 14px; margin: 0 auto;'>
                <div style='box-sizing: border-box; padding-left: 50px; padding-right: 50px; overflow: hidden;'>
                <h2>Welcome to ClothingCo.</h2>
                    <div>You must be excited to start your new job as $position at $store, but before you can start working and getting paid.
                    First, this is your Employee ID, you will need it to sign up on the system: $ID, you also need to complete the following steps to ensure we have all you details:
                    <ol>
                        <li style='padding-bottom: 10px;'>Access a device with an internet connection</li>
                        <li style='padding-bottom: 10px;'>Click on the button below</li>
                        <li style='padding-bottom: 10px;'>Click on 'Sign Up'</li>
                        <li style='padding-bottom: 10px;'>Enter the email you provided to your manager on the day of your interview or any communication after</li>
                        <li style='padding-bottom: 10px;'>Enter a strong password (at least 8 characters, an upper case letter and a number)</li>
                        <li style='padding-bottom: 10px;'>Enter the your Employee ID if not already entered</li>
                        <li style='padding-bottom: 10px;'>Click on 'SIGN UP'</li>
                        </ol></div></div>
                    <a href='#d41d8cd98f00b204e9800998ecf8427e' style='background-color: #33a7a7; width: 100%; display: block; text-align: center; padding-top: 15px; padding-bottom: 17px; color: white; text-decoration: none; font-weight: bold;'>Access system</a>
                    <div style='display: block;margin: 0 auto;padding-top: 10px;text-align: center;font-size: 11px;color: #bdbdbd;font-weight: bold;padding-bottom: 10px;'>Please do not respond to this email</div>
                </div>

            </div>
        </body>
    </html>
    ";
    //Emailer($email, null, "Welcome to ClothingCo.", null,null,null,null,null,null,$html);
//print_r(openssl_get_cipher_methods());
$encoded = rtrim(strtr(base64_encode('E0000007.1'), '+/', '-_'), '='); 
echo $encoded."\n";
$res= base64_decode(str_pad(strtr($encoded, '-_', '+/'), strlen($encoded) % 4, '=', STR_PAD_RIGHT));
echo explode ("." , $res)[0];
header("Location: http://192.168.113.143/cgit/webpages/start.php?sid=$encoded");

?>