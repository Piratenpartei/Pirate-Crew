<?php

/* 
 * crew Shortcode
 */

    $out = '';
    extract(shortcode_atts(array(
        'id' => false
    ), $atts));
	$options = $this->get_options('pirate_crew', $id);
	if (!$options) {
	    $out = '<div class="pirate-crew-error">' . __('Crew not found', $this->text_domain) . '</div>';
	     return $out;
	}
	if (empty($options['memberlist'])) {
	    $out = '<div class="pirate-crew-error">' . __('No members found', $this->text_domain) . '</div>';
	    return $out;
	}
	$template = $this->settings['plugin_path'] . 'templates/' . $options['team-style'] . '.php';

     if (file_exists($template)) {

	    $teamargs = array(
		'orderby' => 'post__in',
		'post_type' => 'pirate_crew_member',
		'post__in' => $options['memberlist'],
		'posts_per_page' => -1 ,
	    );
	    $team     = new WP_Query($teamargs);
	    ob_start();
	    include $template;
	    $var = ob_get_contents();
	    ob_end_clean();
	    // wp_reset_postdata();
	    $out = $var;
	}