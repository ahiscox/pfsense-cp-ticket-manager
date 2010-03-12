<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.01//EN"
        "http://www.w3.org/TR/html4/strict.dtd">

<?php

/**
 *
 * Interfaces with pfsense's captive portal user database
 * Currently adds 10 new randomly generated user/pass pairs to the db.
 * Returns the list to the browser in a manner suitable for printing.
 *
 * Copyright (c) 2009 Anthony Hiscox
 * Written for Cotes Technology Solutions
 *
 **/


require_once('config.inc');
require_once('functions.inc');

function add_user() {
	// Adds a user to the captive portal user configuration.

	global $config;

	$users = &$config['captiveportal']['user'];

	$tmp['name'] = rand(100000, 900000); // Create a temporary username
	// TODO: Double check if username exists, if it does, try until we get a good one.
	$randpass = rand(100000, 900000);
	$tmp['password'] = md5($randpass);
	$tmp['fullname'] = 'Automatically added by ticket manager';

        /**
         * Expiration date is the day of the month, 48 hours from now.
         * This is to avoid cancelling the Internet at 12am if a client
         * logged in at 11pm which a 24 hour expiration would do.
         * This could be more complex, but should be fine for now.
         * in the end, $tmp['expirationdate'] is in the format 'mm/dd/yyyy'
         **/
	$tmp['expirationdate'] = date("m/d/Y", time()+((60*60)*48));

	$users[] = $tmp;
	write_config();

	return Array('name' => $tmp['name'], 'pass' => $randpass, 'expire' => $tmp['expirationdate']);
}
?>

<html>
    <head>
        <title>New Users List</title>

        <style type="text/css">
            #cards {}

            .card {
                font-family: sans-serif;
            }

        </style>
    </head>
    <body>
        <div id="cards">
        <?php foreach (range(1,10) as $num): ?>

            <?php $user = add_user(); ?>
            <div class="card">
                    <div class="userpass">
                        Username: <?php echo $user['name']; ?>
                        Password: <?php echo $user['pass']; ?>
			Expires: <?php echo $user['expire']; ?>
                    </div>
	    	    <hr />
            </div><!-- /card -->

        <?php endforeach; ?>
        </div><!-- /cards -->

	<h1>Current users: </h1>
	<table>
        <?php foreach ($config['captiveportal']['user'] as $user): ?>
		<tr>
			<td><?php echo $user['name']; ?></td>
			<td><?php echo $user['expirationdate']; ?></td>
		</tr>
        <?php endforeach; ?>
	</table>
    </body>
</html>
