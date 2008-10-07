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
 * @author	Julian Kleinhans <typo3@kj187.de>
 */
 
 
class tx_kjimagelightbox2 {

    /**
     * Content object
     * @var object
     */
    public $cObj;

	/**
	 * This function 
	 *
	 * @param array	$content:
	 * @param array	$conf: 
	 */
    public function main($content,$conf) {
		// if imageLightbox is activate
		if($this->cObj->data['tx_kjimagelightbox2_activate'] == 1) {
			$addParams = 'rel="lightbox"';
		}

		// if imageLightbox is activate and imageset			
		if($this->cObj->data['tx_kjimagelightbox2_activate'] == 1 && $this->cObj->data['tx_kjimagelightbox2_imageset'] == 1) {
			$addParams = 'rel="lightbox[' . $this->cObj->data['uid'] . ']"';
		}
		
		// if imageLightbox is activate and presentationmode with or without imageset		
		if($this->cObj->data['tx_kjimagelightbox2_activate'] == 1 && $this->cObj->data['tx_kjimagelightbox2_presentation'] == 1) {
			$addParams = 'rel="lightbox[presentation' . $this->cObj->data['uid'] . ']"';
		}
		
		// if image_link is set kill the lightbox and call the link
		if($this->cObj->data['image_link'] != '') {
			$addParams = '';
		}

		if($this->cObj->data['tx_kjimagelightbox2_hovereffect']){
			$previewConf = $GLOBALS['TSFE']->tmpl->setup['plugin.']['tx_kjimagelightbox2.'];
			$previewConf['imagePreview.']['file'] = $GLOBALS['TSFE']->lastImageInfo['origFile'];
			$hoverImage = $this->cObj->cObjGetSingle($previewConf['imagePreview'],$previewConf['imagePreview.']);
		}
		
		return '<a href="' . $content['url'] . ' "' . $content['targetParams'] . ' ' . $content["aTagParams"] . $addParams . '>' . ($this->cObj->data['tx_kjimagelightbox2_hovereffect'] ? $hoverImage : '' ) ;
	}
}


if (defined('TYPO3_MODE') && $TYPO3_CONF_VARS[TYPO3_MODE]['XCLASS']['ext/kj_imagelightbox2/lib/class.tx_kjimagelightbox2.php'])	{
	include_once($TYPO3_CONF_VARS[TYPO3_MODE]['XCLASS']['ext/kj_imagelightbox2/lib/class.tx_kjimagelightbox2.php']);
}

?>
