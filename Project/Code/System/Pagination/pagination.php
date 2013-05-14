<? 
require_once FILE_ACCESS_CORE_CODE.'/Objects/Database/starfishDatabase.php';

class pagination
{
	private $offset_value;
	private $limit_clause;
	private $posts_per_page;
	private $pages;
	private $numrows;
	
	public function getLimitClause() { return $this->limit_clause; }
	
	public function getPages() { return $this->pages; }
	
	public function setOffset($offset) {
		$this->offset_value = $offset;	
	}
	
	public function setPostsPerPage($posts) {
		$this->posts_per_page = $posts;
	}
	
	public function count_all_rows($pdo_statement)
	{
		$this->numrows = $pdo_statement->rowCount($pdo_statement);
		$this->pages = ceil($this->numrows/$this->posts_per_page);
	}
	
	public function price_limit_offset()
	{
		$offsetValue = ($this->offset_value)*$this->posts_per_page;
		$this->limit_clause =  " LIMIT {$this->posts_per_page} OFFSET ".$offsetValue;
	}
}

?>