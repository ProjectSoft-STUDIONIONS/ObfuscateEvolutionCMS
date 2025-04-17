<?php namespace ProjectSoft\Util;

class Obfuscate {

	public function __constructor() {}

	private function ordutf8($string, &$offset_obfus) {
		$code = ord(substr($string, $offset_obfus,1)); 
		if ($code >= 128):                     //0xxxxxxx
			if ($code < 224):
				$bytesnumber = 2;              //110xxxxx
			else:
				if ($code < 240):
					$bytesnumber = 3;          //1110xxxx
				else:
					if ($code < 248):
						$bytesnumber = 4;      //11110xxx
					endif;
				endif;
			endif;
			$codetemp = $code - 192 - ($bytesnumber > 2 ? 32 : 0) - ($bytesnumber > 3 ? 16 : 0);
			for ($i = 2; $i <= $bytesnumber; $i++):
				$offset_obfus ++;
				$code2 = ord(substr($string, $offset_obfus, 1)) - 128;        //10xxxxxx
				$codetemp = $codetemp*64 + $code2;
			endfor;
			$code = $codetemp;
		endif;
		$offset_obfus += 1;
		if ($offset_obfus >= strlen($string)) $offset_obfus = -1;
		return $code;
	}

	private function obfuscate_replacer(&$matches){
		$str = trim($matches[2]);
		$str = preg_replace('|&nbsp;|', ' ',$str);
		$arr = explode("<br />", $str);
		$out = array();
		$offset_obfus = 0;
		foreach($arr as $key=>$value):
			$offset_obfus = 0;
			$obfus = "";
			while ($offset_obfus >= 0):
				$obfus .= "&#". $this->ordutf8($value, $offset_obfus) . ";";
			endwhile;
			$out[] = $obfus;
		endforeach;
		$html = implode("<br />", $out);
		return $html;
	}

	public function render(string $text = '') {
		$regex = "#(\{obfuscate\}(.+)\{\/obfuscate})#Usi";
		$text = preg_replace_callback($regex, array($this, 'obfuscate_replacer'), $text);
		return $text;
	}
}