# Fonctionnalite Posts Candidats

## Contexte

Les candidats peuvent maintenant publier des posts pour encourager les electeurs a voter pour eux. Ces posts peuvent contenir des images ou videos. Les electeurs peuvent interagir avec ces posts en ajoutant des likes/dislikes et des commentaires.

Les administrateurs ont un acces complet pour moderer le contenu.

---

## Base de donnees

### Modifications dans database/mma_election.sql

Les nouvelles tables ont ete ajoutees directement dans le fichier principal mma_election.sql.

**Tables ajoutees :**

**Table `post` :**
- ID_post (cle primaire)
- id_candidat (cle etrangere vers candidat)
- type_media (image ou video)
- chemin_media (chemin du fichier)
- description (texte optionnel)
- date_creation

**Table `reaction` :**
- ID_reaction (cle primaire)
- id_post (cle etrangere vers post)
- id_electeur (cle etrangere vers electeur)
- type_reaction (like ou dislike)
- date_creation
- Contrainte unique : un electeur ne peut avoir qu'une reaction par post

**Table `commentaire` :**
- ID_commentaire (cle primaire)
- id_post (cle etrangere vers post)
- id_electeur (cle etrangere vers electeur)
- contenu (texte du commentaire)
- date_creation

**Installation :**
1. Ouvrir phpMyAdmin
2. Creer une nouvelle base de donnees : mma_election
3. Importer le fichier database/mma_election.sql
4. Toutes les tables seront creees automatiquement

---

## Fichiers crees

### 1. pages/mes_posts.php

Page pour les candidats pour gerer leurs posts.

**Fonctionnalites :**
- Formulaire pour creer un nouveau post
  * Upload d'image ou video
  * Ajout description optionnelle
- Liste de tous les posts du candidat
- Possibilite de supprimer ses propres posts

**Acces :** Reserve aux candidats connectes

**Upload des fichiers :**
- Images : jpg, jpeg, png, gif
- Videos : mp4, mpeg, quicktime
- Stockage dans : uploads/posts/images/ ou uploads/posts/videos/
- Nom unique genere automatiquement

---

### 2. pages/posts.php

Page publique pour voir tous les posts de tous les candidats.

**Affichage :**
- Photo de profil du candidat
- Nom du candidat
- Date de publication
- Media (image ou video)
- Description
- Compteurs de likes/dislikes
- Compteur de commentaires

**Interactions pour electeurs :**
- Liker/disliker un post
- Cliquer sur le compteur de commentaires pour voir le post en detail

**Pour les non-connectes :**
- Affichage des compteurs seulement
- Pas d'interaction possible

---

### 3. pages/reagir_post.php

Script pour gerer les reactions (likes/dislikes).

**Fonctionnement :**
1. Verifier que c'est un electeur connecte
2. Recuperer l'ID de l'electeur
3. Verifier s'il a deja reagi a ce post
4. Si oui et meme reaction : supprimer la reaction
5. Si oui et reaction differente : modifier la reaction
6. Si non : creer une nouvelle reaction
7. Rediriger vers la page precedente

**Parametres URL :**
- id : ID du post
- type : like ou dislike

---

### 4. pages/voir_post.php

Page pour voir un post en detail avec tous ses commentaires.

**Affichage :**
- Post complet
- Compteurs likes/dislikes
- Formulaire pour ajouter un commentaire (si electeur connecte)
- Liste de tous les commentaires
- Bouton supprimer sur chaque commentaire (si admin)

**Fonctionnalites :**
- Ajout de commentaire en temps reel
- Affichage nom et prenom de l'auteur du commentaire
- Date du commentaire
- Lien retour vers liste des posts

---

### 5. pages/supprimer_post.php

Script pour supprimer un post.

**Acces :**
- Candidat : peut supprimer ses propres posts uniquement
- Administrateur : peut supprimer n'importe quel post

**Fonctionnement :**
1. Verifier les droits
2. Recuperer le chemin du fichier media
3. Supprimer le fichier physique
4. Supprimer l'entree en base (cascade sur reactions et commentaires)
5. Rediriger

**Suppression en cascade :**
Quand un post est supprime, grace aux cles etrangeres :
- Toutes les reactions sont supprimees automatiquement
- Tous les commentaires sont supprimes automatiquement

---

### 6. pages/supprimer_commentaire.php

Script pour supprimer un commentaire.

**Acces :** Administrateurs uniquement

**Fonctionnement :**
1. Verifier que c'est un admin
2. Supprimer le commentaire
3. Rediriger vers le post ou vers la page de moderation

**Parametres URL :**
- id : ID du commentaire
- post : ID du post (optionnel, pour redirection)

---

### 7. pages/moderation_posts.php

Interface de moderation pour les administrateurs.

**Sections :**

**Section Posts :**
- Tableau avec tous les posts
- Colonnes : Candidat, Type, Date, Description, Actions
- Actions : Voir, Supprimer

**Section Commentaires :**
- Tableau avec les 50 derniers commentaires
- Colonnes : Electeur, Date, Contenu, Actions
- Actions : Voir post, Supprimer

**Utilisation :**
- Vue d'ensemble rapide de tout le contenu
- Suppression facile des contenus inappropries
- Acces direct aux posts depuis les commentaires

---

## Fichiers modifies

### includes/header.php

**Ajouts dans le menu :**

**Pour tous :**
- Lien "Posts" visible par tout le monde

**Pour les candidats :**
- Lien "Mes Posts" en bleu et en gras

**Pour les administrateurs :**
- Lien "Moderation" pour acceder a la moderation

**Code ajoute :**
```php
<a href="<?php echo $base_url; ?>/pages/posts.php">Posts</a>

// Pour candidat
<a href="<?php echo $base_url; ?>/pages/mes_posts.php">Mes Posts</a>

// Pour admin
<a href="<?php echo $base_url; ?>/pages/moderation_posts.php">Moderation</a>
```

---

## Structure des dossiers

Les fichiers uploades sont stockes dans :
```
uploads/
  posts/
    images/
      post_xxxxx.jpg
      post_xxxxx.png
    videos/
      post_xxxxx.mp4
```

**Creation automatique :**
Les dossiers sont crees automatiquement lors du premier upload si ils n'existent pas.

---

## Parcours utilisateur

### Candidat

1. **Se connecter**
2. **Aller dans "Mes Posts"**
3. **Creer un post :**
   - Choisir une image ou video
   - Ajouter une description (optionnel)
   - Cliquer sur "Publier"
4. **Voir ses posts publies**
5. **Supprimer un post si necessaire**

### Electeur

1. **Aller sur "Posts"** (meme sans connexion)
2. **Voir tous les posts des candidats**
3. **Se connecter pour interagir**
4. **Liker ou disliker un post**
   - Cliquer sur le pouce en haut ou en bas
   - Recliquer pour annuler
   - Cliquer sur l'autre pour changer d'avis
5. **Commenter un post :**
   - Cliquer sur le compteur de commentaires
   - Ecrire un commentaire dans le formulaire
   - Envoyer

### Administrateur

1. **Aller sur "Moderation"**
2. **Voir tous les posts et commentaires**
3. **Supprimer du contenu inapproprie :**
   - Cliquer sur "Supprimer" dans la ligne concernee
   - Confirmer la suppression

---

## Fonctionnalites techniques

### Gestion des reactions

- Un electeur = une reaction par post
- Peut changer d'avis (like vers dislike ou inverse)
- Peut annuler sa reaction
- Compteurs mis a jour en temps reel

### Gestion des commentaires

- Illimites par post
- Affiches du plus recent au plus ancien
- Nom et prenom de l'electeur visible
- Admin peut tout supprimer

### Gestion des medias

- Verification du type MIME
- Nom unique pour eviter les conflits
- Suppression du fichier physique lors de la suppression du post
- Affichage adapte (img pour images, video pour videos)

### Securite

- Verification du type d'utilisateur
- Verification des droits (candidat ne peut supprimer que ses posts)
- Protection contre l'injection SQL (requetes preparees)
- Filtrage XSS (htmlspecialchars)

---

## Tests a effectuer

### Test candidat - Creation post

1. Se connecter en tant que candidat
2. Aller dans "Mes Posts"
3. Upload une image
4. Ajouter une description
5. Publier
6. Verifier que le post apparait
7. Verifier que le fichier est dans uploads/posts/images/

### Test candidat - Suppression post

1. Cliquer sur "Supprimer" sur un de ses posts
2. Confirmer
3. Verifier que le post a disparu
4. Verifier que le fichier a ete supprime du disque

### Test electeur - Reactions

1. Se connecter en tant qu'electeur
2. Aller sur "Posts"
3. Liker un post
4. Verifier que le compteur augmente
5. Reliker le meme post
6. Verifier que le compteur diminue (reaction annulee)
7. Disliker le meme post
8. Verifier que le compteur de dislike augmente

### Test electeur - Commentaires

1. Cliquer sur un post
2. Ecrire un commentaire
3. Envoyer
4. Verifier que le commentaire apparait
5. Verifier nom et prenom affiches

### Test admin - Moderation

1. Se connecter en tant qu'admin
2. Aller sur "Moderation"
3. Verifier que tous les posts sont visibles
4. Supprimer un post
5. Verifier qu'il a disparu partout
6. Aller sur un post avec commentaires
7. Supprimer un commentaire
8. Verifier qu'il a disparu

---

## Problemes possibles

### Erreur upload

**Symptome :** "Erreur lors du telechargement du fichier"

**Solutions :**
- Verifier les permissions du dossier uploads/
- Verifier la taille max dans php.ini (upload_max_filesize)
- Verifier la taille du fichier

### Images/videos ne s'affichent pas

**Solutions :**
- Verifier que le chemin est correct
- Verifier les permissions des fichiers
- Verifier que les fichiers existent physiquement

### Reactions ne fonctionnent pas

**Solutions :**
- Verifier que l'electeur est bien connecte
- Verifier que la table reaction existe
- Verifier les cles etrangeres

---

## Evolutions possibles

1. Limite du nombre de posts par candidat
2. Possibilite de modifier un post
3. Signalement de contenu inapproprie par les electeurs
4. Reponses aux commentaires (systeme de thread)
5. Partage de posts sur les reseaux sociaux
6. Notifications pour le candidat quand quelqu'un commente
7. Statistiques pour les candidats (vues, engagements)

---

## Auteurs

Lucas Charpentier & Ahmed Errebache
Projet SAE S3 - IUT
Janvier 2026
