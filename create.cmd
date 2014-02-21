@echo off
TITLE MakeDocBook

REM ==============================================================
REM Edit these lines:
SET scriptpath=E:\xampp\htdocs\books\vendor
set src=E:\xampp\htdocs\books\output

SET stylepath=%scriptpath%\docbook\epub3
SET foppath=%scriptpath%\fop
set repository=%scriptpath%\repository
set fooutput=%src%\fo
set pdfoutput=%src%\pdf
set xsltproc_home=%scriptpath%\libxslt-1.1.26.win32\bin
REM ==============================================================

  %xsltproc_home%\xsltproc -o %fooutput%\ebook1\OEBPS %stylepath%\chunk.xsl %src%\src\%1.xml
%scriptpath%\zip\zip.exe -r -X %fooutput%\mybook.epub %fooutput%\ebook1\mimetype %fooutput%\ebook1\META-INF %fooutput%\ebook1\OEBPS
