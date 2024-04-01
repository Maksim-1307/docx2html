<?php

require_once 'TagHandler.php';

define("CASHFOLDER", "docxToHtmlCashFolder");

class Handler{

    public $taghandler;// = new ZipArchive;
    public $path;
    public $xml;

    function __construct($pathToDocx) {
        $this->path = $pathToDocx;
        $this->taghandler = new TagHandler();
    }

    function __destruct(){
        $this->deleteDir(CASHFOLDER);
    }

    function get_html(){
        return $this->handle();
    }

    function save_html($path){
        return 1;
    }
    
    function handle(){
        $this->unpack();
        $xml = file_get_contents(CASHFOLDER . "/word/document.xml");
        $this->deleteDir(CASHFOLDER);
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

        if (is_dir(CASHFOLDER)) {
            $this->deleteDir(CASHFOLDER);
        }

        if (!mkdir(CASHFOLDER)) {
            echo "ERROR: DocxToHtml mkdir failed";
        }

        if (!($zip->extractTo(CASHFOLDER))) {
            echo "ERROR: DocxToHtml zip extract failed";
        }
    }
}



?>