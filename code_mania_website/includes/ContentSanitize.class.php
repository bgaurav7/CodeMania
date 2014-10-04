<?php

class Sanitize
{
	private $s = '';
	public function __construct($str = "")
	{
		$this -> s = $str;
	}
	
	public function cleanString($str)
	{
		$this -> s = addslashes(htmlspecialchars(trim($str), ENT_QUOTES));
		$this -> s  = str_replace("<script>", "</script/>", $this -> s);
		$this -> s = str_replace("'", "", $this -> s);
		$this -> s = str_replace('"', '', $this -> s);
		return $this -> s;
	}
	
	/*public function cleanHtml($str)
	{
		$this -> s  = str_replace("<script>", "</script/>", $str);
		return $this -> s;
	}
	
	public function cleanJs($str)
	{
		$this -> s = $str;
		$this -> s = str_replace("'", "", $str);
		$this -> s = str_replace('"', '', $str);
		return $this -> s;
	}*/
	
	public function cleanFileName($str)
	{
		$st = $str;
		$newstr = "";
		for($i = 0; $i < strlen($st); $i++)
		{
			if( ($st[$i] >='A' && $st[$i] <='Z') || ($st[$i] >='a' && $st[$i] <='z') || ($st[$i] >='0' && $st[$i] <='9') || $st[$i] == '.' || $st[$i] == '_')
				$newstr = $newstr . $st[$i];
		}
		return $newstr;
	}

	public function parseTextareaToShow($str)
	{
		$this -> s = str_replace("<textarea>", "<textarea1>", $str);
		$this -> s = str_replace("</textarea>", "</textarea1>", $this -> s);
		return $this -> s;
	}

	public function parseTextareaToSave($str)
	{
		$this -> s = str_replace("<textarea1>", "<textarea>", $str);
		$this -> s = str_replace("</textarea1>", "</textarea>", $this -> s);
		return $this -> s;
	}
};

?>
