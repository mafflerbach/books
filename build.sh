#!/bin/bash
stylesheet=/vagrant/project/vendor/docbook/epub3/chunk.xsl
fooutput=/vagrant/project/tmp/$2/gen/output
source=/vagrant/project/tmp/$2/gen/$1/$1.xml

xsltproc  --stringparam toc.section.depth 0 -o /vagrant/project/tmp/$2/gen/output/OEBPS \
/vagrant/project/vendor/docbook/epub3/chunk.xsl \
/vagrant/project/tmp/$2/gen/$1/$1.xml

mimetype=mimetype
metainf=META-INF
oebps=OEBPS

cd /vagrant/project/tmp/$2/gen/output
zip -rXD /vagrant/project/tmp/$2/gen/test_book.epub $mimetype $metainf $oebps
/vagrant/project
/vagrant/project/vendor/kindleGen/kindlegen /vagrant/project/tmp/$2/gen/$1.epub

mv /vagrant/project/tmp/$2/gen/$1.epub /vagrant/project/tmp/$2/gen/$1/$1.epub
mv /vagrant/project/tmp/$2/gen/$1.mobi /vagrant/project/tmp/$2/gen/$1/$1.mobi

rm -rf /vagrant/project/tmp/$2/gen/output