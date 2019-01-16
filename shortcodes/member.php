<?php

/* 
 * Handling of [pirate] shortcode
 */

 $out = '';
	extract(shortcode_atts(array(
                'id'		=> false,
		'alignclass'	=> '',
		'format'	=> 'card',
		'style'		=> 'style1',
		'showcontent'	=> 'true'
            ), $atts));
	    
	$id = intval($id);
	$class = pirate_crew_sanitize_shortcodeclass($alignclass);
	$format = pirate_crew_sanitize_shortcodeformat($format);
	$style = pirate_crew_sanitize_shortcodestyle($style);
	
	
	$post = get_post($id);
	if ($post && $post->post_type == 'pirate_crew_member') {

	    $flip = false;
	    $flipclass = array("picrew-figcaption");

	    if ($format == 'list') {
		$class = 'list';
	    }
	    $out .= '<div id="'.Pirate_Crew::add_id(array('pirate-crew',$id)).'" class="pirate-crew-single '.$class.'">';
	    $styleclass = '';
	    if ($format == 'list') {
		if ($style == 'style2') {
		    $styleclass= 'style-2';
		} else {
		    $styleclass= 'style-1';
		}
		$out .= '<div class="list-style '.$styleclass.' grid-2-col picrew-grid grid-full-col">';
	    } else {
		if ($style == 'style4') {
		    $styleclass= 'style-4';
		} elseif ($style == 'style3') {
		    $styleclass= 'style-3';
		} elseif ($style == 'style2') {
		    $styleclass= 'style-2';
		} else {
		    $styleclass= 'style-1';
		}
		$out .= '<div class="cards-style '.$styleclass.' picrew-grid grid-full-col">';
	    }
	    $teamdata = $this->get_options('pirate_crew_member', $post->ID);
	    $out .=  '<div id="'.$this->add_id(array('pirate_crew_member',$id,$post->ID)).'" class="picrew-grid-card">';
	    $out .= '<figure>';
	    $out .= '<img src="'.$this->pirate_team_get_thumbnail($post->ID).'" alt="">';
	    $out .= '<figcaption class="'.$this->addclass($flipclass).'">';
	    $out .= '<div class="picrew-personal-info">';
	    $out .= '<h3>'.get_the_title($post->ID).'</h3>';
	    $out .= '<p>'.$teamdata['pirate-crew-designation'].'</p>';
	    $out .= '</div> <!-- .picrew-personal-info -->';
	    $out .= '<div class="picrew-contact-info">';

	    $out .= '<nav class="picrew-social-icons">';
		foreach ($teamdata['pirate_crew_social'] as $social) {
		    if (isset($social['link'])) {
			$out .= '<span><a href="' . esc_url($social['link']) . '"><i class="picrew-icon-' . $social['icon'] . '" aria-hidden="true"></i><span class="screen-reader-text">'. $social['icon'].'</span></a></span>';
		    }
		}
	    $out .= '</nav>';


	    $out .= '</div> <!-- .picrew-contact-info -->';
	    $out .= '</figcaption>';
	    if (($format == 'list')&& ($showcontent == true)) {		       
		if ($post->post_content) {
		    $content = apply_filters( 'the_content', $post->post_content );
		    $content = str_replace( ']]>', ']]&gt;', $content );	
		    $out .=$content;
		
		}
	    }
	    
	    $out .= '</figure></div>';

	    $out .= '</div>';
	    $out .= '</div>';


	    wp_reset_postdata();
	    return $out;
	
	    
    
		
	} else {
	    $out = '<div class="pirate-crew-error">' . __('Pirate not found', 'pirate-crew') . '</div>';
	}
