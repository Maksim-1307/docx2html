<?php

require_once "TagHandler.php";

$xml = new DOMDocument();
$xml->load('document.xml');

$handler = new TagHandler();

echo $handler->get_html($xml->getElementsByTagName('body')[0]);

?>