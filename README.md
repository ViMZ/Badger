# Badger
## News
    Le développement de cette application a démarré le 20/01/2022, elle est encore en cours de développement.
    
## Installation
```bash
# Pour pouvoir build les assets correctement, il faut une version récente de nodejs > 16 . On mettra peut être en place un container dans le futur

# Installe les dépendances back et front, build les assets
make build

# Stop tous les containers et lance ceux du projet
make start

# Initialise la base de données
docker exec -it badger_web_1 bin/console d:s:c
docker exec -it badger_web_1 bin/console d:f:l -n
```

## URLs
```bash
# Ouvrir l'appli web
    http://127.0.0.1:8081/
```
```bash
# Adminer de la BDD : password : sasql, système : PostGreSQL
    http://localhost:8080/?pgsql=database&username=root
```
