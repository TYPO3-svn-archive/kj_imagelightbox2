<?php
if (!defined ("TYPO3_MODE")) 	die ("Access denied.");
$TYPO3_CONF_VARS[TYPO3_MODE]['XCLASS']['tslib/class.tslib_content.php']=t3lib_extMgm::extPath($_EXTKEY).'class.ux_tslib_content.php';
?>