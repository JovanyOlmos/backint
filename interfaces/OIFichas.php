<?php
namespace backint\interfaces;
require_once("./core/OInterface.php");
require_once("./definitions/SQLFormat.php");
use backint\core\OInterface;
class OIFichas extends OInterface {
    private $folio, $fecha, $idpersona, $idusuario, $idsucursal, $idpoliza, $idoperacionfuente, 
        $efectivo, $cheques, $transferencia, $cancelada, $referencia, $idfactura, $pagada, $comentario,
        $nuevo, $masnuevo;

    public function __construct(string $DBTableName, string $columnNameFromIdTable) {
        parent::__construct($DBTableName, $columnNameFromIdTable);
        $this->folio = $this->addField("folio", VARCHAR);
        $this->fecha = $this->addField("fecha", DATETIME);
        $this->idpersona = $this->addField("idpersona", INT);
        $this->idusuario = $this->addField("idusuario", INT);
        $this->idsucursal = $this->addField("idsucursal", INT);
        $this->idpoliza = $this->addField("idpoliza", INT);
        $this->idoperacionfuente = $this->addField("idoperacionfuente", INT);
        $this->efectivo = $this->addField("efectivo", DECIMAL);
        $this->cheques = $this->addField("cheques", DECIMAL);
        $this->transferencia = $this->addField("transferencia", DECIMAL);
        $this->cancelada = $this->addField("cancelada", BOOLEAN);
        $this->referencia = $this->addField("referencia", VARCHAR);
        $this->idfactura = $this->addField("idfactura", INT);
        $this->pagada = $this->addField("pagada", BOOLEAN);
        $this->comentario = $this->addField("comentario", VARCHAR);
        $this->nuevo = $this->addField("nuevo", VARCHAR);
        $this->masnuevo = $this->addField("masnuevo", VARCHAR);
    }

    public function getFolio() {
        return $this->folio;
    }

    public function getFecha() {
        return $this->fecha;
    }

    public function getIdPersona() {
        return $this->idpersona;
    }

    public function getIdUsuario() {
        return $this->idusuario;
    }

    public function getIdSucursal() {
        return $this->idsucursal;
    }

    public function getIdPoliza() {
        return $this->idpoliza;
    }

    public function getIdOperacionFuente() {
        return $this->idoperacionfuente;
    }

    public function getEfectivo() {
        return $this->efectivo;
    }

    public function getCheques() {
        return $this->cheques;
    }

    public function getTransferencia() {
        return $this->transferencia;
    }

    public function getCancelada() {
        return $this->cancelada;
    }

    public function getReferencia() {
        return $this->referencia;
    }

    public function getIdFactura() {
        return $this->idfactura;
    }

    public function getPagada() {
        return $this->pagada;
    }

    public function getComentario() {
        return $this->comentario;
    }

    public function getNuevo() {
        return $this->nuevo;
    }

    public function getMasNuevo() {
        return $this->masnuevo;
    }
}
?>