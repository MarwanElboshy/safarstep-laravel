<?php
echo "REQUEST_URI: " . ($_SERVER['REQUEST_URI'] ?? 'not set') . "\n";
echo "SCRIPT_FILENAME: " . ($_SERVER['SCRIPT_FILENAME'] ?? 'not set') . "\n";
echo "SCRIPT_NAME: " . ($_SERVER['SCRIPT_NAME'] ?? 'not set') . "\n";
echo "PHP_SELF: " . ($_SERVER['PHP_SELF'] ?? 'not set') . "\n";
echo "DOCUMENT_ROOT: " . ($_SERVER['DOCUMENT_ROOT'] ?? 'not set') . "\n";
echo "PWD: " . getcwd() . "\n";
echo "PHP Version: " . phpversion() . "\n";
echo "File exists /home/safarstep/public_html/v2/public/index.php: " . (file_exists('/home/safarstep/public_html/v2/public/index.php') ? 'YES' : 'NO') . "\n";
echo "File exists ../index.php: " . (file_exists(__DIR__ . '/../index.php') ? 'YES' : 'NO') . "\n";
?>