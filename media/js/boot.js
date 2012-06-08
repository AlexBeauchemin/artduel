$(document).ready(function() {
	$('.verif-hp').hide();
	$("#login-link").removeAttr("href");
	$("#register-link").removeAttr("href");
	$('#register #password').pstrength({ 'displayMinChar': false });
	$(".ellipsis").ellipsis();
	
	//UPDATE CATEGORIES LIST WIDTH
	var cat_width = $('#categoriesContainer').width();
	$('ul#categories_list').css('min-width', cat_width);
	
	var open = false;
    var timeout;
	//header categories menu
    $('#categoriesContainer').live('mouseenter',function () {
		openCategoriesMenu();
    });
	$('#categoriesContainer').live('mouseleave',function () {
		closeCategoriesMenu();
    });
	
	function openCategoriesMenu(){
        clearTimeout(timeout);
        if (!open){
            open = true;
            $('a#categories_button').parent().css('background-color', '#363636');
    	    $('ul#categories_list').stop(true,true).show('medium', function() {
                //animation done
            });
        }
	}

    function closeCategoriesMenu(){
        timeout = setTimeout(function(){
            open=false;
            $('ul#categories_list').stop(true,true).hide('medium', function() {
                $('a#categories_button').parent().css('background-color', '#434343');
            })
        },400);
    }
	
	//Check for new badges
	var xmlhttp;
	if (window.XMLHttpRequest){
	  xmlhttp=new XMLHttpRequest();
	}
	else {// code for IE6, IE5
	  xmlhttp=new ActiveXObject("Microsoft.XMLHTTP");
	}
	
	xmlhttp.onreadystatechange=function(){
	  if (xmlhttp.readyState==4 && xmlhttp.status==200){
		if(xmlhttp.responseText!=""){
			$.fancybox({
				'width'         		: 550,
				'scrolling'   			: 'no',
				'padding'				: 0,
				'autoScale': true,
				'transitionIn': 'fade',
				'transitionOut': 'fade',
				'type': 'iframe',
				'href': '../includes/ajax/newBadge.php?Badges='+xmlhttp.responseText,
                onClosed: function() {
                    $.ajax({
                        url: '../includes/ajax/newBadge.php?updateSeenState=1&Badges='+xmlhttp.responseText
                    });
                }
			});
		}
	  }
	}
	
	xmlhttp.open("GET","../includes/ajax/getNewBadges.php",true);
	xmlhttp.send();
	
	
	/*var timeout;
		
	$('#categories').show();
	$('#categories_list').hide();
	$('#categories').bind('mouseenter', function(){
		$('.menuBottom').hide();
		$('#categories_list').show();
	});
	$('#categories_list').bind('mouseleave', function(){
		timeout = setTimeout(function(){
			$('.menuBottom').show();
			$('#categories_list').hide();
		  }, 500);
	});
	$('#categories_list').bind('mouseenter', function(){
		clearTimeout(timeout);
	});*/

    $('.fb-connect').live('click',function(){
        FB.login(function(response){
            if (response){
                alert('user is logged in');
            }
        });
        //},
        //{perms:'publish_stream'});
    });

});

$(function() {
	$("a.fancybox").fancybox({
		'autoScale' 			: false,
		'autoDimensions'		: false	
	});
	$("a.iframe").fancybox({
		'type' : 'iframe',
		'width'         		: 550,
		'height'        		: 400,
		'scrolling'   			: 'no',
		'padding'				: 0
	});
});

function showLogin(){
		$('#register').hide();
		$('#login').toggle('fast', function() {
			$(this).find('#login_email').focus();
	});
}

function showRegister(){
		$('#login').hide();
		$('#register').toggle('fast', function() {
			$(this).find('#email').focus();
	});
}