<?php
# Installation:
# - Put in any directory you like on your PHP-capable webspace.
# - Fit the design to your needs just using HTML and CSS. Currently useing basic Bootstrap. 
#
# - requires PHP 5.3.0 or later
#
#Visit https://github.com/fredghostkyle/index

### configuration

# Show the local path. Disable this for security reasons.
define('SHOW_PATH', TRUE);

# Show a link to the parent directory ('..').
define('SHOW_PARENT_LINK', FALSE);

# Show "hidden" directories and files, i.e. those whose names
# start with a dot.
define('SHOW_HIDDEN_ENTRIES', FALSE);

### /configuration


function get_grouped_entries($path) {
    list($dirs, $files) = collect_directories_and_files($path);
    $dirs = filter_directories($dirs);
    $files = filter_files($files);
    return array_merge(
        array_fill_keys($dirs, TRUE),
        array_fill_keys($files, FALSE));
}

function collect_directories_and_files($path) {
    # Retrieve directories and files inside the given path.
    # Also, `scandir()` already sorts the directory entries.
    $entries = scandir($path);
    return array_partition($entries, function($entry) {
        return is_dir($entry);
    });
}

function array_partition($array, $predicate_callback) {
    # Partition elements of an array into two arrays according
    # to the boolean result from evaluating the predicate.
    $results = array_fill_keys(array(1, 0), array());
    foreach ($array as $element) {
        array_push(
            $results[(int) $predicate_callback($element)],
            $element);
    }
    return array($results[1], $results[0]);
}

function filter_directories($dirs) {
    # Exclude directories. Adjust as necessary.
    return array_filter($dirs, function($dir) {
        return $dir != '.'  # current directory
            && (SHOW_PARENT_LINK || $dir != '..') # parent directory
            && !is_hidden($dir);
    });
}

function filter_files($files) {
    # Exclude files. Adjust as necessary.
    return array_filter($files, function($file) {
        return !is_hidden($file)
            && substr($file, -4) != '.php';  # PHP scripts
    });
}

function is_hidden($entry) {
    return !SHOW_HIDDEN_ENTRIES
        && substr($entry, 0, 1) == '.'  # Name starts with a dot.
        && $entry != '.'  # Ignore current directory.
        && $entry != '..';  # Ignore parent directory.
}

$path = __DIR__ . '/';
$entries = get_grouped_entries($path);
?>

<!DOCTYPE html>
<html lang="en">
  <head>
    <meta http-equiv="Content-Type" content="text/html; charset=UTF-8">
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="description" content="My Projects">
    <meta name="author" content="">
    
    <link rel="shortcut icon" href="http://getbootstrap.com/assets/ico/favicon.ico">

    <title>Projects</title>
    <!--Bootstrap's CSS-->
    <link href="http://getbootstrap.com/dist/css/bootstrap.min.css" rel="stylesheet">

  </head>
 
  <body>

    <div class="jumbotron">
      <div class="container ">
        <center>
            <h1>Projects</h1>
            <br>
            <br>
            <br>
        </center>
      </div></div>  
      <div class="container ">
        <!--List-->
        <center>
        <ul class="list-group">
        <?php
            foreach ($entries as $entry => $is_dir) {
                $class_name = $is_dir ? 'directory' : 'file';
                $escaped_entry = htmlspecialchars($entry);
                printf('        <li class="%s list-group-item"><a href="%s">%s</a></li>' . "\n",
                $class_name, $escaped_entry, $escaped_entry);
            }
        ?> 
        </ul>
        </center>
        <br>
        This is basically a list of folders on this server. PhP created by <a href="http://twitter.com/fredghostkyle">@fredghostkyle</a>. <!--While I don't care if you remove this link atleast comment it out for me! Thanks -->
      </div>

    <div class="container">
      <hr>
      <footer>
        © YOU 2014 
      </footer>
    </div> 
    <br>
    <br>
</body>
</html>
