#!/bin/bash -x

vendor/kindleGen/kindlegen tmp/$2/gen/$1/$1.epub
fop -fo tmp/$2/gen/output/$1.fo -pdf tmp/$2/gen/$1/$1.pdf
