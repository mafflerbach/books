<?php

  $diff
    = '
diff --git a/Section_1-31-105.xml b/Section_1-31-105.xml
index 834ad72..9f57218 100644
--- a/Section_1-31-105.xml
+++ b/Section_1-31-105.xml
@@ -3 +3,2 @@
{+<para>+}Lorem pisstum{+ Das ist ein weiterer test</para>+}

diff --git a/Section_2-31-106.xml b/Section_2-31-106.xml
index 3ad50b7..047bcaa 100644
--- a/Section_2-31-106.xml
+++ b/Section_2-31-106.xml
@@ -4 +3,0 @@
[-<para>Lorem ipsum dolor sit amet, consetetur sadipscing elitr, sed diam nonumy eirmod</para>-]
diff --git a/section_1-32-107.xml b/section_1-32-107.xml
index a667960..02222b3 100644
--- a/section_1-32-107.xml
+++ b/section_1-32-107.xml
@@ -5,2 +5,2 @@
<para>asdfkja sfjaölsjdf aslködj asdfkja sfjaölsjdf aslködjasdfkja sfjaölsjdf aslködjasdfkja sfjaölsjdf aslködjasdfkja sfjaölsjdf aslködjasdfkja sfjaölsjdf aslködjasdfkja sfjaölsjdf aslködjasdfkja sfjaölsjdf aslködj[-asdfkja sfjaölsjdf aslködjasdfkja sfjaölsjdf aslködjasdfkja sfjaölsjdf aslködjasdfkja sfjaölsjdf aslködjasdfkja sfjaölsjdf aslködjasdfkja sfjaölsjdf aslködjasdfkja sfjaölsjdf aslködjasdfkja sfjaölsjdf aslködjasdfkja sfjaölsjdf aslködjasdfkja sfjaölsjdf aslködjasdfkja sfjaölsjdf aslködjasdfkja sfjaölsjdf aslködjasdfkja sfjaölsjdf aslködjasdfkja sfjaölsjdf aslködjasdfkja sfjaölsjdf aslködjasdfkja sfjaölsjdf aslködjasdfkja sfjaölsjdf aslködjasdfkja sfjaölsjdf aslködjasdfkja sfjaölsjdf aslködjasdfkja sfjaölsjdf aslködj-]</para>
<para>asdfkja sfjaölsjdf aslködjasdfkja sfjaölsjdf aslködjasdfkja sfjaölsjdf aslködjasdfkja sfjaölsjdf aslködjasdfkja sfjaölsjdf aslködjasdfkja sfjaölsjdf aslködj[-asdfkja sfjaölsjdf aslködjasdfkja sfjaölsjdf aslködjasdfkja sfjaölsjdf aslködjasdfkja sfjaölsjdf aslködjasdfkja sfjaölsjdf aslködjasdfkja sfjaölsjdf aslködj-]</para>
';

$numstat = '2	1	Section_1-31-105.xml
0	1	Section_2-31-106.xml
2	2	section_1-32-107.xml';

  parseNumStat($numstat);
function parseNumStat($numstat) {
  $stats = explode("\n", $numstat);
  foreach ($stats as $stat) {
    $statline = explode('	', $stat);
    $file =  explode('-', $statline[2] );

    $sectionId = str_replace('.xml', '', $file[2]);
    print($sectionId."\n");
  }
}


  $test = explode('diff --git', $diff);
  $commit = array();

  for ($i = 1; $i < count($test); $i++) {
    $commit[] = array(
      'filename' => 'file' . $i,
      'patch'    => $test[$i]
    );
  }

  function printLine($line) {
    $line = htmlspecialchars($line);
    $line = replaceGitMarker($line);

    print('
    <tr>
      <td class="line-code">
        <pre>' . $line . '</pre>
      </td>
    </tr>');

  }

  function replaceGitMarker($line) {
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

  function removeTags($line) {

    $line = str_replace('<para>', '', $line);
    $line = str_replace('</para>', '', $line);
    $line = str_replace('<section>', '', $line);
    $line = str_replace('</section>', '', $line);
    $line = str_replace('<title>', '', $line);
    $line = str_replace('</title>', '', $line);

    return $line;
  }

  function printTable($file) {
    $firstLine = TRUE;
    $lines = explode("\n", $file['patch']);

    print('<table class="parseDiff" cellpadding="0" cellspacing="0">');
    $i = 0;
    foreach ($lines as $line) {
      $line = removeTags($line);
      if ($i <= 4) {
        $i++;
        continue;
      }
      if ($line == '') {
        continue;
      }

      if (!$firstLine) {
        printLine($line);
      } else {
        printLine($line);
        $firstLine = FALSE;
      }
    }
    print('</table>');
  }

  function buildDiff($commit) {
    foreach ($commit as $file) {
      print('<div>');
      print('<div style="overflow: auto">');
      print('<div>Section:</div>');
      printTable($file);
      print('</div>
  </div>');
    }
  }

?>


<html>
<head>
  <style>
    pre {
      white-space: pre-wrap; /* css-3 */
      white-space: -moz-pre-wrap; /* Mozilla, since 1999 */
      white-space: -pre-wrap; /* Opera 4-6 */
      white-space: -o-pre-wrap; /* Opera 7 */
      word-wrap: break-word; /* Internet Explorer 5.5+ */
      padding: 0px;
      margin: 2px;
    }

    .line-code span.add {
      background-color: rgba(0, 200, 0, 0.1);
    }

    .line-code span.remove {
      background-color: rgba(255, 0, 0, 0.15);
    }

    .parseDiff {
      border: 1px solid lightgray;
      margin: 5px;
      background: #fcfff4; /* Old browsers */
      background: -moz-linear-gradient(top, #fcfff4 0%, #e9e9ce 100%); /* FF3.6+ */
      background: -webkit-gradient(linear, left top, left bottom, color-stop(0%,#fcfff4), color-stop(100%,#e9e9ce)); /* Chrome,Safari4+ */
      background: -webkit-linear-gradient(top, #fcfff4 0%,#e9e9ce 100%); /* Chrome10+,Safari5.1+ */
      background: -o-linear-gradient(top, #fcfff4 0%,#e9e9ce 100%); /* Opera 11.10+ */
      background: -ms-linear-gradient(top, #fcfff4 0%,#e9e9ce 100%); /* IE10+ */
      background: linear-gradient(to bottom, #fcfff4 0%,#e9e9ce 100%); /* W3C */
      filter: progid:DXImageTransform.Microsoft.gradient( startColorstr='#fcfff4', endColorstr='#e9e9ce',GradientType=0 ); /* IE6-9 */
    }

  </style>
</head>
<body>
<?php
  buildDiff($commit);
?>

</body>
</html>