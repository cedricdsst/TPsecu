
# Projet Docker

Ce projet contient :
- Deux applications PHP (`secured_app` et `unsecured_app`).
- Une base de données MySQL partagée.
- Une interface phpMyAdmin pour la gestion de la base de données.

## Comment exécuter le projet

1. Assurez-vous que Docker et Docker Compose sont installés sur votre machine.
2. Clonez le dépôt et accédez au répertoire du projet.
3. Lancez le projet avec la commande :
   ```bash
   docker-compose up --build
   ```
4. Accédez aux composants dans votre navigateur aux adresses suivantes :
   - **Application sécurisée** : [http://localhost:8080](http://localhost:8080)
   - **Application non sécurisée** : [http://localhost:8081](http://localhost:8081)
   - **phpMyAdmin** : [http://localhost:8082](http://localhost:8082)

## Identifiants des utilisateurs pour les applications

- **Utilisateur Administrateur** : 
  - Nom d'utilisateur : `admin`
  - Mot de passe : `admin123`
- **Utilisateur Régulier** : 
  - Nom d'utilisateur : `user1`
  - Mot de passe : `password`

## Identifiants pour phpMyAdmin

- **Hôte MySQL** : `db`
- **Nom d'utilisateur** : `user`
- **Mot de passe** : `password`

## Fonctionnalités partagées

- Les deux applications partagent le même dossier `uploads` (`/uploads`) et la même base de données MySQL.
- Les images téléchargées dans une application sont accessibles dans l'autre.

## Arrêter le projet

Pour arrêter tous les conteneurs, exécutez la commande :
```bash
docker-compose down
```
