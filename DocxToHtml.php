<?php

require_once 'TagHandler.php';

class Handler{

    public $taghandler;// = new ZipArchive;
    public $path;
    public $xml;

    function __construct($pathToDocx) {
        $this->path = $pathToDocx;
        $this->taghandler = new TagHandler();
    }

    function __destruct(){
        $this->deleteDir("cash");
    }
    
    function handle(){
        $this->unpack();
        $xml = file_get_contents("cash/word/document.xml");
        $dom = new DOMDocument();
        $dom->loadXML($xml);
        return $this->taghandler->get_html($dom->documentElement);
    }

    function deleteDir(string $dir)
    {
        system('rm -rf -- ' . escapeshellarg($dir), $retval);
        return ($retval == 0);
    }

    function unpack(){

        $zip = new ZipArchive;
        $zip->open($this->path);

        $extractDir = "cash";

        if (is_dir($extractDir)) {
            $this->deleteDir($extractDir);
        }

        if (!mkdir($extractDir)) {
            echo "ERROR: DocxToHtml mkdir failed";
        }

        if (!($zip->extractTo($extractDir))) {
            echo "ERROR: DocxToHtml zip extract failed";
        }
    }
}



?>