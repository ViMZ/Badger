# Badger

## Installation
```bash
# Pour pouvoir build les assets correctement, il faut une version récente de nodejs > 16 . On mettra peut être en place un container dans le futur

# Installe les dépendances back et front, build les assets
make build

# Stop tous les containers et lance ceux du projet, lance le serveur symfony
make start

# Initialise la base de données
symfony console d:s:c
symfony console d:f:l -n
```

## URLs
```bash
# Ouvrir l'appli web
    symfony open:local
# ou
    http://127.0.0.1:8000/
```
```bash
# Adminer de la BDD : password : sasql, système : PostGreSQL
    http://localhost:8080/?pgsql=database&username=root
```
