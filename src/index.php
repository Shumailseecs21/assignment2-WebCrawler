<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Search Crawler</title>
  <link rel="stylesheet" href="./styles/index.css">
</head>
<body>

<div class="container" id="container">
  <h1>Welcome to Search Crawler!</h1>
  <h2>You can search anything</h2>
  <form action="" method="POST">
    <input name="searchInput" type="text" placeholder="Write any word to search" id="searchForm">
    <input type="submit" value="Search">
  </form>
</div>
    <?php

    // Check if the search parameter is set
    if (isset($_POST['searchInput'])) {
        echo "<script>
            var searchResultsDiv = document.createElement('div');
            searchResultsDiv.id = 'container2';
            searchResultsDiv.className='container';
            
            var h1Element = document.createElement('h1');
            h1Element.innerHTML = 'Search Results';
            
            searchResultsDiv.appendChild(h1Element);
            
            document.body.appendChild(searchResultsDiv);
            </script>";
        $searchTerm = $_POST['searchInput'];
        $resultFiles = glob('./results/result-*.txt');
        // Loop through each result file
        foreach ($resultFiles as $resultFile) {
            $fileContent = file_get_contents($resultFile);

            // Check if the search term exists in the file content
            if (stripos($fileContent, $searchTerm) !== false) {
                echo "<script>
                var searchResultsDiv=document.getElementById('container2');
                const h2Element = document.createElement('h2');
                h2Element.textContent = 'Match found in $resultFile';
                console.log(h2Element);
                searchResultsDiv.appendChild(h2Element);
                </script>";
                // Read the first line from the file
                $firstLine = fgets(fopen($resultFile, 'r'));
                
                // Extract the URL from the first line
                $urlExtract = trim(str_replace('URL:', '', $firstLine));
                echo "<script>
                var searchResultsDiv=document.getElementById('container2');
                const pElement = document.createElement('p');
                pElement.innerHTML=`$fileContent`;
                console.log(pElement);
                searchResultsDiv.appendChild(pElement);
                </script>";
            }
            else{
                echo "<script>
                var searchResultsDiv=document.getElementById('container2');
                const h2Element = document.createElement('h2');
                h2Element.innerHTML = 'Match not found';
                searchResultsDiv.appendChild(h2Element);
                </script>";
            }
        }
    }
    ?>


</body>
</html>
