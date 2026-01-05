# Instructions de test - Systeme de vote corrige

## Pre-requis

1. Avoir XAMPP demarre (Apache + MySQL)
2. Avoir la base de donnees `mma_election` importee
3. Etre sur la branche `Ae-fix-voting-system`

## Etape 1 : Appliquer la migration

### Option A : Premiere installation
Si c'est la premiere fois que vous utilisez cette branche :

1. Ouvrir phpMyAdmin : http://localhost/phpmyadmin
2. Selectionner la base `mma_election`
3. Aller dans l'onglet SQL
4. Copier/coller le contenu de `database/migration_fix_vote_system.sql`
5. Executer

### Option B : Base existante a reinitialiser
Si vous avez deja des donnees de test :

1. Ouvrir phpMyAdmin
2. Selectionner la base `mma_election`
3. Aller dans l'onglet SQL
4. Executer d'abord `database/migration_fix_vote_system.sql`
5. Puis executer `database/reset_for_testing.sql`

## Etape 2 : Test administrateur

1. Se connecter : http://localhost/sae3_mma_charpentier_errebache/pages/login.php
   - Email : `admin@exemple.com`
   - Mot de passe : `Admin123!`

2. Aller dans "Gestion des Scrutins" (menu admin)

3. **Creer un scrutin :**
   - Annee : 2026
   - Date ouverture : aujourd'hui (05/01/2026)
   - Date fermeture : dans 30 jours (04/02/2026)
   - Cliquer sur "Creer le scrutin"
   - Le scrutin devrait apparaitre avec phase "preparation"

4. **Affecter des candidats :**
   - Cliquer sur le bouton bleu "Candidats" a cote du scrutin
   - Une modale s'ouvre avec la liste des candidats
   - Cocher au moins 3 candidats (par exemple : Silva, Nurmagomedov, Rousey)
   - Cliquer sur "Enregistrer"
   - La modale se ferme

5. **Ouvrir le vote :**
   - Dans le menu deroulant "Changer phase...", selectionner "Vote"
   - La page se recharge
   - Le scrutin doit maintenant afficher "Vote" en vert

## Etape 3 : Test electeur - Vote normal

1. Se deconnecter (clic sur "Deconnexion")

2. Se connecter en tant qu'electeur :
   - Email : `electeur1@exemple.com`
   - Mot de passe : `Electeur123!`

3. Cliquer sur "Voter" dans le menu

4. **Verifier l'affichage :**
   - Seuls les candidats affectes au scrutin doivent apparaitre
   - Si vous en avez affecte 3, vous ne devez en voir que 3
   - Le titre doit indiquer le scrutin actif

5. **Effectuer un vote :**
   - Cliquer sur un candidat (il doit se mettre en surbrillance rouge)
   - Un badge "SELECTIONNE" apparait
   - Cliquer sur "CONFIRMER MON VOTE"
   - Une modale de confirmation s'affiche
   - Confirmer le vote
   - Message de succes : "Votre vote a ete enregistre avec succes !"

6. **Verifier le vote enregistre :**
   - Rafraichir la page
   - Le candidat choisi doit s'afficher dans un encadre dore
   - Un message indique "Votre vote a ete enregistre et ne peut plus etre modifie"
   - Les autres candidats ne sont plus visibles

## Etape 4 : Test electeur - Tentative de vote multiple

1. Rester connecte avec electeur1

2. Essayer de voter a nouveau :
   - Aller sur la page de vote
   - Le systeme doit afficher votre vote precedent
   - Aucun formulaire de vote ne doit etre disponible

3. Se deconnecter

4. Se reconnecter avec un autre electeur :
   - Email : `electeur2@exemple.com`
   - Mot de passe : `Electeur123!`

5. Voter pour un candidat different

6. Verifier que le vote est bien enregistre

## Etape 5 : Test professionnels

### Journaliste

1. Se connecter :
   - Email : `journaliste1@exemple.com`
   - Mot de passe : `Journaliste123!`

2. Voter et verifier que ca fonctionne

### Coach

1. Se connecter :
   - Email : `coach1@exemple.com`
   - Mot de passe : `Coach123!`

2. Voter et verifier que ca fonctionne

## Etape 6 : Verification en base de donnees

1. Ouvrir phpMyAdmin

2. Executer cette requete :
```sql
SELECT 
    v.ID_vote,
    v.date_vote,
    e.nom AS nom_electeur,
    e.prenom AS prenom_electeur,
    c.nom AS nom_candidat,
    c.prenom AS prenom_candidat,
    co.type AS type_college
FROM vote v
JOIN electeur e ON v.id_electeur = e.ID_electeur
JOIN candidat c ON v.id_candidat = c.ID_candidat
JOIN college co ON v.id_college = co.ID_college
ORDER BY v.date_vote DESC;
```

3. Verifier que :
   - Tous les votes ont un `id_electeur` renseigne
   - Tous les votes ont une `date_vote` renseigne
   - Les votes sont bien lies aux bons candidats

## Tests negatifs (ce qui NE doit PAS fonctionner)

1. **Scrutin sans candidats :**
   - Creer un nouveau scrutin
   - Ne pas affecter de candidats
   - Passer en phase "vote"
   - Se connecter en electeur
   - Message : "Aucun candidat n'a encore ete affecte au scrutin en cours"

2. **Scrutin en preparation :**
   - Creer un scrutin avec candidats
   - Laisser en phase "preparation"
   - Se connecter en electeur
   - Message : "Aucun scrutin n'est actuellement ouvert"

3. **Vote deja effectue :**
   - Apres avoir vote une fois
   - Essayer de revenir sur la page de vote
   - Le vote precedent doit etre affiche
   - Aucun formulaire disponible

## Problemes connus et solutions

### Probleme : Aucun candidat n'apparait
**Solution :** Verifier que des candidats ont bien ete affectes au scrutin via le bouton "Candidats"

### Probleme : Message "Aucun scrutin ouvert"
**Solution :** Verifier que le scrutin est en phase "vote" et que la date d'ouverture est passee

### Probleme : Erreur SQL lors du vote
**Solution :** Verifier que la migration a bien ete appliquee (colonnes id_electeur et date_vote)

### Probleme : La modale ne s'ouvre pas
**Solution :** Verifier que JavaScript est active dans le navigateur

## Resultats attendus

A la fin des tests, vous devriez avoir :

- Au moins 1 scrutin cree et en phase "vote"
- Au moins 3 candidats affectes a ce scrutin
- Au moins 5 votes enregistres (3 electeurs publics + 1 journaliste + 1 coach)
- Tous les votes visibles dans la table `vote` avec id_electeur et date_vote
- Aucun electeur ne peut voter deux fois

## Support

En cas de probleme :
- Consulter le fichier `README-Ae-fix-voting-system.md`
- Consulter le fichier `GUIDE-MIGRATION.md`
- Verifier les logs d'erreur PHP (dans XAMPP)

---

**Note :** Les mots de passe par defaut sont tous au format `<Type>123!` 
(ex: Admin123!, Electeur123!, Coach123!, Journaliste123!)
