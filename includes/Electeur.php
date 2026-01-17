<?php
/**
 * Classe Electeur - Représente un électeur
 * Principe SOLID : Single Responsibility (gestion données électeur uniquement)
 */
class Electeur {
    private $id;
    private $email;
    private $nom;
    private $prenom;
    private $sexe;
    private $nationalite;
    private $date_naissance;
    private $age;
    private $id_college;
    private $has_voted;
    
    public function __construct($data) {
        $this->id = $data['ID_electeur'] ?? null;
        $this->email = $data['email'] ?? null;
        $this->nom = $data['nom'] ?? null;
        $this->prenom = $data['prenom'] ?? null;
        $this->sexe = $data['sexe'] ?? null;
        $this->nationalite = $data['nationalite'] ?? null;
        $this->date_naissance = $data['date_naissance'] ?? null;
        $this->age = $data['age'] ?? null;
        $this->id_college = $data['id_college'] ?? null;
        $this->has_voted = $data['has_voted'] ?? 0;
    }
    
    // Getters
    public function getId() { return $this->id; }
    public function getEmail() { return $this->email; }
    public function getNom() { return $this->nom; }
    public function getPrenom() { return $this->prenom; }
    public function getSexe() { return $this->sexe; }
    public function getNationalite() { return $this->nationalite; }
    public function getDateNaissance() { return $this->date_naissance; }
    public function getAge() { return $this->age; }
    public function getIdCollege() { return $this->id_college; }
    public function hasVoted() { return $this->has_voted == 1; }
    
    /**
     * Retourne le nom complet de l'électeur
     */
    public function getNomComplet() {
        return trim($this->prenom . ' ' . $this->nom);
    }
    
    /**
     * Vérifie si l'électeur est majeur
     */
    public function isMajeur() {
        return $this->age >= 18;
    }
}
?>
