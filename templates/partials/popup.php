<div id="<?php echo $this->add_id(array('modal-style',$id,$team->post->ID));?>" class="picrew-modal-item">
    <div id="<?php echo $this->add_id(array('picrew-member-info',$id,$team->post->ID));?>" class="picrew-modal-content">
        <div class="picrew-modal-content-main">
            <div class="picrew-image-main">
                <img src="<?php echo $this->pirate_team_get_thumbnail($team->post->ID);?>" alt="<?php the_title();?>">
            </div>
            <div class="picrew-modal-details">
                <div class="picrew-modal-content-inner">
                    <?php 
                    $this->checkprint('<h3>%s</h3>', $teamdata['pirate-crew-designation']);
                    the_title( '<h2>', '</h2>'); 
                    the_content();
                    include( $this->settings['plugin_path'].'templates/partials/contact.php' );
                    include( $this->settings['plugin_path'].'templates/partials/social.php' );
                    ?>
                </div><!-- .picrew-modal-content-inner -->
            </div> <!-- .picrew-modal-details -->
        </div> <!-- .picrew-modal-content-main -->
    </div> <!-- .picrew-modal-content -->
</div> <!-- .picrew-modal-item -->