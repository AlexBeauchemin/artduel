<?php if($show_retry): ?>
    <p class="center"><a class="button" href="<?php echo htmlentities($_SERVER['PHP_SELF']); ?>">Retry</a></p>
<?php endif; ?>
    <form action="<?php echo htmlentities($_SERVER['PHP_SELF']); ?>" name="frmDuel" method="post" enctype="multipart/form-data">
    <div id="battle">
        <?php if ($challenger1->data->ID && $challenger2->data->ID): ?>
            <div id="versus">
                <input type="image" src="/media/images/design/vote-left.png" name="vote1" alt="VOTE" onMouseOut="this.src='/media/images/design/vote-left.png'" onMouseOver="this.src='/media/images/design/btn-vote-leftH.png'" /><input type="image" src="/media/images/design/vote-right.png" name="vote2" alt="VOTE" onMouseOut="this.src='/media/images/design/vote-right.png'" onMouseOver="this.src='/media/images/design/btn-vote-rightH.png'"/><br /><a href="index.php" class="button button_small">Skip</a>
            </div>
        <?php endif; ?>

        <div class="clear">&nbsp;</div>

            <?php if ($challenger1->data->ID && $challenger2->data->ID):  ?>
             <div class="art left" itemscope itemtype="http://schema.org/ImageObject">
                <div class="right" <?php if($image_margin[0] != 0){echo "style='margin-top:".$image_margin[0]."px;'";} ?>>
                    <p>
                        <a href="/pages/submission-info.php?ID=<?php echo $challenger1->data->ID; ?>"><strong><span itemprop="name"><?php echo $challenger1->data->name; ?></span></strong></a>
                        by: <a href="/pages/user.php?ID=<?php echo $challenger1->data->IDUser; ?>"><span itemprop="author"><?php echo $challenger1->data->username; ?></span></a>
                    </p>
                    <p class="dueler-img"><a href="<?php echo $challenger1->getImageUrl('big'); ?>" class="fancybox" rel="group"><img src="<?php echo $challenger1->getImageUrl('medium'); ?>" alt="<?php echo $challenger1->data->category . ' : ' . $challenger1->data->name; ?>" itemprop="contentURL" /></a></p>
                    <p class="hidden">Tags: <?php echo $challenger1->data->tags; ?></p>
                    <p class="dueler-actions"><a href="#" title="Add art to favorites" class="left favorite" onClick="addFavorite(<?php echo $challenger1->data->ID; ?>,'left');"><img src="/media/images/design/icons/icon_fav.png" alt="Add to favorites" /></a> <a href="/includes/ajax/comment-submission.php?ID=<?php echo $challenger1->data->ID; ?>" title="Comment" class="iframe left commentSubmission"><img src="/media/images/design/icons/icon_msg.png" alt="Comment" /></a><a href="/includes/ajax/report-submission.php?ID=<?php echo $challenger1->data->ID; ?>" class="iframe right" title="Report art"><img src="/media/images/design/icons/icon_report.png" alt="Report" /></a></p>
                    <input type="hidden" name="fighter1" value="<?php echo $challenger1->data->ID; ?>"  />
                    <input type="hidden" name="iduser1" value="<?php echo $challenger1->data->IDUser; ?>"  />
                    <div class="submission-comment hidden">
                        <textarea name="comment"></textarea>
                        <a href="#" class="button button_small sendComment leftCommentButton">Comment</a>
                    </div>
                </div>
            </div>
            <?php endif; ?>
            <?php if ($challenger1->data->ID && $challenger2->data->ID): ?>
            <div class="art right" itemscope itemtype="http://schema.org/ImageObject">
                <div class="left" <?php if($image_margin[1] != 0){echo "style='margin-top:".$image_margin[1]."px;'";} ?>>
                    <p>
                        <a href="/pages/submission-info.php?ID=<?php echo $challenger2->data->ID; ?>"><strong><span itemprop="name"><?php echo $challenger2->data->name; ?></span></strong></a>
                        by: <a href="/pages/user.php?ID=<?php echo $challenger2->data->IDUser; ?>"><span itemprop="author"><?php echo $challenger2->data->username; ?></span></a>
                    </p>
                    <p class="dueler-img"><a href="<?php echo $challenger2->getImageUrl('big') ?>" class="fancybox" rel="group"><img src="<?php echo $challenger2->getImageUrl('medium'); ?>" alt="<?php echo $challenger2->data->category . ' : ' . $challenger2->data->name; ?>" itemprop="contentURL" /></a></p>
                    <p class="hidden">Tags: <?php echo $challenger2->data->tags; ?></p>
                    <p class="dueler-actions">
                        <a href="#" title="Add to favorites" class="left favorite" onClick="addFavorite(<?php echo $challenger2->data->ID; ?>,'right');">
                            <img src="/media/images/design/icons/icon_fav.png" alt="Add art to favorites" />
                        </a>
                        <a href="/includes/ajax/comment-submission.php?ID=<?php echo $challenger1->data->ID; ?>" title="Comment" class="iframe left commentSubmission">
                            <img src="/media/images/design/icons/icon_msg.png" alt="Comment" />
                        </a>
                        <a href="/includes/ajax/report-submission.php?ID=<?php echo $challenger2->data->ID; ?>" class="iframe right" title="Report submission">
                            <img src="/media/images/design/icons/icon_report.png" alt="Report art" />
                        </a>
                    </p>

                    <input type="hidden" name="fighter2" value="<?php echo $challenger2->data->ID; ?>"  />
                    <input type="hidden" name="iduser2" value="<?php echo $challenger2->data->IDUser; ?>"  />
                    <input type="hidden" name="IDDuel" value="<?php echo $newDuelID; ?>" />
                    <div class="submission-comment hidden">
                        <textarea name="comment"></textarea>
                        <a href="#" class="button button_small sendComment rightCommentButton">Comment</a>
                    </div>
                </div>
            </div>
            <?php endif; ?>

        <div class="clear">&nbsp;</div>
    </div>
    </form>

</div> <!-- container -->

<div class="leaders">
	<div class="container">
        <div id="leaders_left_infos">
			<div class="vertical_title"><img src="/media/images/design/leaders.gif" alt="Leaders" /></div>
            <?php foreach($top_percent as $num => $leader): ?>
                <div class="leader_case">
                    <a href="/media/images/submissions/<?php echo $leader->IDUser.'/'.$leader->image; ?>_big.jpg" class="fancybox" style="display:block;">
                        <img src="/media/images/submissions/<?php echo $leader->IDUser.'/'.$leader->image; ?>_small.jpg" alt="<?php echo $leader->category.' : '.$leader->name; ?>" class="leaders_img"/>
                    </a>
                    <p><a href="/pages/user.php?ID=<?php echo $leader->IDUser; ?>"><?php echo $leader->username; ?></a><br />
                    <strong><?php echo $leader->percent; ?>%</strong> wins</p>
                    <div class="medals">
                        <?php if ($num==1): ?>
                            <img src="/media/images/design/medal_gold.png" alt="Leader #1 Best Winning Percentage" height="68" width="47" />
                        <?php elseif ($num==2): ?>
                            <img src="/media/images/design/medal_silver.png" alt="Leader #2 Best Winning Percentage" height="68" width="47" />
                        <?php elseif ($num==3): ?>
                            <img src="/media/images/design/medal_bronze.png" alt="Leader #3 Best Winning Percentage" height="68" width="47" />
                        <?php endif; ?>
                    </div>
                </div>
            <?php endforeach; ?>

            <div class="leader_case">
                <a href="/media/images/submissions/<?php echo $top_sequence->IDUser.'/'.$top_sequence->image.'_big.jpg'; ?>" class="fancybox"><img src="/media/images/submissions/<?php echo $top_sequence->IDUser.'/'.$top_sequence->image.'_small.jpg'; ?>" alt="<?php echo $top_sequence->category.' : '.$top_sequence->name; ?>" class="leaders_img"/></a><br />

                <p>
                    <a href="/pages/user.php?ID=<?php echo $top_sequence->IDUser; ?>" ><?php echo $top_sequence->username; ?></a>
                    <br />
                    sequence: <strong><?php echo $top_sequence->sequence; ?></strong>
                </p>

                <div class="medals">
                    <img src="/media/images/design/medal_sequence.png" alt="Best winning sequence" height="68" width="47" />
                </div>
            </div>
            <div class="leader_case last">
                <ul class="top_users">
                    <?php foreach($top_users as $id => $leader): ?>
                         <li>
                            <div class="left number-img">
                                <span class="number"><?php echo ($id+1); ?> </span>
                                <a href="/pages/user.php?ID=<?php echo $leader->ID; ?>">
                                    <img src="<?php echo $leader->picture; ?>" alt="<?php echo outputData($leader->name); ?> profile picture">
                                </a>
                            </div>
                            <div class="left stats">
                                <a href="/pages/user.php?ID=<?php echo $leader->ID; ?>" class="stats ellipsis"><?php echo outputData($leader->name); ?></a><br />
                                    <?php echo $leader->points; ?> points
                            </div>
                         </li>
                    <?php endforeach; ?>
                    <li>
                    	<a href="/pages/leaders.php" class="button">View more</a>
                    </li>
                </ul>
            </div>

            <div class="clear">&nbsp;</div>
        </div> <!-- leaders_left_infos -->
   </div><!-- container -->
</div><!-- leaders -->
<div class="container">