<?php 
require_once(INCDIR."articolo.inc.php");
require_once(INCDIR."utente.inc.php");
require_once(INCDIR."emoticon.inc.php");

$emoticonObj = new emoticon();
$articoloObj = new articolo();
$utenteObj = new utente();

$getGiornata = GIORNATA;
if (!empty($_GET['giorn']))
	$getGiornata = $_GET['giorn'];
if (!empty($_POST['giorn']))
	$getGiornata = $_POST['giorn'];
$contenttpl->assign('getGiornata',$getGiornata);

$articoloObj->setidgiornata($getGiornata);
$articoloObj->setidlega($_SESSION['legaView']);

$articolo = $articoloObj->select($articoloObj,'=','*');
if($articolo != FALSE)
	foreach ($articolo as $key => $val)
		$articolo[$key]['text'] = $emoticonObj->replaceEmoticon($val['text'],IMGSURL.'emoticons/');
$contenttpl->assign('articoli',$articolo);


$contenttpl->assign('squadre',$utenteObj->getElencoSquadreByLega($_SESSION['legaView']));
$giornateWithArticoli = $articoloObj->getGiornateArticoliExist($_SESSION['legaView']);
if($giornateWithArticoli != FALSE)
{
	rsort($giornateWithArticoli);
	if(!in_array($giornata,$giornateWithArticoli))
		array_unshift($giornateWithArticoli,$giornata);
	$key = array_search($getGiornata,$giornateWithArticoli);
}
else
	$giornateWithArticoli = $key = FALSE;

$operationtpl->assign('giornateWithArticoli',$giornateWithArticoli);
if($key > 0)
{
	if(isset($giornateWithArticoli[$key+1]))
		$operationtpl->assign('giornprec',$giornateWithArticoli[$key+1]);
	else
		$operationtpl->assign('giornprec',FALSE);
	$operationtpl->assign('giornsucc',$giornateWithArticoli[$key-1]);
}
elseif(($key == 0 || $giornata == $getGiornata) && count($giornateWithArticoli) != 1)
{
	$operationtpl->assign('giornprec',$giornateWithArticoli[$key+1]);
	$operationtpl->assign('giornsucc',FALSE);
}
elseif(!$key)
{
	$operationtpl->assign('giornprec',FALSE);
	$operationtpl->assign('giornsucc',FALSE);
}

$operationtpl->assign('articoloExist',1);
$operationtpl->assign('getGiornata',$getGiornata);
if(isset($_SESSION['idSquadra']))
{
	$articoloObj->setidgiornata($getGiornata);
	$articoloObj->setidsquadra($_SESSION['idSquadra']);
	$articoloObj->setidlega($_SESSION['idLega']);
	$articoloExist = $articoloObj->select($articoloObj,'=','*');
	if(!empty($articoloExist))
		$contenttpl->assign('articoloExist',0);
}
?>
