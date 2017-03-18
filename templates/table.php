<div id="<?php echo $this->add_id(array('pirate-crew',$id));?>" class="pirate-crew-grid-wrapper">
	<?php if ($team->have_posts()): ?>
	<div class="pirate-crew-table <?php echo $this->item_style($options);?>">
		<div class="pirate-crew-table-row pirate-crew-table-head">
			<div class="pirate-crew-table-cell">
				<?php _e('Image'); ?>
			</div><!-- .pirate-crew-table-cell -->
			<div class="pirate-crew-table-cell">
				<?php _e('Name'); ?>
			</div><!-- .pirate-crew-table-cell -->
			<div class="pirate-crew-table-cell">
				<?php _e('Designaion'); ?>
			</div><!-- .pirate-crew-table-cell -->
			<div class="pirate-crew-table-cell">
				<?php _e('Short Description'); ?>
			</div><!-- .pirate-crew-table-cell -->			
			<div class="pirate-crew-table-cell">
				<?php _e('Social Links'); ?>
			</div><!-- .pirate-crew-table-cell -->
		</div>
		<?php 
		while ($team->have_posts()): $team->the_post();
		$teamdata = $this->get_options('pirate_crew_member', $team->post->ID);
		?>
		<div id="<?php echo $this->add_id(array('pirate_crew_member',$id,$team->post->ID));?>" class="pirate-crew-table-row">
			<div class="pirate-crew-table-cell pirate-crew-table-image">
				<div class="pirate-crew-table-img-holder">
					<img src="<?php echo $this->pirate_team_get_thumbnail($team->post->ID);?>" alt="<?php the_title();?>">
				</div><!-- .pirate-crew-img-holder -->
			</div><!-- .pirate-crew-table-cell -->
			<div class="pirate-crew-table-cell pirate-crew-table-name">
				<div class="pirate-crew-table-cell-inner"><?php the_title(); ?></div>
			</div><!-- .pirate-crew-table-cell -->
			<div class="pirate-crew-table-cell pirate-crew-table-designation">
				<div class="pirate-crew-table-cell-inner"><?php $this->checkprint('%s', $teamdata['pirate-crew-designation']);?></div>
			</div><!-- .pirate-crew-table-cell -->
			<div class="pirate-crew-table-cell pirate-crew-table-description">
				<div class="pirate-crew-table-cell-inner"><?php $this->checkprint('<p>%s</p>', $teamdata['pirate-crew-short-desc']);?></div>
			</div><!-- .pirate-crew-table-cell -->
			<div class="pirate-crew-table-cell">
				<?php include( $this->settings['plugin_path'].'templates/partials/social.php' ); ?>
			</div><!-- .pirate-crew-table-cell -->
		</div><!-- .pirate-crew-table-row -->
		<?php endwhile; wp_reset_postdata();?>
	</div>
	<?php endif;?>	
</div>