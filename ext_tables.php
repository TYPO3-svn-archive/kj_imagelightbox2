<?php
if (!defined ('TYPO3_MODE')) 	die ('Access denied.');
$tempColumns = Array (
	"tx_kjimagelightbox2_activate" => Array (		
		"exclude" => 1,		
		"label" => "LLL:EXT:kj_imagelightbox2/locallang_db.xml:tt_content.tx_kjimagelightbox2_activate",		
		"config" => Array (
			"type" => "check",
		)
	),
	"tx_kjimagelightbox2_imageset" => Array (		
		"exclude" => 1,		
		"label" => "LLL:EXT:kj_imagelightbox2/locallang_db.xml:tt_content.tx_kjimagelightbox2_imageset",		
		"config" => Array (
			"type" => "check",
		)
	),
	"tx_kjimagelightbox2_presentation" => Array (		
		"exclude" => 1,		
		"label" => "LLL:EXT:kj_imagelightbox2/locallang_db.xml:tt_content.tx_kjimagelightbox2_presentation",		
		"config" => Array (
			"type" => "check",
		)
	),
	"tx_kjimagelightbox2_hovereffect" => Array (
		"exclude" => 1,
		"label" => "LLL:EXT:kj_imagelightbox2/locallang_db.xml:tt_content.tx_kjimagelightbox2_hovereffect",
		"config" => Array (
			"type" => "check",
		)
	),
);


t3lib_div::loadTCA("tt_content");
t3lib_extMgm::addTCAcolumns("tt_content",$tempColumns,1);
$GLOBALS['TCA']['tt_content']['palettes']['7']['showitem'] .= ',tx_kjimagelightbox2_activate, tx_kjimagelightbox2_imageset, tx_kjimagelightbox2_presentation, tx_kjimagelightbox2_hovereffect';

t3lib_extMgm::addStaticFile($_EXTKEY,'static/KJ__Imagelightbox/', 'KJ: Imagelightbox 2');
?>
