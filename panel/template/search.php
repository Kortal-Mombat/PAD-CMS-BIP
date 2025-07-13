<h2><?php echo $pageTitle?></h2>
<div id="formSearch">
    <?php
    echo $message;
    ?>    
    <form action="<?php echo $PHP_SELF?>" method="get">
	<fieldset>
	    <legend class="hide">Wyszukiwarka</legend>
  
	    <input type="hidden" name="c" value="<?php echo $_GET['c']?>" />
	    <input type="hidden" name="action" value="search" />
	    
	    <label for="query">Wyszukiwana fraza</label>:
	    <input type="text" size="50" value="<?php echo $_SESSION['query']?>" id="query" name="query" />
	    
	    <input type="submit" value="Szukaj" />
	    
	</fieldset>
    </form>
</div>
<?php
if ($showResults)
{
?>
<?php
/*
 * Pages
 */
if ($showPages)
{
    ?>
    <div class="searchResult">
	<h3>Strony</h3>
	<div class="listItalic">Ilość pozycji: <?php echo $numPages?></div>
	<div id="listHead" aria-hidden="true">
	    <div class="listPos">L.p.</div>
	    <div class="listTitle">Nazwa</div>
	</div>
	<ul class="listRow">
	<?php
	$n = 1;
	foreach ($outPages as $value)
	{
	    $rowColor = '';
	    if ($n % 2 == 0){
		$rowColor = ' class="rowOdd"';
	    }	    
	    ?>
	    <li<?php echo $rowColor?>>
		<div class="listPos"><?php echo $n?>.</div>
		<div class="listTitle">
		    <a href="<?php echo $PHP_SELF . '?c=page&amp;action=edit&amp;id=' . $value['id'] ?>"><?php echo $value['name']?></a>
		</div>
	    </li>
	    <?php
	    $n++;
	}
	?>
	</ul>
    </div>
    <?php
}
/*
 * Articles
 */
if ($showArticles)
{
    ?>
    <div class="searchResult">
	<h3>Artykuły</h3>
	<div class="listItalic">Ilość pozycji: <?php echo $numArticles?></div>
	<div id="listHead" aria-hidden="true">
	    <div class="listPos">L.p.</div>
	    <div class="listTitle">Nazwa</div>
	</div>
	<ul class="listRow">
	<?php
	$n = 1;
	foreach ($outArticles as $value)
	{
	    $rowColor = '';
	    if ($n % 2 == 0){
		$rowColor = ' class="rowOdd"';
	    }
	    ?>
	    <li<?php echo $rowColor?>>
		<div class="listPos"><?php echo $n?>.</div>
		<div class="listTitle">
		    <a href="<?php echo $PHP_SELF . '?c=articles&amp;action=edit&amp;id=' . $value['id_art'] . '&amp;idp=' . $value['id_page'] ?>"><?php echo $value['name']?></a>
		</div>
	    </li>
	    <?php
	    $n++;
	}
	?>
	</ul>
    </div>
    <?php
}

/*
 * Photos
 */
if ($showPhotos)
{
    ?>
    <div class="searchResult">
	<h3>Zdjęcia</h3>
	<div class="listItalic">Ilość pozycji: <?php echo $numPhotos?></div>
	<div id="listHead" aria-hidden="true">
	    <div class="listPos">L.p.</div>
	    <div class="listTitle">Nazwa</div>
	</div>
	<ul class="listRow">
	<?php
	$n = 1;
	foreach($outPhotos as $value)
	{
	    $rowColor = '';
	    if ($n % 2 == 0){
		$rowColor = ' class="rowOdd"';
	    }
	    ?>
	    <li<?php echo $rowColor?>>
		<div class="listPos"><?php echo $n?>.</div>
		<div class="listTitleShort">
		    <a href="../files/<?php echo $lang?>/<?php echo $value['file']?>" target="_blank" title="Otwórz plik w nowym oknie"><img src="../files/<?php echo $lang?>/mini/<?php echo $value['file']?>" alt="" width="80"/></a>
		</div>
		<div class="listTitle">
		    <a href="../files/<?php echo $lang?>/<?php echo $value['file']?>" target="_blank" title="Otwórz plik w nowym oknie"><?php echo ($value['name'] == '') ? $value['file'] : $value['name'];?></a>
		</div>
		
		<?php
		switch($value['c'])
		{
		    case 'page':
			$url = $PHP_SELF . '?c=' .  $value['c'] . '&amp;action=edit&amp;id=' . $value['id_page'] . '&amp;autoload=1#photo';
			break;
		    
		    case 'articles':
			$url = $PHP_SELF . '?c=' .  $value['c'] . '&amp;action=edit&amp;id=' . $value['id_art'] . '&amp;idp=' . $value['id_page'] . '&amp;autoload=1#photo';
			break;
		}
		?>
		<div class="listAction"><span class="butIcons"><a href="<?php echo $url?>" title="Przejdź do <?php echo $value['link']?>"><img src="template/images/icoGoto.png" alt="Przejdź do <?php echo $value['link']?>" /></a></span></div>
	    </li>
	    <?php
	    $n++;
	}
	?>
	</ul>
    </div>
    <?php
}

/*
 * Files
 */
if ($showFiles)
{
    ?>
    <div class="searchResult">
	<h3>Pliki do pobrania</h3>
	<div class="listItalic">Ilość pozycji: <?php echo $numFiles?></div>
	<div id="listHead" aria-hidden="true">
	    <div class="listPos">L.p.</div>
	    <div class="listTitle">Nazwa</div>
	</div>
	<ul class="listRow">
	<?php
	$n = 1;
	foreach ($outFiles as $value)
	{
	    $rowColor = '';
	    if ($n % 2 == 0){
		$rowColor = ' class="rowOdd"';
	    }	    
	    ?>
	    <li<?php echo $rowColor?>>
		<div class="listPos"><?php echo $n?>.</div>
		<div class="listTitle">
		    <a href="../download/<?php echo $value['file']?>" target="_blank" title="Otwórz plik w nowym oknie"><?php echo $value['name']?></a>
		</div>
		<?php
		switch($value['c'])
		{
		    case 'page':
			$url = $PHP_SELF . '?c=' .  $value['c'] . '&amp;action=edit&amp;id=' . $value['id_page'] . '&amp;autoload=1#file';
			break;
		    
		    case 'articles':
			$url = $PHP_SELF . '?c=' .  $value['c'] . '&amp;action=edit&amp;id=' . $value['id_art'] . '&amp;idp=' . $value['id_page'] . '&amp;autoload=1#file';
			break;
		}
		?>
		<div class="listAction"><span class="butIcons"><a href="<?php echo $url?>" title="Przejdź do <?php echo $value['link']?>"><img src="template/images/icoGoto.png" alt="Przejdź do <?php echo $value['link']?>" /></a></span></div>
	    </li>
	    <?php
	    $n++;
	}
	?>
	</ul>
    </div>
    <?php
}

}
?>
