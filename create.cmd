#@echo off
TITLE MakeDocBook

REM ==============================================================
REM Edit these lines:
SET scriptpath=E:\xampp\htdocs\books\vendor
set src=E:\xampp\htdocs\books\tmp\%2%

set base=E:\xampp\htdocs\books\

SET stylepath=%scriptpath%\docbook\epub3
set fooutput=%src%\%1%
set xsltproc_home=%scriptpath%\libxslt-1.1.26.win32\bin
REM ==============================================================


REM bash scripting is... wuaaa
set userdir=tmp/%2%
set out=%userdir%/%1%
set ba=%out%/mimetype
set mu=%out%/META-INF
set ml=%out%/OEBPS

set gendir= %userdir%/gen/%1
set me=%gendir%/%1%.epub

%xsltproc_home%\xsltproc --stringparam toc.section.depth 0 -o %fooutput%\OEBPS %stylepath%\chunk.xsl %gendir%\%1.xml

%scriptpath%\zip\zip.exe -r -X %me% %ba% %mu% %ml%

cd %gendir%
sleep 5
%scriptpath%\kindelGen\kindlegen.exe %1%.epub
cd %base%