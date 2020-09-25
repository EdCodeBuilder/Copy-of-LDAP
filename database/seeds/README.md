# Seeders

Se creó una carpeta con una clase predefinida para el seeder por 
cada módulo con el fin de evitar conflictos en GitHub referente
a combinaciones de archivos o Merge. Dentro de estas subcarpetas
cada módulo puede crear su clase de Migración como lo requiera;
para su correcto funcionamiento se debe ejecutar:

```sh
$ composer dump-autoload
```
Seguido de ello se puede ejecutar el comando correspondiente
a cada módulo para realizar sus migraciones. Por ejemplo:

```sh
World Information

$ php artisan db:seed --class=DatabaseWorldInformationSeeder
```