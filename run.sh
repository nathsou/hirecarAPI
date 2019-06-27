cd src/Entity/
rm token.json
php tokenGoogleAPIGenerator.php && php ../../bin/console server:run
