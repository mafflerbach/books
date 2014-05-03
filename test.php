<?php

  $diff
    = '
diff --git a/Neuer_Prozess-15-21.xml b/Neuer_Prozess-15-21.xml
index d17875a..8dd23a1 100644
--- a/Neuer_Prozess-15-21.xml
+++ b/Neuer_Prozess-15-21.xml
@@ -5,2 +5,2 @@
<para>Dies bedeutet , das ein zweiter Entwickler eine Review über den erzeugten code macht. Er gibt dem ersten Entwickler Feedback, ob er noch Änderungen vornehmen muss, oder ob die Tests ausreichend vollständig gemacht wurden. Der zweite Entwickler übernimmt  [-&nbsp;eine-]{+eine+} Teilverantwortung an dem umgesetzten Feature. Dieses vorgehen bringt uns zudem einen entscheidenden Nebeneffekt - <emphasis role="underline">Jeder Entwickler kennt jedes Projekt</emphasis>. So können zuvor Tester eines Projekte recht effizient in ein ihm bekanntes Projekt eingesetzt werden, wenn der jeweilige Stammentwickler ausgefallen ist, oder er anderweitig in einem Projekt gebunden ist.</para>
<para>Durch die Implementierung des 4 Augenprizips werden so die Akzeptanz der funktionalen Tests erzwungen, und weitergehen die Qualität der Unit-Test [-erhöht.&nbsp;</para>-]{+erhöht. </para>+}
diff --git a/Schulungen-15-24.xml b/Schulungen-15-24.xml
index ef82e25..58095d3 100644
--- a/Schulungen-15-24.xml
+++ b/Schulungen-15-24.xml
@@ -4,2 +4,2 @@
<para>Die Entwickler müssen noch darin geschult werden ihr Augenmerk auch auf die funktionalen Tests zu richten. Er muss wissen wann er im laufe der Entwicklung die Screenshot-Tests erstellen muss. Wo die Referenzbilder abgelegt werden müssen. Wann werden die anderen funktionalen Tests erstellt, und werden wie [-abgelegt.&nbsp;</para>-]{+abgelegt. </para>+}
<para>Dies alles soll in einem Workshop in kleinen Gruppen vermittelt werden. Derzeit sind es etwa acht Entwickler die Schulungsbedarf haben. Vorgeschlagen wird, das in jeweils vierer Gruppen geschult wird. So ist ausreichend Raum für fragen und antworten.  [-&nbsp;</para>-]{+</para>+}

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
    $line = str_replace('<itemizedlist>', '', $line);
    $line = str_replace('</itemizedlist>', '', $line);
    $line = str_replace('<listitem>', '', $line);
    $line = str_replace('</listitem>', '', $line);

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