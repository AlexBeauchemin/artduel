<div id="all">
    <div id="up">
        <div id="up_container">
            <div class="logo left">
                <h1><a href="/index.php"><img src="/media/images/design/logo_artduel.png" alt="ArtDuel - An artistic community for reviews, feedbacks and contests" /></a></h1>
            </div>
            <?php if (!$current_user['logged_in']): ?>
                <div class="headerMenu right">
                    <strong><a href="/pages/login.php">Submit your art</a></strong> /
                    <a href="/pages/login.php" onclick="showLogin(); return false;" id="login-link">Login</a> /
                    <a href="/pages/register.php" onclick="showRegister(); return false;" id="register-link">Register</a>
                </div>

                <div class="clear">&nbsp;</div>

                <div id="login" style="display:none;">
                    <div class="facebook-login">
                        <p>
                            <a href="#" class="fb-connect"><img src="/media/images/design/fb-connect.png" alt="Facebook login"/></a>
                        </p>
                    </div>
                    <form action="/account/login" method="post" enctype="multipart/form-data">
                            <div class="login_element">
                                <label for="login_email">Email:</label>
                                <br />
                                <input type="text" id="login_email" name="login_email" value="<?php echo $this->input->post('email',true); ?>" tabindex="1"/>
                            </div>

                            <div class="login_element">
                                <label for="login_password">Password:</label>
                                <div class="right"><a href="/pages/forgot.php">Forgot Pass</a></div>
                                <div class="clear">&nbsp;</div>
                                <input type="password" name="login_password" id="login_password" tabindex="2" /><br />
                                <p class="text_right">
									<input type="checkbox" id="login_remember" name="login_remember" value='1' checked="checked" />
									<label for="login_remember" class="small">Remember me</label>
                                </p>

                                <?php addHoneyPot(); ?>
                            </div>

                            <div class="login_bas">
                                <input type="submit" name="submit" value="Login" class="button bloc_middle" tabindex="3" />
                            </div>
                      </form>
                </div>
                <div id="register" style="display:none;">
                    <div class="facebook-login">
                        <p>
                            <a href="#" class="fb-connect"><img src="/media/images/design/fb-connect.png" alt="Facebook login"/></a>
                        </p>
                    </div>
                    <form action="/account/register" method="post" enctype="multipart/form-data">
                        <div class="login_element">
                            <label for="email">Email:</label><br />
                            <input type="text" name="email" id="email"  autocomplete="off" value="<?php echo $this->input->post('email',true); ?>" tabindex="1"/>
                        </div>

                        <div class="login_element">
                            <label for="password">Password:</label><br />
                            <input type="password" name="password" id="password" autocomplete="off" class="password"  tabindex="2"/>
                            <input type="hidden" id="uid" value="<?php echo time(); ?>" />
                        </div>

                        <div class="login_element">
                            <label for="passconf">Confirm Password:</label><br />
                            <input type="password" name="passconf" id="passconf"  tabindex="3"/>
                        </div>

                        <?php addHoneyPot(); ?>

                        <div class="login_bas">
                            <input type="submit" name="submit" value="Register" class="button bloc_middle" tabindex="4"/>
                            <div class="clear">&nbsp;</div>
                        </div>

                    </form>
                </div>
            <?php else: ?>
                <div class="headerInfos left">
                    <div class="left">
                        <a href="/pages/profil.php">
                            <img src="<?php echo $current_user['picture']; ?>" alt="" height="30" />
                        </a>
                    </div>

                    <div class="left">
                        <p>
                            <a href="/pages/profil.php">
                            <?php
                                echo $current_user['name'].'</a>';
                                if ($new_alerts>0)
                                    echo ' <span class="newFights"><a href="/pages/alerts.php">('.$new_alerts.')</a></span>'; ?>
                            <br /><a href="/pages/profil.php">Level : <?php echo $current_user['level']; ?></a>
                         </p>
                     </div>

                     <div class="clear">&nbsp;</div>
                </div>
                <div class="headerMenu right">
                        <a href='/pages/profil.php'>My profile</a> / <strong><a href='/pages/submission.php'>Submit your art</a></strong> /

                        <?php if ($me): ?>
                            <!--<a href="<?php echo $logoutUrl; ?>">
                               <img src="http://static.ak.fbcdn.net/rsrc.php/z2Y31/hash/cxrz4k7j.gif">-->
                            <a href="/pages/logout.php" onclick="fBlogout(); return false;">
                            Log out
                            </a>
                        <?php else: ?>
                            <a href='/pages/logout.php'>Log out</a>
                        <?php endif; ?>
                </div>
            <?php endif; ?>
        </div> <!-- upcontainer -->
        <div class="clear">&nbsp;</div>
    </div> <!-- up -->
	<div id="sub_menu">
		<div class="container" style="z-index: 900;">
        	<div id="categoriesContainer">
                <a id="categories_button" href="#">
					Categories
					<?php foreach ($categories as $category): ?>
                        <?php if($current_user['category']==$category->ID): ?>
                            <span class="grey small">-><?php echo $category->name; ?></span>
                        <?php elseif(isset($challenger1->data->IDCategory) && $category->ID==$challenger1->data->IDCategory): ?>
                            <span class="grey small">-><?php echo $category->name; ?></span>
                        <?php endif; ?>
                    <?php endforeach; ?>
				</a>

                <ul id="categories_list" class="left">
                    <li><a href="/home/index/all" class="first <?php if($current_user['category']==0) echo 'current'; ?>">Everything</a></li>
                    <?php foreach( $categories as $num => $category ): ?>
                        <li <?php if($num == count($categories)) echo 'class="last"'?>>
                            <a href="<?php echo $category->url; ?>" class="<?php if ($category->ID==$current_user['category']) echo 'current'; ?>">
                                <?php echo $category->name; ?>
                            </a>
                        </li>
                    <?php endforeach; ?>
                </ul>
                <div class="clear">&nbsp;</div>
            </div>

			<ul id="menu_header_right" class="right">
				<li><a href="/pages/leaders.php">Leaders</a></li>
				<li><a href="/pages/discuss.php">Discuss</a></li>
				<li><a href="/pages/contact.php">Contact</a></li>
				<li><a href="/pages/info.php" class="last">?</a></li>
			</ul>

			<div class="clear">&nbsp;</div>
		</div>
    </div>

    <div class="container container-body">
	    <!--[if lt IE 7]> <div style=' clear: both; height: 59px; padding:0 0 0 15px; position: relative;'> <a href="http://windows.microsoft.com/en-US/internet-explorer/products/ie/home?ocid=ie6_countdown_bannercode"><img src="http://www.theie6countdown.com/images/banners/warning_bar_0000_us.jpg" border="0" height="42" width="820" alt="You are using an outdated browser. For a faster, safer browsing experience, upgrade for free today." /></a></div> <![endif]-->
        <noscript>
            <div id='showErrors'><ul><li>JavaScript is disabled in your browser, please enable JavaScript or upgrade to a JavaScript-capable browser to avoid errors and have a better user experience.</li></ul></div>
        </noscript>

		<?php
            $this->load->view('messages');
         ?>