<!DOCTYPE html>
<html lang="en" xmlns:fb="http://ogp.me/ns/fb#" xmlns:addthis="http://www.addthis.com/help/api-spec">
<head>
    <title><?php echo $page_title; ?></title>
    <meta name="msvalidate.01" content="1DE7A0C249C7CE76490060D0DE53757E" />
    <meta name="language" content="english" />
    <meta name="distribution" content="global" />
    <meta name="author" content="ArtDuel" />
    <meta name="publisher" content="ArtDuel" />
    <meta name="copyright" content="2011 ArtDuel" />
    <meta name="fb:app_id" content="146151782117021" />

    <meta http-equiv="content-type" content="text/html; charset=utf-8">
    <meta http-equiv="content-language" content="en">
    <meta name="description" content="New creative platform to gain exposure, reviews and instant feedback by dueling against other artists. Graphic design, web design and photography contests and inspiration" />
    <meta name="keywords" content="Art, 3D Design, Photo, Photography, Creative, Digital Art, Traditional Art, Community Art, Wallpapers, Prints, Graphic Design, Web Design, Digital Images, Design Review, Design Inspiration, Photography Contest, Graphic Design Contest, Web Design Contest" />
    <meta name="classification" content="Art" />
    <meta name="title" content="ArtDuel: A new kind of artistic and creative platform!" />
    <meta http-equiv="X-UA-Compatible" content="IE=edge,chrome=1" />

    <script src="/media/js/analytics.js"></script>

    <!--[if lt IE 9]>
        <script src="http://html5shiv.googlecode.com/svn/trunk/html5.js"></script>
    <![endif]-->

    <link href="/media/js/jquery/plugins/jquery.fancybox-1.3.4/fancybox/jquery.fancybox-1.3.4.css" rel="stylesheet" />
    <link href="/media/js/jquery/jquery-ui-1.8.16.custom/css/custom-theme/jquery-ui-1.8.16.custom.css" rel="stylesheet" />
    <link href="/media/css/global.css" rel="stylesheet"/>
</head>

<body>

    <div id="fb-root"></div>
    <script>
      window.fbAsyncInit = function() {
        FB.init({
          appId      : '<?php echo $facebook_config['appId']; ?>', // App ID
          channelUrl : '<?php echo base_url(); ?>channel.html', // Channel File
          status     : true, // check login status
          cookie     : true, // enable cookies to allow the server to access the session
          xfbml      : true  // parse XFBML
        });

      };

      // Load the SDK Asynchronously
      (function(d){
         var js, id = 'facebook-jssdk', ref = d.getElementsByTagName('script')[0];
         if (d.getElementById(id)) {return;}
         js = d.createElement('script'); js.id = id; js.async = true;
         js.src = "//connect.facebook.net/en_US/all.js";
         ref.parentNode.insertBefore(js, ref);
       }(document));
    </script>