<?php
error_reporting(-1);
ini_set('display_errors', 'On');
if ($_POST) { // eñëè ïeðeäaí ìaññèâ POST
	$name = htmlspecialchars($_POST["name"]); // ïèøeì äaííûe â ïeðeìeííûe è ýêðaíèðóeì ñïeöñèìâoëû
	$email = htmlspecialchars($_POST["mail"]);
	$number = htmlspecialchars($_POST["number"]);
	$message = htmlspecialchars($_POST["message"]);
	$subject=htmlspecialchars($_POST["trip"]);
	$json = array(); // ïoäãoòoâèì ìaññèâ oòâeòa
	if (!$name or !$email or !$number) { // eñëè õoòü oäío ïoëe oêaçaëoñü ïóñòûì
		$json['error'] = 'Required field/s are/is empty.'; // ïèøeì oøèáêó â ìaññèâ
		echo json_encode($json); // âûâoäèì ìaññèâ oòâeòa 
		die(); // óìèðaeì
	}
	if(!preg_match("|^[-0-9a-z_\.]+@[-0-9a-z_^\.]+\.[a-z]{2,6}$|i", $email)) { // ïðoâeðèì email ía âaëèäíoñòü
		$json['error'] = 'Wrong e-mail format!'; // ïèøeì oøèáêó â ìaññèâ
		echo json_encode($json); // âûâoäèì ìaññèâ oòâeòa
		die(); // óìèðaeì
	}

	function mime_header_encode($str, $data_charset, $send_charset) { // ôóíêöèÿ ïðeoáðaçoâaíèÿ çaãoëoâêoâ â âeðíóþ êoäèðoâêó 
		if($data_charset != $send_charset)
		$str=iconv($data_charset,$send_charset.'//IGNORE',$str);
		return ('=?'.$send_charset.'?B?'.base64_encode($str).'?=');
	}
	/* ñóïeð êëaññ äëÿ oòïðaâêè ïèñüìa â íóæíoé êoäèðoâêe */
	class TEmail {
	public $from_email;
	public $from_name;
	public $to_email;
	public $to_name;
	public $subject;
	public $data_charset='UTF-8';
	public $send_charset='windows-1251';
	public $body='';
	public $type='text/plain';

	function send(){
		$dc=$this->data_charset;
		$sc=$this->send_charset;
		$enc_to=mime_header_encode($this->to_name,$dc,$sc).' <'.$this->to_email.'>';
		$enc_subject=mime_header_encode($this->subject,$dc,$sc);
		$enc_from=mime_header_encode($this->from_name,$dc,$sc).' <'.$this->from_email.'>';
		$enc_body=$dc==$sc?$this->body:iconv($dc,$sc.'//IGNORE',$this->body);
		$headers='';
		$headers.="Mime-Version: 1.0\r\n";
		$headers.="Content-type: ".$this->type."; charset=".$sc."\r\n";
		$headers.="From: ".$enc_from."\r\n";
		return mail($enc_to,$enc_subject,$enc_body,$headers);
	}

	}

	$emailgo= new TEmail; // èíèöèaëèçèðóeì ñóïeð êëaññ oòïðaâêè
	$emailgo->from_email= $email; // oò êoão
	$emailgo->from_name= $name;
	$emailgo->to_email= 'samigullinroma@gmail.com'; // êoìó
	$emailgo->to_name= 'Roman Samiguzlo';
	$emailgo->subject= $subject; // òeìa
	$bodymail = "Hi, my name is ";
	$bodymail .= $name;
	$bodymail .= ". ";
	$bodymail .= " Telephone: ";
	$bodymail .= $number;
	$bodymail .= " Message: ";
	$bodymail .= $message;
	$emailgo->body=$bodymail ; // ñooáùeíèe
	$emailgo->send(); // oòïðaâëÿeì

	$json['error'] = 0; // oøèáoê íe áûëo

	echo json_encode($json); // âûâoäèì ìaññèâ oòâeòa
} else { // eñëè ìaññèâ POST íe áûë ïeðeäaí
	echo 'GET LOST!'; // âûñûëaeì
}
?>