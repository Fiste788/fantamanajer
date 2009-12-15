<?php 
require_once(INCDIR . 'trasferimento.db.inc.php');
require_once(INCDIR . 'utente.db.inc.php');
require_once(INCDIR . 'giocatore.db.inc.php');
require_once(INCDIR . 'selezione.db.inc.php');
require_once(INCDIR . 'punteggio.db.inc.php');
require_once(INCDIR . 'evento.db.inc.php');
require_once(INCDIR . 'mail.inc.php');

$trasferimentoObj = new trasferimento();
$utenteObj = new utente();
$giocatoreObj = new giocatore();
$selezioneObj = new selezione();
$punteggioObj = new punteggio();
$eventoObj = new evento();
$mailObj = new mail();
$mailContentObj = new Savant3();

$filterSquadra = $_SESSION['idSquadra'];
$acquisto = NULL;
$lasciato = NULL;
$flag = 0;
$appo = 0;
if(isset($_GET['squadra']))
	$filterSquadra = $_GET['squadra'];
if(isset($_POST['squadra']))
	$filterSquadra = $_POST['squadra'];

$ruoli = array('P'=>'Portiere','D'=>'Difensori','C'=>'Centrocampisti','A'=>'Attaccanti');

$trasferimenti = $trasferimentoObj->getTrasferimentiByIdSquadra($filterSquadra);
$numTrasferimenti = count($trasferimenti);
$playerFree = array();
foreach($ruoli as $key => $val)
	$playerFree = array_merge($playerFree,$giocatoreObj->getFreePlayer($key,$_SESSION['legaView']));

$trasferiti = $giocatoreObj->getGiocatoriTrasferiti($_SESSION['idSquadra']);
/*
 * Quì effettuo il trasferimento diretto
 */ 
if($trasferiti != FALSE)
{
	$appo = 1;
	$numTrasferiti = 0;
	$contenttpl->assign('trasferiti',$trasferiti);
	foreach($trasferiti as $key => $val)
		$freePlayerByRuolo[$val['idGioc']] = $giocatoreObj->getFreePlayer($val['ruolo'],$_SESSION['idLega']);
	$contenttpl->assign('freePlayerByRuolo',$freePlayerByRuolo);
	foreach($trasferiti as $masterKey => $masterVal)
	{
		if($numTrasferimenti < $_SESSION['datiLega']['numTrasferimenti'] )
		{
			if(isset($_POST['submit']) && $_POST['submit'] == 'OK')
			{
				if(isset($_POST['acquista'][$masterKey]) && !empty($_POST['acquista'][$masterKey]) )
				{
					$giocatoreAcquistato = $giocatoreObj->getGiocatoreById($_POST['acquista'][$masterKey]);
					$flag = 0;
					foreach($playerFree as $key => $val)
						if($val['idGioc'] == $_POST['acquista'][$masterKey])
							$flag = 1;
					if($flag != 0)
					{
						$giocatoreLasciato = $giocatoreObj->getGiocatoreById($masterVal['idGioc']);
						if($giocatoreAcquistato[$_POST['acquista'][$masterKey]]['ruolo'] == $giocatoreLasciato[$masterVal['idGioc']]['ruolo'])
						{
							$trasferimentoObj->transfer($masterVal['idGioc'],$_POST['acquista'][$masterKey],$filterSquadra,$_SESSION['idLega']);
							$appo = 0;
							$numTrasferiti ++;
							$selezione = $selezioneObj->getSelezioneByIdSquadra($_SESSION['idSquadra']);
							if($selezione['giocOld'] == $masterVal['idGioc']);
								$selezioneObj->unsetSelezioneByIdSquadra($filterSquadra);
							$trasferimenti = $trasferimentoObj->getTrasferimentiByIdSquadra($filterSquadra);
							$numTrasferimenti = count($trasferimenti);
							foreach($ruoli as $key => $val)
								$playerFree = array_merge($playerFree,$giocatoreObj->getFreePlayer($key,$_SESSION['idLega']));
							$message->success('Trasferimento effettuato correttamente');
						}
						else
							$message->warning('I giocatori devono avere lo stesso ruolo');
					}
					else
						$message->error('Il giocatore non è libero');
				}
				elseif($appo == 1)
					$message->error('Non hai compilato correttamente');
			}
		}
		else
			$message->error('Hai raggiunto il limite di trasferimenti');
	}
	if($numTrasferiti == count($trasferiti))
	{
		unset($contenttpl->generalMessage);
		unset($contenttpl->trasferiti);
		unset($_POST);
	}
}



/*
 * Quì seleziono il giocatore per il trasferimento
 */   
if($_SESSION['logged'] && $_SESSION['idSquadra'] == $filterSquadra)
{
	if($numTrasferimenti < $_SESSION['datiLega']->numTrasferimenti )
	{
		$selezione = $selezioneObj->getSelezioneByIdSquadra($_SESSION['idSquadra']);

		if(!empty($selezione))
		{
			$acquisto = $selezione['giocNew'];
			$lasciato = $selezione['giocOld'];
		}
		if(isset($_POST['acquista']))
			$acquisto = $_POST['acquista'];
		if(isset($_POST['lascia']))
			$lasciato = $_POST['lascia'];

		if(isset($_POST['submit']) && $_POST['submit'] == 'Cancella acq.')
		{
			$selezioneObj->unsetSelezioneByIdSquadra($_SESSION['idSquadra']);
			$message->success('Cancellazione eseguita con successo');
			$acquisto = NULL;
			$lasciato = NULL;
		}
	
		if($acquisto != NULL)
			$acquistoDett = $giocatoreObj->getGiocatoreById($acquisto);
		if($lasciato != NULL)
			$lasciatoDett = $giocatoreObj->getGiocatoreById($lasciato);
		$numSelezioni = $selezioneObj->getNumberSelezioni($filterSquadra);

		if(isset($_POST['submit']) && $_POST['submit'] == 'OK')
		{
			if($lasciatoDett[$lasciato]->ruolo == $acquistoDett[$acquisto]->ruolo)
			{
				if(isset($_POST['acquista']) && !empty($_POST['acquista']) && isset($_POST['lascia']) && !empty($_POST['lascia']) && ($selezione['giocNew'] != $acquisto || $selezione['giocOld'] != $lasciato))
				{
					if($numSelezioni < $_SESSION['datiLega']->numSelezioni)
					{	
						$filterSquadraOld = $selezioneObj->checkFree($acquisto,$_SESSION['idLega']);
						if($filterSquadraOld != FALSE)
						{
							$classifica = $punteggioObj->getClassificaByGiornata($_SESSION['idLega'],GIORNATA);
							$squadre = $filterSquadraObj->getElencoSquadre();
							foreach ($classifica as $key => $val)
								$classificaNew[$key] = $val[0];
							$posSquadraOld =  array_search($filterSquadraOld,$classificaNew);
							$posSquadraNew = array_search($_SESSION['idSquadra'],$classificaNew);
							if($posSquadraNew > $posSquadraOld)
							{
								$selezioneObj->updateGioc($acquisto,$lasciato,$_SESSION['idLega'],$_SESSION['idSquadra']);
								$mailContent->assign('giocatore',$acquistoDett[$acquisto]->nome . ' ' . $acquistoDett[$acquisto]->cognome);
								$appo = $squadre[$acquistoDett[$acquisto]->idSquadraAcquisto];
								$mailObj->sendEmail($squadre[$appo]->mail,$mailContent->fetch(MAILTPLDIR . 'mailGiocatoreRubato.tpl.php'),'Giocatore rubato!');
							}
							else
							{
								$message->warning('Un altra squadra inferiore di te ha già selezionato questo giocatore');
								$acquisto = NULL;
								$lasciato = NULL;
								$flag = 1;
							}
						}
						else
						{
							$selezioneObj->updateGioc($acquisto,$lasciato,$_SESSION['idLega'],$_SESSION['idSquadra']);
							$eventoObj->addEvento('2',$_SESSION['idSquadra'],$_SESSION['idLega']);
							$message->success('Operazione eseguita con successo');
						}
					}
					else
					{
						$flag = 1;
						$message->warning('Hai già cambiato ' . $_SESSION['datiLega']->numSelezioni . ' volte il tuo acquisto');
					}
			
				}
				elseif($selezione['giocNew'] == $acquisto && $selezione['giocOld'] == $lasciato)
					$messagge->warning('Hai già selezionato questi giocatori per l\'acquisto');
				else
					$message->error('Non hai compilato correttamente');
			}
			else
				$message->error('I giocatori devono avere lo stesso ruolo');
		}
		if($flag == 1)
		{
			$acquisto = $selezione['giocNew'];
			$lasciato = $selezione['giocOld'];
		}
		$contenttpl->assign('giocAcquisto',$acquisto);
		$contenttpl->assign('giocLasciato',$lasciato);
	
		$giocatoreAcquistatoOld = $selezioneObj->getSelezioneByIdSquadra($_SESSION['idSquadra']);	
		if(!empty($giocatoreAcquistatoOld))
			$contenttpl->assign('isset',$giocatoreAcquistatoOld);
	}
	else
		$message->error('Hai raggiunto il limite di trasferimenti');
}
$contenttpl->assign('ruoli',$ruoli);
$contenttpl->assign('squadra',$filterSquadra);
$contenttpl->assign('giocSquadra',$giocatoreObj->getGiocatoriByIdSquadra($filterSquadra));
$contenttpl->assign('freePlayer',$playerFree);
$contenttpl->assign('trasferimenti',$trasferimenti);
$contenttpl->assign('numTrasferimenti',$numTrasferimenti);
$operationtpl->assign('elencoSquadre',$utenteObj->getElencoSquadreByLega($_SESSION['legaView']));
$operationtpl->assign('squadra',$filterSquadra);
?>
