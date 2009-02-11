for i in *.bmp
do
   	convert -resize 640x480 "$i" ${i%bmp}jpg
	echo $i
done

