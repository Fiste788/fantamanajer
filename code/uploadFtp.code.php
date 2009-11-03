<?php 
require_once(INCDIR.'fileSystem.inc.php');

$fileSystemObj = new fileSystem();

$root = ".";
$files = directoryToArray($root, true);

function directoryToArray($directory, $recursive) {
	$array_items = array();
	$path = "httpdocs/test2/";
	$connection = ftp_connect("develop.fantamanajer.it", 21) or die('Server non disponibile.');
	ftp_login($connection, "developuser", "fantadevelop") or die('Username o password errati.');

	if ($handle = opendir($directory)) {
		while (false !== ($file = readdir($handle))) {
			if ($file != "." && $file != ".." && $file != ".svn" && $file != "imgs") {
				if (is_dir($directory. "/" . $file)) {
					$appo = $directory. "/" . $file;
					$array_items[] = preg_replace("/\/\//si", "/", $directory . "/" . $file);
					if(substr($appo,2) == "./")
					ftp_mkdir($connection,$path. substr($appo,2));
					else
					ftp_mkdir($connection,$path.$appo);
					echo "creo cartella:" . $path. substr($appo,2) . "<br />";
					
					flush();
					if($recursive) {
						$array_items = array_merge($array_items, directoryToArray($directory. "/" . $file, $recursive));
					}
					$file = $directory . "/" . $file;
				} else {
					$file = $directory . "/" . $file;
					echo $file;
					//echo mime_content_type($file);
					if(substr(mime_content_type($file), 4) == 'text')
						echo ftp_put($connection, $path.substr($file,2), $file, FTP_ASCII);
					else
						echo ftp_put($connection, $path.substr($file,2), $file, FTP_BINARY);
					echo "creo file: " . $file . "      in   " . $path.substr($file,2) . "<br />";
					flush();
					$array_items[] = preg_replace("/\/\//si", "/", $file);
				}
			}
		}
		closedir($handle);
	}
	return $array_items;
}
 
 echo "<pre>".print_r($files,1)."</pre>";


?>
