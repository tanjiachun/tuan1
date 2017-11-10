<?php
if(!defined("InMall")) {
    exit("Access Invalid!");
}

class Security {
	public function fliterXss($input) {
        $patterns = array("/<script.*>.*<\\/script>/siU", "/<iframe.*>.*<\\/iframe>/siU", "/<input(.*submit.*)(\\>|\\/\\>)/siUe", "/<form(.*action.*)>/siUe", "/on(abort|activate|afterprint|afterupdate|beforeactivate|beforecopy|beforecut|beforedeactivate|beforeeditfocus|beforepaste|beforeprint|beforeunload|beforeupdate|blur|bounce|cellchange|change|click|contextmenu|controlselect|copy|cut|dataavailable|datasetchanged|datasetcomplete|dblclick|deactivate|drag|dragend|dragenter|dragleave|dragover|dragstart|drop|error|errorupdate|filterchange|finish|focus|focusin|focusout|help|keydown|keypress|keyup|layoutcomplete|load|losecapture|mousedown|mouseenter|mouseleave|mousemove|mouseout|mouseover|mouseup|mousewheel|move|moveend|movestart|paste|propertychange|readystatechange|reset|resize|resizeend|resizestart|rowenter|rowexit|rowsdelete|rowsinserted|scroll|select|selectionchange|selectstart|start|stop|submit|unload)\\s*=\\s*('|\")[^\"]*('|\")/i");
        $replacements = array("", "", "", "", "");
        return preg_replace($patterns, $replacements, $input);
    }

    public function fliterSql($input) {
        $patterns = array("/(and|or)\\b.+?(>|<|=|in|like)|\\/\\*.+?\\*\\/|<\\s*script\\b|\\bEXEC\\b|UNION.+?SELECT|UPDATE.+?SET|(INSERT|REPLACE)\\s+INTO.+?VALUES|(INSERT|REPLACE)\\s+INTO.+?SET|(SELECT|DELETE).+?FROM|(CREATE|ALTER|DROP|TRUNCATE)\\s+(TABLE|DATABASE)/i");
        $replacements = array("");
        $input = preg_replace($patterns, $replacements, $input);
		return $input;
    }
	
	public function fliterHtmlSpecialChars($string) {
        $string = preg_replace("/&amp;((#(\\d{3,5}|x[a-fA-F0-9]{4})|[a-zA-Z][a-z0-9]{2,5});)/", "&\\1", str_replace(array("&", "\"", "<", ">"), array("&amp;", "&quot;", "&lt;", "&gt;"), $string));
        return $string;
    }

    public function validateForInput($array, $ignore = array()) {		
        if(!empty($array)) {
            while(list($k, $v) = each($array)) {
                if(is_string($v)) {
					$v = trim($v);
                    if(!in_array($k, $ignore)) {
						$v = $this->fliterxss($v);
						$v = $this->fliterSql($v);	
					}
					$v = $this->fliterhtmlspecialchars($v);
					$array[$k] = $v;
                } elseif(is_array($v)) {
                    $array[$k] = $this->validateForInput($v);
                }
            }
            return $array;
        }
        return false;
    }
}
?>
