Apres avoir cloner le dépot

``` bash
composer install
```
puis 
```bash
npm install
```

MAJ migrations
```bash
php bin/console doctrine:migrations:migrate
```

Pour compiler le js/css
```bash
npm run dev-serve
```

pour lancer le serveur symfony
```bash
synmfony serve
```