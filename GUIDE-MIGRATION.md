# Guide d'application de la migration

## Etape 1 : Sauvegarder la base de donnees

Avant d'appliquer la migration, faites une sauvegarde de votre base de donnees :

1. Ouvrir phpMyAdmin (http://localhost/phpmyadmin)
2. Selectionner la base `mma_election`
3. Cliquer sur "Exporter"
4. Choisir "Rapide" et cliquer sur "Executer"
5. Sauvegarder le fichier SQL

## Etape 2 : Appliquer la migration

1. Ouvrir phpMyAdmin
2. Selectionner la base de donnees `mma_election`
3. Cliquer sur l'onglet "SQL"
4. Copier le contenu du fichier `database/migration_fix_vote_system.sql`
5. Coller dans la zone de texte
6. Cliquer sur "Executer"

Si tout se passe bien, vous devriez voir un message de succes.

## Etape 3 : Verifier la migration

Verifier que les colonnes ont bien ete ajoutees :

```sql
DESCRIBE vote;
```

Vous devriez voir les colonnes `id_electeur` et `date_vote` dans la liste.

## Etape 4 : Tester l'application

1. Se connecter en tant qu'administrateur
2. Creer un nouveau scrutin
3. Affecter des candidats au scrutin
4. Passer le scrutin en phase "vote"
5. Se connecter en tant qu'electeur
6. Verifier que seuls les candidats du scrutin sont affiches
7. Effectuer un vote
8. Verifier que le vote est enregistre

## En cas d'erreur

Si vous rencontrez une erreur lors de l'application de la migration :

1. Restaurer la sauvegarde de la base de donnees
2. Verifier que vous avez bien selectionne la bonne base
3. Verifier qu'aucune contrainte existante ne bloque l'ajout
4. Contacter l'equipe de developpement

## Notes

- La migration ajoute des colonnes sans supprimer de donnees
- Les votes existants ne seront pas affectes
- Si vous voulez reinitialiser les votes pour les tests, decommentez les lignes a la fin du fichier de migration
