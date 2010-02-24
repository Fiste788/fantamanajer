<?php 
require_once(INCDIR . 'fileSystem.inc.php');
include_once(INCDIR . 'createZip.inc.php');

$fileSystemObj = new fileSystem();
FB::log($_POST);
if(isset($_POST['type']))
{
	if($_POST['type'] == 'csv')
		$filesVoti = $fileSystemObj->getFileIntoFolder(str_replace('/ajax','',VOTICSVDIR));
	else
		$filesVoti = $fileSystemObj->getFileIntoFolder(str_replace('/ajax','',VOTIXMLDIR));
	sort($filesVoti); 
	
	$contentTpl->assign('filesVoti',$filesVoti);
}

if(isset($_POST['giornata']) && !empty($_POST['giornata']) && isset($_POST['type']))
{
	if($_POST['type'] == 'csv')
		$path = VOTICSVDIR;
	else
		$path = VOTIXMLDIR;
	if($_POST['giornata'] == "all")
	{
		$createZip = new createZip();
		$path = $createZip->createZipFromDir($path,'voti' . strtoupper($_POST['type']));
		$createZip->forceDownload($path,"voti" . strtoupper($_POST['type']) . ".zip");
		@unlink($path);
	}
	else
	{
		header("Content-type: text/csv");
		header("Content-Disposition: attachment;filename=" . basename($_POST['giornata']));
		header("Content-Transfer-Encoding: binary");
		header("Expires: 0");
		header("Pragma: no-cache");
		readfile($path . $_POST['giornata']);
	}
	die();
}
?>
