<?php

/**
 * Classe de gestion des Factures
 * Manager : Model_factures
 *
 * @author Xanthellis - WILLEMIN François - http://www.xanthellis.com
 */
class Facture {

    protected $factureId;
    protected $factureDate;
    protected $factureClientId;
    protected $factureClient;
    protected $factureObjet;
    protected $factureTauxTVA;
    protected $factureTotalHT;
    protected $factureTotalTVA;
    protected $factureTotalTTC;
    protected $factureAffaireId;
    protected $factureAffaire;
    protected $factureSolde;
    protected $factureLignes;
    protected $factureModeReglement;
    protected $factureModeReglementText;
    protected $factureReglements;
    protected $factureAvoirs;
    protected $factureTotalAvoirs;

    public function __construct(array $valeurs = []) {
        /* Si on passe des valeurs, on hydrate l'objet */
        if (!empty($valeurs))
            $this->hydrate($valeurs);
    }

    public function hydrate(array $donnees) {
        foreach ($donnees as $key => $value):
            $method = 'set' . ucfirst($key);
            if (method_exists($this, $method))
                $this->$method($value);
        endforeach;

        $CI = & get_instance();
        $this->factureModeReglementText = $CI->xth->affModeReglement($this->factureModeReglement);
    }

    public function hydrateAffaire() {
        $CI = & get_instance();
        $this->factureAffaire = $CI->managerAffaires->getAffaireById($this->factureAffaireId);
    }

    public function hydrateClient() {
        $CI = & get_instance();
        $this->factureClient = $CI->managerClients->getClientById($this->factureClientId);
    }

    public function hydrateLignes() {
        $CI = & get_instance();
        $this->factureLignes = $CI->managerFactureLignes->liste(array('factureLigneFactureId' => $this->factureId));
    }

    public function hydrateReglements() {
        $CI = & get_instance();
        $this->factureReglements = $CI->managerReglements->liste(array('reglementFactureId' => $this->factureId, 'reglementUtile' => 1));
    }

    public function hydrateAvoirs() {
        $CI = & get_instance();
        $this->factureAvoirs = $CI->managerAvoirs->liste(array('avoirFactureId' => $this->factureId));
        if ($this->factureAvoirs):
            foreach ($this->factureAvoirs as $a):
                $this->factureTotalAvoirs += $a->getAvoirTotalHT();
            endforeach;
        endif;
    }

    function solde() {
        $totalPaye = 0;
        $this->hydrateReglements();
        if (!empty($this->factureReglements)):
            foreach ($this->factureReglements as $r):
                $totalPaye += $r->getReglementMontant();
            endforeach;
        endif;

        $this->hydrateAvoirs();
        if (!empty($this->factureAvoirs)):
            foreach ($this->factureAvoirs as $a):
                $totalPaye += $a->getAvoirTotalTTC();
            endforeach;
        endif;
        $this->setFactureSolde(round($this->getFactureTotalTTC() - $totalPaye, 2));
    }

    function getFactureTotalAvoirs() {
        return $this->factureTotalAvoirs;
    }

    function setFactureTotalAvoirs($factureTotalAvoirs) {
        $this->factureTotalAvoirs = $factureTotalAvoirs;
    }

    function getFactureId() {
        return $this->factureId;
    }

    function getFactureDate() {
        return $this->factureDate;
    }

    function getFactureClientId() {
        return $this->factureClientId;
    }

    function getFactureClient() {
        return $this->factureClient;
    }

    function getFactureObjet() {
        return $this->factureObjet;
    }

    function getFactureTauxTVA() {
        return $this->factureTauxTVA;
    }

    function getFactureTotalHT() {
        return $this->factureTotalHT;
    }

    function getFactureTotalTVA() {
        return $this->factureTotalTVA;
    }

    function getFactureTotalTTC() {
        return $this->factureTotalTTC;
    }

    function getFactureAffaireId() {
        return $this->factureAffaireId;
    }

    function getFactureAffaire() {
        return $this->factureAffaire;
    }

    function getFactureSolde() {
        return $this->factureSolde;
    }

    function getFactureLignes() {
        return $this->factureLignes;
    }

    function getFactureModeReglement() {
        return $this->factureModeReglement;
    }

    function getFactureModeReglementText() {
        return $this->factureModeReglementText;
    }

    function getFactureReglements() {
        return $this->factureReglements;
    }

    function setFactureId($factureId) {
        $this->factureId = $factureId;
    }

    function setFactureDate($factureDate) {
        $this->factureDate = $factureDate;
    }

    function setFactureClientId($factureClientId) {
        $this->factureClientId = $factureClientId;
    }

    function setFactureClient($factureClient) {
        $this->factureClient = $factureClient;
    }

    function setFactureObjet($factureObjet) {
        $this->factureObjet = $factureObjet;
    }

    function setFactureTauxTVA($factureTauxTVA) {
        $this->factureTauxTVA = $factureTauxTVA;
    }

    function setFactureTotalHT($factureTotalHT) {
        $this->factureTotalHT = $factureTotalHT;
    }

    function setFactureTotalTVA($factureTotalTVA) {
        $this->factureTotalTVA = $factureTotalTVA;
    }

    function setFactureTotalTTC($factureTotalTTC) {
        $this->factureTotalTTC = $factureTotalTTC;
    }

    function setFactureAffaireId($factureAffaireId) {
        $this->factureAffaireId = $factureAffaireId;
    }

    function setFactureAffaire($factureAffaire) {
        $this->factureAffaire = $factureAffaire;
    }

    function setFactureSolde($factureSolde) {
        $this->factureSolde = $factureSolde;
    }

    function setFactureLignes($factureLignes) {
        $this->factureLignes = $factureLignes;
    }

    function setFactureModeReglement($factureModeReglement) {
        $this->factureModeReglement = $factureModeReglement;
    }

    function setFactureModeReglementText($factureModeReglementText) {
        $this->factureModeReglementText = $factureModeReglementText;
    }

    function setFactureReglements($factureReglements) {
        $this->factureReglements = $factureReglements;
    }

    function getFactureAvoirs() {
        return $this->factureAvoirs;
    }

    function setFactureAvoirs($factureAvoirs) {
        $this->factureAvoirs = $factureAvoirs;
    }

}
