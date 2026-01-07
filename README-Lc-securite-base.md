# Securisation de l'application - Mesures de base

## Contexte

Dans le cadre du cours de securite Web, cette branche applique les mesures de protection fondamentales pour securiser l'application de vote MMA contre les attaques courantes.

Le cours identifie les principales menaces :
- Acces aux ressources (indiscretion)
- Injection XSS et SQL
- Man in the Middle
- Vol de bases de donnees

## Objectif

Implementer les protections de base enseignees dans le cours, adaptees a un projet etudiant :
1. Protection contre les injections XSS
2. Verification des requetes SQL preparees
3. Securisation du stockage des mots de passe
4. Protection des fichiers sensibles
5. Headers HTTP de securite

## Modifications apportees

### 1. Protection des fichiers sensibles avec .htaccess

**Fichiers crees :**

#### a) database/.htaccess
```apache
Deny from all
```
**But :** Empecher l'acces direct aux fichiers SQL et scripts de base de donnees.
Les fichiers .sql ne doivent jamais etre accessibles via le navigateur.

#### b) includes/.htaccess
```apache
Deny from all
```
**But :** Bloquer l'acces aux fichiers PHP de configuration (config.php, functions.php).
Ces fichiers contiennent des informations sensibles (identifiants BDD).

#### c) uploads/.htaccess
```apache
Options -Indexes
```
**But :** Desactiver le listing des fichiers uploades.
Les utilisateurs ne peuvent pas voir la liste complete des images/videos uploadees.

### 2. Fonctions de validation et nettoyage (includes/functions.php)

**Ajouts :**

#### a) cleanInput($data)
```php
function cleanInput($data) {
    $data = trim($data);              // Enleve les espaces
    $data = stripslashes($data);       // Enleve les backslashes
    $data = htmlspecialchars($data, ENT_QUOTES, 'UTF-8');  // Protection XSS
    return $data;
}
```
**But :** Nettoyer toutes les entrees utilisateur avant affichage.
Convertit les caracteres speciaux HTML (<, >, ", ', &) en entites HTML.
Exemple : `<script>` devient `&lt;script&gt;` et ne s'execute pas.

#### b) validateEmail($email)
```php
function validateEmail($email) {
    $email = filter_var($email, FILTER_SANITIZE_EMAIL);
    if (filter_var($email, FILTER_VALIDATE_EMAIL)) {
        return $email;
    }
    return false;
}
```
**But :** Valider et nettoyer les emails.
Utilise les filtres PHP natifs pour verifier le format.

#### c) validateInt($value)
```php
function validateInt($value) {
    return filter_var($value, FILTER_VALIDATE_INT) !== false;
}
```
**But :** Valider les entiers (IDs, ages, etc.).
Evite les injections SQL via des parametres numeriques.

### 3. Headers HTTP de securite (includes/security.php)

**Fichier cree :**

```php
// Protection XSS
header("X-XSS-Protection: 1; mode=block");

// Empecher le chargement dans une iframe
header("X-Frame-Options: DENY");

// Empecher le sniffing MIME
header("X-Content-Type-Options: nosniff");

// Politique de referrer
header("Referrer-Policy: strict-origin-when-cross-origin");

// Desactiver les informations de version PHP
header_remove("X-Powered-By");
```

**Explications :**

- **X-XSS-Protection** : Active la protection XSS du navigateur
- **X-Frame-Options** : Empeche le site d'etre charge dans une iframe (protection contre clickjacking)
- **X-Content-Type-Options** : Force le navigateur a respecter le MIME-type (evite execution de fichiers malveillants)
- **Referrer-Policy** : Controle les informations envoyees dans le header Referer
- **X-Powered-By** : Cache la version de PHP (moins d'infos pour l'attaquant)

**Integration :** Le fichier est inclus au debut de header.php, donc applique sur toutes les pages.

### 4. Verification des protections existantes

**Verifications effectuees :**

#### a) Mots de passe
- ✅ Utilisation de `password_hash()` dans register.php
- ✅ Utilisation de `password_verify()` dans login.php
- ✅ Algorithme PASSWORD_DEFAULT (bcrypt avec salt automatique)

Exemple dans register.php :
```php
$hashedPassword = password_hash($password, PASSWORD_DEFAULT);
```

Le salt est genere automatiquement par PHP, pas besoin de le gerer manuellement.

#### b) Requetes SQL
- ✅ Toutes les requetes utilisent PDO avec requetes preparees
- ✅ Pas de concatenation directe dans les requetes SQL

Exemple type dans le code :
```php
$sql = "SELECT * FROM electeur WHERE email = :email";
$stmt = $connexion->prepare($sql);
$stmt->execute([':email' => $email]);
```

Les parametres sont lies de maniere securisee, impossible d'injecter du SQL.

#### c) Validation des entrees
- ✅ contact.php utilise deja `htmlspecialchars()` et `filter_var()`
- ✅ Les emails sont valides avec FILTER_VALIDATE_EMAIL

### 5. Structure de securite

```
sae3_mma_charpentier_errebache/
├── database/
│   └── .htaccess               # Nouveau - Bloque acces direct
├── includes/
│   ├── .htaccess               # Nouveau - Bloque acces direct
│   ├── security.php            # Nouveau - Headers HTTP
│   ├── functions.php           # Modifie - Ajout fonctions validation
│   └── header.php              # Modifie - Inclut security.php
└── uploads/
    └── .htaccess               # Nouveau - Desactive listing
```

## Comment tester les protections

### 1. Test protection .htaccess

**Tester database/.htaccess :**
```
http://localhost/sae3_mma_charpentier_errebache/database/mma_election.sql
```
**Resultat attendu :** 403 Forbidden

**Tester includes/.htaccess :**
```
http://localhost/sae3_mma_charpentier_errebache/includes/config.php
```
**Resultat attendu :** 403 Forbidden

**Tester uploads/.htaccess :**
```
http://localhost/sae3_mma_charpentier_errebache/uploads/
```
**Resultat attendu :** Pas de listing, erreur 403 ou page vide

### 2. Test protection XSS

**Sans protection :**
Si on entre dans un formulaire : `<script>alert('XSS')</script>`
Le script s'execute et affiche une alerte.

**Avec protection (cleanInput) :**
Le script est converti en : `&lt;script&gt;alert('XSS')&lt;/script&gt;`
Il s'affiche comme texte, ne s'execute pas.

**Test pratique :**
1. Aller sur contact.php
2. Entrer dans le champ nom : `<b>Test</b>`
3. L'email de confirmation doit afficher le texte `<b>Test</b>` et non pas **Test** en gras

### 3. Test headers HTTP

**Verifier avec les outils developpeur (F12) :**
1. Ouvrir n'importe quelle page du site
2. Onglet "Reseau" (Network)
3. Actualiser la page
4. Cliquer sur la requete principale
5. Onglet "En-tetes" (Headers)

**Headers attendus :**
```
X-XSS-Protection: 1; mode=block
X-Frame-Options: DENY
X-Content-Type-Options: nosniff
Referrer-Policy: strict-origin-when-cross-origin
```

**X-Powered-By** ne doit PAS apparaitre dans les headers.

### 4. Test SQL Injection (verification)

**Tentative classique :**
Dans le champ email de connexion, entrer :
```
' OR '1'='1
```

**Resultat attendu :** Echec de connexion.
Les requetes preparees empechent l'injection, le texte est traite comme une chaine litterale.

### 5. Test mot de passe hash

**Verifier dans la base de donnees :**
1. Ouvrir phpMyAdmin
2. Table `electeur`
3. Colonne `mot_de_passe`

**Attendu :** Les mots de passe sont hashes, format bcrypt :
```
$2y$10$abcdefghijklmnopqrstuvwxyz1234567890ABCDEFGHIJKLM
```
- Commence par `$2y$` (bcrypt)
- Longueur fixe de 60 caracteres
- Chaque hash est different meme pour le meme mot de passe (grace au salt)

## Principes de securite appliques

### Principe 1 : Defense en profondeur
Plusieurs couches de protection :
- .htaccess (serveur Apache)
- Headers HTTP (navigateur)
- Validation PHP (serveur)
- Requetes preparees (base de donnees)

### Principe 2 : Moindre privilege
- Les fichiers sensibles ne sont accessibles qu'au serveur
- Les mots de passe ne sont jamais stockes en clair
- Le type d'utilisateur limite les actions possibles

### Principe 3 : Validation systematique
- Toutes les entrees utilisateur sont validees
- Les sorties sont echappees avant affichage
- Les types de donnees sont verifies

## Conformite avec le cours de securite

| Menace du cours | Protection implementee |
|----------------|----------------------|
| Acces aux ressources | .htaccess sur dossiers sensibles |
| XSS Injection | htmlspecialchars() + cleanInput() |
| SQL Injection | Requetes preparees PDO (deja present) |
| Vol de BDD | password_hash() avec bcrypt (deja present) |
| Man in the Middle | Headers HTTP de securite |

## Limites acceptees (projet etudiant)

### Ce qui N'est PAS implemente :

1. **HTTPS** - Necessiterait un certificat SSL (hors perimetre)
2. **Authentification 2 facteurs** - Mentionne comme non prevu dans le cahier des charges
3. **Detection VPN** - Trop complexe pour un projet etudiant
4. **CSRF tokens** - Non vu dans le cours de base
5. **Rate limiting** - Protection contre brute force avancee
6. **Content Security Policy** - Header CSP complexe

### Pourquoi ces limites sont acceptables :

- Projet en environnement local (pas de production)
- Duree limitee de l'election (risque temporel reduit)
- Volume d'utilisateurs reduit (projet academique)
- Conformite avec le niveau du cours (mesures de base)

## Prochaines etapes possibles

Si vous souhaitez renforcer la securite plus tard :

1. **HTTPS** : Configurer un certificat SSL avec Let's Encrypt
2. **CSP** : Ajouter Content-Security-Policy header
3. **CSRF** : Implementer des tokens anti-CSRF dans les formulaires
4. **Rate limiting** : Limiter le nombre de tentatives de connexion
5. **Logs de securite** : Enregistrer les tentatives d'acces suspects
6. **Scan automatique** : Utiliser des outils comme OWASP ZAP

## Auteurs

Lucas Charpentier & Ahmed Errebache  
BUT Informatique - IUT de Saint-Die-des-Vosges  
Janvier 2026

---

**Note importante :** Ces mesures correspondent au niveau de securite attendu dans un projet etudiant de 2eme annee. Pour une application en production, des mesures supplementaires seraient necessaires.
