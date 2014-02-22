@echo off
TITLE MakeDocBook

REM ==============================================================
REM Edit these lines:
SET scriptpath=E:\xampp\htdocs\books\vendor
set src=E:\xampp\htdocs\books\tmp

SET stylepath=%scriptpath%\docbook\epub3
set fooutput=%src%\%1%
set xsltproc_home=%scriptpath%\libxslt-1.1.26.win32\bin
REM ==============================================================
#%xsltproc_home%\xsltproc --stringparam section.autolabel 0 --stringparam chapter.autolabel 0 -o %fooutput%\OEBPS %stylepath%\chunk.xsl %src%\%1.xml
%xsltproc_home%\xsltproc --stringparam section.autolabel 0 --stringparam chapter.autolabel 0 -o %fooutput%\OEBPS %stylepath%\chunk.xsl %src%\%1.xml

set out=tmp\%1%
set me=tmp\%1%.epub
set ba=%out%\mimetype
set mu=%out%\META-INF
set ml=%out%\OEBPS
%scriptpath%\zip\zip.exe -r -X %me% %ba% %mu% %ml%
