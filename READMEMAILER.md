# Config PHPMailer

## Installation

D'abord, il faut installer PHPMailer avec Composer.

### Installer PHPMailer

```bash
composer require phpmailer/phpmailer
```

## Configuration

Faut modifier ton fichier `includes/functions.php` pour mettre tes identifiants email.

### Lignes 64-66 à changer :

```php
$mail->Username = 'ton-email@gmail.com';
$mail->Password = 'ton-mot-de-passe-app';
```

## Setup Gmail

### Étape 1 : Activer la double authentification
- Va sur https://myaccount.google.com/security
- Active la validation en deux étapes

### Étape 2 : Créer un mot de passe d'application
- Va sur https://myaccount.google.com/apppasswords
- Choisis "Courrier" 
- Choisis "Autre" 
- Mets "MMA Election" comme nom
- Copie le mot de passe de 16 caractères

### Étape 3 : Mettre à jour functions.php
```php
$mail->Username = 'ton-email@gmail.com';
$mail->Password = 'le-mot-de-passe-que-tas-copié';
```

**Attention** : Mets le même email dans `setFrom()` ligne 70.

## Base de données

Faut lancer le fichier SQL pour ajouter les colonnes nécessaires :

### Avec phpMyAdmin :
1. Ouvre phpMyAdmin
2. Clique sur la base `mma_election`
3. Va dans l'onglet "SQL"
4. Copie/colle le contenu de `database/update_electeur.sql`
5. Clique sur "Exécuter"

### Avec la ligne de commande MySQL :
```bash
cd c:\xampp\mysql\bin
.\mysql.exe -u root -e "USE mma_election; ALTER TABLE electeur ADD COLUMN type_professionnel ENUM('journaliste', 'coach') DEFAULT NULL AFTER nationalite;"
```

**Note** : Cette colonne permet de gérer les comptes professionnels (journalistes et coaches) créés avec un code professionnel.