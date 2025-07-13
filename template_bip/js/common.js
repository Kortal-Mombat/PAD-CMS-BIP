$(document).ready(function() {

	if ($('#menuWrapper h2.tm').css('background-image') == 'none'){
		$('.fontDefault img').attr("src", templateDir+'/images/fontDefault_c.png');
		$('.fontBigger img').attr("src", templateDir+'/images/fontBig_c.png');
		$('.fontBig img').attr("src", templateDir+'/images/fontBigger_c.png');
		$('.fontContrast img').attr("src", templateDir+'/images/icoContrast_c.png');
	}
		
    $('a[data-rel]').each(function() {
		$(this).attr('rel', $(this).data('rel'));
    });   
	
	$("#mg, #tm").accessibleDropDown();

    /* new window */

    $('#content_txt a').each(function() {
        var _this = $(this);
        if (_this.attr('target') == '_blank')
        {
            _this.addClass('newWindow');
        }
    }); 
	
    $('a').mouseover(function() {
        var _this = $(this);
        var new_window = _this.attr('target');   
        if (new_window == '_blank')
        {
            _this.attr('title', 'Otwarcie w nowym oknie');  
        }
    });
	
    $('a').focus(function() {
        var _this = $(this);
        var new_window = _this.attr('target');   
        if (new_window == '_blank')
        {
            _this.attr('title', 'Otwarcie w nowym oknie');  
        }
    });	
	
	/* close cookie */
	$("#cclose").click(function() {
		setCookie("cookieOK", 1, 365);
		$("#cookiesMsg").hide();
		return false;
	});	
	
	/* Toggle register*/
	$("#metryka table").hide();
	$("#histZmian table").hide();
	
	$('#metryka h3 a').toggle(
		function() {
			$("#metryka table").show();
			$(this).children('span').text('Zwiń '); 
			$(this).css({
						'background':'url("'+templateDir+'/images/icoMinus.png") no-repeat 0 6px'
						}); 
			return false;
		},
		function() {
			$("#metryka table").hide();
			$(this).children('span').text('Rozwiń '); 
			$(this).css({
						'background':'url("'+templateDir+'/images/icoPlus.png") no-repeat 0 6px'
						}); 			
			return false;
		}	
	);	
	
	$('#histZmian h3 a').toggle(
		function() {
			$("#histZmian table").show();
			$(this).children('span').text('Zwiń '); 
			$(this).css({
						'background':'url("'+templateDir+'/images/icoMinus.png") no-repeat 0 6px'
						}); 
			return false;
		},
		function() {
			$("#histZmian table").hide();
			$(this).children('span').text('Rozwiń '); 
			$(this).css({
						'background':'url("'+templateDir+'/images/icoPlus.png") no-repeat 0 6px'
						}); 			
			return false;
		}	
	);	
	
	$("form.f_contact").submit(function(e) {
		if(!$('#zgoda:checked').length) {
			alert("Musisz wyrazić zgodę na przetwarzanie danych osobowych.");
			return false;
		}
		return true;
	});	
});

/**
 * Usuwanie cookies 
 */
	function deleteCookie( name ) {
		if ( getCookie( name ) ) { 
			document.cookie = name + "=" +	";expires=Thu, 01-Jan-1970 00:00:01 GMT";
		}
	}
	
/**
 * Tworzenie cookies 
 */	
	function setCookie(c_name,value,expiredays)
	{
		var exdate = new Date();
		exdate.setDate(exdate.getDate()+expiredays);
		document.cookie = c_name + "=" + escape(value) + ((expiredays==null) ? "" : ";expires=" + exdate.toUTCString());
	}	

/**
 * Pobranie cookies 
 */
	function getCookie(c_name)
	{
		if (document.cookie.length>0)
		{
			c_start=document.cookie.indexOf(c_name + "=");
			if (c_start!=-1)
			{
				c_start=c_start + c_name.length+1;
				c_end=document.cookie.indexOf(";",c_start);
				if (c_end==-1) 
				{
					c_end=document.cookie.length;
				}
				return unescape(document.cookie.substring(c_start,c_end));
			}
		}
		return "";
	}	

/**
 * Sprawdzenie cookies 
 */
	function checkCookie(c_name)
	{
		var c = getCookie(c_name);
		if(c!=null && c!='') 
			return true;
		else 
			return false;
	}	
