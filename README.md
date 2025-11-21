# Plateforme MMA - Élection du Combattant de l'Année 2025

## Description

Plateforme de vote transparente permettant d'élire le meilleur combattant MMA de l'année. Le système rassemble trois types d'électeurs (public, journalistes, coachs) avec une pondération équilibrée pour garantir un résultat représentatif.

## Architecture
- **Backend**: PHP 
- **Frontend**: HTML, Tailwind CSS, JavaScript
- **Base de données**: MySQL 8.0
- **Serveur**: Apache (XAMPP)

## Rôles et permissions

### Visiteur (non connecté)
- Consultation des informations générales
- Visualisation du processus de vote
- Accès à la page de contact
- Inscription en tant qu'électeur public

### Électeur (utilisateur connecté)


### Candidat


### Admin



## Structure des fichiers

```
sae3_mma_charpentier_errebache/
├── index.php                    # Page d'accueil avec sections home
├── README.md                    # Documentation du projet
├── includes/
│   ├── config.php              # Configuration de l'application et BDD
│   ├── functions.php           # Fonctions utilitaires
│   ├── header.php             # Template header avec navigation
│   └── footer.php             # Template footer
├── pages/
│   ├── accueil.php            # Page d'accueil alternative
│   ├── candidats.php          # Liste des candidats MMA
│   ├── candidat.php           # Profil détaillé d'un candidat
│   ├── contact.php            # Page de contact et FAQ
│   ├── login.php              # Connexion (public/journaliste/coach)
│   ├── register.php           # Inscription électeur public
│   ├── profil.php             # Profil utilisateur connecté
│   └── home/                  # Sections modulaires de l'accueil
│       ├── heroSection.php    # Section principale avec CTA
│       ├── countdown.php      # Compteur temps restant
│       ├── howItwork.php      # Explication du processus
│       ├── ponderation.php    # Détails de la pondération
│       └── contactSection.php # Section contact rapide
├── admin/
│   └── index.php              # Interface d'administration
├── assets/
│   ├── css/
│   │   └── style.css          # Styles personnalisés + Tailwind
│   ├── js/
│   │   ├── main.js            # Scripts JavaScript principaux
│   │   └── countdown.js       # Logique du compteur
│   └── img/                   # Images et ressources visuelles
├── images/                    # Photos des candidats (uploads)
└── database/
    └── schema.sql             # Structure et données de la BDD
```

## Installation

### 1. Prérequis
- XAMPP (Apache + MySQL + PHP)
- Navigateur web

### 2. Configuration de la base de données
1. Démarrer XAMPP (Apache + MySQL)
2. Accéder à phpMyAdmin (http://localhost/phpmyadmin)
3. Créer la base de données `mma_platform`
4. Importer le fichier `database/schema.sql`
5. Vérifier que la base `mma_platform` est bien implémentée

### 3. Configuration de l'application
1. Placer le dossier du projet dans `C:\xampp\htdocs\`
2. Modifier `includes/config.php` si nécessaire (paramètres BDD)

### 4. Accès à l'application
- URL principale: http://localhost/sae3_mma_charpentier_errebache
- Administration: http://localhost/sae3_mma_charpentier_errebache/admin (à venir)

## Palette de couleurs

### Configuration Tailwind CSS personnalisée

```javascript
// tailwind.config.js - Extension de la configuration
module.exports = {
  theme: {
    extend: {
      colors: {
        // Palette MMA principale
        'rouge': '#DE1315',      // Rouge - Actions principales, CTA
        'bleu': '#1847C7',       // Bleu - Actions secondaires, liens
        'dore': '#D5A845',       // Doré - Éléments premium, récompenses
        'gris-clair': '#F3F3F3', // Gris clair - Arrière-plans
        'noir': '#19191E',       // Noir - Textes et contrastes

        // Couleurs d'état et feedback
        'success': '#10B981',    // Vert - Succès, validation
        'warning': '#F59E0B',    // Orange - Avertissement
        'error': '#EF4444',      // Rouge - Erreurs
        'info': '#3B82F6',       // Bleu - Information
      },
      fontFamily: {
        // Typographies du projet
        'bebas': ['Bebas Neue', 'sans-serif'],  // Titres et headers
        'anek': ['Anek Bangla', 'sans-serif'],  // Texte de contenu
      }
    }
  }
}
```
## Équipe de développement
- **Lucas Charpentier** - Développement Frontend/Backend
- **Ahmed Errebache** - Développement Frontend/Backend

**Projet**: SAÉ S3 - IUT de Saint-Dié-des-Vosges  
**Année**: 2025