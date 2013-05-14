<?php 
require_once 'Project/Code/System/Photo_Library/album/albums.php';
require_once 'Project/Code/System/Photo_Library/image/images.php';
class photos_and_albumsNavigation
{   
    public function displayPhotoAndAlbumsNavigation()
    {
        $albums = new albums();
        $albums->select();
        
        $array_of_albums = $albums->__get('array_of_albums');
        
        if(count($array_of_albums) == 0)
            $array_of_albums = array();
        
        $content = '<div id="heading" class="pAl">
        				<h3>PHOTO ALBUMS
        					<span class="sprite pointer" id="add_album"></span>
        				</h3>
        			</div>';
        $content .= '<div id="sideNavigation" class="clearfix">';
        $content .= '<ul id="nav_list" clas="unstyled clearfix">';
        $content .= self::get_AlbumsList_from_Albums($array_of_albums);
        $content .= '</ul>';
        $content .= '</div>';
        $content .= '<div id="heading" class="pLl mBxl mTs"><h3>ALL PHOTOS ('.images::selectCount().')</h3></div>';
    
        return $content;
    }
    
//====================================================================================================================
    
    private function get_AlbumsList_from_Albums($array_of_albums)
    {
        $content = '';
        
        foreach($array_of_albums as $album)
        {
            $active = '';
        
        
            if(isset($_POST['album_id']) && $album->__get('album_id') == $_POST['album_id'])
                $active = ' class="active"';
            
            $content .= "<li{$active}>
                            <form method='POST'>
                                <input type='hidden' name='album_id' value='{$album->__get('album_id')}'>
                                <input id='albumActionType' type='hidden' name='album_action' value='view'>
                                <span class='nav'><input class='fs-xl fwB' type='submit' name='view_album' value='{$album->__get('album_title')}'></span>
                            </form>
                        </li>";
        }
        
    
        return $content;
    }
}
    
?>