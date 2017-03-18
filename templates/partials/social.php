<?php
if (!empty($teamdata['pirate_crew_social'])) {
    echo '<nav class="pirate-crew-social-icons">';
    foreach ($teamdata['pirate_crew_social'] as $social) {
        echo '<span><a href="' . esc_url($social['link']) . '"><i class="pirate-crew-icon-' . $social['icon'] . '" aria-hidden="true"></i><span class="screen-reader-text">'. $social['icon'].'</span></a></span>';
    }
    echo '</nav>';
}
?>