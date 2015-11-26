vendor/bin/doctrine orm:schema-tool:drop --force
vendor/bin/doctrine orm:schema-tool:update --force
vendor/bin/doctrine orm:generate-proxies
php fake.php