<?php
/***************************************************************
*  Copyright notice
*
*  (c) 2005 Julian Kleinhans (typo3@kj187.de)
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
 *
 * @author	Julian Kleinhans <typo3@kj187.de>
 */

$value = $_GET['image'];

if (isset($value)){
	$value = addslashes(htmlspecialchars(trim($value)));
	if(is_file($_SERVER['DOCUMENT_ROOT'].'/'.$value)){		
		$content = '
			<html>
			<head>
				<title>Print Image</title>
			</head>
			
			<body onload="javascript:self.print()">
			
			<img src="http://'.$_SERVER['HTTP_HOST'].'/'.$value.'" />
			
			</body>
			</html>
		';		
		echo $content;				
	}else{
		echo 'Non existing image';
	}
}
?>