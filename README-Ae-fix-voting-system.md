# Branche Ae-fix-voting-system

## Description
Cette branche corrige le systeme de vote de l'application MMA Election. Le probleme principal etait que les electeurs ne pouvaient pas voter meme lorsqu'un scrutin etait cree et ouvert par l'administrateur.

## Problemes identifies

1. **Candidats non lies au scrutin** : Les candidats n'etaient pas affectes a un scrutin specifique. La colonne `id_scrutin` existait dans la table `candidat` mais n'etait jamais utilisee.

2. **Affichage de tous les candidats** : La page de vote affichait tous les candidats verifies sans filtrer par le scrutin actif.

3. **Pas d'interface d'affectation** : Il n'y avait aucun moyen pour l'administrateur d'affecter des candidats a un scrutin specifique.

4. **Structure de la table vote incomplete** : La table `vote` ne contenait pas de colonne `id_electeur` ni `date_vote`, ce qui empechait de tracer correctement qui avait vote.

## Modifications effectuees

### 1. Base de donnees (`database/migration_fix_vote_system.sql`)

Creation d'un fichier de migration SQL qui ajoute :
- Colonne `id_electeur` dans la table `vote` pour lier le vote a l'electeur
- Colonne `date_vote` pour enregistrer la date et l'heure exactes du vote
- Contrainte de cle etrangere pour assurer l'integrite des donnees

**Comment appliquer la migration :**
```sql
-- Se connecter a phpMyAdmin ou MySQL
-- Selectionner la base de donnees mma_election
-- Executer le fichier migration_fix_vote_system.sql
```

### 2. Page administration (`admin/creer_scrutin.php`)

**Ajout du traitement d'affectation de candidats :**
- Nouvelle action `affecter_candidats` qui permet de lier des candidats a un scrutin
- Utilisation d'une transaction pour garantir l'integrite (desaffectation puis affectation)
- Recuperation de la liste des candidats verifies pour l'affichage

**Interface modale pour affecter les candidats :**
- Ajout d'une modale avec des checkboxes pour chaque candidat
- Bouton "Candidats" sur chaque ligne du tableau des scrutins
- Script JavaScript pour gerer l'ouverture/fermeture de la modale
- Pre-selection des candidats deja affectes au scrutin

**Elements HTML ajoutes :**
- Modale `#modaleCandidats` avec formulaire de selection
- Fonction JS `afficherModaleCandidats(scrutinId)` pour ouvrir la modale
- Fonction JS `fermerModaleCandidats()` pour fermer la modale
- Gestion du clic en dehors de la modale pour la fermer

### 3. Page de vote (`pages/voter.php`)

**Filtrage des candidats par scrutin :**
- Modification de la requete SQL pour recuperer uniquement les candidats du scrutin actif
- Ajout d'une condition `id_scrutin = :id_scrutin`
- Gestion du cas ou aucun scrutin n'est actif

**Amelioration des messages :**
- Message clair quand aucun candidat n'est affecte au scrutin
- Message distinct pour les electeurs qui peuvent voter mais pour lesquels aucun candidat n'est disponible
- Affichage uniquement des candidats du scrutin en cours

**Corrections HTML :**
- Suppression d'une balise de fermeture en double qui causait un probleme d'affichage

### 4. Fonctions PHP (`includes/functions.php`)

**Fonction `enregistrerVote()` :**
- Ajout de l'enregistrement de `id_electeur` lors de l'insertion du vote
- Ajout de l'enregistrement de `date_vote` avec NOW() pour avoir l'heure exacte
- Garantit le lien entre vote, electeur et scrutin

**Fonction `getVoteElecteur()` :**
- Correction des noms de colonnes dans la requete SQL
- Utilisation de `ID_candidat` au lieu de `id_candidat` (respect de la casse)
- Utilisation de `ID_scrutin` au lieu de `id_scrutin`

## Workflow d'utilisation

### Pour l'administrateur :

1. **Creer un scrutin**
   - Aller dans "Gestion des Scrutins"
   - Remplir l'annee, date d'ouverture et date de fermeture
   - Cliquer sur "Creer le scrutin"
   - Le scrutin est cree avec la phase "preparation"

2. **Affecter les candidats au scrutin**
   - Cliquer sur le bouton "Candidats" du scrutin cree
   - Dans la modale, cocher les candidats qui participeront
   - Cliquer sur "Enregistrer"
   - Les candidats sont maintenant lies au scrutin

3. **Ouvrir le vote**
   - Dans le menu deroulant "Changer phase...", selectionner "Vote"
   - Le scrutin passe en phase de vote
   - Les electeurs peuvent maintenant voter

### Pour l'electeur :

1. **Se connecter**
   - Utiliser son email et mot de passe

2. **Acceder a la page de vote**
   - Cliquer sur "Voter" dans le menu

3. **Voter**
   - Voir uniquement les candidats du scrutin actif
   - Cliquer sur un candidat pour le selectionner
   - Cliquer sur "CONFIRMER MON VOTE"
   - Le vote est enregistre et ne peut plus etre modifie

## Tests a effectuer

1. **Creation de scrutin :**
   - Creer un scrutin avec des dates valides
   - Verifier qu'il apparait dans la liste avec la phase "preparation"

2. **Affectation de candidats :**
   - Cliquer sur "Candidats" pour un scrutin
   - Selectionner quelques candidats
   - Enregistrer et verifier que la selection est bien conservee

3. **Passage en phase vote :**
   - Changer la phase du scrutin vers "Vote"
   - Verifier que le scrutin est considere comme actif

4. **Vote electeur :**
   - Se connecter en tant qu'electeur
   - Verifier que seuls les candidats du scrutin actif sont affiches
   - Effectuer un vote et verifier l'enregistrement
   - Tenter de voter une deuxieme fois (doit etre bloque)

5. **Verification base de donnees :**
   - Verifier que la table `vote` contient bien `id_electeur` et `date_vote`
   - Verifier que les candidats ont leur `id_scrutin` correctement renseigne

## Fichiers modifies

- `admin/creer_scrutin.php` : Ajout de l'interface d'affectation de candidats
- `pages/voter.php` : Filtrage des candidats par scrutin actif
- `includes/functions.php` : Correction des fonctions de vote
- `database/migration_fix_vote_system.sql` : Migration de la structure de la base

## Notes techniques

- Les transactions SQL garantissent l'integrite lors de l'affectation de candidats
- Le flag `has_voted` dans la table `electeur` permet un controle rapide
- La table `vote` contient maintenant toutes les informations necessaires pour le suivi
- Le systeme de ponderation existant reste inchange et fonctionnel

## Prochaines etapes possibles

- Ajouter une page de statistiques pour voir le nombre de votes par candidat
- Permettre la modification des candidats affectes meme apres le debut du vote
- Ajouter une confirmation avant de changer la phase d'un scrutin
- Ameliorer l'interface de selection des candidats (recherche, filtres)

---

**Auteur :** Ahmed Errebache  
**Date :** 05/01/2026  
**Branche :** Ae-fix-voting-system  
**Base :** develop
