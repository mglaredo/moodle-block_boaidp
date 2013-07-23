#/bin/bash
LIST=`ls *.php`
DEST=""
for i in $LIST ;
do 
DEST="${i}_utf-8"
iconv -f iso-8859-1 -t utf-8 $i -o $DEST;
mv $DEST $i;
done
