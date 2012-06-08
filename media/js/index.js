$(document).ready(function() {
    $('.dueler-actions a').hide();
    $('.submission-comment').hide();
    $('.art').hover(function(){
        $(this).find('.dueler-actions a').stop(true,true).show('fast');
    }, function(){
        $(this).find('.dueler-actions a').hide('fast');
    });
    /*$('.commentSubmission').click(function(){
        $(this).parent().parent().find('.submission-comment').toggle('fast');
        $(this).parent().parent().find('textarea').focus();
        return false;
    });

    $('.sendComment').click(function(){
        var el=$(this);
        var side = "left";
        var ID=el.parent().parent().find('input[name="fighter1"]').val();
        var IDSubmission2=el.parent().parent().find('input[name="fighter2"]').val();
        if(ID==null){
            ID=IDSubmission2;
            side="right";
        }
        var message=el.parent().find('textarea').val();
        var honeypotMessage = el.parent().find('input[name="message"]').val();
        var honeypotBody = el.parent().find('input[name="message"]').val();

        $.ajax({
            type: "POST",
            url: "/includes/ajax/addSubmissionMessage.php",
            data: "ID="+ID+"&comment="+encodeURIComponent(message)+"&message="+encodeURIComponent(honeypotMessage)+"&body="+encodeURIComponent(honeypotBody),
            success: function(msg){
               if(msg!=""){
                   $('.art.'+side).find('.dueler-img').append('<div class="ajaxError fixBottom">Error : ' + msg + '. Contact and administrator if the problem persists.</div>');
               }
               else{
                   $('.art.'+side).find('.dueler-img').append('<div class="ajaxMessage fixBottom">Your message has been sent.</div>');
               }

                $('.art.'+side).find('.submission-comment').remove();
                $('.art.'+side).find('.commentSubmission').remove();

               setTimeout(function(){
                    $('.ajaxMessage').hide('slow');
                    $('.ajaxError').hide('slow');
                }, 3000);
            },
            error: function(jqXHR, textStatus, errorThrown){
                $('.art.'+side).find('.dueler-img').append('<div class="ajaxError fixBottom">Error! Your message has not been sent. Contact and administrator if the problem persists.</div>');
                setTimeout(function(){
                    $('.ajaxError').hide('slow');
                }, 3000);
            }
        })
    })*/
});

function addFavorite(id,side){
     $.ajax({
         type: "GET",
         url: "/includes/ajax/addFavorite.php",
         data: "ID=" + id,
         success: function(msg){
                    var message = "The image has been added to your favorites!";
                    var classMessage = "ajaxMessage";
                    if(msg!=''){
                        message="Error : "+msg+". If the problem persist, contact an administrator.";
                        classMessage = "ajaxError";
                    }
                    if(side=='left'){
                        $('.art.left').find('.dueler-img').append('<div class="'+classMessage+' fixBottom">'+message+'</div>');
                        $('.art.left').find('.favorite').hide('slow',function(){ $(this).remove(); });
                    }
                    else {
                        $('.art.right').find('.dueler-img').append('<div class="'+classMessage+' fixBottom">'+message+'</div>');
                        $('.art.right').find('.favorite').hide('slow',function(){ $(this).remove(); });
                    }

                    setTimeout(function(){
                        $('.ajaxMessage').hide('slow');
                        $('.ajaxError').hide('slow');
                    }, 2000);
                },
         error: function(jqXHR, textStatus, errorThrown){
                    if(side=='left'){
                        $('.art.left').parent().append('<div class="ajaxError">There was an error trying to add the image to your favorite. Contact an admin if the problem persists.</div>');
                        $('.art.left').find('.favorite').hide('slow');
                    }
                    else {
                        $('.art.right').parent().append('<div class="ajaxError">There was an error trying to add the image to your favorite. Contact an admin if the problem persists.</div>');
                        $('.art.right').find('.favorite').hide('slow');
                    }

                    setTimeout(function(){
                        $('.ajaxError').hide('slow');
                    }, 4000);
         }
    });
}