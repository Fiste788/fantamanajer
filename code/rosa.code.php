<?php

require_once(INCDIR.'utente.inc.php');
require_once(CODEDIR.'upload.code.php');	//IMPORTO IL CODE PER EFFETTUARE IL DOWNLOAD
require_once(INCDIR.'punteggi.inc.php');
require_once(INCDIR.'giocatore.inc.php');
require_once(INCDIR.'voti.inc.php');

$giocatoreObj = new giocatore();
$punteggiObj = new punteggi();
$utenteObj = new utente();
$votiObj = new voti();

$squadra = NULL;
if(isset($_GET['squadra']))
	$squadra = $_GET['squadra'];

$contenttpl->assign('squadra',$squadra);
$contenttpl->assign('data', 0);

$classifica = $punteggiObj->getClassifica($_SESSION['idLega']);
foreach($classifica as $key => $val)
{
	if($squadra == $val['idUtente'])
	{
		$contenttpl->assign('media',substr($classifica[$key]['punteggioMed'],0,5));
		$contenttpl->assign('min',$classifica[$key]['punteggioMin']);
		$contenttpl->assign('max',$classifica[$key]['punteggioMax']);
	}
}
$contenttpl->assign('classifica',$classifica);
$contenttpl->assign('posizioni',$punteggiObj->getPosClassifica($_SESSION['idLega']));
if(isset($_POST['passwordnew']) && isset($_POST['passwordnewrepeat']) )
{
	if($_POST['passwordnew'] == $_POST['passwordnewrepeat'])
	{
		unset($_POST['passwordnewrepeat']);
		if( (isset($_POST['nomeProp'])) || (isset($_POST['cognome'])) || (isset($_POST['usernamenew'])) || (isset($_POST['mail'])) || (isset($_POST['nome'])) || (isset($_POST['passwordnew'])) )
			$contenttpl->assign('data',$utenteObj->changeData($_POST,$_SESSION['idSquadra']));
	}
	else
		$contenttpl->assign('data',1);
}
$elencoSquadre = $utenteObj->getElencoSquadre();
$contenttpl->assign('elencosquadre',$elencoSquadre);
$contenttpl->assign('squadradett',$utenteObj->getSquadraById($squadra));

$ruoli = array('P'=>'Por.','D'=>'Dif.','C'=>'Cen','A'=>'Att.');
$values = $giocatoreObj->getGiocatoriByIdSquadraWithStats($squadra);
if(($squadra != NULL) && ($values))
{

	$i = 0;
	$appo = 0;
	$mediaVoto = 0;
	$mediaPartite = 0;
	$mediaGol = 0;
	$mediaAssist = 0;
	$mediaMagic=0;
	foreach($values as $key => $val)
	{
		$giocatori[$i]['idGioc'] = $val['idGioc'];
		$giocatori[$i]['nome'] = $val['cognome'] . " " . $val['nome'];
		$giocatori[$i]['ruolo'] = $ruoli[$val['ruolo']];
		$giocatori[$i]['club'] = $val['nomeClub'];
		$medieVoti = $votiObj->getMedieVoto($giocatori[$i]['idGioc']);
		$giocatori[$i]['votiAll'] = $medieVoti['mediaPunti'];
		$giocatori[$i]['voti'] = substr($giocatori[$i]['votiAll'],0,4);
		$giocatori[$i]['partite'] = $val['presenze'];
		$giocatori[$i]['partiteeff'] = $medieVoti['presenze'];
		$giocatori[$i]['gol'] = $val['gol'];
		$giocatori[$i]['assist'] = $val['assist'];
		$giocatori[$i]['votoeffAll'] = $medieVoti['mediaVoti'];
		$giocatori[$i]['votoeff'] = substr($giocatori[$i]['votoeffAll'],0,4);
		$mediaVoto += $giocatori[$i]['votoeffAll'];
		$mediaMagic += $giocatori[$i]['votiAll'];
		$mediaPartite += $giocatori[$i]['partite'];
		$mediaGol += $giocatori[$i]['gol'];
		$mediaAssist += $giocatori[$i]['assist'];
		$i++;
	}
	$contenttpl->assign('mediaVoto',substr($mediaVoto / $i,0,4));
	$contenttpl->assign('mediaVotoAll',$mediaVoto / $i);
	$contenttpl->assign('mediaMagicAll',$mediaMagic / $i);
	$contenttpl->assign('mediaMagic',substr($mediaMagic / $i,0,4));
	$contenttpl->assign('mediaPartite',substr($mediaPartite / $i,0,4));
	$contenttpl->assign('mediaPartiteAll',$mediaPartite / $i);
	$contenttpl->assign('mediaGol',substr($mediaGol / $i,0,4));
	$contenttpl->assign('mediaGolAll',$mediaGol / $i);
	$contenttpl->assign('mediaAssist',substr($mediaAssist / $i,0,4));
	$contenttpl->assign('mediaAssistAll',$mediaAssist / $i);
	$contenttpl->assign('giocatori',$giocatori);	
}

?>
