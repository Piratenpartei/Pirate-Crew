<div id="<?php echo $this->add_id(array('pirate-crew',$id));?>" class="picrew-grid-wrapper">
    <?php if ($team->have_posts()): ?>
	<div class="picrew-grid <?php echo $this->item_style($options);?>">
		<?php
                    while ($team->have_posts()): $team->the_post();
                        $teamdata = $this->get_options('pirate_crew_member', $team->post->ID);?>
			<div id="<?php echo $this->add_id(array('pirate_crew_member',$id,$team->post->ID));?>" class="picrew-grid-card">
                           <figure>
                                 <img src="<?php echo $this->pirate_team_get_thumbnail($team->post->ID);?>" alt="<?php the_title();?>">
                                 <figcaption>
                                    <div class="picrew-personal-info">
                                       <h3><?php the_title();?></h3>
                                       <?php $this->checkprint('<span>%s</span>', $teamdata['pirate-crew-designation']);?>
                                    </div> <!-- .picrew-personal-info -->
                                    <div class="picrew-contact-info">
                                        <?php
                                                   
                                                        include( $this->settings['plugin_path'].'templates/partials/social.php' );
							
							 the_content();
                                                ?>
                                    </div> <!-- .picrew-contact-info -->
                                 </figcaption>
                           </figure>
			</div>
		<?php endwhile; wp_reset_postdata();?>
	</div><!-- .grid -->
    <?php endif;?>
</div>