<?
// Main content

//Check what component must be loaded
	$location = $_GET["mod"]; //----In which module are we now? Decide which module to load
	if(empty($location)){ // If no mod detected, load home module instead
		$location = $compdefault; 
	} 
///////////////	
	
	include "Components/" . $location . "/com_setting.php"; //---Load component's setting
	
// Load component's specific language file	
	if(file_exists("Language/" . $slang . "/com_". $location .".php")){
		include "Language/" . $slang . "/com_". $location .".php"; //---Load system's language file
	} else {
		include "Language/" . $compdeflang . "/com_". $location .".php"; //---Load component's default language file file if no system language file exist for the component
	}
	
	include "Components/" . $location . "/index.php"; //---Load Componet
	

?>
