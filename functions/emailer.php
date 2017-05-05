<?php include "sendgrid-php/sendgrid-php.php";
//Emailer("ze_yaya@msn.com", "This is a message", "This is the subject", "who to respond to, can be null if no one", "name of person to respond to, can be null", "name of the person sending, can be null", "path to attachment, can be null if not sendint", "name of the file to show in email, can be null if no attachment", "greeting in body of email, if null will just be 'Hello,'", "leave null if you dont have special html for the body, will use the one designed by yannick");
function Emailer($toEmail, $message, $Mailsubject, $fromEmail, $fromName, $sendingName, $filePath, $fileNametoDisplay, $greeting = 'Hello,', $html = "<!DOCTYPE html>
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
            <h2>$greeting</h2>
                <div>$message</div></div>
                <div style='display: block;margin: 0 auto;padding-top: 10px;text-align: center;font-size: 11px;color: #bdbdbd;font-weight: bold;padding-bottom: 10px;'>Please do not respond to this email unless stated</div>
            </div>
            
        </div>
    </body>
</html>
")
{
    $sendingEmail = 'contact@ptrmanagement.online';
    if ($fromEmail == null) {
        $fromEmail = $sendingEmail;
    }
    if ($sendingName == null) {
        $sendingName = "PTR Management";
    }
    if ($fromName == null) {
        $fromName = "PTR Management";
    }
    $apiKey = 'API KEY';
    $sg = new SendGrid($apiKey);
    $data = ['personalizations' =>
             [['to' =>
               [['email' => $toEmail]], 'subject' => $Mailsubject]],
             'from' => ['email' => $sendingEmail, 'name' => $sendingName ],
             'reply_to' => ['email' => $fromEmail, 'name' => $fromName],
             'content' => [['type' => ' text/html', 'value' => $html]]];
    if (!empty($filePath) && !empty($fileNametoDisplay)) {
        $Filedata = file_get_contents($filePath);
        $base64 = base64_encode($Filedata);
        $data['attachments'] = [['content' => $base64,
                                'filename' => $fileNametoDisplay,
                                 'disposition' => 'attachment']];
    }

    $response = $sg->client->mail()->send()->post($data);
    echo $response->statusCode();
    print_r($response->headers());
    print_r($response->body());
}
