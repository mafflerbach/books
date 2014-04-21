<?php

  $diff
    = '
diff --git a/Section_1-31-105.xml b/Section_1-31-105.xml
index 834ad72..9f57218 100644
--- a/Section_1-31-105.xml
+++ b/Section_1-31-105.xml
@@ -1,4 +1,5 @@
<section>
<title>Section 1</title>
{+<para>+}Lorem pisstum{+ Das ist ein weiterer test</para>+}

</section>
diff --git a/Section_2-31-106.xml b/Section_2-31-106.xml
index 3ad50b7..047bcaa 100644
--- a/Section_2-31-106.xml
+++ b/Section_2-31-106.xml
@@ -1,7 +1,6 @@
<section>
<title>Section 2</title>
<para>Lorem ipsum dolor sit amet, consetetur sadipscing elitr, sed diam nonumy eirmod</para>
[-<para>Lorem ipsum dolor sit amet, consetetur sadipscing elitr, sed diam nonumy eirmod</para>-]
<para>Lorem ipsum dolor sit amet, consetetur sadipscing elitr, sed diam nonumy eirmo </para>

</section>
diff --git a/section_1-32-107.xml b/section_1-32-107.xml
index a667960..02222b3 100644
--- a/section_1-32-107.xml
+++ b/section_1-32-107.xml
@@ -2,7 +2,7 @@
<title>section 1</title>
<para>Lorem ipsum dolor sit amet, consetetur sadipscing elitr, sed diam nonumy eirmod</para>
<para>adksjfaljd fasdjf </para>
<para>asdfkja sfjaölsjdf aslködj asdfkja sfjaölsjdf aslködjasdfkja sfjaölsjdf aslködjasdfkja sfjaölsjdf aslködjasdfkja sfjaölsjdf aslködjasdfkja sfjaölsjdf aslködjasdfkja sfjaölsjdf aslködjasdfkja sfjaölsjdf aslködj[-asdfkja sfjaölsjdf aslködjasdfkja sfjaölsjdf aslködjasdfkja sfjaölsjdf aslködjasdfkja sfjaölsjdf aslködjasdfkja sfjaölsjdf aslködjasdfkja sfjaölsjdf aslködjasdfkja sfjaölsjdf aslködjasdfkja sfjaölsjdf aslködjasdfkja sfjaölsjdf aslködjasdfkja sfjaölsjdf aslködjasdfkja sfjaölsjdf aslködjasdfkja sfjaölsjdf aslködjasdfkja sfjaölsjdf aslködjasdfkja sfjaölsjdf aslködjasdfkja sfjaölsjdf aslködjasdfkja sfjaölsjdf aslködjasdfkja sfjaölsjdf aslködjasdfkja sfjaölsjdf aslködjasdfkja sfjaölsjdf aslködjasdfkja sfjaölsjdf aslködj-]</para>
<para>asdfkja sfjaölsjdf aslködjasdfkja sfjaölsjdf aslködjasdfkja sfjaölsjdf aslködjasdfkja sfjaölsjdf aslködjasdfkja sfjaölsjdf aslködjasdfkja sfjaölsjdf aslködj[-asdfkja sfjaölsjdf aslködjasdfkja sfjaölsjdf aslködjasdfkja sfjaölsjdf aslködjasdfkja sfjaölsjdf aslködjasdfkja sfjaölsjdf aslködjasdfkja sfjaölsjdf aslködj-]</para>

</section>

';

  $test = explode('diff --git', $diff);
  $commit = array();

  for ($i = 1; $i < count($test); $i++) {
    $commit[] = array(
      'filename' => 'file' . $i,
      'patch'    => $test[$i]
    );
  }

?>

<?php foreach ($commit as $file) { ?>
  <div>
    <?php
      $firstLine = TRUE;
      $patch = explode("@@", $file['patch']);
      $lines = explode("\n", $file['patch']);
    ?>
    <div style="overflow: auto">
      <table class="parseDiff" cellpadding="0" cellspacing="0">
        <?php foreach ($lines as $line) {
          if (!$firstLine) {
            printLine($line);
          } else {
            printLine($line);
            $firstLine = FALSE;
          }
        }
        ?>
      </table>
    </div>
  </div>
<?php } ?>


<?php

  function getLineType($start_line, $line) {
    $char = strlen($line) ? $line[0] : '~';
    switch ($char) {
      case '-':
        $start_line['left']++;
        $type = "removed";
        break;
      case '+':
        $start_line['right']++;
        $type = "added";
        break;
      default:
        $start_line['left']++;
        $start_line['right']++;
        $type = "neutral";
        break;
    }

    return $type;
  }

  function printLine($line) {
    $start_line = array();
    $line = htmlspecialchars($line);
    $line = replaceGitMarker($line);
    print('
  <tr class="line-type-' . getLineType($start_line, $line) . '">
              <td class="line-code">
                <pre>' . $line . '</pre>
              </td>
            </tr>');

  }

  function replaceGitMarker($line) {
    $line = str_replace('[- eirmod', '<span style="background-color: lightpink">', $line);
    $line = str_replace('{+ eirmod', '<span style="background-color: lightgreen">', $line);
    $line = str_replace('eirmod[-', '<span style="background-color: lightpink">', $line);
    $line = str_replace('eirmod{+', '<span style="background-color: lightgreen">', $line);
    $line = str_replace('[-eirmod', '<span style="background-color: lightpink">', $line);
    $line = str_replace('{+eirmod', '<span style="background-color: lightgreen">', $line);
    $line = str_replace('[-', '<span style="background-color: lightpink">', $line);
    $line = str_replace('{+', '<span style="background-color: lightgreen">', $line);
    $line = str_replace('+}', '</span>', $line);
    $line = str_replace('-]', '</span>', $line);

    return $line;
  }

?>