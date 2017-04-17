<?php

class YYMail {

	private $header;
	private $parts;
	private $message;
	private $subject;
	private $to_address;
	private $cc_address;
	private $boundary;

	public function __construct () {
        return;
    }

    public function init($to, $from, $mail_subject, $cc="") {
		$this->to_address = $to;
		$this->cc_address = $cc;
		$this->subject    = "=?UTF-8?B?".base64_encode($mail_subject)."?=";
		$this->parts      = array();
		$this->boundary   = md5("niuniu");
		$this->header     = "From: $from\r\n";

		if (!empty($this->cc_address)) {
			$this->header .= "Cc: ".$this->cc_address."\r\n";
		}

		$mime = "MIME-Version: 1.0\r\n";
		$mime .= "Content-Type: multipart/related;charset=UTF-8;\n";
		$mime .= " boundary=\"". $this->boundary."\"\r\n";
		$mime .= "X-Mailer: PHP/".phpversion()."\r\n";

		$this->header .= $mime;
        return;
	}

	public function addMessage($msg = "", $ctype = "text/plain") {
		$content  = "Content-Type: $ctype; charset=utf-8\r\n";
		$content .= "Content-Transfer-Encoding: 7bit\r\n";
		$content .= "\n"; // Important, don't delete
		$content .= $msg;

		$this->parts[0] = $content;
        return;
	}

	public function addAttachment($file, $ctype) {
		$filename = basename($file);
		$data     = file_get_contents($file);
		$data     = chunk_split(base64_encode($data));

		$sid = rand(0,10000);
		$content_id = "part$sid.".sprintf("%09d", crc32($filename)).strrchr($this->to_address, "@");
		
		$content  = "Content-Type: $ctype; name=\"".$filename."\"\r\n";
		$content .= "Content-Transfer-Encoding: base64\r\n";
		$content .= "Content-ID: <$content_id>\r\n";
		$content .= "Content-Disposition: attachment; filename=\"".$filename."\"\r\n\r\n";
		$content .= $data."\r\n";

		// reserver parts[0] for message
		if(count($this->parts) == 0)
		{
			$this->parts[1] = $content;
		}
	    else
	    {
	    	$this->parts[] = $content;
	    }
	    return $content_id;
	}

	public function buildMessage() {
		$this->message = "This is a multipart message in mime format.\r\n";
		$total = count($this->parts);
		for($i=0; $i<$total; $i++) {
			$this->message .= "\n--" . $this->boundary . "\n";
			$this->message .= $this->parts[$i];
		}
		$this->message .= "\n--" . $this->boundary . "--";
        return;
	}

	/* to get the message body as a string */
	public function getMessage() {
		$this->buildMessage();
		return $this->message;
	}

	public function sendmail() {
		$this->buildMessage();
		mail($this->to_address,$this->subject,$this->message,$this->header);
        return;
	}
}
