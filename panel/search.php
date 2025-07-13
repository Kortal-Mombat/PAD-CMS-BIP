<?php
if ($showPanel)
{
    if (get_priv_controler($_GET['c']))
    {
	$TEMPL_PATH = CMS_TEMPL . DS . 'search.php';
	
	$pageTitle = 'Wyszukiwarka';
	$message = '';
	
	$crumbpath[] = array ('name' => $pageTitle, 'url' => $PHP_SELF . '?c=search');
	if($_GET['query'] != '')
	{
	    $query = $_GET['query'];
	} else
	{
	    $query = $_SESSION['query'];
	}
	
	$showResults = false;

	if (strlen($query) < 3)
	{
	    if ($_GET['action'] == 'search')
	    {
		$message .= show_msg ('err', 'Wyszukiwana fraza jest za krótka. Min. 3 znaki.');
	    }
	} else
	{
	    $_SESSION['query'] = $query;
	    
	    $res = new resClass;
	    
	    $showResults = true;
	    $showPages = false;
	    $showArticles = false;
	    $showPhotos = false;
	    $showFiles = false;
	    
	    /*
	     * Pages
	     */
	    $sqlQuery = "(`name` like '%" . $query . "%') or (`text` like '%" . $query . "%') or (`attrib` like '%" . $query . "%')";
	    $sql = "select * from `" . $dbTables['pages'] . "` where `lang`='" . $lang . "' and " . $sqlQuery;
	    $params = array();
	    $res->bind_execute($params, $sql);
	    
	    $outPages = $res->data;
	    $numPages = count($outPages);
	    if($numPages > 0)
	    {
		$showPages = true;
	    }
	    
	    /*
	     * Articles
	     */
	    $sqlQuery = "(`name` like '%" . $query . "%') or (`lead_text` like '%" . $query . "%') or (`text` like '%" . $query . "%') or (`attrib` like '%" . $query . "%')";
	    $sql = "select * from `" . $dbTables['articles'] . "` where `lang`='" . $lang . "' and " . $sqlQuery;
	    $params = array();
	    $res->bind_execute($params, $sql);
	    
	    $outDbArticles = $res->data;
	    $numArticles = count($outDbArticles);
	    if($numArticles > 0)
	    {
		$showArticles = true;
		$outArticles = array();
		foreach ($outDbArticles as $value)
		{
		    $sql = "select * from `" . $dbTables['art_to_pages'] . "` where `id_art`= ?";
		    $params = array(
			'id_art' => $value['id_art']
		    );
		    $res->bind_execute($params, $sql);
		    $outArtPage = $res->data[0];
		   
		    $outArticles [] = array(
			'id_page'   => $outArtPage['id_page'], 
			'id_art'    => $outArtPage['id_art'], 
			'name'	    => $value['name']
		    );
		}
	    }
	    
	    /*
	     * Photos
	     */
	    $sqlQuery = "(`name` like '%" . $query . "%') or (`file` like '%" . $query . "%') or (`keywords` like '%" . $query . "%')";
	    $sql = "select * from `" . $dbTables['photos'] . "` where " . $sqlQuery;
	    $params = array();
	    $res->bind_execute($params, $sql);
	    
	    $outDbPhotos = $res->data;
	    $numPhotos = count($outDbPhotos);
	    $outPhotos = array();
	    if ($numPhotos > 0)
	    {
		$showPhotos = true;
		foreach ($outDbPhotos as $value)
		{
		    switch ($value['type'])
		    {
			case 'page':
			    $outPhotos[] = array(
				'name'	    => $value['name'],
				'file'	    => $value['file'],
				'id_page'   => $value['id_page'],
				'c'	    => 'page',
				'link'	    => 'strony',				
			    );
			    break;
			
			case 'article':
			    
			    $sql = "select * from `" . $dbTables['art_to_pages'] . "` where `id_art`= ?";
			    $params = array(
				'id_art' => $value['id_page']
			    );
			    $res->bind_execute($params, $sql);
			    $outArtPage = $res->data[0];
			    
			    $outPhotos[] = array(
				'name'	    => $value['name'],
				'file'	    => $value['file'],
				'id_page'   => $outArtPage['id_page'], 
				'id_art'    => $outArtPage['id_art'], 
				'c'	    => 'articles',
				'link'	    => 'artykułu',
			    );			    
			    
			    break;			
		    }
		}
	    }
	    
	    /*
	     * Files
	     */
	    $sqlQuery = "(`name` like '%" . $query . "%') or (`file` like '%" . $query . "%') or (`keywords` like '%" . $query . "%')";
	    $sql = "select * from `" . $dbTables['files'] . "` where " . $sqlQuery;
	    $params = array();
	    $res->bind_execute($params, $sql);
	    
	    $outDbFiles = $res->data;
	    $numFiles = count($outDbFiles);
	    $outFiles = array();
	    if ($numFiles > 0)
	    {
		$showFiles = true;
		foreach ($outDbFiles as $value)
		{
		    switch ($value['type'])
		    {
			case 'page':
			    
			    $outFiles[] = array(
				'name'	    => $value['name'],
				'file'	    => $value['file'],
				'id_page'   => $value['id_page'],
				'c'	    => 'page',
				'link'	    => 'strony',
			    );
			    break;
			
			case 'article':
			    
			    $sql = "select * from `" . $dbTables['art_to_pages'] . "` where `id_art`= ?";
			    $params = array(
				'id_art' => $value['id_page']
			    );
			    $res->bind_execute($params, $sql);
			    $outArtPage = $res->data[0];
			    
			    $outFiles[] = array(
				'name'	    => $value['name'],
				'file'	    => $value['file'],
				'id_page'   => $outArtPage['id_page'], 
				'id_art'    => $outArtPage['id_art'], 
				'c'	    => 'articles',
				'link'	    => 'artykułu',
			    );			    
			    
			    break;
		    }
		}
	    }
	}
	
    } else
    {
	$TEMPL_PATH = CMS_TEMPL . DS . 'error.php';
	$message .= show_msg ('err', $ERR_priv_access);		
    }
    
}
?>
