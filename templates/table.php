<div id="<?php echo $this->add_id(array('pirate-crew',$id));?>" class="picrew-grid-wrapper">
	<?php if ($team->have_posts()): ?>
	<div class="picrew-table <?php echo $this->item_style($options);?>">
		<div class="picrew-table-row picrew-table-head">
			<div class="picrew-table-cell">
				<?php _e('Image'); ?>
			</div><!-- .picrew-table-cell -->
			<div class="picrew-table-cell">
				<?php _e('Name'); ?>
			</div><!-- .picrew-table-cell -->
			<div class="picrew-table-cell">
				<?php _e('Designaion'); ?>
			</div><!-- .picrew-table-cell -->
			<div class="picrew-table-cell">
				<?php _e('Short Description'); ?>
			</div><!-- .picrew-table-cell -->			
			<div class="picrew-table-cell">
				<?php _e('Social Links'); ?>
			</div><!-- .picrew-table-cell -->
		</div>
		<?php 
		while ($team->have_posts()): $team->the_post();
		$teamdata = $this->get_options('pirate_crew_member', $team->post->ID);
		?>
		<div id="<?php echo $this->add_id(array('pirate_crew_member',$id,$team->post->ID));?>" class="picrew-table-row">
			<div class="picrew-table-cell picrew-table-image">
				<div class="picrew-table-img-holder">
					<img src="<?php echo $this->pirate_team_get_thumbnail($team->post->ID);?>" alt="<?php the_title();?>">
				</div><!-- .picrew-img-holder -->
			</div><!-- .picrew-table-cell -->
			<div class="picrew-table-cell picrew-table-name">
				<div class="picrew-table-cell-inner"><?php the_title(); ?></div>
			</div><!-- .picrew-table-cell -->
			<div class="picrew-table-cell picrew-table-designation">
				<div class="picrew-table-cell-inner"><?php $this->checkprint('%s', $teamdata['pirate-crew-designation']);?></div>
			</div><!-- .picrew-table-cell -->
			<div class="picrew-table-cell picrew-table-description">
				<div class="picrew-table-cell-inner"><?php $this->checkprint('<p>%s</p>', $teamdata['pirate-crew-short-desc']);?></div>
			</div><!-- .picrew-table-cell -->
			<div class="picrew-table-cell">
				<?php include( $this->settings['plugin_path'].'templates/partials/social.php' ); ?>
			</div><!-- .picrew-table-cell -->
		</div><!-- .picrew-table-row -->
		<?php endwhile; wp_reset_postdata();?>
	</div>
	<?php endif;?>	
</div>