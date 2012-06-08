<div id="footer">
            <!-- AddThis Button BEGIN -->
            <div class="addthis_toolbox addthis_default_style " addthis:url="http://www.artduel.com">
            <a class="addthis_button_facebook_like" fb:like:layout="button_count"></a>
            <a class="addthis_button_tweet"></a>
            <a class="addthis_button_twitter_follow_native" tf:screen_name="ArtDuelOfficial"></a>
            <a class="addthis_button_google_plusone" g:plusone:size="medium"></a>
            <a class="addthis_counter addthis_pill_style"></a>
            </div>
            <script type="text/javascript" src="http://s7.addthis.com/js/250/addthis_widget.js#pubid=ra-4eb21f7b15a6f054"></script>
            <!-- AddThis Button END -->
            <div class="clear">&nbsp;</div>
			<ul class="list_footer bloc_middle">
				<li><a href="/index.php">Home</a></li>
                <li><strong><a href="/pages/submission.php">Submit your art</a></strong></li>
				<li><a href="/pages/leaders.php">Leaders</a></li>
				<li><a href="pages/discuss.php">Discuss</a></li>
				<li><a href="/pages/contact.php">Contact</a></li>
				<li><a href="/pages/info.php" class="last">What is Artduel?</a></li>
			</ul>

            <ul class="list_footer bloc_middle">
                <?php
                    foreach( $categories as $category ) {
                            echo '<li><a href="'.$category->url.'">'.$category->name.'</a></li>';
                    }
                ?>
			</ul>
			<p>ArtDuel is a community for artists about graphic design, computer graphics, photography, 3d, web design, well, everything that can be seen on a computer. It lets you post your creations, get fast reviews and feedbacks from your peers, find inspiration, get exposure, win prizes in contests and much more. All of this by dueling randomly against other artists in your category, RPG style. Who ever said that artistic platforms shouldn't be fun?</p>
            <p>&nbsp;</p>
            <p>- &copy;2011 ARTDUEL All rights reserved -</p>
            <!--<a href="https://twitter.com/share" class="twitter-share-button" data-url="http://www.artduel.com" data-text="Check out this new artistic community!" data-count="horizontal" data-via="ArtDuelOfficial">Tweet</a><script type="text/javascript" src="//platform.twitter.com/widgets.js"></script>
            <a href="https://twitter.com/ArtDuelOfficial" class="twitter-follow-button" data-show-count="false">Follow @ArtDuelOfficial</a>
            <script src="//platform.twitter.com/widgets.js" type="text/javascript"></script>
            <div class="fb-like-box" data-href="http://www.facebook.com/apps/application.php?id=146151782117021" data-width="200" data-height="60" data-show-faces="false" data-border-color="#434343" data-stream="false" data-header="false"></div>-->
        </div>
	</div><!-- container -->
</div>  <!-- all -->

<script src="https://ajax.googleapis.com/ajax/libs/jquery/1.6.4/jquery.min.js"></script>
<script>window.jQuery || document.write('<script src="/media/js/jquery/jquery-1.6.4.min.js">\x3C/script>')</script>
<script src="/media/js/jquery/jquery-ui-1.8.16.custom/js/jquery-ui-1.8.16.custom.min.js"></script>
<script src="/media/js/jquery/plugins/password/digitialspaghetti.password.min.js"></script>
<script src="/media/js/jquery/plugins/jquery.fancybox-1.3.4/fancybox/jquery.fancybox-1.3.4.pack.js"></script>
<script src="/media/js/jquery/plugins/hinter.js"></script>
<script src="/media/js/ellipsis.js"></script>
<script src="/media/js/boot.js"></script>
<?php if(isset($additional_js)): ?>
    <?php foreach($additional_js as $js): ?>
<script src="/media/js/<?php echo $js; ?>"></script>
    <?php endforeach; ?>
<?php endif; ?>

</body>
</html>