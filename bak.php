
	/**
	 * Returns a <img> tag with the image file defined by $file and processed according to the properties in the TypoScript array.
	 * Mostly this function is a sub-function to the IMAGE function which renders the IMAGE cObject in TypoScript. This function is called by "$this->cImage($conf['file'],$conf);" from IMAGE().
	 *
	 * @param	string		File TypoScript resource
	 * @param	array		TypoScript configuration properties
	 * @return	string		<img> tag, (possibly wrapped in links and other HTML) if any image found.
	 * @access private
	 * @see IMAGE()
	 */
	function cImage($file,$conf) {
		$info = $this->getImgResource($file,$conf['file.']);
		$GLOBALS['TSFE']->lastImageInfo=$info;
		if (is_array($info))	{
			$info[3] = t3lib_div::png_to_gif_by_imagemagick($info[3]);
			$GLOBALS['TSFE']->imagesOnPage[]=$info[3];		// This array is used to collect the image-refs on the page...

			if (!strlen($conf['altText']) && !is_array($conf['altText.']))	{	// Backwards compatible:
				$conf['altText'] = $conf['alttext'];
				$conf['altText.'] = $conf['alttext.'];
			}
			$altParam = $this->getAltParam($conf);















			/**
			 * kj_imagelightbox2 begin
			 * 
			 * Wenn imageLightbox2 im TS existiert oder die Lightbox Checkbox im BE aktiviert ist und Zoom und Link nicht aktiviert ist
			 */

			if($conf['imageLightbox2'] OR ($this->data['tx_kjimagelightbox2_imagelightbox2']==1 AND $this->data['image_zoom'] == 0 AND $this->data['image_link'] == '')){
				$extConf = unserialize($GLOBALS['TYPO3_CONF_VARS']['EXT']['extConf']['kj_imagelightbox2']);

				// dynamic CSS
				$bgColor = ($extConf['bgColor'] != '') ? $extConf['bgColor'] : 'black';
				$styleCloseButton = ($extConf['styleCloseButton'] != '') ? $extConf['styleCloseButton'] : '';
				$cscCaption = ($extConf['cscCaption'] != '') ? $extConf['cscCaption'] : '';

				$GLOBALS['TSFE']->additionalHeaderData['kj_imagelightbox2_dynCss'] = '
					<style type="text/css">
						/*<![CDATA[*/
							#overlay {
								background-color: '.$bgColor.';
							}
							#imageData #bottomNavClose { 
								'.$styleCloseButton.'								
							}
							.csc-textpic-caption {
								'.$cscCaption.'
							}

							
              
										
						/*]]>*/
					</style>
				';


				// dynamic JavaScript
				$resizeSpeed = ($extConf['resizeSpeed'] != '')?$extConf['resizeSpeed']:7;
				$fileLoadingImage = ($extConf['fileLoadingImage'] != '')?$extConf['fileLoadingImage']:'typo3conf/ext/kj_imagelightbox2/lightbox/images/loading.gif';
				$fileBottomNavCloseImage = ($extConf['fileBottomNavCloseImage'] != '')?$extConf['fileBottomNavCloseImage']:'typo3conf/ext/kj_imagelightbox2/lightbox/images/closelabel.gif';
				$numberDisplayLabel = ($extConf['numberDisplayLabel'] != '|')?$extConf['numberDisplayLabel']:'Image | of';
				$numberDisplayLabel = explode('|',$numberDisplayLabel);

				$GLOBALS['TSFE']->additionalHeaderData['kj_imagelightbox2_dynJs'] = '
					<script type="text/javascript">
						/*<![CDATA[*/
							var resizeSpeed = '.$resizeSpeed.';	// controls the speed of the image resizing (1=slowest and 10=fastest)	
							var fileLoadingImage = "'.$fileLoadingImage.'";		
							var fileBottomNavCloseImage = "'.$fileBottomNavCloseImage.'";
							var numberDisplayLabelFirst = "'.$numberDisplayLabel[0].'";
							var numberDisplayLabelLast = "'.$numberDisplayLabel[1].'";
						/*]]>*/
					</script>
				';				

				// add stylesheet and javascript to header
				$GLOBALS['TSFE']->additionalHeaderData['kj_imagelightbox2_css'] = '<link rel="stylesheet" href="'.t3lib_extMgm::siteRelPath('kj_imagelightbox2').'lightbox/css/lightbox.css" type="text/css" media="screen" />';
				$GLOBALS['TSFE']->additionalHeaderData['kj_imagelightbox2_js_prototype'] = '<script type="text/javascript" src="'.t3lib_extMgm::siteRelPath('kj_imagelightbox2').'lightbox/js/prototype.js"></script>';
				$GLOBALS['TSFE']->additionalHeaderData['kj_imagelightbox2_js_scriptaculous'] = '<script type="text/javascript" src="'.t3lib_extMgm::siteRelPath('kj_imagelightbox2').'lightbox/js/scriptaculous.js?load=effects"></script>';
				$GLOBALS['TSFE']->additionalHeaderData['kj_imagelightbox2_js_lightbox'] = '<script type="text/javascript" src="'.t3lib_extMgm::siteRelPath('kj_imagelightbox2').'lightbox/js/lightbox.js"></script>';

				// maxWidth and maxHeight for the clicked image
				$imageLightbox2Conf['maxW'] = ($extConf['imageLightbox2maxW']==700) ? $conf['file.']['imageLightbox2maxW']:$extConf['imageLightbox2maxW'];
				$imageLightbox2Conf['maxH'] = ($extConf['imageLightbox2maxH']==700) ? $conf['file.']['imageLightbox2maxH']:$extConf['imageLightbox2maxH'];
				$newinfo = $this->getImgResource($info['origFile'],$imageLightbox2Conf);

				// set caption from ts or data
				$tempCap = $this->stdWrap($conf['imageLightbox2.']['caption'],$conf['imageLightbox2.']['caption']);
				$cap = explode(chr(10),($tempCap=='' ? $this->data['imagecaption']:$tempCap));
		
			

				// set name for imageset
				$imgSetNumber = (intval($conf['imageLightbox2.']['imageset'])>0)?intval($conf['imageLightbox2.']['imageset']):$this->data['uid'];
				$imgSet = ($this->data['tx_kjimagelightbox2_imageset']==1 OR intval($conf['imageLightbox2.']['imageset'])>0)?'['.$imgSetNumber.']':'';




				if($this->data['tx_kjimagelightbox2_presentationmode']!=1){
					//save image icon
					if($extConf['saveImageShow']==1){
						if($conf['file.']['saveImageShow'] == '' OR intval($conf['file.']['saveImageShow'])>0){
							if($conf['imageLightbox2.']['saveImageShow'] == '' OR intval($conf['imageLightbox2.']['saveImageShow'])>0){
								$saveImageIcon = ($extConf['saveImageIcon'] != '')?$extConf['saveImageIcon']:'typo3conf/ext/kj_imagelightbox2/lightbox/images/save.gif';
								$imageToolbar .= '&lt;a href=&quot;'.htmlspecialchars($GLOBALS['TSFE']->absRefPrefix.t3lib_div::rawUrlEncodeFP($newinfo[3])).'&quot; target=&quot;_blank&quot;&gt;&lt;img src=&quot;'.$saveImageIcon.'&quot; border=&quot;0&quot; title=&quot;'.$extConf['saveImageIconTitle'].'&quot;&quot;&lt;/a&gt; &nbsp; ';
							}
						}
					}else{
						$imageToolbar .= '';
					}

					//print image icon
					if($extConf['printImageShow']==1){
						if($conf['file.']['printImageShow'] == '' OR intval($conf['file.']['printImageShow'])>0){
							if($conf['imageLightbox2.']['printImageShow'] == '' OR intval($conf['imageLightbox2.']['printImageShow'])>0){
								$printImageIcon = ($extConf['printImageIcon'] != '')?$extConf['printImageIcon']:'typo3conf/ext/kj_imagelightbox2/lightbox/images/print.gif';
								$imageToolbar .= '&nbsp;&lt;a href=&quot;'.t3lib_extMgm::siteRelPath('kj_imagelightbox2').'res/print.php?image='.htmlspecialchars($GLOBALS['TSFE']->absRefPrefix.t3lib_div::rawUrlEncodeFP($newinfo[3])).'&quot; target=&quot;_blank&quot; &gt;&lt;img src=&quot;'.$printImageIcon.'&quot; border=&quot;0&quot; title=&quot;'.$extConf['printImageIconTitle'].'&quot;&quot;&lt;/a&gt; &nbsp;';
							}
						}
					}else{
						$imageToolbar .= '';
					}
					$hideNumberDisplay = 0;
				}else{
					// Presentationmode						
					$hideNumberDisplay = 1;	
					$imageArrTmp = explode(',',$this->data['image']);									
					$imageToolbar .= sizeof($imageArrTmp);
				  #$imageToolbar .= '&lt;span class=&quot;presentationmode presentationmodeSpan&quot; &gt;&lt;a href=&quot;#&quot; class=&quot;presentationmode&quot; id=&quot;'.$i.'&quot;  onClick=&quot;myLightbox.changeImage('.($i-1).'); return false;&quot;&gt;'.$i.'&lt;/a&gt;&lt;/span&gt;';
					unset($imageArrTmp);					
				}
				// get break
				#$imageToolbar = ($imageToolbar=='')?'':chr(10).$imageToolbar;

				// create the imagelink
				$theValue = '<a href="'.htmlspecialchars($GLOBALS['TSFE']->absRefPrefix.t3lib_div::rawUrlEncodeFP($newinfo[3])).'" rel="lightbox'.$imgSet.'" title="'.$cap[$this->singleCaption].'" id="'.$this->singleCaption.'" showNumberDisplay="'.$hideNumberDisplay.'" kjtag="'.$imageToolbar.''.($this->data['tx_kjimagelightbox2_presentationmode'] ? '' : $cap[$this->singleCaption]).'"><img src="'.htmlspecialchars($GLOBALS['TSFE']->absRefPrefix.t3lib_div::rawUrlEncodeFP($info[3])).'" width="'.$info[0].'" height="'.$info[1].'"'.$this->getBorderAttr(' border="'.intval($conf['border']).'"').($conf['params']?' '.$conf['params']:'').($altParam).' /></a>';				

				$this->singleCaption++;

				/**
				 * kj_imagelightbox2 end
				 */
				 
				 
				 
				 
				 
				 
				 
				 
				 
				 
				 
				 
				 
				 
				 
				 
				 
				 
				 
				 
			} else {
				$theValue = '<img src="'.htmlspecialchars($GLOBALS['TSFE']->absRefPrefix.t3lib_div::rawUrlEncodeFP($info[3])).'" width="'.$info[0].'" height="'.$info[1].'"'.$this->getBorderAttr(' border="'.intval($conf['border']).'"').($conf['params']?' '.$conf['params']:'').($altParam).' />';
			}

			if ($conf['linkWrap'])	{
				$theValue = $this->linkWrap($theValue,$conf['linkWrap']);
			} elseif ($conf['imageLinkWrap']) {
				$theValue = $this->imageLinkWrap($theValue,$info['origFile'],$conf['imageLinkWrap.']);
			}
			return $this->wrap($theValue,$conf['wrap']);
		}
	}























	/**
	 * Rendering the cObject, IMGTEXT
	 *
	 * @param	array		Array of TypoScript properties
	 * @return	string		Output
	 * @link http://typo3.org/doc.0.html?&tx_extrepmgm_pi1[extUid]=270&tx_extrepmgm_pi1[tocEl]=363&cHash=cf2969bce1
	 */
	function IMGTEXT($conf) {
		
    
    
    
    
    
    
    
    
    /**
		 * kj_imagelightbox2 begin
		 */
		$this->singleCaption = 0;
		/**
		 * kj_imagelightbox2 end
		 */











		$content='';
		if (is_array($conf['text.']))	{
			$content.= $this->stdWrap($this->cObjGet($conf['text.'],'text.'),$conf['text.']);	// this gets the surrounding content
		}
		$imgList=trim($this->stdWrap($conf['imgList'],$conf['imgList.']));	// gets images
		if ($imgList)	{
			$imgs = t3lib_div::trimExplode(',',$imgList);
			$imgStart = intval($this->stdWrap($conf['imgStart'],$conf['imgStart.']));

			$imgCount= count($imgs)-$imgStart;

			$imgMax = intval($this->stdWrap($conf['imgMax'],$conf['imgMax.']));
			if ($imgMax)	{
				$imgCount = t3lib_div::intInRange($imgCount,0,$conf['imgMax']);	// reduces the number of images.
			}

			$imgPath = $this->stdWrap($conf['imgPath'],$conf['imgPath.']);

			// initialisation
			$caption='';
			if (is_array($conf['caption.']))	{
				$caption= $this->stdWrap($this->cObjGet($conf['caption.'], 'caption.'),$conf['caption.']);
				$captionArray=array();
				
				
				
				
				
				
				
				
				
				
				
				
				
				
				
				/**
				 * kj_imagelightbox2
				 * AND $this->data['tx_kjimagelightbox2_imagelightbox2']!=1   hinzugefügt  
				 *				 
				 */                  				
				
				if ($conf['captionSplit'] AND $this->data['tx_kjimagelightbox2_imagelightbox2']!=1)	{				
					$capSplit = $this->stdWrap($conf['captionSplit.']['token'],$conf['captionSplit.']['token.']);
					if (!$capSplit) {$capSplit=chr(10);}
					$caption2= $this->cObjGetSingle($conf['captionSplit.']['cObject'],$conf['captionSplit.']['cObject.'],'captionSplit.cObject');
					$captionArray=explode($capSplit,$caption2);
					while(list($ca_key,$ca_val)=each($captionArray))	{
						$captionArray[$ca_key] = $this->stdWrap(trim($captionArray[$ca_key]), $conf['captionSplit.']['stdWrap.']);
					}
				}

				$tablecode='';
				$position=$this->stdWrap($conf['textPos'],$conf['textPos.']);

				$tmppos = $position&7;
				$contentPosition = $position&24;
				$align = $this->align[$tmppos];
				$cap = ($caption)?1:0;
				$txtMarg = intval($this->stdWrap($conf['textMargin'],$conf['textMargin.']));
				if (!$conf['textMargin_outOfText'] && $contentPosition<16)	{
					$txtMarg=0;
				}

				$cols = intval($this->stdWrap($conf['cols'],$conf['cols.']));
				$rows = intval($this->stdWrap($conf['rows'],$conf['rows.']));
				$colspacing = intval($this->stdWrap($conf['colSpace'],$conf['colSpace.']));
				$rowspacing = intval($this->stdWrap($conf['rowSpace'],$conf['rowSpace.']));

				$border = intval($this->stdWrap($conf['border'],$conf['border.'])) ? 1:0;
				$borderColor = $this->stdWrap($conf['borderCol'],$conf['borderCol.']);
				$borderThickness = intval($this->stdWrap($conf['borderThick'],$conf['borderThick.']));

				$borderColor=$borderColor?$borderColor:'black';
				$borderThickness=$borderThickness?$borderThickness:1;

				$caption_align = $this->stdWrap($conf['captionAlign'],$conf['captionAlign.']);
				if (!$caption_align) {
					$caption_align = $align;
				}
				// generate cols
				$colCount = ($cols > 1) ? $cols : 1;
				if ($colCount > $imgCount)	{$colCount = $imgCount;}
				$rowCount = ($colCount > 1) ? ceil($imgCount / $colCount) : $imgCount;
				// generate rows
				if ($rows>1)  {
					$rowCount = $rows;
					if ($rowCount > $imgCount)	{$rowCount = $imgCount;}
					$colCount = ($rowCount>1) ? ceil($imgCount / $rowCount) : $imgCount;
				}

				// max Width
				$colRelations = trim($this->stdWrap($conf['colRelations'],$conf['colRelations.']));
				$maxW = intval($this->stdWrap($conf['maxW'],$conf['maxW.']));

				$maxWInText = intval($this->stdWrap($conf['maxWInText'],$conf['maxWInText.']));
				if (!$maxWInText)	{	// If maxWInText is not set, it's calculated to the 70 % of the max...
					$maxWInText = round($maxW/100*50);
				}

				if ($maxWInText && $contentPosition>=16)	{	// inText
					$maxW = $maxWInText;
				}

				if ($maxW && $colCount > 0) {	// If there is a max width and if colCount is greater than  column
					/*				debug($border*$borderThickness*2);
					debug($maxW);
					debug($colspacing);
					debug(($maxW-$colspacing*($colCount-1)-$colCount*$border*$borderThickness*2));
					*/
					$maxW = ceil(($maxW-$colspacing*($colCount-1)-$colCount*$border*$borderThickness*2)/$colCount);
				}
				// create the relation between rows
				$colMaxW = Array();
				if ($colRelations)	{
					$rel_parts = explode(':',$colRelations);
					$rel_total = 0;
					for ($a=0;$a<$colCount;$a++)	{
						$rel_parts[$a] = intval($rel_parts[$a]);
						$rel_total+= $rel_parts[$a];
					}
					if ($rel_total)	{
						for ($a=0;$a<$colCount;$a++)	{
							$colMaxW[$a] = round(($maxW*$colCount)/$rel_total*$rel_parts[$a]);
						}
						if (min($colMaxW)<=0 || max($rel_parts)/min($rel_parts)>10)	{		// The difference in size between the largest and smalles must be within a factor of ten.
							$colMaxW = Array();
						}
					}
				}
				$image_compression = intval($this->stdWrap($conf['image_compression'],$conf['image_compression.']));
				$image_effects = intval($this->stdWrap($conf['image_effects'],$conf['image_effects.']));
				$image_frames = intval($this->stdWrap($conf['image_frames.']['key'],$conf['image_frames.']['key.']));

				// fetches pictures
				$splitArr=array();
				$splitArr['imgObjNum']=$conf['imgObjNum'];
				$splitArr = $GLOBALS['TSFE']->tmpl->splitConfArray($splitArr,$imgCount);

				// EqualHeight
				$equalHeight = intval($this->stdWrap($conf['equalH'],$conf['equalH.']));
				if ($equalHeight)	{	// Initiate gifbuilder object in order to get dimensions AND calculate the imageWidth's
					$gifCreator = t3lib_div::makeInstance('tslib_gifbuilder');
					$gifCreator->init();
					$relations = Array();
					$relations_cols = Array();
					$totalMaxW = $maxW*$colCount;
					for($a=0;$a<$imgCount;$a++)	{
						$imgKey = $a+$imgStart;
						$imgInfo = $gifCreator->getImageDimensions($imgPath.$imgs[$imgKey]);
						$relations[$a] = $imgInfo[1] / $equalHeight;	// relationship between the original height and the wished height
						if ($relations[$a])	{	// if relations is zero, then the addition of this value is omitted as the image is not expected to display because of some error.
							$relations_cols[floor($a/$colCount)] += $imgInfo[0]/$relations[$a];	// counts the total width of the row with the new height taken into consideration.
						}
					}
				}

				$imageRowsFinalWidths = Array();	// contains the width of every image row
				$imageRowsMaxHeights = Array();
				$imgsTag=array();
				$origImages=array();
				for($a=0;$a<$imgCount;$a++)	{
					$GLOBALS['TSFE']->register['IMAGE_NUM'] = $a;

					$imgKey = $a+$imgStart;
					$totalImagePath = $imgPath.$imgs[$imgKey];
					$this->data[$this->currentValKey] = $totalImagePath;
					$imgObjNum = intval($splitArr[$a]['imgObjNum']);
					$imgConf = $conf[$imgObjNum.'.'];

					if ($equalHeight)	{
						$scale = 1;
						if ($totalMaxW)	{
							$rowTotalMaxW = $relations_cols[floor($a/$colCount)];
							if ($rowTotalMaxW > $totalMaxW)	{
								$scale = $rowTotalMaxW / $totalMaxW;
							}
						}
						// transfer info to the imageObject. Please note, that
						$imgConf['file.']['height'] = round($equalHeight/$scale);

						unset($imgConf['file.']['width']);
						unset($imgConf['file.']['maxW']);
						unset($imgConf['file.']['maxH']);
						unset($imgConf['file.']['minW']);
						unset($imgConf['file.']['minH']);
						unset($imgConf['file.']['width.']);
						unset($imgConf['file.']['maxW.']);
						unset($imgConf['file.']['maxH.']);
						unset($imgConf['file.']['minW.']);
						unset($imgConf['file.']['minH.']);
						$maxW = 0;	// setting this to zero, so that it doesn't disturb
					}

					if ($maxW) {
						if (count($colMaxW))	{
							$imgConf['file.']['maxW'] = $colMaxW[($a%$colCount)];
						} else {
							$imgConf['file.']['maxW'] = $maxW;
						}
					}

					// Image Object supplied:
					if (is_array($imgConf)) {
						if ($this->image_effects[$image_effects])	{
							$imgConf['file.']['params'].= ' '.$this->image_effects[$image_effects];
						}
						if ($image_frames)	{
							if (is_array($conf['image_frames.'][$image_frames.'.']))	{
								$imgConf['file.']['m.'] = $conf['image_frames.'][$image_frames.'.'];
							}
						}
						if ($image_compression && $imgConf['file']!='GIFBUILDER')	{
							if ($image_compression==1)	{
								$tempImport = $imgConf['file.']['import'];
								$tempImport_dot = $imgConf['file.']['import.'];
								unset($imgConf['file.']);
								$imgConf['file.']['import'] = $tempImport;
								$imgConf['file.']['import.'] = $tempImport_dot;
							} elseif (isset($this->image_compression[$image_compression])) {
								$imgConf['file.']['params'].= ' '.$this->image_compression[$image_compression]['params'];
								$imgConf['file.']['ext'] = $this->image_compression[$image_compression]['ext'];
								unset($imgConf['file.']['ext.']);
							}
						}

						// "alt", "title" and "longdesc" attributes:
						if (!strlen($imgConf['altText']) && !is_array($imgConf['altText.'])) {
							$imgConf['altText'] = $conf['altText'];
							$imgConf['altText.'] = $conf['altText.'];
						}
						if (!strlen($imgConf['titleText']) && !is_array($imgConf['titleText.'])) {
							$imgConf['titleText'] = $conf['titleText'];
							$imgConf['titleText.'] = $conf['titleText.'];
						}
						if (!strlen($imgConf['longdescURL']) && !is_array($imgConf['longdescURL.'])) {
							$imgConf['longdescURL'] = $conf['longdescURL'];
							$imgConf['longdescURL.'] = $conf['longdescURL.'];
						}
					} else {
						$imgConf = array(
						'altText' => $conf['altText'],
						'titleText' => $conf['titleText'],
						'longdescURL' => $conf['longdescURL'],
						'file' => $totalImagePath
						);
					}

					$imgsTag[$imgKey] = $this->IMAGE($imgConf);

					// Store the original filepath
					$origImages[$imgKey]=$GLOBALS['TSFE']->lastImageInfo;

					$imageRowsFinalWidths[floor($a/$colCount)] += $GLOBALS['TSFE']->lastImageInfo[0];
					if ($GLOBALS['TSFE']->lastImageInfo[1]>$imageRowsMaxHeights[floor($a/$colCount)])	{
						$imageRowsMaxHeights[floor($a/$colCount)] = $GLOBALS['TSFE']->lastImageInfo[1];
					}
				}
				// calculating the tableWidth:
				// TableWidth problems: It creates problems if the pictures are NOT as wide as the tableWidth.
				$tableWidth = max($imageRowsFinalWidths)+ $colspacing*($colCount-1) + $colCount*$border*$borderThickness*2;

				// make table for pictures
				$index=$imgStart;

				$noRows = $this->stdWrap($conf['noRows'],$conf['noRows.']);
				$noCols = $this->stdWrap($conf['noCols'],$conf['noCols.']);
				if ($noRows) {$noCols=0;}	// noRows overrides noCols. They cannot exist at the same time.
				if ($equalHeight) {
					$noCols=1;
					$noRows=0;
				}

				$rowCount_temp=1;
				$colCount_temp=$colCount;
				if ($noRows)	{
					$rowCount_temp = $rowCount;
					$rowCount=1;
				}
				if ($noCols)	{
					$colCount=1;
				}
				// col- and rowspans calculated
				$colspan = (($colspacing) ? $colCount*2-1 : $colCount);
				$rowspan = (($rowspacing) ? $rowCount*2-1 : $rowCount) + $cap;


				// Edit icons:
				$editIconsHTML = $conf['editIcons']&&$GLOBALS['TSFE']->beUserLogin ? $this->editIcons('',$conf['editIcons'],$conf['editIcons.']) : '';

				// strech out table:
				$tablecode='';
				$flag=0;
				if ($conf['noStretchAndMarginCells']!=1)	{
					$tablecode.='<tr>';
					if ($txtMarg && $align=='right')	{	// If right aligned, the textborder is added on the right side
						$tablecode.='<td rowspan="'.($rowspan+1).'" valign="top"><img src="'.$GLOBALS['TSFE']->absRefPrefix.'clear.gif" width="'.$txtMarg.'" height="1" alt="" title="" />'.($editIconsHTML?'<br />'.$editIconsHTML:'').'</td>';
						$editIconsHTML='';
						$flag=1;
					}
					$tablecode.='<td colspan="'.$colspan.'"><img src="'.$GLOBALS['TSFE']->absRefPrefix.'clear.gif" width="'.$tableWidth.'" height="1" alt="" /></td>';
					if ($txtMarg && $align=='left')	{	// If left aligned, the textborder is added on the left side
						$tablecode.='<td rowspan="'.($rowspan+1).'" valign="top"><img src="'.$GLOBALS['TSFE']->absRefPrefix.'clear.gif" width="'.$txtMarg.'" height="1" alt="" title="" />'.($editIconsHTML?'<br />'.$editIconsHTML:'').'</td>';
						$editIconsHTML='';
						$flag=1;
					}
					if ($flag) $tableWidth+=$txtMarg+1;
					//			$tableWidth=0;
					$tablecode.='</tr>';
				}

				// draw table
				for ($c=0;$c<$rowCount;$c++) {	// Looping through rows. If 'noRows' is set, this is '1 time', but $rowCount_temp will hold the actual number of rows!
					if ($c && $rowspacing)	{		// If this is NOT the first time in the loop AND if space is required, a row-spacer is added. In case of "noRows" rowspacing is done further down.
						$tablecode.='<tr><td colspan="'.$colspan.'"><img src="'.$GLOBALS['TSFE']->absRefPrefix.'clear.gif" width="1" height="'.$rowspacing.'"'.$this->getBorderAttr(' border="0"').' alt="" title="" /></td></tr>';
					}
					$tablecode.='<tr>';	// starting row
					for ($b=0; $b<$colCount_temp; $b++)	{	// Looping through the columns
						if ($b && $colspacing)	{		// If this is NOT the first iteration AND if column space is required. In case of "noCols", the space is done without a separate cell.
							if (!$noCols)	{
								$tablecode.='<td><img src="'.$GLOBALS['TSFE']->absRefPrefix.'clear.gif" width="'.$colspacing.'" height="1"'.$this->getBorderAttr(' border="0"').' alt="" title="" /></td>';
							} else {
								$colSpacer='<img src="'.$GLOBALS['TSFE']->absRefPrefix.'clear.gif" width="'.($border?$colspacing-6:$colspacing).'" height="'.($imageRowsMaxHeights[$c]+($border?$borderThickness*2:0)).'"'.$this->getBorderAttr(' border="0"').' align="'.($border?'left':'top').'" alt="" title="" />';
								$colSpacer='<td valign="top">'.$colSpacer.'</td>';	// added 160301, needed for the new "noCols"-table...
								$tablecode.=$colSpacer;
							}
						}
						if (!$noCols || ($noCols && !$b))	{
							$tablecode.='<td valign="top">';	// starting the cell. If "noCols" this cell will hold all images in the row, otherwise only a single image.
							if ($noCols)	{$tablecode.='<table width="'.$imageRowsFinalWidths[$c].'" border="0" cellpadding="0" cellspacing="0"><tr>';}		// In case of "noCols" we must set the table-tag that surrounds the images in the row.
						}
						for ($a=0;$a<$rowCount_temp;$a++)	{	// Looping through the rows IF "noRows" is set. "noRows"  means that the rows of images is not rendered by physical table rows but images are all in one column and spaced apart with clear-gifs. This loop is only one time if "noRows" is not set.
							$imgIndex = $index+$a*$colCount_temp;
							if ($imgsTag[$imgIndex])	{
								if ($rowspacing && $noRows && $a) {		// Puts distance between the images IF "noRows" is set and this is the first iteration of the loop
									$tablecode.= '<img src="'.$GLOBALS['TSFE']->absRefPrefix.'clear.gif" width="1" height="'.$rowspacing.'" alt="" title="" /><br />';
								}

								$imageHTML = $imgsTag[$imgIndex].'<br />';
								$Talign = (!trim($captionArray[$imgIndex]) && !$noRows && !$conf['netprintApplicationLink']) ? ' align="left"' : '';  // this is necessary if the tablerows are supposed to space properly together! "noRows" is excluded because else the images "layer" together.
								if ($border)	{$imageHTML='<table border="0" cellpadding="'.$borderThickness.'" cellspacing="0" bgcolor="'.$borderColor.'"'.$Talign.'><tr><td>'.$imageHTML.'</td></tr></table>';}		// break-tag added 160301  , ($noRows?'':' align="left"')  removed 160301, break tag removed 160301 (later...)
								$imageHTML.=$editIconsHTML;		$editIconsHTML='';
								if ($conf['netprintApplicationLink'])	{$imageHTML = $this->netprintApplication_offsiteLinkWrap($imageHTML,$origImages[$imgIndex],$conf['netprintApplicationLink.']);}
















								/**
								 * kj_imagelightbox2 begin
								 */
								 
								#echo t3lib_div::debug($this->data); 
								if($conf['imageLightbox2'] OR ($this->data['tx_kjimagelightbox2_imagelightbox2']==1 AND $this->data['image_zoom'] == 0 AND $this->data['image_link'] == '')){																
                  $imageHTML.=$captionArray[$imgIndex];	// Adds caption.
								}else{
                  $imageHTML.=$captionArray[$imgIndex];	// Adds caption.
                }
								/**
								 * kj_imagelightbox2 end
								 */


















								if ($noCols)	{$imageHTML='<td valign="top">'.$imageHTML.'</td>';}		// If noCols, put in table cell.
								$tablecode.=$imageHTML;
							}
						}
						$index++;
						if (!$noCols || ($noCols && $b+1==$colCount_temp))	{
							if ($noCols)	{$tablecode.='</tr></table>';}	// In case of "noCols" we must finish the table that surrounds the images in the row.
							$tablecode.='</td>';	// Ending the cell. In case of "noCols" the cell holds all pictures!
						}
					}
					$tablecode.='</tr>';	// ending row
				}
				if ($c)	{
					// Table-tag is inserted
					$i=$contentPosition;
					$table_align = (($i==16) ? 'align="'.$align.'"' : '');
					$tablecode = '<table'.($tableWidth?' width="'.$tableWidth.'"':'').' border="0" cellspacing="0" cellpadding="0" '.$table_align.' class="imgtext-table">'.$tablecode;
					if ($editIconsHTML)	{	// IF this value is not long since reset.
						$tablecode.='<tr><td colspan="'.$colspan.'">'.$editIconsHTML.'</td></tr>';
						$editIconsHTML='';
					}
					if ($cap)	{









						/**
						 * kj_imagelightbox2 begin
						 */						
						if(!$conf['captionSplit'] AND $this->data['tx_kjimagelightbox2_imagelightbox2']!=1){
							$tablecode.='<tr><td colspan="'.$colspan.'" align="'.$caption_align.'">'.$caption.'</td></tr>';
						}
						
						/**
						 * kj_imagelightbox2 end
						 */
						 
						 




						 
						 
						 
					}
					$tablecode.='</table>';
					if ($conf['tableStdWrap.'])	{$tablecode=$this->stdWrap($tablecode,$conf['tableStdWrap.']);}
				}

				$spaceBelowAbove = intval($this->stdWrap($conf['spaceBelowAbove'],$conf['spaceBelowAbove.']));
				switch ($contentPosition)	{
					case '0':	// above
					$output= '<div style="text-align:'.$align.';">'.$tablecode.'</div>'.$this->wrapSpace($content, $spaceBelowAbove.'|0');
					break;
					case '8':	// below
					$output= $this->wrapSpace($content, '0|'.$spaceBelowAbove).'<div style="text-align:'.$align.';">'.$tablecode.'</div>';
					break;
					case '16':	// in text
					$output= $tablecode.$content;
					break;
					case '24':	// in text, no wrap
					$theResult = '';
					$theResult.= '<table border="0" cellspacing="0" cellpadding="0" class="imgtext-nowrap"><tr>';
					if ($align=='right')	{
						$theResult.= '<td valign="top">'.$content.'</td><td valign="top">'.$tablecode.'</td>';
					} else {
						$theResult.= '<td valign="top">'.$tablecode.'</td><td valign="top">'.$content.'</td>';
					}
					$theResult.= '</tr></table>';
					$output= $theResult;
					break;
				}
			} else {
				$output= $content;
			}

			if ($conf['stdWrap.']) {
				$output = $this->stdWrap($output, $conf['stdWrap.']);
			}
			return $output;
		}
	}
	
	
	
	
	
