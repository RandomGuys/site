#!/bin/bash

echo "" > facteurs.csv
echo "" > mod_fact.csv

while read line 
do
	echo $line | cut -d',' -f2 >> tmp_facteurs.csv
	echo $line | cut -d',' -f1,2 >> tmp_mod_fact.csv
done < moduli_p_q_uniq

sort -u tmp_facteurs.csv | tr -d " " > facteurs.csv
rm tmp_facteurs.csv

cat tmp_mod_fact.csv | tr -d " " > mod_fact.csv
rm tmp_mod_fact.csv

mysql -u root -p --local-infile < insert_mods_fact.sql