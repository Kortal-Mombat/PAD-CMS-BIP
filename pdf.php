<?php
	include("mpdf/mpdf.php");
	
	$head = '
		<html lang="pl">
		<head>
		<style type="text/css">
			body {background: #fff; font-family: Tahoma, Arial, Helvetica, sans-serif; font-size:12px; color: #444; margin: 0; padding: 0}
			input, select, textarea {font-size: 12px; font-family: Tahoma, Arial, Helvetica, sans-serif;}
			
			h1, h2, h3, h4, h5, h6 { font-weight:bold; }
			h1 { color:#111; }
			table 
			
			table { border:1px solid #666; border-collapse: collapse; width:99%;}
			td { border:1px solid #666; border-collapse: collapse; padding:5px 10px; }
			th { border:1px solid #666; background:#f1f1f1; border-collapse: collapse; text-align:left; padding:5px 10px; }
						
			a img { border:none;}
			a, a:visited { color:#2e488b; text-decoration:underline; background:none;}

			.header {padding:20px 0 15px 0; text-align:center; border-bottom: 2px solid #eee; font-weight:bold; color:#666;}
			.footer {border-top: 2px solid #eee; margin:50px 0; padding: 10px; text-align:center;}
			.fr {width:5%; float:right; text-align:right; font-size:10px}
			.lead {padding:0 0 20px 0; margin: 0 0 20px 0; border-bottom:2px solid #eee;}				
		</style>
		
		</head>
		<body>';
	
	$html .= '<h1>' . $pageName . '</h1>';
		
	switch ($_GET['c']) {
		case 'txt' : 
			$txt = $pageText;
		break;

		case 'page' :
			if ($showPage)
			{		
				$row['text'] = str_replace ('src="http://'.$pageInfo['host'].'/', 'src="', $row['text']);
				$txt = $row['text'];	
			}
		break;

		case 'article' : 
			if ($showArticle)
			{		
				$txt = '<div class="lead">' . $article['lead_text'] . '</div>';	
				$txt .= $article['text'];		
			}
		break;

	}	
	
	$html .= $txt;
		
	$foot = '</body></html>';
	
	
	
	$mpdf=new mPDF('','A4','','',20,20,15,10,0,10); 
	
	$mpdf->SetHTMLHeader('<div class="header">'.$pageInfo['name'].'</div>');
	
	$mpdf->AddPage('P','','','','',20,20,35,30);
	
	$mpdf->SetHTMLFooter('<div class="footer"><p class="fr">{PAGENO}</p></div>');
	
	$mpdf->WriteHTML($head . $html . $foot);
	
	$filename =  trans_url_name($pageName . '.pdf');
	
	$mpdf->Output($filename, "I");
?>