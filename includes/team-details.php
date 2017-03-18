<div class="wrap">
        <div class="pirate-crew-customize">
        <div class="pirate-crew-customize-inner">
            <div class="pirate-crew-customize-member">
                    <div class="pirate-crew-heading-group">
                            <h2 class="sub-h"><?php _e('Members', $this->text_domain);?></h2>
                            <span><?php _e('Select members from the dropdown, drag and drop them to reorder.', $this->text_domain);?></span>
                    </div>
                    <div class="pirate-crew-select-members">
                            <?php 
                            if($members->have_posts()): ?>
                            <select name="members" id="pirate-crew-members">			
                                    <?php 
                                    echo '<option value="" data-img="'.$defaultimage.'">'._e('Select a member',$this->text_domain).'</option>';
                                    while($members->have_posts()):  $members->the_post();
                                            $disabled ="";
                                            if(in_array($members->post->ID, $options['memberlist']) ) $disabled ="disabled";
                                            echo '<option value="'.$members->post->ID.'" data-img="'.$this->pirate_team_get_thumbnail($members->post->ID,'thumbnail').'" '.$disabled.'>'.get_the_title().'</option>';
                                    endwhile;
                                    wp_reset_postdata();
                                    ?>
                            </select>
                            <?php else: 
                            $addmember = admin_url('post-new.php?post_type=pirate_crew_member');
                            echo '<p>';
                            _e('You haven’t added any team members yet. ',$this->text_domain); 
                            echo '<a href="'.$addmember.'">'.__("Add a team member",$this->text_domain).'</a>';
                            echo '</p>';
                            endif;?>
                    </div><!-- .pirate-crew-select-members -->
                    <ul class="pirate-crew-members-list-selected">
                            <div class="pirate-crew-members-info">No Members Selected</div>
                            <script type="text/html" id="tmpl-pirate-crew-member-list">
                               <li data-member-id="{{{data.id}}}" class="">
                                    <img width="31" height="31" src="{{{data.src}}}"/>
                                    <p>{{{data.title}}}</p><span class="remove-member-to-list" data-member="{{{data.id}}}"><i class="pirate-crew-icon-close"></i></span>
                                    <input type="hidden" name="memberlist[]" value='{{{data.id}}}'>
                                    </li>
                            </script>
                            <?php
                            if($options['memberlist']):
                                $teamargs = array(
                                    'orderby' => 'post__in', 
                                    'post_type' => 'pirate_crew_member',
                                    'post__in' => $options['memberlist'],
                                 );
                                $team = new WP_Query($teamargs);
                                if($team->have_posts()):
                                while($team->have_posts()):  $team->the_post();?>
                                   <li data-member-id="<?php echo $team->post->ID;?>" class="">
                                        <img width="31" height="31" src="<?php echo $this->pirate_team_get_thumbnail($team->post->ID,'thumbnail');?>"/>
                                        <p><?php the_title();?></p><span class="remove-member-to-list" data-member="<?php echo $team->post->ID; ?>"><i class="pirate-crew-icon-close"></i></span>
                                        <input type="hidden" name="memberlist[]" value="<?php echo $team->post->ID;?>">
                                        </li>	 
                                <?php endwhile;
                            wp_reset_postdata();
                            endif;
                    endif;
                            ?>
                    </ul><!-- .pirate-crew-members-list-selected -->
            </div><!-- .pirate-crew-customize-member -->
            <div class="pirate-crew-customize-style">
                    <div class="pirate-crew-heading-group">
                            <h2 class="sub-h"><?php echo __('Presets', $this->text_domain);?></h2>
                            <span><?php echo __('Choose a preset from below.', $this->text_domain);?></span>
                    </div>
                    <div class="pirate-crew-preset-list pirate-crew-clearfix">
                            <?php
                            $styles = array(
                                    'Cards'     => array(4, 1),
                                    'List'      => array(2, 0),
                                    'Table'     => array(3, 0),
                            );
                            foreach ($styles as $key => $set):
                                $val = strtolower($key);?>
                            <input class="pirate-crew-radio-hidden" id="rad-<?php echo $val;?>" type="radio" data-style="<?php echo $set[0];?>" data-column="<?php echo $set[1];?>" name="team-style" value="<?php echo $val;?>" <?php checked($val,$options['team-style']);?>>
                            <label for="rad-<?php echo $val;?>"><img src="<?php echo $this->settings['plugin_url'] . '/images/' . $val . '.jpg';?>">
                                    <span data-type="<?php echo $val;?>"><?php echo $key;?></span>
                            </label>
                             <?php endforeach;?>
                    </div><!-- .pirate-crew-preset-list -->
                    <div class="pirate-crew-section pirate-crew-clearfix">
                            <div class="pirate-crew-heading-group">
                                    <h2 class="sub-h"><?php echo __('Style', $this->text_domain);?></h2>
                            </div><!-- .pirate-crew-heading-group -->
                            <div class="pirate-crew-row">
                                    <div class="pirate-crew-col-2">
                                            <?php
                                            $preset = array(
                                                'style-1'=>'Style 1',
                                                'style-2'=>'Style 2',
                                                'style-3'=>'Style 3',
                                                'style-4'=>'Style 4');
                                            $this->selectbuilder('preset', $preset, $options['preset'], '', "pirate-crew-select-default dyn-sel pirate-crew-styles",'key'); 
                                            ?>
                                    </div><!-- .pirate-crew-col-2 -->
                                    <div class="pirate-crew-col-2 pirate-crew-columns-wrap">
                                            <?php
                                            $columns = array(
                                                '2'=>'2 Column',
                                                '3'=>'3 Column',
                                                '4'=>'4 Column',
                                                '5'=>'5 Column');
                                            $this->selectbuilder('columns', $columns, $options['columns'], '', "pirate-crew-select-default dyn-sel pirate-crew-columns",'key'); 
                                            ?>
                                    </div><!-- .pirate-crew-col-2 -->
                            </div><!-- .pirate-crew-row -->
                    </div><!-- .pirate-crew-row -->
                   
            </div><!-- .pirate-crew-customize-style -->
            <div class="pirate-crew-clearfix"></div>
        </div><!-- .pirate-crew-customize-inner -->
    </div><!-- .pirate-crew-customize -->
</div><!-- wrap -->
<script type="text/html" id="tmpl-pirate-crew-member-select">
    <div class="select2-result-repository clearfix">
    	<# if ( data.src ) { #>
    	<img class="select2-result-repository__avatar" width="31" height="31" src="{{{data.src}}}" />
    	<# } #>
    	<p class="select2-result-repository__title">{{{data.title}}}</p>
    	<# if ( data.disabled ) { #>
    	<span class="select2-result-repository__disabled"><?php _e('Added',$this->text_domain);?></span>
    	<# } #>
   </div>
</script>