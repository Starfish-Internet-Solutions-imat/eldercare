<?php
require_once FILE_ACCESS_CORE_CODE.'/Framework/MVC_superClasses_Core/modelSuperClass_Core/modelSuperClass_Core.php';

class pages_editorModel extends modelSuperClass_Core
{
	
	private  $fileNameOfPageXML;
	
	
	public function getPageXML($pagesXML)
	{
		if (isset($_GET['page_selected']))
		{
			$page_selected = $_GET['page_selected'];
	
			//load the correct page
	
			$navigationNamesArray = $pagesXML->xpath("//page[page_id='".$page_selected."']/../navigation_group_id");
			
			$dataHandler = new dataHandler();
			
			if(count($navigationNamesArray) < 1)
			{
				$navigationNamesArray	= $this->getNavigationNameGroupName($pagesXML, $page_selected);
				$currentNavigationGroup = strval($navigationNamesArray[0]);
				$parent_pageArray		= $pagesXML->xpath("//page[page_id='".$page_selected."']/../page_id");
				$parent_page			= strval($parent_pageArray[0]);
				
				$this->fileNameOfPageXML = 'Data/'.PRIMARY_DOMAIN.'/Content/Pages/'.$currentNavigationGroup.'/'.$parent_page.'/'.$page_selected.'.xml';
				$pageXML = $dataHandler->loadDataSimpleXML($this->fileNameOfPageXML);

			}
			
			else
			{
				$currentNavigationGroup = strval($navigationNamesArray[0]);
				$this->fileNameOfPageXML = 'Data/'.PRIMARY_DOMAIN.'/Content/Pages/'.$currentNavigationGroup.'/'.$page_selected.'/data.xml';
				$pageXML = $dataHandler->loadDataSimpleXML($this->fileNameOfPageXML);
			}
		}
		else
		{
			//default page
			$navigationNamesArray = $pagesXML->xpath("/pages/navigation_group/page[@xml='default']/../navigation_group_id");
			$currentNavigationGroup = strval($navigationNamesArray[0]);
	
			$defaultPageXML = $pagesXML->xpath("/pages/navigation_group/page[@xml='default']");
			$defaultPage = strval($defaultPageXML[0]->page_id);
	
	
			$this->fileNameOfPageXML = 'Data/'.PRIMARY_DOMAIN.'/Content/Pages/'.$currentNavigationGroup.'/'.$defaultPage.'/data.xml';
	
			$dataHandler = new dataHandler();
			$pageXML = $dataHandler->loadDataSimpleXML('Data/'.PRIMARY_DOMAIN.'/Content/Pages/'.$currentNavigationGroup.'/'.$defaultPage.'/data.xml');
		}
		return $pageXML;
	}
	
//----------------------------------------------------------------------------------------------------------------------	
	
	public function getNavigationNameGroupName($pagesXML, $page_selected, $parent_xpath = '')
	{
		$parent_xpath .= '/..';
		$navigationNamesArray = $pagesXML->xpath("//page[page_id='{$page_selected}']{$parent_xpath}/navigation_group_id");
			
		if(count($navigationNamesArray) < 1)
			return $this->getNavigationNameGroupName($pagesXML, $page_selected, '/..');
		
		else
			return $navigationNamesArray;
	}
	
//----------------------------------------------------------------------------------------------------------------------	
	
	public function getFileNameOfPageXML()
	{
		//getPageXML must be run first
		return $this->fileNameOfPageXML;
	}
	//===========================================================================================================================	
	//===========================================================================================================================
	//===========================================================================================================================	
	//===========================================================================================================================
	
	public function updateDOMObjectWithPOST($ambiguousDomObject)
	{
		//This accept a lot of different object but outputs a DOM
		//used in the updating via a POST
		if ('DOMNodeList' == get_class($ambiguousDomObject))
		{
			$domList = $ambiguousDomObject;
			$dom = new DOMDocument();
			for ($i = 0; $i < $domList->length; $i++)
			{
			$domnode = $dom->importNode($domList->item($i), true);
			$dom->appendChild($domnode);
			$dom = $this->updateDOMObjectWithPOST_internal($dom);
			}
		}
		elseif ('SimpleXMLElement' == get_class($ambiguousDomObject))
		{
			$dom = dom_import_simplexml($ambiguousDomObject);
			$dom = $this->updateDOMObjectWithPOST_internal($dom);
		}
		elseif ('DOMNode' == get_class($ambiguousDomObject))
		{
			print "DOMNode traverseDOM to be done but we want to see if its used"; die;
		}
		elseif ('DOMDocument' == get_class($ambiguousDomObject))
		{
			$dom = $ambiguousDomObject;
			$dom = $this->updateDOMObjectWithPOST_internal($dom);
		}
		elseif ('DOMElement' == get_class($ambiguousDomObject))
		{
			$dom = new DOMDocument();
			$domnode = $dom->importNode($ambiguousDomObject, true);
			$dom->appendChild($domnode);
			$dom = $this->updateDOMObjectWithPOST_internal($dom);
		}
		else
		{
			print "UNKNOWN DATA TYPE in updateDOMObjectWithPOST"; die;
		}
		return $dom;
	}
	//===========================================================================================================================
	
	// CMS Only function
	// To use this you have to make sure that the elements in the POST are numbered
	// sequentially starting from 1
	// It requires a specific input that is created by TraverseDOM and the rendering function in a View
	
	public $idCount; // THIS CAN BE SET BEFORE USING updateDOMObjectWithPOST, USED BY catalogueCMSController, saveItemEditsAction because it updates more than one XML object at a time
	
	public $yui_rte_prefix ='';// bloody catalogueCMSController.
	public function updateDOMObjectWithPOST_internal($dom)
	{
		$grouperCount=1;
	
		if (!isset ($this->idCount)) {
			$idCount=1;
		}
		else {
			$idCount= $this->idCount;
		}
		
		$string = '';
		
		//I cant believe how easy this was....
		//It only took 1 month to study how to use DOM ..
		//If you change a nodelist it changes the underlying dom
		//@todo doesnt deal with attributes!
		$nodes = $dom->getElementsByTagName("*");
		foreach ($nodes as $node)
		{
			if  ($node->nodeType==XML_ELEMENT_NODE)
			{
				if ($node->childNodes->length > 1)
				{
					//Do nothing - as its a container/grouping element
				}
				else
				{
					if (isset($_POST[$this->yui_rte_prefix.$idCount]))
					{
						//if there is HTML, put CDATA
						// This is needed too but is repeated in dataHanlder
	
						$string = $_POST[$this->yui_rte_prefix.$idCount];
						
						debug::getInstance()->logVariable($string,'$string',true,'green',true);
	
						if ($node->nodeName=='filename')
						{
							$currentFilename = $_POST['currentFileName'.$idCount];
							 
							$fileExtension = $_POST['fileExtension'.$idCount];
							 
							//get rid of spaces
							$newfilename = str_replace(' ','-',$_POST[$this->yui_rte_prefix.$idCount]);
							$string = $newfilename;
							 
							if ($fileExtension!='') //sometimes the field is filled with nonsense info as a placemarker for instance
							{
								//the current Filename must have the base string in it
								if (false===strpos($newfilename,routes::getInstance()->getCurrentSectionBase()))
								{
									$newfilename = $newfilename.'-'.routes::getInstance()->getCurrentSectionBase();
								}
	
								$newfilename = $newfilename.$fileExtension;
								$string = $newfilename;
								 
								if($currentFilename != $newfilename)
								{
									$sourcePath =  $this->getCodeBasePathToFile().'/data/images';
									$groupName = $_POST['groupFolderName'.$idCount];
						    
									$image = new imageHandler;
									$image->renameAllOccurrencesOfAnImage($currentFilename,$newfilename,$sourcePath,$groupName);
								}
							}
						}
	
	
						if ($string=='')
						{
							// a little workaround so that empty xml sections are displayed correctly
							// and posts are saved to the right XML tag
							$string='.';
						}
	
						$string = stripslashes($string);
						$string = strip_tags($string,'<b><i><u><hr><a><sub><sup><blockquote><br><ul><ol><li><img><p><table><tbody><tr><td>');
						//$string = str_replace ( '&#039;', '\'', $string );
						$string = str_replace ( '&quot;', '\"', $string );
						$string = str_replace ( '&lt;', '<', $string );
						$string = str_replace ( '&gt;', '>', $string );
	
						//FOR SOME WEIRD REASON YOU NEED TO HAVE & as & amp;
						//& amp; is the proper way of presenting & in html
						$string = str_replace ( '&', '&amp;', $string);
	
						$pos = strpos($string, '<');
						$pos1 = strpos($string, '&');
	
						if (($pos === false) && ($pos1 === false))
						{
							$node->nodeValue = $string;
						}
						else
						{
							$string = "<![CDATA[".$string."]]>";
							$node->nodeValue = $string;
						}
					}
					 
					 
					 
					$idCount++;
				}
			}
		}
		return $dom;
	}
	

	
}
?>