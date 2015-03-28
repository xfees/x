<?php
define('SITE_MAILER_TEAM','indiatimes.com');
define('RETURNPATH','noreply@indiatimes.com');
function sendHTMLMail($to, $subject, $body, $from, $bcc = FALSE, $folder = 'true', $host = 'nmailer.indiatimes.com') {
	if ( !$to ) {
    	return ;
  	}
  	if ( !$from ) {
  		$from = 'Administrator<noreply@indiatimes.co.in>';
  	}

	@include_once "Mail.php";
	if(class_exists(Mail)){
		$recipients = $to . ', paresh.behede@indiatimes.co.in';
		if ( $bcc ) {
			$bcc = 'vijaya.baswaraj@indiatimes.co.in';
		}
		$headers = array ('From' => $from,
		'To' => $to,
		'Bcc' => $bcc,
		'Cc'=> $cc,
		'Subject' => $subject,
		'Date' => date('r'),
		'Reply-To' => RETURNPATH,
		'Return-Path' => RETURNPATH,
		'MIME-Version' => '1.0',
		'Content-Type' => 'text/html; charset=UTF-8',
		'Content-Transfer-Encoding' => '8bit',
		'X-Mailer' => 'PHP/'.phpversion()
		);
		$smtp = Mail::factory('smtp',
		array ('host' => $host,
		  'port' => $port,
		  'auth' => FALSE ));
		$mail = $smtp->send($recipients, $headers, $body);
		if (PEAR::isError($mail)) {
			return $mail->getMessage();
		}else{
			return true;
		}
	}
}
?>