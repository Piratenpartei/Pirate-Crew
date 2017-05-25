<div class="wrap">
        <div class="pirate-crew-customize">
        <div class="pirate-crew-customize-inner">
            <div class="pirate-crew-customize-member">
                    <div class="picrew-heading-group">
                            <p><?php _e('Select a member from the list to add as author to the post', $this->text_domain);?></p>
                    </div>
                    <div class="picrew-select-members">
                            <?php 
                            if($members->have_posts()): ?>
                            <select name="pirate_crew_member_id" id="picrew-members">			
                                    <?php 
                                    echo '<option value="" data-img="'.$defaultimage.'">'.__('Select a member',$this->text_domain).'</option>';
                                    while($members->have_posts()):  $members->the_post();
                                            $disabled ="";
                                            if($members->post->ID ==$preauthor ) $disabled ='selected = "selected"';
					    
					    $thumb = $this->pirate_team_get_thumbnail($members->post->ID,'thumbnail');
                                            echo '<option value="'.$members->post->ID.'" data-img="'.$thumb.'" '.$disabled.'>'.get_the_title().'</option>';
                                    endwhile;
                                    wp_reset_postdata();
                                    ?>
                            </select>
                            <?php else: 
                            $addmember = admin_url('post-new.php?post_type=pirate_crew_member');
                            echo '<p>';
                            _e('You haven\'t added any crew members yet.',$this->text_domain); 
                            echo '<a href="'.$addmember.'">'.__("Add a crew member",$this->text_domain).'</a>';
                            echo '</p>';
                            endif;?>
                    </div><!-- .picrew-select-members -->
	    </div>
	    </div>
	</div>
</div>
    