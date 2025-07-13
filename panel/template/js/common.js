/* Limit czasu bezczynności */	
var currentDate = new Date();
var minutes = new Date(currentDate.getTime() + (15 * 60 * 1000));

$(document).ready(function(){  
	
	$(document).on("click", ".helpTip", function(evt){ 
	    evt.preventDefault();
	});

	/* Tabs */
	$("#tabs ul li a").click(function() { 
		return false;
	});									
	
	/* Reklama */
	$("input[name=typ]").click(function() { 
		var typ = $('input[name=typ]:checked').val();
		if (typ == 'image'){
			$('#set_image').show();
			$('#set_flash').hide();
			$('#set_html').hide();
		}
		if (typ == 'flash'){
			$('#set_image').hide();
			$('#set_flash').show();
			$('#set_html').hide();
		}
		if (typ == 'html'){
			$('#set_image').hide();
			$('#set_flash').hide();
			$('#set_html').show();
		}		
	}); 
	
	/* wysokosc tresci */
	var menu_height = $('#menuWrapper').outerHeight(true);
	var content_height = $('#contentWrapper').outerHeight(true);
	if (menu_height > content_height) {
		$('#contentWrapper').css('min-height', menu_height );
	}
	
	/* Kalendarzyk */
	$(".datepicker").datepicker({
			dateFormat: "yy-mm-dd",
			showOn: "both",
			buttonImage: "template/images/calendar.gif"
		});

	/* Zakladki tabs */
	$('#tabs').tabs(); 
    

	$('#timeLimit').countdown(minutes, {elapse: true})
		.on('update.countdown', function(event) {
			var $this = $(this);
			if (event.elapsed) {
				$this.html(event.strftime('Twoja sesja wygasła'));
				window.location.replace("index.php?c=logout");
			} else {
				if(event.offset.minutes < 1) {
					// wywołac okienko dialogowe
					showDialog(this);
				}
				$this.html(event.strftime('Twoja sesja wygaśnie za: <span>%H:%M:%S</span>'));
		}
	});	    
}); 

/* Tips v.2*/
$(document).on("mouseover focus", "a, .tip", function(e) {	 

	$('body').children('div#tooltip').remove();
	var sw = screen.width/2;
	var tw = $('#tooltip').width();
	var tip = $(this).attr('title');   
	
	if (tip != '' && tip != undefined)
	{
		if (e.pageX != undefined)
		{
			$(this).attr('title','');
			tip = tip.replace('|', '<br/>');
			tip = tip.replace('|', '<br/>');
			$('body').append('<div id="tooltip"><div class="tipBody">' + tip + '</div></div>');    

			if (e.pageX < sw) {
				$('#tooltip').css('top', e.pageY + 10 );
				$('#tooltip').css('left', e.pageX + 15 );
			} else {
				$('#tooltip').css('top', e.pageY + 10 );
				$('#tooltip').css('left', e.pageX - tw - 30);
			}
		}
		else
		{
			var clas = $(this).attr('class');   
			if (clas == 'helpTip')
			{
				$(this).attr('title','');
				tip = tip.replace('|', ' ');
				tip = tip.replace('|', ' ');			
				$(this).append('<div id="tooltip"><div class="tipBody">' + tip + '</div></div>');    			
				$('#tooltip').addClass("focus-tip");
				$(this).css({'outline':'none'});
			}
		}
	}
});

$(document).on("mouseover", "a, .tip", function(e) {
	var sw = screen.width/2;	
	var tw = $('#tooltip').width();
		
	$('#tooltip').css('top', e.pageY + 10 );
	if (e.pageX < sw) {
		$('#tooltip').css('left', e.pageX + 15 );
	} else {
		$('#tooltip').css('left', e.pageX - tw -30);			
	}
});

$(document).on("mouseout blur", "a, .tip", function(e) {
		$(this).attr('title',$('.tipBody').html());
		//$('body').children('div#tooltip').remove();
		$('div#tooltip').remove();
});

/* Dodawanie input ankieta*/
$(document).on("click", ".addInput", function(){ 
	$('#answers').append('<p class="answ"><input type="text" name="answer[]" size="100" maxlength="255" value="" /><a href="#" class="delInput" title="Usuń odpowiedź"><img src="template/images/menuTreeSub_.png" alt="Usuń odpowiedź" /></a></p>')
});

/* Usuwanie input ankieta*/
$(document).on("click", ".delInput", function(){ 
	$(this).parent().remove(); 
});

$(document).on("click", "#showUsers", function(){ 
	$("#newsletterUsers").slideToggle('fast'); 
});

$(document).ready(function(){
	if($('body').find('.stripeTop'))
		window.setInterval(function(){$(".stripeTop").load("sendMailing.php");},30000);
});


function on_box() {
	a = document.getElementsByTagName("input");
	for(i=0; i<a.length; i++)
	{
		a[i].checked = true;
	}
}

function off_box() {
	a = document.getElementsByTagName("input");
	for(i=0; i<a.length; i++){
		a[i].checked = false;
	}
}

function confirmLink(theLink, text)
{
	qs = confirm(text);
	if(qs)
	{
		window.location=theLink;
	}
}

function OpenWindow(theURL,winName,w,h,scroll)
{
	LeftPosition = (screen.width) ? (screen.width-w)/2 : 0;
	TopPosition = (screen.height) ? (screen.height-h)/2 : 0;
	settings ='height='+h+',width='+w+',top='+TopPosition+',left='+LeftPosition+',scrollbars='+scroll+',resizable=no'
	newWindow = window.open(theURL,winName,settings).focus();
	if (window.focus) {newwindow.focus()}
	if (!newwindow.closed) {newwindow.focus()}
	return false;
}

/* dialog */
var dialogOpen = false, lastFocus, dialog, okbutton, pagebackground;

function logout() {
	window.location.replace("index.php?c=logout");
}

function setTimeLimit() {
	var currentDate = new Date();
	var minutes = new Date(currentDate.getTime() + (15 * 60 * 1000));
	$('#timeLimit').countdown(minutes, {elapse: true});
}

function showDialog(el) {
	lastFocus = el || document.activeElement;
	toggleDialog('show');
}

function hideDialog(el) {
	setTimeLimit();
	toggleDialog('hide');
}

function toggleDialog(sh) {
	dialog_wrap = document.getElementById("dialogWrapper");
	dialog = document.getElementById("box");
	okbutton = document.getElementById("ok");
	pagebackground = document.getElementById("bg");

	if (sh == "show") {
		dialogOpen = true;

		// show the dialog 
		var bg_height = pagebackground.clientHeight + 120;
		dialog_wrap.style.height = bg_height + 'px';
		dialog_wrap.style.display = 'block';
		dialog.style.display = 'block';
		
		// after displaying the dialog, focus an element inside it
		okbutton.focus();
		
		// only hide the background *after* you've moved focus out of the content that will be "hidden"
		pagebackground.setAttribute("aria-hidden","true");
		
	} else {
		dialogOpen = false;
		dialog_wrap.style.display = 'none';
		dialog.style.display = 'none';
		pagebackground.setAttribute("aria-hidden","false");
		lastFocus.focus(); 
	}
}


document.addEventListener("focus", function(event) {

    var d = document.getElementById("box");

    if (dialogOpen && !d.contains(event.target)) {
        // event.stopPropagation();
        d.focus();
    }

}, true);

// escape button
document.addEventListener("keydown", function(event) {
    if (dialogOpen && event.keyCode == 27) {
        toggleDialog('hide');
    }
}, true);