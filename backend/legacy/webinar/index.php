<?php
chdir(dirname(__DIR__));

ob_start();
include dirname(__DIR__) . '/webinar_index.php';
$html = ob_get_clean();

$replacements = [
    'href="webinar/webinar.css"' => 'href="webinar.css"',
    'src="webinar/webinar.js"' => 'src="webinar.js"',
    'href="webinar/Payment-' => 'href="Payment-',
    'href="images/' => 'href="../images/',
    'src="images/' => 'src="../images/',
    'url("../images/' => 'url("../../images/',
    'href="Privacy-Policy"' => 'href="../Privacy-Policy"',
    'href="Terms-of-Service"' => 'href="../Terms-of-Service"',
    'href="Contact"' => 'href="../Contact"',
    'href="Contact.php"' => 'href="../Contact.php"',
    'href="Book' => 'href="../Book',
    'href="Disclaimer"' => 'href="../Disclaimer"',
    'href="js/' => 'href="../js/',
    'src="js/' => 'src="../js/',
];

echo strtr($html, $replacements);
