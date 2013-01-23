<?php

namespace Fantamanajer\Db\Table;

require_once(TABLEDIR . 'Schieramento.table.db.inc.php');

class Schieramento extends SchieramentoTable {

    public static function getSchieramentoById($idFormazione) {
        $q = "SELECT *
				FROM schieramento
				WHERE idFormazione = :idFormazione
				ORDER BY posizione";
        $exe = ConnectionFactory::getFactory()->getConnection()->prepare($q);
        $exe->bindValue(":idFormazione", $idFormazione, PDO::PARAM_INT);
        $exe->execute();
        FirePHP::getInstance()->log($q);
        return $exe->fetchAll(PDO::FETCH_CLASS,__CLASS__);
    }

    public function getVoto() {
        require_once(INCDBDIR . 'voto.db.inc.php');
        return Voto::getByGiocatoreAndGiornata($this->getIdGiocatore(), $this->getFormazione()->getIdGiornata());
    }

}

?>