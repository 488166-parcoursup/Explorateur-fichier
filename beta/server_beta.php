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
    $directories = [];
    $filesOnly = [];

    foreach ($files as $file) {
        if (is_dir($file)) {
            $directories[] = $file;
        } else {
            $filesOnly[] = $file;
        }
    }

    sort($directories);
    sort($filesOnly);

    echo '<ul>';

    foreach ($directories as $dir) {
        $dirName = basename($dir);
        $dirPath = str_replace($GLOBALS['baseDirectory'], '', $dir);
        echo '<li>';
        echo '<a class="text-yellow-600" href="?file=' . ltrim($dirPath, '/') . '"><small>📁</small> ' . $dirName . ' </a>';
        echo '</li>';
        echo '<li class="content" style="display: none;">';
        listFiles($dir);
        echo '</li>';
    }

    foreach ($filesOnly as $file) {
        $fileName = basename($file);
        $fileSize = filesize($file);
        $filePath = str_replace($GLOBALS['baseDirectory'], '', $file);
        $fileType = '📄';

        echo '<li>';
        echo '<a href="' . ltrim($filePath, '/') . '">' . $fileName . ' - ' . formatFileSize($fileSize) . '</a>';
        echo '</li>';
    }

    echo '</ul>';
}

function formatFileSize($size)
{
    $units = ['b', 'Kb', 'Mb', 'Gb', 'Tb'];

    for ($i = 0; $size >= 1024 && $i < 4; $i++) {
        $size /= 1024;
    }

    return round($size, 2) . ' ' . $units[$i];
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
    <h1 class="text-4xl">
        <small>🗂️</small> File Explorer
    </h1>
    <h6 class="mb-4">
        <?php
        echo str_replace($GLOBALS['baseDirectory'], '', $directory);
        ?>
    </h6>
    <?php
    if (str_replace($GLOBALS['baseDirectory'], '', $directory) == "") {
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
    if ($backLink !== $GLOBALS['baseDirectory']) {
        echo '<p><a class="inline-flex items-center font-medium text-blue-600 dark:text-blue-500 hover:underline" href="?file=' . ltrim(str_replace($GLOBALS['baseDirectory'], '', $backLink), '/') . '">Back</a></p>';
    }
    ?>
    <br>
    <?php listFiles($directory); ?>
    <br>
    <hr width="85px">
    <small><em>Version 2B1</em></small>
</body>

</html>