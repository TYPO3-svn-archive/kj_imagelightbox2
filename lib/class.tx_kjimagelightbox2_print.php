<?php
/***************************************************************
*  Copyright notice
*
*  (c) 2008 Julian Kleinhans (typo3@kj187.de)
*  All rights reserved
*
*  This script is part of the TYPO3 project. The TYPO3 project is
*  free software; you can redistribute it and/or modify
*  it under the terms of the GNU General Public License as published by
*  the Free Software Foundation; either version 2 of the License, or
*  (at your option) any later version.
*
*  The GNU General Public License can be found at
*  http://www.gnu.org/copyleft/gpl.html.
*
*  This script is distributed in the hope that it will be useful,
*  but WITHOUT ANY WARRANTY; without even the implied warranty of
*  MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
*  GNU General Public License for more details.
*
*  This copyright notice MUST APPEAR in all copies of the script!
***************************************************************/

/**
 * Imagelightbox v2
 * PrintVersion
 * 
 * @author	Julian Kleinhans <typo3@kj187.de>
 */

define('PATH_thisScript',str_replace('//','/', str_replace('\\','/', (PHP_SAPI=='cgi'||PHP_SAPI=='isapi' ||PHP_SAPI=='cgi-fcgi')&&($_SERVER['ORIG_PATH_TRANSLATED']?$_SERVER['ORIG_PATH_TRANSLATED']:$_SERVER['PATH_TRANSLATED'])? ($_SERVER['ORIG_PATH_TRANSLATED']?$_SERVER['ORIG_PATH_TRANSLATED']:$_SERVER['PATH_TRANSLATED']):($_SERVER['ORIG_SCRIPT_FILENAME']?$_SERVER['ORIG_SCRIPT_FILENAME']:$_SERVER['SCRIPT_FILENAME']))));
define('PATH_site', dirname(dirname(dirname(dirname(dirname(PATH_thisScript))))).'/');
define('PATH_typo3conf', PATH_site . 'typo3conf/');
define('PATH_t3lib', PATH_site . 't3lib/');

require_once(PATH_t3lib . 'class.t3lib_div.php');
require_once(PATH_typo3conf . 'localconf.php');

$image = t3lib_div::_GET('image');

//first check if the requested file has an valid image file extension, not the nicest security feature but at least it prevents from downloading php files like localconf.php.
$allowedExtensions = t3lib_div::trimExplode(',', (strlen($TYPO3_CONF_VARS['GFX']['imagefile_ext']) > 0 ? $TYPO3_CONF_VARS['GFX']['imagefile_ext'] : 'gif,jpg,jpeg,tif,bmp,pcx,tga,png,pdf,ai'), 1);

$imageInfo = pathinfo($image);
if(!in_array(strtolower($imageInfo['extension']), $allowedExtensions)) { die('You are trying to download a file, which you don\'t have access to'); }

echo '
<html>
	<head>
    	<title>Print Image</title>
	</head>
	<body onload="javascript:self.print()">
		<img src="' . $image . '" style="border:none;cursor:pointer;" onclick="self.close()">		
	</body>
</html>';
 
?>