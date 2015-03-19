<style type="text/css">
  body {
    font-family: monospace;
  }

  .remove {
    background: #fcc;
  }

  .add {
    background: #cfc;
  }

  tr {
    background: #eee;
  }

  td {
    padding: 2px 4px;
  }

  tr:hover {
    background: #ffc;
  }
</style>
<?php

require 'lib/GitDiff.class.php';

function display($diff) {
  foreach($diff->files as $file) {
    echo "<h2>$file->file_name</h2>";
    foreach($file->sections as $section) {
      echo "<h3>".$section->header."</h3>";
      echo "<table>";
      foreach($section->lines as $line) {
        $class = '';
        if ($line->mode == 1) {
          $class = 'class="add"';
        }

        if ($line->mode == -1) {
          $class = 'class="remove"';
        }

        echo "<tr $class><td>".$line->line_numbers['left']."</td><td>".$line->line_numbers['right']."</td><td>".$line."</td></tr>";
      }
      echo "</table>";
    }
  }
}

$start = microtime(true);

if ($handle = opendir('./')) {
  while (false !== ($file = readdir($handle))) {
    if (substr($file, strrpos($file, '.') + 1) == 'diff') {
      echo '<h1>'.$file.'</h1>';
        $file = new GitDiff(file_get_contents($file));
        display($file);
    }
  }
  closedir($handle);
}

$time = microtime(true) - $start;

echo 'time: '.$time;
