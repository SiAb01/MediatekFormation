- Pour générer la documentation PHPDoc si PHP Version = 8.1 , récupérer cette version phpDocumentor v3.3.0 et l'ajouter dans le reprtoire du projet . 
Et pour générer le documentation sur le repertoire du projet et prendre en compte que le dossiers src
''' php phpDocumentor.phar -d ./src -t docs/api  '''
Pour lire la documentation nouvellement généré  ouvrir docs\api\index.html