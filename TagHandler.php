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

    function default ($domElement){
        $name = $domElement->nodeName;
        $name = remove_namespace($name);
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

    function property_glow($propElement){
        $colorElement = $propElement->getElementsByTagName("srgbClr");
        $colorVal = $colorElement[0]->attributes[0]->nodeValue;
        if (!$colorVal) echo "fail";
        return "background-color: " . $colorVal;
    }

}

?>