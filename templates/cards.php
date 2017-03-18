<?php
$flip = false;
$flipclass = array("picrew-figcaption");
if(in_array($options['preset'], array('style-2'))){
	$flip = true;	
	$flipclass = array("picrew-flip-back");
}
?>
<div id="<?php echo $this->add_id(array('pirate-crew',$id));?>" class="picrew-grid-wrapper">
	<?php if ($team->have_posts()): ?>
		<div class="picrew-grid <?php echo $this->item_style($options);?>">
		<?php
			while ($team->have_posts()): $team->the_post();
                                $teamdata = $this->get_options('pirate_crew_member', $team->post->ID);?>
				<div id="<?php echo $this->add_id(array('pirate_crew_member',$id,$team->post->ID));?>" class="picrew-grid-card">
				   <figure>
				      <!-- <span class="picrew-grid-holder"> -->
				      	<?php $this->checkprint('<div class="picrew-flip-front">',$flip);?>
				         <img src="<?php echo $this->pirate_team_get_thumbnail($team->post->ID);?>" alt="<?php the_title();?>">
				        <?php $this->checkprint('</div>',$flip);?>
				         <figcaption class="<?php echo $this->addclass($flipclass);?>">
				         	<?php $this->checkprint('<div class="picrew-flip-back-inner">',$flip);?>
				            <div class="picrew-personal-info">
				               <h3><?php the_title();?></h3>
				               <span><?php echo $teamdata['pirate-crew-designation'];?></span>
				            </div> <!-- .picrew-personal-info -->
				            <div class="picrew-contact-info">
				                <?php
							    $this->checkprint('<p>%s</p>', $teamdata['pirate-crew-short-desc']);
							    include( $this->settings['plugin_path'].'templates/partials/social.php' );
    							?>
				            </div> <!-- .picrew-contact-info -->
				            <?php $this->checkprint('</div><!-- .picrew-flip-back-inner -->',$flip);?>
				         </figcaption>
				      <!-- </span> -->
				      <!-- .picrew-grid-holder -->
				   </figure>
				</div>
			<?php endwhile; wp_reset_postdata();?>
		</div><!-- .grid -->
		<?php endif;?>
</div>