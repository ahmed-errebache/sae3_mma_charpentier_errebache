# Configuration appliquee - Systeme pret pour les tests

## Migration SQL executee avec succes

✅ **Colonne `id_electeur`** ajoutee a la table `vote`  
✅ **Colonne `date_vote`** ajoutee a la table `vote`  
✅ **Index et contrainte de cle etrangere** ajoutes  

## Configuration de test appliquee

✅ **Tous les votes supprimés** de la table `vote`  
✅ **Flag `has_voted` reinitialise** pour tous les electeurs  
✅ **6 candidats affectes** au scrutin actif (ID: 3)  

## Scrutin actif

**ID:** 3  
**Annee:** 2026  
**Date ouverture:** 2026-01-05 (aujourd'hui)  
**Date fermeture:** 2026-01-12 (dans 7 jours)  
**Phase:** **VOTE** (✅ ouvert)  

## Candidats disponibles pour le vote

1. **Alexandre Silva**
2. **Khabib Nurmagomedov**
3. **Ronda Rousey**
4. **Georges St-Pierre**
5. **Amanda Nunes**
6. **Ahmed Errebache**

Tous affectes au scrutin 3.

## Comptes de test disponibles

### Administrateur
- **Email:** admin@exemple.com
- **Mot de passe:** Admin123!
- **Acces:** Interface admin complete

### Electeurs publics
1. **Email:** electeur1@exemple.com  
   **Mot de passe:** Electeur123!  
   **Statut:** Pret a voter

2. **Email:** electeur2@exemple.com  
   **Mot de passe:** Electeur123!  
   **Statut:** Pret a voter

3. **Email:** electeur3@exemple.com  
   **Mot de passe:** Electeur123!  
   **Statut:** Pret a voter

4. **Email:** electeur4@exemple.com  
   **Mot de passe:** Electeur123!  
   **Statut:** Pret a voter

5. **Email:** electeur5@exemple.com  
   **Mot de passe:** Electeur123!  
   **Statut:** Pret a voter

### Journalistes (College 2 - 40% du vote)
1. **Email:** journaliste1@exemple.com  
   **Mot de passe:** Journaliste123!

2. **Email:** journaliste2@exemple.com  
   **Mot de passe:** Journaliste123!

3. **Email:** journaliste3@exemple.com  
   **Mot de passe:** Journaliste123!

### Coachs (College 3 - 40% du vote)
1. **Email:** coach1@exemple.com  
   **Mot de passe:** Coach123!

2. **Email:** coach2@exemple.com  
   **Mot de passe:** Coach123!

3. **Email:** coach3@exemple.com  
   **Mot de passe:** Coach123!

## Instructions pour tester

### Test 1 : Vote en tant qu'electeur

1. Ouvrir : http://localhost/sae3_mma_charpentier_errebache/pages/login.php
2. Se connecter avec : electeur1@exemple.com / Electeur123!
3. Cliquer sur "Voter" dans le menu
4. **Verifier :** Vous devez voir les 6 candidats
5. Cliquer sur un candidat (il devient rouge avec "SELECTIONNE")
6. Cliquer sur "CONFIRMER MON VOTE"
7. Confirmer dans la modale
8. **Resultat attendu :** Message "Votre vote a ete enregistre avec succes !"
9. Rafraichir la page
10. **Resultat attendu :** Votre vote s'affiche dans un cadre dore

### Test 2 : Verifier qu'on ne peut pas voter deux fois

1. Rester connecte avec electeur1
2. Essayer d'aller sur la page de vote
3. **Resultat attendu :** Seul votre vote s'affiche, pas de formulaire

### Test 3 : Vote avec un autre electeur

1. Se deconnecter
2. Se connecter avec : electeur2@exemple.com / Electeur123!
3. Voter pour un candidat different
4. **Resultat attendu :** Vote enregistre avec succes

### Test 4 : Vote en tant que journaliste

1. Se deconnecter
2. Se connecter avec : journaliste1@exemple.com / Journaliste123!
3. Voter
4. **Resultat attendu :** Vote enregistre (poids 40%)

### Test 5 : Vote en tant que coach

1. Se deconnecter
2. Se connecter avec : coach1@exemple.com / Coach123!
3. Voter
4. **Resultat attendu :** Vote enregistre (poids 40%)

### Test 6 : Interface admin

1. Se connecter avec : admin@exemple.com / Admin123!
2. Aller dans "Gestion des Scrutins"
3. Cliquer sur "Candidats" du scrutin 3
4. **Resultat attendu :** Les 6 candidats sont coches
5. Decocher 2 candidats
6. Enregistrer
7. Se reconnecter en electeur
8. **Resultat attendu :** Seuls 4 candidats apparaissent maintenant

## Verification en base de donnees

Pour verifier que tout fonctionne, executez dans phpMyAdmin :

```sql
-- Voir tous les votes enregistres
SELECT 
    v.ID_vote,
    v.date_vote,
    e.nom AS nom_electeur,
    e.prenom AS prenom_electeur,
    c.nom AS nom_candidat,
    c.prenom AS prenom_candidat,
    co.type AS type_college,
    co.poids AS poids_vote
FROM vote v
JOIN electeur e ON v.id_electeur = e.ID_electeur
JOIN candidat c ON v.id_candidat = c.ID_candidat
JOIN college co ON v.id_college = co.ID_college
ORDER BY v.date_vote DESC;
```

## Points de verification

✅ Les votes contiennent bien `id_electeur` et `date_vote`  
✅ Chaque electeur ne peut voter qu'une seule fois  
✅ Seuls les candidats du scrutin actif sont affiches  
✅ Le type de college est correctement enregistre  
✅ La modale d'affectation de candidats fonctionne  

## Tout est pret !

Le systeme est completement configure et pret pour vos tests.

**URL de l'application :** http://localhost/sae3_mma_charpentier_errebache/

**Branche Git :** Ae-fix-voting-system  
**Date de configuration :** 05/01/2026  

---

Si vous rencontrez un probleme, verifiez :
1. XAMPP Apache et MySQL sont demarres
2. La base de donnees `mma_election` existe
3. Vous utilisez bien la branche `Ae-fix-voting-system`
