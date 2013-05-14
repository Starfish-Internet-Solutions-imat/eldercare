<?php
require_once FILE_ACCESS_CORE_CODE.'/Framework/MVC_superClasses_Core/viewSuperClass_Core/viewSuperClass_Core.php';
//require_once STAR_CORE_CODE.'/Objects/Image/imageHandler.php';

/*	Notes : there are lots of functions that are not being used. we need to recheck this out */
class pages_editor_View_Renderer extends viewSuperClass_Core
{
	private $_colourPointer=1;
	private $_cmsView;
	
	public $yui_rte_prefix = ''; 	
	
	public $_idCount;	

	public $_grouperCount; 		
	
	public $_grouperLevel;

	public $groupingElementsToHideBy; 
	
	public function __construct()
	{
		$content = $this->renderTemplate('Project/Design/2_Enterprise/Applications/Content_Management_System/Controllers/pages_editor/templates/js_and_css_links.phtml');
		response::getInstance()->addContentToStack('css_and_javascript_links_for_this_page_only',array('PAGE EDITOR CS JS'=>$content));
		
		$content = $this->renderTemplate('Project/Design/2_Enterprise/Applications/Content_Management_System/Controllers/pages_editor/templates/js_links_inpage_page_editor.phtml');
		response::getInstance()->addContentToStack('inpage_javascript_top',array('PAGE EDITOR JS INPAGE'=>$content));
		
		$this->_idCount = 0;
		$this->_grouperCount = 0; 
	}
	
	/*	call this one if you want the standard sumbit button and form being call in cmsview */	
	public function render_default_data_entry_form($xmlObj) {
		
		#added a default lander upon clicking the submit button
		
		$this->_content .= '<form method="post" id="content-form" action="" >';
			$this->_content .= '<input class="button_container hAuto wAuto btn-skin btn-save-right" type="submit" name="save" value="Save"/>';		
			$this->_content .= '<div id="content-form-inputs">';
				$this->render_data($xmlObj);
			$this->_content .= '</div>';
		$this->_content .= '</form>';
			
		return $this->_content;	
	}
	
	/*  You can call this function directly if you dont need a form or a submit button
		This function calls TraversDOM which calls renderDefaultDOM
	*/
	public function render_data($xmlObj) {					
		xmlProcessor::getInstance()->traverseDOM($xmlObj,$this,'renderDefaultDOM');		
	}
	
	/*	public only because called from xmlProcessor::getInstance()->traverseDOM
		calls from xmlProccessor
	*/
	public function renderDefaultDOM ($nodeName,$nodeValue,$attributes,$dom,$startOrEndTag){ 
		//CMS function to display XML in an editable HTML form	  	
		//legacy code for when there was only one attribute per tag, 
		//@todo needs to be updated!!
		
		$attrName ='';
		foreach ($attributes as $key=>$value) {
			$attrName = $key;
			$attrValue = $value;
		}
		
		//START TAGS
		if ($startOrEndTag=='START') {
				
		        /* if value is not empty */
		        if ($nodeValue=='') {
					$this->_grouperCount ++;
					 
					if ($nodeName == 'image') {
						
						$xmlObj = simplexml_import_dom($dom);
       					$filename = $xmlObj->filename;
	       				
						$grouping = routes::getInstance()->getCurrentGrouping();
						$base = routes::getInstance()->getCurrentTopSectionBase();
						
						$imagegroup=$xmlObj->group;
						
						//folder should not be set because it ties a group to one specific size
						//the size should be set by the View or Template
						if (!isset($xmlObj->folder)) {
							$image = new imageHandler();
							$imageFolderArray = $image->listImageDirectoriesWithinAGroup_ExcludingThumbs($imagegroup);
							if (isset($imageFolderArray[0])) {
								$imageFolder = $imageFolderArray[0];	
							}
							else {
								$imageFolder = '';
							}
						}
						else { 
							$imageFolder = $xmlObj->folder;
						}
						
						//added sept 2008 to take into account images in Modules
						//Not sure if the view should see the model but I dont want to repeat the code
						//and I dont want pass the model via the controller so.... 
						$model = new modelSuperClass_Core;
						$path = $model->getCodeBasePathToFile();
						
						//OLD sept2008 $folderName = '/Site/Code/Application/Sections/'.$grouping.'/'.$base.'/data/images/'.$imagegroup.'/'.$imageFolder;
						$folderName = '/'.$path.'/data/images/'.$imagegroup.'/'.$imageFolder;
	       				$image = $this->displayThumbnail($filename,$folderName);
	       				if	($image==false) {
	       					$image="<strong>image not found</strong>";	
	       				}
	       				
	       				$this->_content .= '<div class="imagepadder">';
		       				$this->_content .= '<div class="imagesource">';
		       					$this->_content .=  $image;
		       				$this->_content .= '</div>';
	       				$this->_content .= '<div class="imageDefaultEntry" >';
	       				
		       				//filename	       				
							$ext = strrchr(strval($filename), '.');
							if ($ext !== false) 	{
								$filename_sans_extension = substr($filename, 0, -strlen($ext));
								$readonly = '';
							}
							else {
								//filenames might not have an extension because they are used as placeholders
								$filename_sans_extension = $filename;
								$readonly = 'readonly'; 
							}
							
		       				$this->_idCount++;
		       				$this->_content .= "<div class='holder'>";
		       					$this->_content .= '<label class="hidethis" for="'.$this->_idCount.'">Filename</label> ';
		       					$this->_content .= '<input class="fileNames hidethis" type="text" name="'.$this->_idCount.'" id="'.$this->_idCount.'" value="'.$filename_sans_extension.'" readonly />';
		       				$this->_content .= "</div>";	       				
	
		       				$this->_content .= '<input type="hidden" name="currentFileName'.$this->_idCount.'" id="filename'.$this->_idCount.'" value="'.$filename.'" />';
		       				$this->_content .= '<input type="hidden" name="fileExtension'.$this->_idCount.'" id="fileExtension'.$this->_idCount.'" value="'.$ext.'" />';
		       					
		       				//group cannot be modified
		       				$this->_content .= "<div class='holder'>";	       				
		       					$this->_content .= '<label class="hidethis" for="groupFolderName'.$this->_idCount.'">Group Folder Name</label> <input type="text" class="hidethis" name="groupFolderName'.$this->_idCount.'" id="groupFolderName'.$this->_idCount.'" value="'.$imagegroup.'" readonly />';
		       				$this->_content .= "</div>";	       				
		       				
		       				//ALT Tag Must be 
		       				if (isset($xmlObj->image_alt)) {
		       					$this->_idCount++;
		       					$this->_content .= "<div class='holder'>";	
		       						$this->_content .= '<label for="alt">Alt</label> <input type="text" name="'.$this->_idCount.'" id="alt" value="'.$xmlObj->image_alt.'">';
		       					$this->_content .= "</div>";	       				
		       				}
		       				
		       				//20th October 2009
		       				//Button to change an image
	       					$this->_content .= '<div class="change_this_image btn-skin" element_id="" file_to_manipulate="'.$filename.'" image_group="'.$imagegroup.'" image_folder="'.$imageFolder.'">change image</div>';
	       				
	       				//-------------------------------------------------------------------------------------------------------------------------------
	       				$this->_content .= '</div>';	
	       				$this->_content .= '</div>';
					}
					else {
	        			$this->_content .= '<div class="containerMarkerXML bgcol'.$this->_colourPointer.'" id="group_'.$this->_grouperCount.'"><b class="d_title">'.str_replace('_', ' ', ucfirst($nodeName)).'</b>';
	       		 		$this->_colourPointer ++;
			        }
				}
				else // not empty nodeValue
				{
					$this->_idCount++;

					//deal with CDATA, remove it here and put it after the POST
					$nodeValue = str_replace('<![CDATA[', '', $nodeValue);
					$nodeValue = str_replace(']]>', '', $nodeValue);
					
					if($nodeName == 'date') { 	//jquery datpicker
						$this->_colourPointer ++; 
						$this->_content .= '<div class="XMLdefaultLabel"><span class="s_title">'.ucwords(str_replace('_',' ',$nodeName));
						$this->_content .= '</span><input type="text" size="10" value="'.$nodeValue.'" class="datepicker" id="'.$this->_idCount.'"/>'; 
						$this->_content .= '<style type="text/css">.embed + img { position: relative; left: -21px; top: -1px; }</style>';
					}
					else
					{	
		        		$this->_colourPointer ++; 
		        		$this->_content .= '<div class="XMLdefaultLabel"><span class="s_title">'.ucwords(str_replace('_',' ',$nodeName)).'</span> ';
		        		$this->_content .= '<textarea id="'.$this->yui_rte_prefix.$this->_idCount.'" name="'.$this->yui_rte_prefix.$this->_idCount.'" class="mytextarea ">'.$nodeValue.'</textarea>';
					}
				}
		}
		//END TAGS
		else
		{
			//nodevalue is not  always empty 	
			if ($nodeValue=='')
			{					
	        		$this->_content .= '</div>';
	       			$this->_colourPointer --;
			}
		}
	}


	
}

?>