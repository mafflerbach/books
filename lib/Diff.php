<?php

  /**
   * Created by PhpStorm.
   * User: maren
   * Date: 03.05.14
   * Time: 11:47
   */
  class Diff {
    private $patch;

    public function __construct($patch = "") {
      $this->patch = $patch;
    }

    public function setPatch($patch) {
      $this->patch = $patch;
    }

    private function getCommits() {
      $test = explode('diff --git', $this->patch);
      $commit = array();



      for ($i = 1; $i < count($test); $i++) {
        $commit[] = array(
          'filename' => 'file' . $i,
          'patch'    => $test[$i]
        );
      }

      return $commit;
    }


    private function parseIds($test) {


      $tmp = explode("\n", $test);
      $tmp = explode(" ", $tmp[0]);

      $file =  explode('-', str_replace('a/', '', $tmp[1]));
      $sectionId = str_replace('.xml', '', $file[2]);

      $result = array('sectionId' => $sectionId,
      'chapterId' =>$file[1]);

      return $result;
    }


    private function replaceGitMarker($line) {
      $line = str_replace('[- eirmod', '<span class="remove">', $line);
      $line = str_replace('{+ eirmod', '<span  class="add">', $line);
      $line = str_replace('eirmod[-', '<span  class="remove">', $line);
      $line = str_replace('eirmod{+', '<span  class="add">', $line);
      $line = str_replace('[-eirmod', '<span  class="remove">', $line);
      $line = str_replace('{+eirmod', '<span  class="add">', $line);
      $line = str_replace('[-', '<span  class="remove">', $line);
      $line = str_replace('{+', '<span  class="add">', $line);
      $line = str_replace('+}', '</span>', $line);
      $line = str_replace('-]', '</span>', $line);

      return $line;
    }

    private function removeTags($line) {

      $line = str_replace('<para>', '', $line);
      $line = str_replace('</para>', '', $line);
      $line = str_replace('<section>', '', $line);
      $line = str_replace('</section>', '', $line);
      $line = str_replace('<title>', '', $line);
      $line = str_replace('</title>', '', $line);

      return $line;
    }

    private function printLine($line) {
      $line = htmlspecialchars($line);
      $line = $this->replaceGitMarker($line);

      return ('<tr>
                <td class="line-code">
                  <pre>' . $line . '</pre>
                </td>
              </tr>');
    }

    private function buildTable($file) {
      $table = '';
      $firstLine = TRUE;
      $lines = explode("\n", $file['patch']);

      $table .= '<table class="parseDiff" cellpadding="0" cellspacing="0">';
      $i = 0;
      foreach ($lines as $line) {
        $line = $this->removeTags($line);
        if ($i <= 4) {
          $i++;
          continue;
        }
        if ($line == '') {
          continue;
        }

        if (!$firstLine) {
          $table .= $this->printLine($line);
        } else {
          $table .= $this->printLine($line);
          $firstLine = FALSE;
        }
      }
      $table .= '</table>';

      return $table;
    }

    private function getChapterTitle($id) {
      $this->db()->query('select * from chapter where id=:id',
        array(':id' => $id
        )
      );

      $this->db()->execute();
      $res = $this->db()->fetch();
      return $res[0]['title'];
    }

    private function getSectionTitle($id) {
      $this->db()->query('select * from sections where id=:id',
        array(':id' => $id
        )
      );

      $this->db()->execute();
      $res = $this->db()->fetch();
      return $res[0]['title'];

    }


    public function buildDiff() {
      $commit = $this->getCommits();
      $output = '';

      foreach ($commit as $file) {
        $output .= '<div>';
        $output .= '<div style="overflow: auto">';
        $ids = $this->parseIds($file['patch']);
        $output .= '<div>Chapter: '.$this->getChapterTitle($ids['chapterId']) . ', Section: '.$this->getSectionTitle($ids['sectionId']).'</div>';
        $output .= $this->buildTable($file);
        $output .= '</div>';
        $output .= '</div>';
      }

      return $output;
    }


    public function db(\Database\Adapter $instance = null) {
      if ($instance != null) {
        $this->db = $instance;
      } else {
        $this->db = \Database\Adapter::getInstance();
      }
      return $this->db;
    }

  }