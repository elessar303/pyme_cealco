#!/bin/bash
# comprimir los archivos 

source config.ini
cd $DIRECTORIO_ORIGEN

for ZIP in `ls|grep .csv`
    do  
	NombreSolo="${ZIP%.[^.]*}"
        FECHA=$(date +%Y-%m-%d-%H:%M:%S)
        echo "$FECHA-Comprimiendo: $ZIP"
#comprimiendo
        zip  $NombreSolo $ZIP
#encriptacion del .zip
	openssl enc -aes-256-cbc -pass file:key.txt -in $NombreSolo.zip -out $NombreSolo.encrypted;
#creacion del archivo hash_md5
	`md5sum $NombreSolo.encrypted > $NombreSolo.txt`
        algo=$(perl -pe 's/^(.*?)\s+(.*)$/"$1","$2"/' $NombreSolo.txt 2>&1)
	echo $algo &> $NombreSolo.txt
    done

FECHA=$(date +%Y-%m-%d-%H:%M:%S)
# echo "$FECHA-FIN-DEL-PROCESO"
