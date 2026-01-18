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
├── index.php                        # Page d'accueil avec sections home
├── README.md                        # Documentation du projet
├── READMEMAILER.md                  # Documentation PHPMailer
├── composer.json                    # Dépendances PHP (PHPMailer)
├── admin/                           # Interface d'administration
│   ├── index.php                   # Dashboard admin
│   ├── creer_scrutin.php           # Création/gestion des scrutins
│   ├── generer_codes.php           # Génération codes professionnels
│   └── resultats.php               # Consultation des résultats
├── includes/                        # Configuration et logique métier
│   ├── config.php                  # Configuration BDD et chargement des fichiers
│   ├── Database.php                # Classe Singleton pour connexion PDO
│   ├── Candidat.php                # Classe entité Candidat
│   ├── Electeur.php                # Classe entité Electeur
│   ├── functions_candidats.php     # Fonctions gestion candidats
│   ├── functions_votes.php         # Fonctions gestion votes/scrutins
│   ├── functions_email.php         # Fonctions envoi emails (PHPMailer)
│   ├── functions_codes.php         # Fonctions codes professionnels
│   ├── functions_utils.php         # Fonctions utilitaires générales
│   ├── functions.php.old           # Ancien fichier monolithique (backup)
│   ├── header.php                  # Template header avec navigation
│   ├── footer.php                  # Template footer
│   └── security.php                # Fonctions de sécurité
├── pages/                           # Pages publiques et espaces utilisateurs
│   ├── login.php                   # Connexion (électeur/candidat/admin)
│   ├── register.php                # Inscription électeur public
│   ├── logout.php                  # Déconnexion
│   ├── profil.php                  # Profil utilisateur (électeur/candidat)
│   ├── completer_profil.php        # Complétion profil candidat
│   ├── completer_profil_electeur.php # Complétion profil électeur
│   ├── candidats.php               # Liste des candidats MMA
│   ├── candidat.php                # Profil détaillé d'un candidat
│   ├── voter.php                   # Interface de vote
│   ├── posts.php                   # Fil des publications candidats
│   ├── voir_post.php               # Détail d'une publication
│   ├── mes_posts.php               # Gestion posts du candidat connecté
│   ├── reagir_post.php             # Réagir à un post (like/dislike)
│   ├── moderation_posts.php        # Modération des commentaires
│   ├── supprimer_post.php          # Suppression d'un post
│   ├── supprimer_commentaire.php   # Suppression d'un commentaire
│   ├── supprimer_compte.php        # Suppression compte utilisateur
│   ├── contact.php                 # Page de contact
│   ├── a_propos.php                # Page à propos
│   ├── licence.php                 # Conditions d'utilisation
│   ├── politique_confidentialite.php # RGPD et confidentialité
│   ├── politique_cookies.php       # Politique des cookies
│   ├── test-debug.php              # Tests de débogage
│   ├── test-profil-simple.php      # Tests profil
│   └── home/                       # Sections modulaires de l'accueil
│       ├── heroSection.php         # Section principale avec CTA
│       ├── countdown.php           # Compteur temps restant
│       ├── howItwork.php           # Explication du processus
│       ├── ponderation.php         # Détails de la pondération
│       └── contactSection.php      # Section contact rapide
├── assets/                          # Ressources statiques
│   ├── css/
│   │   └── style.css               # Styles personnalisés + Tailwind
│   ├── js/
│   │   ├── main.js                 # Scripts JavaScript principaux
│   │   ├── countdown.js            # Logique du compteur
│   │   └── cookies.js              # Gestion des cookies
│   └── img/                        # Images et ressources visuelles
├── images/                          # Photos des candidats (uploads)
│   └── candidats/                  # Photos de profil candidats
├── uploads/                         # Médias uploadés par les candidats
│   └── posts/
│       ├── images/                 # Images des posts
│       └── videos/                 # Vidéos des posts
├── templates/                       # Templates emails
│   └── email_code_professionnel.html # Template email code pro
├── database/                        # Scripts SQL
│   ├── mma_election_complete.sql   # Base de données complète (À UTILISER)
│   ├── README_DATABASE.md          # Documentation base de données
│   ├── schema.sql.old              # Ancien fichier structure (backup)
│   ├── stored_procedures.sql.old   # Anciennes procédures (backup)
│   ├── update_database.sql.old     # Anciennes mises à jour (backup)
│   ├── create_code_professionnel.sql.old # Ancienne table (backup)
│   └── mma_election.sql.old        # Ancienne version (backup)
└── vendor/                          # Dépendances Composer
    ├── autoload.php                # Autoloader Composer
    ├── composer/                   # Fichiers Composer
    └── phpmailer/                  # Librairie PHPMailer 6.9
```

## Architecture SOLID (Améliorations appliquées)

Le projet suit les principes SOLID pour une meilleure maintenabilité :

### Single Responsibility Principle (SRP)
- **functions_candidats.php** : Gestion exclusive des candidats
- **functions_votes.php** : Gestion exclusive des votes et scrutins
- **functions_email.php** : Gestion exclusive des emails
- **functions_codes.php** : Gestion exclusive des codes professionnels
- **functions_utils.php** : Fonctions utilitaires générales
- **Database.php** : Connexion PDO unique (Singleton)
- **Candidat.php / Electeur.php** : Classes entités avec méthodes métier

### Avantages
- Code modulaire et organisé
- Facilité de maintenance
- Tests unitaires possibles
- Réutilisabilité des fonctions
- Séparation claire des responsabilités
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