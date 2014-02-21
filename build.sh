#!/bin/sh
USER_HOME="/home/userhome"
FOP_HOME="/usr/local/fop/fop-0.95/"
DOCBOOK_HOME="$USER_HOME/docbook/"
SOURCE_HOME="$USER_HOME/Documents/documentation"
OUTPUT_HOME="$USER_HOME/Documents/documentation"
docbookfo="/usr/share/xml/docbook/stylesheet/nwalsh/current/fo"



# using xslt for xslt:fo

#xsltproc	--xinclude \
#		--nonet \
#		--stringparam section.autolabel 1 \
#		--stringparam xref.with.number.and.title 0 \
#		--stringparam body.start.indent 0mm \
#		--output $1.fo $docbookfo/docbook.xsl $1.xml
# using fop to generate pdf.
#$FOP_HOME/fop -fo $1.fo -pdf $1.pdf

fooutput="output"
stylepath="vendor/docbook/epub3/"
src="tmp"
xsltproc -o $fooutput/ebook1/OEBPS $stylepath/chunk.xsl $src/$1.xml
