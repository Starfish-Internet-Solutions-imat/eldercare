<?phprequire_once(FILE_ACCESS_CORE_CODE.'/Framework/MVC_superClasses_Core/viewSuperClass_Core/viewSuperClass_Core.php');class view extends viewSuperClass_Core {


	
	public function handleWebsites(){
		$ctr = 1;
		$content = "<ul class='websites-ul'>";
			foreach($this->_XMLObj->websites->website_list->page as $each) {
				$class = "";
				($ctr % 3 == 0 ) && $class = "third";
				$content .= "<li class='{$class}'>";
					$content .= "<span class='bg-top'></span>";
					$content .="<div>";
					$content .= "<a href='/starfish-products-and-services/id/{$each->link}' class='website-image' id='website-image-{$each->link}'></a>";	
					$content .= "<a id='{$each->link}' href='/starfish-products-and-services/id/{$each->link}' class='context_title'>{$each->context_title}</a>";			
					$content .= "<p>{$each->context}</p>";
					$content .= "<h3><a class='button' href='/starfish-products-and-services/id/{$each->link}'>{$each->link_title}</a></h3>";
					$content .="</div>";
					$content .= "<span class='bg-bottom'></span>";					
				$content .= "</li>";
				$ctr++;
			}
		$content .= "</ul>";
		return $content;	
	}
	public function handlePricing(){
		$ctr = 1;
		$content = "<ul class='pricing-ul'>";
			foreach($this->_XMLObj->pricing->pricing_list->page as $each) {
				$class = "";
				($ctr % 2 == 0 ) && $class = "second";
				$content .= "<li class='{$class}'>";
					$content .= "<span>{$each->title}</span>";
					$content .= "<p>{$each->description}</p>";					
				$content .= "</li>";
				$ctr++;
			}
		$content .= "</ul>";
		return $content;		
	}
	public function handleSupport(){
		$ctr = 1;
		$content = "<ul class='support-ul'>";
			foreach($this->_XMLObj->support->support_list->page as $each) {
				$class = "";
				($ctr % 3 == 0 ) && $class = "third";
				$content .= "<li class='{$class}'>";
					$content .= "<span>{$each->title}</span>";
					$content .= "<p>{$each->description}</p>";					
				$content .= "</li>";
				$ctr++;
			}
		$content .= "</ul>";
		return $content;		
	}
	public function handleProcess(){
		$ctr = 1;
		$content = "<ul class='process-ul'>";
			foreach($this->_XMLObj->process->process_list->page as $each) {
				$class = "";
				($ctr % 2 == 0 ) && $class = "second";
				$content .= "<li class='{$class}'>";
					$content .= "<span>{$each->title}</span>";
					$content .= "<p>{$each->description}</p>";					
				$content .= "</li>";
				$ctr++;
			}
		$content .= "</ul>";
		return $content;			
	}	
}?>