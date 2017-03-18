<div id="<?php echo $this->add_id(array('modal-style',$id,$team->post->ID));?>" class="pirate-crew-modal-item">
    <div id="<?php echo $this->add_id(array('pirate-crew-member-info',$id,$team->post->ID));?>" class="pirate-crew-modal-content">
        <div class="pirate-crew-modal-content-main">
            <div class="pirate-crew-image-main">
                <img src="<?php echo $this->pirate_team_get_thumbnail($team->post->ID);?>" alt="<?php the_title();?>">
            </div>
            <div class="pirate-crew-modal-details">
                <div class="pirate-crew-modal-content-inner">
                    <?php 
                    $this->checkprint('<h3>%s</h3>', $teamdata['pirate-crew-designation']);
                    the_title( '<h2>', '</h2>'); 
                    the_content();
                    include( $this->settings['plugin_path'].'templates/partials/contact.php' );
                    include( $this->settings['plugin_path'].'templates/partials/social.php' );
                    ?>
                </div><!-- .pirate-crew-modal-content-inner -->
            </div> <!-- .pirate-crew-modal-details -->
        </div> <!-- .pirate-crew-modal-content-main -->
    </div> <!-- .pirate-crew-modal-content -->
</div> <!-- .pirate-crew-modal-item -->