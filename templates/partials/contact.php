<?php
if (!empty($teamdata['pirate_crew_contact'])) {
    echo '<div class="pirate-crew-contact-details">';
    foreach ($teamdata['pirate_crew_contact'] as $contact) {
    	if(filter_var($contact['content'], FILTER_VALIDATE_EMAIL)){
    		$contact['content'] = sprintf('<a href="mailto:%1$s">%1$s</a>',$contact['content']);
    	}
        echo '<p><span>'.$contact['label'].':</span>'.$contact['content'].'</p>';
    }
    echo '</div>';
}
?>