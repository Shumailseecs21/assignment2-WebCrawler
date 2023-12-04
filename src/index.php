<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Search Crawler</title>
  <link rel="stylesheet" href="./styles/index.css">
</head>
<body>

<div class="container">
  <h1>Welcome to Search Crawler!</h1>
  <h2>You can search anything</h2>
  <form>
    <input type="text" placeholder="Write any word to search">
    <input type="submit" value="Search">
  </form>
</div>
<div class="container">
    <h1>Search Results</h1>

    <?php

    // Check if the search parameter is set
    if (isset($_GET['search'])) {
        $searchTerm = $_GET['search'];
        $resultFiles = glob('./results/result-*.txt');
        print_r($resultFiles);
        echo "<script>console.log($resultFiles)</script>";
        // Loop through each result file
        foreach ($resultFiles as $resultFile) {
            $fileContent = file_get_contents($resultFile);

            // Check if the search term exists in the file content
            if (stripos($fileContent, $searchTerm) !== false) {
                echo "<h2>Match found in $resultFile</h2>";
                // Read the first line from the file
                $firstLine = fgets(fopen($resultFile, 'r'));

                // Extract the URL from the first line
                $urlExtract = trim(str_replace('URL:', '', $firstLine));
                echo "<br>" . $fileContent;
            }
        }

        // If no matches were found
        if (!isset($fileContent) || empty($fileContent)) {
            echo "<p>No matches found.</p>";
        }
    }
    ?>

</div>

</body>
</html>
