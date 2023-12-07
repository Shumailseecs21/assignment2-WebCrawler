<!-- <!DOCTYPE html>
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
    require "./db_config.php";
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
</html> -->
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
    echo "<div class='container' id='container2'>
            <h1>Search Results</h1>";

    $searchTerm = $_POST['searchInput'];
    $conn=new mysqli("localhost","root","12345678","webassignment2");
        if ($conn->connect_error) { 
            die("Connection failed: ". $conn->connect_error);
        }else{
            echo "Connected successfully";
        }

    // Prepare and execute a query
    $stmt = $conn->prepare("SELECT id, file_name, url, content FROM urlTable WHERE content LIKE ?");
    $searchTerm = "%{$searchTerm}%";
    $stmt->bind_param("s", $searchTerm);
    $stmt->execute();
    $result = $stmt->get_result();

    // Process the results
    while ($row = $result->fetch_assoc()) {
        echo "<h2>Match found in {$row['file_name']}</h2>";
        echo "<p>{$row['content']}</p>";
    }

    // Check if no results were found
    if ($result->num_rows === 0) {
        echo "<h2>Match not found</h2>";
    }

    // Close the database connection
    $stmt->close();
    $conn->close();

    echo "</div>";
}
?>

</body>
</html>
