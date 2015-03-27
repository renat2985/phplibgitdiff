<style type="text/css">
  body {
    font-family: monospace;
  }

  a {
    color: #000;
  }

  .remove {
    color: #808040;
  }

  .add {
    color: #0080FF;
  }

  td {
    padding: 2px 4px;
  }

  tr:hover {
    background: #ffc;
  }

  h2 {
    margin: 50px 0;
  }

  h4 {
    color: #FF8000;
  }

  h3 {
    color: #0000FF;
    margin-top: 100px;
  }

  h5 {
    color: #008000;
  }

  .download {
    color: rgba(128, 128, 64, 0.34);
  }

  td span {
    float: right;
    display:none;
  }

  td:hover span {
    display:block;
  }
</style>
<?php

require 'lib/GitDiff.class.php';

function display($diff) {
  foreach($diff->files as $file) {
    echo "<h3>$file->file_name</h3>";
    $status = '';
    if ($file->action == 1) {
      $status='Edit';
    }
    if ($file->action == 2) {
      $status='Delete';
    }
    if ($file->action == 3) {
      $status='Create';
    }
    echo "<h5>$status file</h5>";

    foreach($file->sections as $section) {
      echo "<h4>".$section->header."</h4>";
      echo "<table>";
      foreach($section->lines as $line) {
        $class = '';
        if ($line->mode == 1) {
          $class = 'add';
        }

        if ($line->mode == -1) {
          $class = 'remove';
        }

        echo "<tr class=".$class."><td>".$line->line_numbers['left']."</td><td>".$line->line_numbers['right']."</td><td style=\"width: 100%;\">".htmlspecialchars($line)." <span>".$class." line</span></td></tr>";
      }
      echo "</table>";
    }
  }
}

$start = microtime(true);

if ($handle = opendir('./')) {
  while (false !== ($file = readdir($handle))) {
    if (substr($file, strrpos($file, '.') + 1) == 'diff') {
      echo '<h2 id="'.$file.'"> <a href="#'.$file.'">#'.$file.'</a> <a href="./'.$file.'" class="download">download</a></h2>';
      $file = new GitDiff(file_get_contents($file));
      display($file);
    }
  }
  closedir($handle);
}

$time = microtime(true) - $start;

echo '<br><h6>time: '.$time.' / <a href="https://github.com/renat2985">github</a></h6>';
