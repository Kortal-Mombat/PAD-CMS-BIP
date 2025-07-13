<div id="footerWrapper">
    <div id="footer"  role="contentinfo">

        <div id="footerMenu" role="navigation">
            <a id="skip_foot"></a>
            <h2 class="hide">Menu Stopka</h2>
            <?php
          	  get_menu_tree ('ft');
            ?>	            
        </div>
        
        <div id="logosWrapper">
            <div id="logosPAD">
            	<div class="padWrap">
                <ul>
                    <li><a href="http://widzialni.org/"><img src="/<?php echo $templateDir ; ?>/images/logoFW.png" alt="Przejdz do strony Fundacji Widzialni"/></a></li>
                    <li><a href="http://mac.gov.pl/"><img src="/<?php echo $templateDir ; ?>/images/logoMAiC.png" alt="Przejdz do strony Ministerstwa Administracji i Cyfryzacji"/></a></li>
                </ul>
                <p>Strona zostala opracowana w ramach projektu<br/>
                	<span>Polska Akademia Dostepnosci</span><br/>
               	 	realizowanego przez <span>Fundacje Widzialni</span> i <span>Ministerstwo Administracji i Cyfryzacji</span>
                </p>
                </div>
            </div>
        </div>  

    </div>
</div>