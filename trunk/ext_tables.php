<?php
if (!defined ('TYPO3_MODE')) 	die ('Access denied.');
t3lib_extMgm::addStaticFile($_EXTKEY,'static/ImageLightbox_v2/', 'KJ: Image Lightbox v2');

$tempColumns = Array (
	'tx_kjimagelightbox2_imagelightbox2' => Array (		
		'exclude' => 1,		
		'label' => 'LLL:EXT:kj_imagelightbox2/locallang_db.php:tt_content.tx_kjimagelightbox2_imagelightbox2',		
		'config' => Array (
			'type' => 'check',
		)
	),
	'tx_kjimagelightbox2_imageset' => Array (		
		'exclude' => 1,		
		'label' => 'LLL:EXT:kj_imagelightbox2/locallang_db.php:tt_content.tx_kjimagelightbox2_imageset',		
		'config' => Array (
			'type' => 'check',
		)
	),	
	'tx_kjimagelightbox2_presentationmode' => Array (		
		'exclude' => 1,		
		'label' => 'LLL:EXT:kj_imagelightbox2/locallang_db.php:tt_content.tx_kjimagelightbox2_presentationmode',		
		'config' => Array (
			'type' => 'check',
		)
	),
);


t3lib_div::loadTCA("tt_content");
t3lib_extMgm::addTCAcolumns("tt_content",$tempColumns,1);

$extConf = unserialize($GLOBALS['TYPO3_CONF_VARS']['EXT']['extConf']['kj_imagelightbox2']);
if($extConf['dividers2tabs'] == 1){
	$GLOBALS['TCA']['tt_content']['ctrl']['dividers2tabs'] = true;	
	$GLOBALS['TCA']['tt_content']['types']['textpic']['showitem'] = 'CType;;4;button;1-1-1, header;;3;;2-2-2, bodytext;;9;richtext:rte_transform[flag=rte_enabled|mode=ts_css];3-3-3, rte_enabled, text_properties, --div--;LLL:EXT:kj_imagelightbox2/locallang_db.xml:dividers2tabs.image, image;;;;4-4-4, imageorient;;2, imagewidth;;13,--palette--;LLL:EXT:cms/locallang_ttc.php:ALT.imgLinks;7,--palette--;LLL:EXT:cms/locallang_ttc.php:ALT.imgOptions;11,imagecaption;;5,altText;;;;1-1-1,titleText,longdescURL,--div--;LLL:EXT:kj_imagelightbox2/locallang_db.xml:dividers2tabs.generall';
	$GLOBALS['TCA']['tt_content']['types']['image']['showitem'] = 'CType;;4;button;1-1-1, header;;3;;2-2-2, image;;;;4-4-4, imageorient;;2, imagewidth;;13,--palette--;LLL:EXT:cms/locallang_ttc.php:ALT.imgLinks;7,--palette--;LLL:EXT:cms/locallang_ttc.php:ALT.imgOptions;11, imagecaption;;5, altText;;;;1-1-1,titleText,longdescURL,--div--;LLL:EXT:kj_imagelightbox2/locallang_db.xml:dividers2tabs.generall';
	$GLOBALS['TCA']['tt_content']['types']['list']['showitem'] = 'CType;;4;button;1-1-1, header;;3;;2-2-2, list_type;;;;5-5-5, layout, select_key, pages;;12';
}

$GLOBALS['TCA']['tt_content']['palettes']['7']['showitem'] = 'image_link, image_zoom, tx_kjimagelightbox2_imagelightbox2, tx_kjimagelightbox2_imageset, tx_kjimagelightbox2_presentationmode';
?>