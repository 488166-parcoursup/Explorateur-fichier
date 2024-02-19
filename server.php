<?php
$baseDirectory = __DIR__;
$directory = $baseDirectory;

if (isset($_GET['file'])) {
  $file = $_GET['file'];
  $filePath = $directory . '/' . $file;

  if (is_dir($filePath)) {
    $directory = $filePath;
  } elseif (is_file($filePath) && pathinfo($filePath, PATHINFO_EXTENSION) === 'php') {
    require $filePath;
    exit;
  } elseif (is_file($filePath)) {
    header('Location: ' . $filePath);
    exit;
  }
}

function listFiles($directory)
{
  $files = glob($directory . '/*');
  echo '<ul>';
  foreach ($files as $file) {
    $fileName = basename($file);
    $fileType = is_dir($file) ? '📁' : '📄';

    echo '<li>';

    if (is_file($file)) {
      echo '<a href="' . str_replace($GLOBALS['baseDirectory'] . '/', '', $file) . '">' . $fileName . ' (' . $fileType . ')</a>';
    }

    if (is_dir($file)) {
      echo '<a href="?file=' . str_replace($GLOBALS['baseDirectory'] . '/', '', $file) . '">' . $fileName . ' (' . $fileType . ')</a>';
    }

    echo '</li>';

    if (is_dir($file)) {
      echo '<li class="content" style="display: none;">';
      listFiles($file);
      echo '</li>';
    }
  }
  echo '</ul>';
}
?>

<!DOCTYPE html>
<html>

<head>
  <title>Index</title>
  <style>
    .content {
      margin-left: 20px;
    }
  </style>
  <script src="https://cdn.tailwindcss.com"></script>
  <script>
    function toggleContent(element) {
      var listItem = element.parentNode.parentNode;
      var contentItem = listItem.nextElementSibling;

      if (contentItem.style.display === 'none') {
        contentItem.style.display = 'block';
      } else {
        contentItem.style.display = 'none';
      }
    }
  </script>
</head>

<body class="bg-slate-100 p-6 font-serif dark:bg-stone-800 dark:text-slate-300">
  <h1 class="text-4xl"> 🗂️ <?php echo str_replace($baseDirectory, '', $directory); ?></h1>
  <?php
  if (str_replace($baseDirectory, '', $directory) == "") {
    $display_back = "none";
  } else {
    $display_back = "inline-flex";
  }
  ?>
  <a href="./server.php?=" class="inline-flex items-center font-medium text-blue-600 dark:text-blue-500 hover:underline" style="display: <?php echo $display_back; ?>;">
    <svg aria-hidden="true" class="w-5 h-5 ml-1" fill="currentColor" viewBox="0 0 20 20" xmlns="http://www.w3.org/2000/svg">
      <path fill-rule="evenodd" d="M7.707 14.707a1 1 0 01-1.414 0l-4-4a1 1 0 010-1.414l4-4a1 1 0 011.414 1.414L5.414 9H17a1 1 0 110 2H5.414l2.293 2.293a1 1 0 010 1.414z" clip-rule="evenodd"></path>
    </svg>
    Back to src
  </a>


  <?php
  // Génère le lien pour revenir au répertoire précédent
  $backLink = dirname($directory);
  if ($backLink !== $baseDirectory) {
    echo '<p><a class="inline-flex items-center font-medium text-blue-600 dark:text-blue-500 hover:underline" href="?file=' . str_replace($baseDirectory . '/', '', $backLink) . '">Back</a></p>';
  }
  ?>
  <br>
  <?php listFiles($directory); ?>
</body>

</html>