<div id="bg">
<a id="top"></a>
<ul class="skipLinks">
    <li><a href="#skip_mm">Skocz do menu</a></li> 
    <li><a href="#skip_txt">Skocz do treści</a></li> 
</ul>	

<div id="popup"></div>
<div id="dialogWrapper"></div>
<div style="display: none;" role="dialog" aria-labelledby="myDialogTitle" aria-describedby="myDialogText" id="box" class="dialogBox" tabindex="-1">
	<h3 id="myDialogTitle">Twoja aktualna sesja za chwilę wygaśnie</h3>
	<div id="myDialogText"><p>Ze względów bezpieczeństwa, sesja wygasa po 15 minutach nieaktywności.</p></div>
	<button id="ok" onclick="hideDialog(this);" class="close-button">Przedłuż sesję</button>
	<button onclick="logout();" class="close-button">Wyloguj się</button>		
</div>

<div id="header" role="banner">

    <div class="topWrapper">
    	<a href="index.php" class="logoPAD"><img src="template/images/logoPADCMS.png" alt="Strona główna panelu"/></a>
        <p>Zobacz również</p>
        <ul>
        	<li><a href="http://portal.widzialni.org"><img src="template/images/logoPADPortal.png" alt="Portal Polskiej Akademii Dostępności"/></a></li>
            <li><a href="http://platforma.widzialni.org"><img src="template/images/logoPADPlatforma.png" alt="Platforma e-learningowa"/></a></li>
            <li><a href="http://widzialni.org/szkolenie-redagowanie-tresci-dostepnej-strony-internetowej-wcag-20,m,mg,44,56">Warsztaty <img src="template/images/icoMore.png" alt=""/></a></li>
        </ul>
    </div>
    
    <h1><span><?= $cmsConfig['cms_title'] . ' (' . $cms_version . ')</span>' . $pageInfo['name']; ?></h1>

    <ul class="menuLogout">
    	<li class="loggedUser"><span>Witaj,</span><?= $_SESSION['userData']['name']; ?></li>
        <li class="logOut"><a href="<?php echo $PHP_SELF.'?c=logout'?>"><?php echo $TXT_but_logout; ?> <img src="template/images/imgLogout.png" alt=""/></a></li>
        <li class="course"><a href="http://pad.widzialni.org/container/samouczki/samouczek--czyli-jak-korzystac-z-panelu-administracyjnego-dostepnego-biuletynu-informacji-publicznej-pad.doc" title="Dokument DOC - 7MB">Samouczek<img src="template/images/butCourse.png" alt=""/></a></li>
    </ul>
    
    <div class="bok">
		<div id="timeLimit"></div>
    </div>    
    <a href="../" class="panelLogo" target="_blank"><img src="template/images/icoGo.png" alt=""/> Przejdź na stronę<span class="hide"> otwarcie w nowym oknie</span></a>
            
</div>