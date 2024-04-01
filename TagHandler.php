<?php

define('POPERTY_FUNCTION_REFIX', 'prop_');


function remove_namespace($str){
    $n = explode(':', $str);
    if (count($n) > 1){
        return $n[1];
    } else {
        return $str;
    }
}

class TagHandler{
    public $domElement;

    function get_html($domElement){
        $funcname = remove_namespace($domElement->nodeName);

        if (method_exists($this, $funcname)) {
            return $this->$funcname($domElement);
        } else {
            return $this->default($domElement);
        }
    }

    function get_css($domElement)
    {
        $styleElementName = remove_namespace($domElement->nodeName) . "Pr";
        $styleElement = $domElement->getElementsByTagName($styleElementName)[0];
        if (!$styleElement) {
            return "";
        }
        $css = "";
        $propTags = $styleElement->childNodes;
        foreach ($propTags as $propTag) {
            $css .= $this->get_property($propTag);
        }
        if ($css) return ' style="' . $css . '"';
    }

    function get_property($propDomElement){
        $funcname = 'property_' . remove_namespace($propDomElement->nodeName);
        if (method_exists($this, $funcname)) {
            return $this->$funcname($propDomElement);
        } 
    }

    function default ($domElement, $tagName = ""){
        $name = $tagName;
        if (!strlen($tagName)){
            $name = $domElement->nodeName;
            $name = remove_namespace($name);
        }
        $innercontent = "";
        $nodes = $domElement->childNodes;
        if (count($nodes)) {
            foreach ($nodes as $child){
                $innercontent .= $this->get_html($child);
            }
        } 
        else {
            $innercontent .= ($domElement->nodeValue);
            return $innercontent;
        }
        $css = $this->get_css($domElement);
        return '<' . $name . $css . '>' . $innercontent . '</' . $name . '>';
    }

    function r($domElement){
        return $this->default ($domElement, "span");
    }

    function rPr($domElement){
        return "";
    }

    function ppr($domElement)
    {
        return "";
    }

    function t($domElement){
        return $domElement->textContent;
    }

    

    function tab($domElement){
        return "    ";
    }

    function tbl($domElement){
        return "";
    }

    function property_glow($propElement){
        $colorElement = $propElement->getElementsByTagName("srgbClr");
        $colorVal = $colorElement[0]->attributes[0]->nodeValue;
        return "background-color: " . $colorVal . ";";
    }

    function property_highlight($propElement)
    {
        $colorVal = $propElement->attributes[0]->nodeValue;
        return "background-color: " . $colorVal . ";";
    }

    function property_b($propElement){
        return "font-weight: bold;";
    }

    function property_color($propElement)
    {
        $colorVal = $propElement->attributes[0]->nodeValue;
        return "color: " . $colorVal . ";";
    }

    function property_u($propElement)
    {
        $css = "text-decoration: underline;text-decoration-style:";
        $style = $propElement->attributes[0]->nodeValue;
        switch ($style) {
            case "double":  $css .= "double"; break;
            case "dotted":  $css .= "dotted"; break;
            case "dash":    $css .= "dashed"; break;
            case "wave":    $css .= "wavy"; break;
            case "dotDash": $css .= "dashed"; break;
            case "thick":   $css .= "solid"; break;
            default:  $css .= "solid"; break;
        }
        return $css . ";";
    }

    function property_szCs($propElement)
    {
        return $this->property_sz($propElement);
    }

    function property_sz($propElement)
    {
        $size = $propElement->attributes[0]->nodeValue;
        return "font-size: " . $size . "px;";
    }

    function property_strike($propElement){
        return "text-decoration: line-through;";
    }

    function property_i($propElement){
        return "font-style: italic;";
    }

    function property_pStyle($propElement){
        $header = $propElement->attributes[0]->nodeValue;
        if ($header == "Heading1"){
            return "color: red;";
        }
    }

    function property_numPr($propElement) {
        $tab = (int)$propElement->childNodes[0]->attributes[0]->nodeValue;
        //$num = $propElement->attributes[1]->nodeValue;
        $tabsize = 30; // px

        return "padding-left: " . $tabsize * $tab . "px;display:block";
    }
    

}

?>