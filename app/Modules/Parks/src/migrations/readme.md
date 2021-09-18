#Migraciones Parques

Para crear migraciones en esta carpeta se debe ejecutar el comando

```
    $ php artisan make:migration create_specified_name_table --create=specified_name  --path=/app/Modules/Parks/src/migrations
    $ php artisan make:migration update_specified_name_table --table=specified_name  --path=/app/Modules/Parks/src/migrations
```

Para migrar las tablas o actualizaciones del Portal, se debe ejecutar e comando de la siguiente forma

```
    $ php artisan migrate --path=/app/Modules/Parks/src/migrations --database=mysql_parks
```
