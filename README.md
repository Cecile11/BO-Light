# BO-Light

Installation : 

	$ git clone  https://github.com/ZeddZull/BO-Light
	$ curl -sS https://getcomposer.org/installer | php
	$ php composer.phar install

	Creation de la base de données:

	$ php app/console doctrine:database:create    
	$ php app/console doctrine:schema:update --force

	Creation d'utilisateurs:

	$ php app/console fos:user:create
	$ php app/console fos:user:promote
	Roles : ROLE_SUPER_ADMIN | ROLE_GUEST

	Charger la base d'in depuis une ancienne:

	$ php app/console loaddb "cheminVersL'ancienneBase"
	ln -s "cheminVersL'ancienneBase" app/data/ipn_ln.db