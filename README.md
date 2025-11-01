# Plateforme MMA - MVP 1

## Description


## Architecture
- **Backend**: PHP 
- **Frontend**: HTML, CSS, JavaScript 
- **Base de données**: MySQL
- **Serveur**: Apache (XAMPP)

## Rôles et permissions

### Visiteur (non connecté)


### Électeur (utilisateur connecté)


### Candidat


### Admin


## Structure des fichiers

```
MMA/
├── index.php                 # Page d'accueil
├── includes/
│   ├── config.php           # Configuration de l'application
│   ├── functions.php        # Fonctions utilitaires
│   ├── header.php          # Template header
│   └── footer.php          # Template footer
├── pages/
│   ├── candidats.php       # Liste des candidats
│   ├── candidat.php        # Profil d'un candidat
│   ├── login.php           # Connexion
│   ├── register.php        # Inscription électeur
│   ├── contact.php         # Page de contact
│   └── logout.php          # Déconnexion
├── admin/
│   └── index.php           # Interface d'administration
├── assets/
│   ├── css/
│   │   └── style.css       # Styles principaux
│   ├── js/
│   │   └── main.js         # Scripts JavaScript
│   └── images/             # Images statiques
├── uploads/                 # Photos des candidats
└── database/
    └── schema.sql          # Structure de la base de données
```

## Installation

### 1. Prérequis
- XAMPP (Apache + MySQL + PHP)
- Navigateur web moderne

### 2. Configuration de la base de données
1. Démarrer XAMPP (Apache + MySQL)
2. Accéder à phpMyAdmin (http://localhost/phpmyadmin)
3. Importer le fichier `database/schema.sql`
4. Vérifier que la base `mma_platform` est créée

### 3. Configuration de l'application
1. Placer le dossier MMA dans `C:\xampp\htdocs\`
2. Modifier `includes/config.php` si nécessaire (paramètres BDD)

### 4. Accès à l'application
- URL principale: http://localhost/MMA
- Admin: http://localhost/MMA/admin
- Compte admin par défaut: (exemple)
  <!-- - Email: admin@exemple.com -->
  <!-- - Mot de passe: password -->

## Fonctionnalités principales
<!-- ... -->