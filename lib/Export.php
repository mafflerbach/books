<?php

  class Export {
    private $bookId;
    private $bookResult;
    private $user;

    private $outputPath;
    private $genFileName;
    private $bookName;


    public function __construct($bookid) {
      $this->bookId = $bookid;

      $db = \Database\Adapter::getInstance();

      $db->query('select * from user where id=:id', array(':id' => $_SESSION['user']));
      $db->execute();
      $this->user = $db->fetch();

      $db->query('select * from book where id=:id', array(':id' => $this->bookId));
      $db->execute();
      $this->bookResult = $db->fetch();

      $this->outputPath = 'tmp/' . $this->user[0]['hash'] . '/gen/output/';
      $this->bookName = $bookName = str_replace(' ', '_', $this->bookResult[0]['title']);
      $this->genFileName = 'tmp/' . $this->user[0]['hash'] . '/gen/' . $bookName . '/' . $bookName;

    }

    public function pdf() {
      $doc = new DOMDocument('1.0', 'UTF-8');
      $xsl = new XSLTProcessor();
      $xsl->setSecurityPrefs(0);
      $doc->load('vendor/docbook/fo/docbook.xsl');
      $xsl->importStyleSheet($doc);

      if (!file_exists($this->genFileName. '.xml')) {
        $this->docbook();
      }
      $doc->load($this->genFileName . '.xml');
      $xsl->setParameter('', 'section.autolabel', 1);
      $xsl->setParameter('', 'xref.with.number.and.title', 0);
      $xsl->setParameter('', 'body.start.indent', '0mm');
      file_put_contents($this->outputPath . $this->bookName . '.fo', $xsl->transformToXml($doc));



      $command = './buildPdf.sh ' . str_replace(' ', '_', $this->bookResult[0]['title']) . ' ' . $this->user[0]['hash'];
      print(shell_exec($command));
    }

    private function html() {
      $db = \Database\Adapter::getInstance();

      $db->query('select * from chapter where bookid=:bookid', array(':bookid' => $this->bookId));
      $db->execute();
      $result = $db->fetch();

      $html = '';
      foreach ($result as $chapter) {
        $html .= '<div><h2>' . $chapter['title'] . '</h2>';

        $db->query('select * from sections where chapterid=:chapterid', array(':chapterid' => $chapter['id']));
        $db->execute();
        $sections = $db->fetch();

        foreach ($sections as $seciton) {
          $html .= '<div><h3>' . $seciton['title'] . '</h3>';
          $html .= $seciton['content'] . '</div>';
        }
        $html .= '</div>';
      }

      $str
        = '
<html>
  <head>
    <title>' . $this->bookResult[0]['title'] . '</title>
  </head>
  <body>' . $html . '</body>
</html>';

      return $str;

    }

    public function docbook() {

      $doc = new DOMDocument('1.0', 'UTF-8');
      $xsl = new XSLTProcessor();
      $xsl->setParameter('', 'firstname', $this->user[0]['name']);
      $xsl->setParameter('', 'surname', $this->user[0]['surname']);
      $xsl->setParameter('', 'year', date("Y"));
      $doc->load('templates/docbook.xsl');
      $xsl->importStyleSheet($doc);

      $doc->loadHTML($this->html());
      $bookName = str_replace(' ', '_', $this->bookResult[0]['title']);
      $path = 'tmp/' . $this->user[0]['hash'] . '/gen/' . $bookName;

      if (!file_exists($path)) {
        mkdir($path, 0777, TRUE);
      }

      if (file_exists('tmp/' . $this->user[0]['hash'])) {
        file_put_contents($this->genFileName . '.xml',
          utf8_decode($xsl->transformToXML($doc)));
      }
    }

    public function epub() {


      $doc = new DOMDocument('1.0', 'UTF-8');
      $xsl = new XSLTProcessor();
      $xsl->setSecurityPrefs(0);
      $doc->load('vendor/docbook/epub3/chunk.xsl');
      $xsl->importStyleSheet($doc);
      $doc->load($this->genFileName . '.xml');

      if (!file_exists($this->outputPath.'OEBPS')) {
        mkdir($this->outputPath.'OEBPS', 0777, TRUE);
        mkdir($this->outputPath.'/META-INF', 0777);
      }

      $xsl->setParameter('',
        'base.dir',
        $this->outputPath.'OEBPS');
      @$xsl->transformToDoc($doc);

      $this->zip($this->outputPath,
        $this->genFileName. '.epub');

    }

    public function mobi() {
      $command = './buildMobi.sh ' . str_replace(' ', '_', $this->bookResult[0]['title']) . ' ' . $this->user[0]['hash'];
      print(shell_exec($command));
    }

    public function roundtrip() {
      $this->docbook();
      $this->epub();
      $this->mobi();
      $this->pdf();
      $this->cleanUp();
    }

    private function zip($source, $destination) {
      if (!extension_loaded('zip') || !file_exists($source)) {
        return FALSE;
      }

      $zip = new ZipArchive();
      if (!$zip->open($destination, ZIPARCHIVE::CREATE)) {
        return FALSE;
      }

      $source = str_replace('\\', '/', realpath($source));

      if (is_dir($source) === TRUE) {
        $files
          = new RecursiveIteratorIterator(new RecursiveDirectoryIterator($source), RecursiveIteratorIterator::SELF_FIRST);

        foreach ($files as $file) {
          $file = str_replace('\\', '/', $file);

          // Ignore "." and ".." folders
          if (in_array(substr($file, strrpos($file, '/') + 1), array('.', '..'))) {
            continue;
          }

          $file = realpath($file);

          if (is_dir($file) === TRUE) {
            $zip->addEmptyDir(str_replace($source . '/', '', $file . '/'));
          } else if (is_file($file) === TRUE) {
            $zip->addFromString(str_replace($source . '/', '', $file), file_get_contents($file));
          }
        }
      } else if (is_file($source) === TRUE) {
        $zip->addFromString(basename($source), file_get_contents($source));
      }

      return $zip->close();
    }

    private function cleanUp () {

      $it = new RecursiveDirectoryIterator($this->outputPath , RecursiveDirectoryIterator::SKIP_DOTS);
      $files = new RecursiveIteratorIterator($it,
        RecursiveIteratorIterator::CHILD_FIRST);
      foreach($files as $file) {
        if ($file->getFilename() === '.' || $file->getFilename() === '..') {
          continue;
        }
        if ($file->isDir()){
          rmdir($file->getRealPath());
        } else {
          unlink($file->getRealPath());
        }
      }
      rmdir($this->outputPath);
    }
  }