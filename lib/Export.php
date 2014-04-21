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
      $user = $db->fetch();
      $this->user = $user[0];

      $db->query('select * from book where id=:id', array(':id' => $this->bookId));
      $db->execute();
      $bookResult = $db->fetch();
      $this->bookResult = $bookResult[0];

      $this->outputPath = 'tmp/' . $this->user['hash'] . '/gen/output/';
      $this->bookName = $bookName = str_replace(' ', '_', $this->bookResult['title']);
      $this->genFileName = 'tmp/' . $this->user['hash'] . '/gen/' . $bookName . '/' . $bookName;

      if (!file_exists('tmp/' . $this->user['hash'] . '/gen/' . $bookName)) {
        mkdir('tmp/' . $this->user['hash'] . '/gen/' . $bookName, 0777, true);
      }

      $this->gitDir = 'tmp/' . $this->user['hash'] . '/git/' . $bookName;

    }

    public function pdf() {
      $doc = new DOMDocument('1.0', 'UTF-8');
      $xsl = new XSLTProcessor();
      $xsl->setSecurityPrefs(0);
      $doc->load('vendor/docbook/fo/docbook.xsl');
      $xsl->importStyleSheet($doc);

      if (!file_exists($this->gitDir. '/docbook.xml')) {
        $this->filesystem();
      }
      $doc->load($this->gitDir. '/docbook.xml');
      $doc->xinclude();
      $xsl->setParameter('', 'section.autolabel', 1);
      $xsl->setParameter('', 'xref.with.number.and.title', 0);
      $xsl->setParameter('', 'body.start.indent', '0mm');
      file_put_contents($this->outputPath . $this->bookName . '.fo', $xsl->transformToXml($doc));

      $command = './buildPdf.sh ' . str_replace(' ', '_', $this->bookResult['title']) . ' ' . $this->user['hash'];
      print(shell_exec($command));
    }

    public function epub() {
      $doc = new DOMDocument('1.0', 'UTF-8');
      $xsl = new XSLTProcessor();
      $xsl->setSecurityPrefs(0);
      $doc->load('vendor/docbook/epub3/chunk.xsl');
      $xsl->importStyleSheet($doc);
      $doc->load($this->gitDir. '/docbook.xml');
      $doc->xinclude();

      if (!file_exists($this->outputPath.'OEBPS')) {
        mkdir($this->outputPath.'OEBPS', 0777, TRUE);
        mkdir($this->outputPath.'/META-INF', 0777);
      }

      $xsl->setParameter('',
        'base.dir',
        $this->outputPath.'OEBPS');
      $xsl->transformToDoc($doc);
      $this->zip($this->outputPath,
        $this->genFileName. '.epub');

    }

    public function mobi() {
      $command = './buildMobi.sh ' . str_replace(' ', '_', $this->bookResult['title']) . ' ' . $this->user['hash'];
      print(shell_exec($command));
    }

    public function roundtrip() {
      $this->filesystem();
      $this->epub();
      /*$this->mobi();
      $this->pdf();*/
      $this->cleanUp();

      $git = new Git($this->gitDir);
      $out = $git->commit('', $this->user['email'], $this->user['name']);
      error_log(print_r($out, true));
    }

    public function filesystem() {
      $db = \Database\Adapter::getInstance();

      $dir = $this->gitDir;
      if (!file_exists($dir)) {
        mkdir($dir, 0777, true);
        $command = 'git init '.$dir;
        error_log(shell_exec($command));
      }

      $db->query('select * from chapter where bookid=:bookid', array(':bookid' => $this->bookId));
      $db->execute();
      $result = $db->fetch();

      $chapterContent= '';
      foreach ($result as $chapter) {

        $db->query('select * from sections where chapterid=:chapterid', array(':chapterid' => $chapter['id']));
        $db->execute();
        $sections = $db->fetch();
        $xinclude = '';

        foreach ($sections as $section) {
          $filename = str_replace(' ', '_', $section['title']).'-'.$chapter['id'].'-'.$section['id'].'.xml';
          $content = str_replace('<p>', '<para>', $section['content'] );
          $content = str_replace('</p>', '</para>'."\n", $content );
          $content = str_replace('<br>', '', $content );

          $sectionContent = "<section>\n<title>".$section['title']."</title>\n$content\n</section>\n";

          file_put_contents($dir.'/'.$filename, $sectionContent);
          $xinclude .= '<xi:include href="'.$filename.'" xmlns:xi="http://www.w3.org/2001/XInclude" />'."\n";
        }

        $chapterContent .= "<chapter>\n<title>" . $chapter['title'] . "</title>\n$xinclude</chapter>\n";
        $Chapterfilename = str_replace(' ', '_', $chapter['title']).'-'.$chapter['id'].'.xml';
        file_put_contents($dir.'/'.$Chapterfilename, $chapterContent);

      }

      $docbook = "<?xml version=\"1.0\"?>
<book>
    <info>
        <title>Test Book</title>
        <author>
            <personname>
                <firstname>".$this->user['name']."</firstname>
                <surname>".$this->user['surname']."</surname>
            </personname>
        </author>
        <copyright>
            <year>2014</year>
            <holder>".$this->user['name']." ".$this->user['surname']."</holder>
        </copyright>
    </info>
    $chapterContent
</book> ";

      file_put_contents($dir."/docbook.xml", $docbook);
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