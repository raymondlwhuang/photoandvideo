<?php

class Bootstrap extends Controller{
    function __construct(){
            
        $url = explode('/', rtrim($_SERVER['REQUEST_URI'],'/'));

        require ('controllers/Page.php');
        
        
        $controller = new Page();

        if (rtrim($_SERVER['REQUEST_URI']) == '/'){
            $controller->load_page('index', false);
        }    
		elseif (isset($url[1])){
				$controller->load_page($url[1], false);	
		}		
    }
}
?>