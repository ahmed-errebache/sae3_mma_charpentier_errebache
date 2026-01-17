<?php
/**
 * Classe Candidat - Représente un candidat
 * Principe SOLID : Single Responsibility (gestion données candidat uniquement)
 */
class Candidat {
    private $id;
    private $email;
    private $nom;
    private $prenom;
    private $surnom;
    private $nationalite;
    private $palmares;
    private $photo_profil;
    private $compte_verifie;
    private $compte_actif;
    private $mdp_provisoire;
    
    public function __construct($data) {
        $this->id = $data['ID_candidat'] ?? null;
        $this->email = $data['email'] ?? null;
        $this->nom = $data['nom'] ?? null;
        $this->prenom = $data['prenom'] ?? null;
        $this->surnom = $data['surnom'] ?? null;
        $this->nationalite = $data['nationalite'] ?? null;
        $this->palmares = $data['palmares'] ?? null;
        $this->photo_profil = $data['photo_profil'] ?? null;
        $this->compte_verifie = $data['compte_verifie'] ?? 0;
        $this->compte_actif = $data['compte_actif'] ?? 1;
        $this->mdp_provisoire = $data['mdp_provisoire'] ?? 1;
    }
    
    // Getters
    public function getId() { return $this->id; }
    public function getEmail() { return $this->email; }
    public function getNom() { return $this->nom; }
    public function getPrenom() { return $this->prenom; }
    public function getSurnom() { return $this->surnom; }
    public function getNationalite() { return $this->nationalite; }
    public function getPalmares() { return $this->palmares; }
    public function getPhotoProfile() { return $this->photo_profil; }
    public function isCompteVerifie() { return $this->compte_verifie == 1; }
    public function isCompteActif() { return $this->compte_actif == 1; }
    public function hasMdpProvisoire() { return $this->mdp_provisoire == 1; }
    
    /**
     * Retourne le nom complet du candidat
     */
    public function getNomComplet() {
        return trim($this->prenom . ' ' . $this->nom);
    }
    
    /**
     * Retourne le nom d'affichage (surnom ou nom complet)
     */
    public function getNomAffichage() {
        return $this->surnom ? $this->surnom : $this->getNomComplet();
    }
    
    /**
     * Retourne le palmarès décodé
     */
    public function getPalmaresDecoded() {
        if ($this->palmares) {
            return json_decode($this->palmares, true);
        }
        return ['victoires' => 0, 'defaites' => 0, 'egalites' => 0, 'no_contest' => 0];
    }
    
    /**
     * Retourne le ratio victoires/combats
     */
    public function getRatioVictoires() {
        $palmares = $this->getPalmaresDecoded();
        $total = $palmares['victoires'] + $palmares['defaites'] + $palmares['egalites'];
        
        if ($total == 0) {
            return 0;
        }
        
        return round(($palmares['victoires'] / $total) * 100, 1);
    }
}
?>
