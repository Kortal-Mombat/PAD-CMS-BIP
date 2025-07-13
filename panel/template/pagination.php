<div class="pagination">
<?php
	if ($pagination['end'] > 1)
	{
	
		$active_page = $pagination['active'];
		
		if ( !isset($pagination['active']) ){
			$active_page = 1;
		}

		echo '<p>' . $TXT_page . '<strong>' . $active_page . '</strong>/' . $pagination['end'] . '</p>';
		
		if ($pagination['start'] != $pagination['prev'])
		{
			echo '<a href="' . $url . $pagination['start'] . '" rel="nofollow" class="page_start">' . $TXT_page_start . '</a> ';
			echo '<a href="' . $url . $pagination['prev'] . '" rel="nofollow" class="page_prev">' . $TXT_page_prev . '<span class="hide"> ' . $TXT_page . '</span> </a> ';
		}
		
		foreach ($pagination as $k => $v)
		{
			if (is_numeric($k))
			{
				//echo '<a href="' . $url . $v . '" title="' . $TXT_page . $v . '" rel="nofollow">' . $v . '</a> ';	
				echo '<a href="' . $url . $v . '" rel="nofollow"><span class="hide">' . $TXT_page . ' </span>' . $v . '</a> ';	
			}
			else if ($k == 'active')
			{
				echo '<span><span class="hide">' . $TXT_page . ' </span>' . $v . '</span> ';	
			}			
		}
		
		if ($pagination['active'] != $pagination['end'])
		{
			echo '<a href="' . $url . $pagination['next'] . '" rel="nofollow" class="page_next">' . $TXT_page_next . '<span class="hide"> ' . $TXT_page . '</span> </a> ';
			echo '<a href="' . $url . $pagination['end'] . '" rel="nofollow" class="page_end">' . $TXT_page_end . '</a> ';
		}
	}
?>
</div>