<?php
require_once('config.inc');
require_once('functions.inc'); 


//erase expired accounts
$a_user = &$config['captiveportal']['user']; 

$changed = false;
for ($i = 0; $i < count($a_user); $i++) {
        if ($a_user[$i]['expirationdate'] && (strtotime("-1 day") > strtotime($a_user[$i]['expirationdate']))) {
		echo "Removing user: " . $a_user[$i]['name'] . " expired on " . $a_user[$i]['expirationdate'] . "<br />\n"; 
                unset($a_user[$i]);
                $changed = true;
        }
}
if ($changed) {
        write_config();
        exit;
} else {
	echo "Nothing to prune \n";
}
?>
