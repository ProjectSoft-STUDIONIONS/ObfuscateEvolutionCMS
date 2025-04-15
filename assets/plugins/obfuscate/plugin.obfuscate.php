<?php
/**
 * 
 * ObfuscateModxEvo
 * 
 * ObfuscateModxEvo plugin for MODX Evo
 *
 * @category    plugin 
 * @version     1.0
 * @license     MIT
 * @internal    @properties 
 * @internal    @events OnWebPagePrerender 
 * @internal    @modx_category Content 
 * @internal    @legacy_names ObfuscateModxEvo
 * @internal    @installset base
 * @author      ProjectSoft <projectsoft2009@yandex.ru>
*/

if(!defined('MODX_BASE_PATH')) die('What are you doing? Get out of here!');

$modx = EvolutionCMS();
$e =& $modx->event;

if(!function_exists("ordutf8")):
	function ordutf8($string, &$offset_obfus) {
		$code = ord(substr($string, $offset_obfus,1)); 
		if ($code >= 128):                     //otherwise 0xxxxxxx
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
endif;

if(!function_exists("obfuscate_replacer")):
	function obfuscate_replacer(&$matches){
		$str = trim($matches[2]);
		$str = preg_replace('|&nbsp;|', ' ',$str);
		$arr = explode("<br />", $str);
		$out = array();
		$offset_obfus = 0;
		foreach($arr as $key=>$value):
			$offset_obfus = 0;
			$obfus = "";
			while ($offset_obfus >= 0):
				$obfus .= "&#". ordutf8($value, $offset_obfus) . ";";
			endwhile;
			$out[] = $obfus;
		endforeach;
		$html = implode("<br />", $out);
		return $html;
	}
endif;

switch ($e->name) {
	case "OnWebPagePrerender":{
		$outputPrepare = $modx->documentOutput;
		$regex = "#(\{obfuscate\}(.+)\{\/obfuscate})#Usi";
		$outputPrepare = preg_replace_callback($regex, 'obfuscate_replacer', $outputPrepare);
		$modx->documentOutput = $outputPrepare;
		break;
	}
}
