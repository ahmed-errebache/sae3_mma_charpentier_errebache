# Branche : Ae-contact-form

## Contexte
Cette branche a été créée pour implémenter la fonctionnalité d'envoi d'emails sur la page de contact. Le formulaire existait déjà visuellement mais n'envoyait pas réellement de messages.

## Problème à résoudre
La page contact.php contenait un formulaire avec les champs nom, email et message, mais l'attribut action était vide (#) et aucun traitement n'était effectué. Les utilisateurs ne pouvaient donc pas envoyer de messages.

## Solution mise en place

### 1. Ajout de la logique d'envoi d'email
**Fichier modifié :** `pages/contact.php`

J'ai ajouté le traitement du formulaire en haut du fichier, avant l'affichage du HTML. Le code récupère les données du formulaire quand il est soumis et utilise PHPMailer pour envoyer l'email.

**Étapes du traitement :**

1. Vérification que le formulaire a été soumis (méthode POST)
2. Récupération et nettoyage des données :
   - Le nom est nettoyé avec htmlspecialchars pour éviter les injections
   - L'email est filtré et validé
   - Le message est également nettoyé

3. Configuration de PHPMailer :
   - Utilisation de SMTP Gmail
   - Port 587 avec STARTTLS pour la sécurité
   - Encodage UTF-8 pour supporter les accents

4. Construction de l'email :
   - Expéditeur : l'adresse saisie par l'utilisateur
   - Destinataire : ahmed.errebache@gmail.com
   - Format HTML avec version texte alternative
   - Sujet clair : "Nouveau message de contact - MMA Fighter Election"

```php
$mail = new PHPMailer\PHPMailer\PHPMailer(true);
$mail->isSMTP();
$mail->Host = 'smtp.gmail.com';
$mail->SMTPAuth = true;
// ... configuration SMTP
$mail->addAddress('ahmed.errebache@gmail.com');
```

### 2. Ajout des messages de retour
J'ai ajouté deux types de messages pour informer l'utilisateur :

**Message de succès (vert) :**
Affiché quand l'email est envoyé correctement. Confirme à l'utilisateur que son message est bien parti.

**Message d'erreur (rouge) :**
Affiché si quelque chose ne va pas (champs vides, email invalide, problème d'envoi). Indique à l'utilisateur qu'il doit réessayer.

Ces messages apparaissent juste au-dessus du formulaire pour que l'utilisateur les voie facilement.

### 3. Modification du formulaire
**Changement mineur :** L'attribut `action="#"` a été retiré car on traite le formulaire dans le même fichier. La méthode POST est conservée.

## Configuration requise

Pour que l'envoi d'emails fonctionne, il faut configurer les identifiants Gmail dans le code :

```php
$mail->Username = 'votre.email@gmail.com';
$mail->Password = 'votre_mot_de_passe_application';
```

**Important :** Il faut utiliser un "mot de passe d'application" Gmail, pas le mot de passe du compte. 

### Comment obtenir un mot de passe d'application Gmail :

1. Aller dans les paramètres Google du compte Gmail
2. Activer la validation en 2 étapes si ce n'est pas déjà fait
3. Aller dans "Mots de passe des applications"
4. Générer un nouveau mot de passe pour "Autre (nom personnalisé)"
5. Copier ce mot de passe dans le code

## Fichiers modifiés
- `pages/contact.php` : Ajout du traitement du formulaire et des messages de retour

## Tests à effectuer

1. Configurer les identifiants Gmail dans le code
2. Accéder à la page contact
3. Remplir le formulaire avec un nom, email et message
4. Cliquer sur ENVOYER
5. Vérifier qu'un message de succès s'affiche
6. Vérifier la réception de l'email dans ahmed.errebache@gmail.com

## Dépendances utilisées
- **PHPMailer** : Déjà présent dans vendor/, utilisé pour envoyer les emails via SMTP

## Points techniques

### Sécurité
- Les données du formulaire sont nettoyées (htmlspecialchars)
- L'email est validé avec filter_var
- Utilisation de SMTP sécurisé (STARTTLS)

### Expérience utilisateur
- Messages clairs de succès ou d'erreur
- Le formulaire reste rempli en cas d'erreur (comportement par défaut du navigateur)
- Redirection pas nécessaire car les messages s'affichent sur la même page

### Format de l'email reçu
L'email reçu contient :
- Le nom de la personne
- Son adresse email (pour pouvoir répondre)
- Le message complet
- Format HTML pour une meilleure présentation

## Améliorations possibles
- Ajouter une validation côté client (JavaScript) pour éviter les soumissions inutiles
- Enregistrer les messages dans la base de données en plus de l'envoi par email
- Ajouter un système de limite de messages par IP pour éviter le spam
- Mettre les identifiants SMTP dans un fichier de configuration séparé
