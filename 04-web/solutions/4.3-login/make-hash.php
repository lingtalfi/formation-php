<?php
// Run once from the command line: php make-hash.php
// Copy the output into login.php. Each run gives a different hash (random salt): that's normal.
echo password_hash('secret123', PASSWORD_DEFAULT) . PHP_EOL;
