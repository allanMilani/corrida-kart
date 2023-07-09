#!/bin/bash

cd /var/scripts/

for arq in *;     
    do         
        table=$(basename -s .sql  $arq);
        echo "Execution Create $table"; mysql -uroot -p$MYSQL_ROOT_PASSWORD -e "CREATE DATABASE $table;";
        echo "Importing: $arq"; mysql -uroot -p$MYSQL_ROOT_PASSWORD -f  $table < $arq;
    done;