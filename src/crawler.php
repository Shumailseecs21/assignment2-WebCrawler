<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Web Crawler/Spider</title>
</head>
<body>
<?php
// Function to check if a URL is valid and not ending with .css or .js
function isValidUrl($url) {
    $filteredUrl = filter_var($url, FILTER_VALIDATE_URL);
    if ($filteredUrl === false) {
        return false; // Not a valid URL
    }
    

    // Check if the URL ends with .css or .js
    return !preg_match('/\.(css|js)$/i', $url);
}

// Function to check if a URL is allowed based on rules in robots.txt
function isUrlAllowedByRobotsTxt($url) {
    $robotsTxtUrl = rtrim($url, '/') . '/robots.txt';

    // Fetch the content of the robots.txt file
    $robotsTxtContent = @file_get_contents($robotsTxtUrl);

    // If unable to fetch the content, assume the URL is allowed
    if ($robotsTxtContent === false) {
        return true;
    }

    // Parse the robots.txt content
    $lines = explode("\n", $robotsTxtContent);
    $userAgent = '*'; // Assuming we are a generic user agent

    foreach ($lines as $line) {
        // Skip comments and empty lines
        $line = trim($line);
        if (empty($line) || $line[0] == '#') {
            continue;
        }

        // Check if the line contains a user agent definition
        if (strtolower(substr($line, 0, 12)) === 'user-agent:') {
            $userAgent = trim(substr($line, 12));
            continue;
        }

        // Check if the rule applies to our user agent
        if ($userAgent === '*' || stripos($line, $userAgent) !== false) {
            // Extract the disallowed paths
            if (strtolower(substr($line, 0, 10)) === 'disallow:') {
                $disallowedPath = trim(substr($line, 10));

                // If the URL starts with the disallowed path, it's not allowed
                if (strpos($url, $disallowedPath) === 0) {
                    return false;
                }
            }
        }
    }

    // If no disallowed paths were found, the URL is allowed
    return true;
}

// Class to manage a queue of URLs
class UrlQueue {
    private $queue = [];

    // Enqueue a URL
    public function enqueue($url) {
        $this->queue[] = $url;
    }

    // Dequeue a URL
    public function dequeue() {
        return array_shift($this->queue);
    }

    // Check if the queue is empty
    public function isEmpty() {
        return empty($this->queue);
    }

    // Crawl a URL with specified depth
    public function crawlUrl($url, $depth, $urlQueue) {
        set_time_limit(120);

        $counterFileName = './counter.txt';
            
        $counter = (int)file_get_contents($counterFileName);
        // Check if the URL is allowed by robots.txt
        if (!isUrlAllowedByRobotsTxt($url)) {
            echo "URL not allowed by robots.txt: $url\n";
            return;
        }
        if ($depth <= MAX_DEPTH) {
            if($depth>1){
                if($urlQueue->isEmpty()){ //if queue is empty then exit the crawling
                    exit();
                }

                $filenameHtmlExtract = 'results/result-' . $counter . '.txt';

                // Read the first line from the file
                $firstLine = fgets(fopen($filenameHtmlExtract, 'r'));

                // Extract the URL from the first line
                $urlExtract = trim(str_replace('URL:', '', $firstLine));
                if($urlExtract===$url){
                    $nextUrlExtract = $urlQueue->dequeue();            
                    $urlQueue->crawlUrl($nextUrlExtract, $depth, $urlQueue);

                }
            }
            
            $conn=new mysqli("localhost","root","12345678","webassignment2");
                if ($conn->connect_error) { 
                    die("Connection failed: ". $conn->connect_error);
                }else{
                    echo "Connected successfully";
                }
            $sql=$conn->prepare("INSERT INTO urlTable(file_name,url,content) VALUES(?,?,?)");
            $sql->bind_param("sss",$connFileName,$connUrl,$connContent);

            
            // Fetch the content of the page
            $htmlContent = file_get_contents($url);
            
            // Check if the page could be fetched
            if ($htmlContent === false) {
                echo "Error fetching content from: $url\n";
                return;
            }
            
            // Attempt to parse the HTML content
            $dom = new DOMDocument();
            $success = @$dom->loadHTML($htmlContent);
            
            // Check if the HTML parsing was successful
            if (!$success) {
                echo "Error parsing HTML content from: $url\n";
                return;
            }
            $allowedTags = '<body>';
            $textContent = strip_tags($htmlContent, $allowedTags);
            
            $counter++;
            $filenameHtml = 'results/result-' . $counter . '.txt';
            
            file_put_contents($counterFileName, $counter);
            
            // Save the content to a file
            file_put_contents($filenameHtml, "URL: $url"."\n\n".$textContent);
            
            $connFileName=$filenameHtml;
            $connUrl=$url;
            $connContent=$textContent;

            echo "Crawled<br>";

            $sql->execute();
            $sql->close();
            echo "Stored in db<br>";
            $conn->close();
            $urls = [];
            
            // Load modified HTML content into DOMDocument
            $dom = new DOMDocument();
            // $htmlContent__modified = file_get_contents("./results/result-".$counter.".html");
            @$dom->loadHTML($htmlContent);
            
            $anchors = $dom->getElementsByTagName('a');
            
            foreach ($anchors as $anchor) {
                $href = $anchor->getAttribute('href');
                // Normalize URLs and filter out non-HTTP(s) links
                if (isValidUrl($href)) {
                    $url = $href;
                };
                
                if ($url !== false) {
                    $urls[] = $url;
                }
            }
            $links = $urls;
            foreach ($links as $link) {
                $urlQueue->enqueue($link);
            }
            
            $nextUrl = $urlQueue->dequeue();            
            $urlQueue->crawlUrl($nextUrl, $depth + 1, $urlQueue);
        } else {
            exit();
        }
    }
}
?>
</body>
</html>
