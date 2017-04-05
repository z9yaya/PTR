<?php
require ("sendgrid-php/sendgrid-php.php");

//example: Emailer("ze_yaya@msn.com", "stuff", "attach", "elias@ptrmanagement.online", "Elias Gebre", "Elias Gebre", "FileName.pdf", "../folder/file.pdf");

function Emailer($toEmail, $message, $Mailsubject, $fromEmail, $fromName ,$sendingName , $filePath, $fileNametoDisplay)
	{
	$sendingEmail = 'contact@ptrmanagement.online';
	if ($fromEmail == null) $fromEmail = $sendingEmail;
	if ($sendingName == null) $sendingName = "PTR Management";
	if ($fromName == null) $fromName = "PTR Management";
	$apiKey = 'please see file(right click open with notepad)';
	$sg = new SendGrid($apiKey);
	$data = ['personalizations' => [['to' => [['email' => $toEmail]], 'subject' => $Mailsubject]], 'from' => ['email' => $sendingEmail, 'name' => $sendingName ], 'reply_to' => ['email' => $fromEmail, 'name' => $fromName], 'content' => [['type' => 'text', 'value' => $message]]];
	if (!empty($filePath) && !empty($fileNametoDisplay))
		{
		$Filedata = file_get_contents($filePath);
		$base64 = base64_encode($Filedata);
		$data['attachments'] = [['content' => $base64, 'filename' => $fileNametoDisplay, 'disposition' => 'attachment']];
		}

	$response = $sg->client->mail()->send()->post($data);
	}

?>