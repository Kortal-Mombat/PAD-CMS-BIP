<?
	if ($pagination['end'] > 1)
	{
		echo '<div class="pagination">';
			
		$active_page = $pagination['active'];
		
		if ( !isset($pagination['active']) ){
			$active_page = 1;
		}

		echo '<p>' . $TXT['page'] . '<strong>' . $active_page . '</strong>/' . $pagination['end'] . '</p>';
		
		if ($pagination['start'] != $pagination['prev'])
		{
			echo '<a href="' . $url . $pagination['start'] . '" rel="nofollow" class="page_start">' . $TXT['page_start'] . '</a> ';
			echo '<a href="' . $url . $pagination['prev'] . '" rel="nofollow" class="page_prev">' . $TXT['page_prev'] . '</a> ';
		}
		
		foreach ($pagination as $k => $v)
		{
			if (is_numeric($k))
			{
				//echo '<a href="' . $url . $v . '" title="' . $TXT['page'] . $v . '" rel="nofollow">' . $v . '</a> ';	
				echo '<a href="' . $url . $v . '" rel="nofollow">' . $v . '</a> ';	
			}
			else if ($k == 'active')
			{
				echo '<span>' . $v . '</span> ';	
			}			
		}
		
		if ($pagination['active'] != $pagination['end'])
		{
			echo '<a href="' . $url . $pagination['next'] . '" rel="nofollow" class="page_next">' . $TXT['page_next'] . '</a> ';
			echo '<a href="' . $url . $pagination['end'] . '" rel="nofollow" class="page_end">' . $TXT['page_end'] . '</a> ';
		}
		
		echo '</div>';
	}
?>