<?php   
class staticPagesNavigation
{
//================================================================================================================================================  
    public $pagesXML;
    
    public function displayStaticPagesNavigation()
    {
        $content = "";
        $dataHandler = new dataHandler();
        $pagesXML = $dataHandler->loadDataSimpleXML('Project/Code/1_Website/Pages/pages_navigation.xml');
        
        $content = '<div id="heading" class="pAl"><h3>YOUR PAGES</h3></div>';
        $content .= '<div id="sideNavigation" class="clearfix">';
        $content .= '<ul id="nav_list" class="unstyled">';
        
        foreach($pagesXML->navigation_group as $pages)
        {   
            $nav_group_id = strval(($pages->navigation_group_id));
    
            foreach($pages as $page)
            {
                $link_text  = strval($page->link_text);
                $attributes = $page->attributes();
                
                if ($link_text != "" && $attributes['cms'] != 'hide')
                {
                    $page_id =  strval($page->page_id);
                    
                    $active = '';
                     
                    if(isset($_GET['page_selected'])){
                        if($page_id == $_GET['page_selected'])
                            $active = ' class="active"';
                    }
                    else{
                    require_once 'Project/Code/2_Enterprise/Applications/Content_Management_System/Controllers/pages_editor/pages_editorModel.php';
                    $pages_editorModel = new pages_editorModel();
                    $dataHandler = new dataHandler();
                     
                    $pagesXML = $dataHandler->loadDataSimpleXML('Project/Code/'.PRIMARY_DOMAIN.'/Pages/pages_navigation.xml');
                    $defaultPageXML = $pagesXML->xpath("/pages/navigation_group/page[@xml='default']");
                    $defaultPage = strval($defaultPageXML[0]->page_id);
                    
                    	if($page_id == $defaultPage)
                    		$active = ' class="active"';
                    }
                    $application_url_name = routes::getInstance()->getCurrentTopLevelURLName();
                    $application = applicationsRoutes::getInstance()->getCurrentControllerURLName();
                    
                    $ahref = "<span class='nav'><a href='/".$application_url_name."/".$application."?page_selected=".$page_id."'>".$link_text."</a></span>";
                    
                    $content .= '<li'.$active.'>'.$ahref;
                    
                    if(count($page->page) > 0)
                    {
                        $content .= '<ul>';
                        
                        foreach($page->page as $subpage)
                        {
                            $link_text  = strval($subpage->link_text);
                            $attributes = $subpage->attributes();
                            
                            if ($link_text != "" && $attributes['cms'] != 'hide')
                            {
                                $page_id =  strval($subpage->page_id);
                                
                                $active = '';
                                 
                                if(isset($_GET['page_selected']))
                                    if($page_id == $_GET['page_selected'])
                                        $active = ' class="active"';
                                
                                $application_url_name = routes::getInstance()->getCurrentTopLevelURLName();
                                $application = applicationsRoutes::getInstance()->getCurrentControllerURLName();
                                
                                $ahref = "<span class='nav'><a href='/".$application_url_name."/".$application."?page_selected=".$page_id."'>".$link_text."</a></span>";
                                $content .= '<li'.$active.'>'.$ahref.'</li>';   
                            }
                        }
                        
                        $content .= '</ul>';
                    }
                }
            }
        }
        $content .= '</ul>';
        $content .= '</div>';
        return $content;
    }
//================================================================================================================================================  
}
?>