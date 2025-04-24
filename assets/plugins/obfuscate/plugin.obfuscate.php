<?php
/**
 * obfuscate
 *
 * obfuscate plugin for Evolution CMS
 *
 * @category    plugin 
 * @version     2.1.1
 * @license     MIT
 * @internal    @properties 
 * @internal    @events OnWebPagePrerender 
 * @internal    @modx_category Content 
 * @internal    @legacy_names obfuscate
 * @internal    @installset base
 * @internal    @disabled 0
 * @author      ProjectSoft <projectsoft2009@yandex.ru>
*/
use ProjectSoft\Util\Obfuscate;

if(!defined('MODX_BASE_PATH')) die('What are you doing? Get out of here!');

require_once __DIR__ . '/Obfuscate.php';

$modx = EvolutionCMS();
$e =& $modx->event;

switch ($e->name) {
	case "OnWebPagePrerender":
		$obfuscate = new Obfuscate();
		$obfuscate->text = $modx->documentOutput;
		$modx->documentOutput = $obfuscate->render();
		break;
}
