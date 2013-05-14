<?php   
class viewTypeNavigation
{
//====================================================================================================================
	public static function displayControllersAsNavigation($current_application_id)
    {
        $optionsXML= applicationsRoutes::getInstance()->getControllersForApplication($current_application_id);
        
        $content = '';
        
        if(isset($_GET['page_selected']))
            $content .= '<h3 class="fleft">'.str_replace('_', ' ', $_GET['page_selected']).'</h3>';
        
        else{
        	require_once 'Project/Code/2_Enterprise/Applications/Content_Management_System/Controllers/pages_editor/pages_editorModel.php';
        	$pages_editorModel = new pages_editorModel();
        	$dataHandler = new dataHandler();
        	
        	$pagesXML = $dataHandler->loadDataSimpleXML('Project/Code/'.PRIMARY_DOMAIN.'/Pages/pages_navigation.xml');
        	$defaultPageXML = $pagesXML->xpath("/pages/navigation_group/page[@xml='default']");
        	$defaultPage = strval($defaultPageXML[0]->page_id);
        	
        	$content .= '<h3 class="fleft">'.str_replace('_',' ',$defaultPage).'</h3>';
        }
        
        $content .= "<ul id='".$current_application_id."_nav'>";
        $content .= self::get_ListItems_fromApplicationNavigationXML($optionsXML);
        $content .= "</ul>";
    
        return $content;
    }
    
//====================================================================================================================
    private function get_ListItems_fromApplicationNavigationXML($optionsXML)
    {   
        $content = "<div class='fright' id='pageNavigation'>";
        foreach($optionsXML as $option)
        {
            $application_url_name = routes::getInstance()->getCurrentTopLevelURLName();
            $controller_name = applicationsRoutes::getInstance()->getCurrentControllerURLName();
            $link_text = strval($option->link_text);
            $url_name =  strval($option->url_name);
            
            $active = '';
            
            if($url_name == $controller_name)
                $active = ' class="active"';
            
            $page_selected = '';
            
            if(isset($_GET['page_selected'])) $page_selected = '?page_selected='.$_GET['page_selected'];
            
            $ahref = "<a href='/".$application_url_name."/".$url_name.$page_selected."'>".$link_text."</a>";
            
            
            
            $content .= '<li id="'.$url_name.'_nav" '.$active.'>'.$ahref.'</li>';
        }
        $content .= "</div>";
        return $content;
    }
//====================================================================================================================  
}
?>