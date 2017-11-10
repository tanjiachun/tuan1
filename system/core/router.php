<?php
if(!defined("InMall")) {
    exit("Access Invalid!");
}

final class Router {
    public static function run() {
        self::getcontroller();
        return;
    }
	
	private static function getController() {
		if(!defined("ProjectName")) {
        	$act_file = realpath(MALL_ROOT.'/control/'.$_GET['act'].'.php');
		} else {
			$act_file = realpath(MALL_ROOT.'/'.ProjectName.'/control/'.$_GET['act'].'.php');
		}
		
		if(is_file($act_file)) {
            require_once($act_file);
            $class_name = $_GET['act'].'Control';
            if(class_exists($class_name)) {
                $main = new $class_name();
                $function = $_GET['op'].'Op';
                if(method_exists($main, $function)) {
                    $main->$function();
                } elseif(method_exists($main, 'indexOp')) {
                    $main->indexOp();
                } else {
                    $error = "Router Error: function ".$function." not in {$class_name}!";
                   	echo $error;
					exit();
                }
            } else {
            	$error = "Router Error: class ".$class_name." isn't exists!";          
            	echo $error;
				exit();
			}
        } else {
        	//$error = "Router Error: access file isn't exists!";   
			echo $_GET['act'];			
        	//echo $error;
			exit();
		}
    }
}

?>
