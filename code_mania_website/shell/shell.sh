#!/bin/sh
#input 
#	[uid] [qid] [timelimit]

file=$1_$2
path=$4
cd $path
g++ shell/code/$file.c -o shell/code/$file 2>shell/error/$file.txt

if [ $? -ne 0 ]
then
	mv -f shell/code/$file.c shell/error/ 
	echo -n 0 #comilation error
	exit
fi

rm -f shell/error/$file.txt

ulimit -t $3

shell/code/$file <data/input/$2.txt >shell/output/$file.txt

if [ $? -ne 0 ]
then
	mv -f shell/code/$file.c shell/error/
	rm -f shell/code/$file shell/output/$file.txt
	echo -n 3 #TLE
	exit
fi

#see man diff
# -i ignore case
diff -t -b -B -N -E -i --strip-trailing-cr data/output/$2.txt shell/output/$file.txt >shell/tmp.buf

if [ $? -eq 0 ]
then	
	echo -n 1 #correct output
else
	mv -f shell/code/$file.c shell/error/ 
	echo -n 2 #wrong output
fi

rm -f shell/code/$file shell/output/$file.txt
exit

