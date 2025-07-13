<h2 class="mainHeader"><?php echo $pageName; ?></h2>
<?php
$txt = '<ul class="margLeft">'
    .'<li><strong>'.$TXT['mod_txt_stats_visit'].':</strong> ' . my_counter() . ' ' . $TXT['mod_txt_stats_pers'] . '</li>'
    .'<li><strong>'.$TXT['mod_txt_stats_toeoy'].':</strong> ' . my_year_end() . ' ' . $TXT['mod_txt_stats_day'] . '</li>'
    .'</ul>';
echo $txt;
?>
