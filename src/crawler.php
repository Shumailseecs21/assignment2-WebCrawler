<?php
    function isValidUrl($url) {
        $filteredUrl = filter_var($url, FILTER_VALIDATE_URL);
        return $filteredUrl;
        if ($filteredUrl === false) {
            return false; // Not a valid URL
        }

        // Check if the URL ends with .css or .js
        return !preg_match('/\.(css|js)$/i', $url);
    }
    class UrlQueue {
        private $queue = [];

        public function enqueue($url) {
            $this->queue[] = $url;
        }

        public function dequeue() {
            return array_shift($this->queue);
        }

        public function isEmpty() {
            return empty($this->queue);
        }

        public function crawlUrl($url, $depth, $urlQueue) {
            set_time_limit(120);
            if ($depth <= MAX_DEPTH) {
                $htmlContent = file_get_contents($url);
                
                $allowedTags = '<h1><h2><h3><h4><h5><h6><p><ul><ol><li><br><a><href>';
                
                $textData = strip_tags($htmlContent,$allowedTags);
                
                $counterFileName = './counter.txt';

                // Read the current counter value from the file
                $counter = (int)file_get_contents($counterFileName);
                
                // Create the filename using the updated counter
                $counter++;
                $filename = 'results/result-' . $counter . '.txt';
                
                // Write the updated counter value back to the file
                file_put_contents($counterFileName, $counter);

                // $filename = 'pages/' . md5($url) . '.txt';
                file_put_contents($filename, "URL: $url"."\n\n".$textData);
        
                // Display or log information about the crawled URL.
                echo "Crawled: $url\n";
        

                $urls = [];

                // Create a DOMDocument instance
                $dom = new DOMDocument;
                
                // Load HTML content into the DOMDocument
                @$dom->loadHTML($htmlContent);

                // Get all anchor tags (a) in the HTML
                $anchors = $dom->getElementsByTagName('a');
                // Extract href attribute from each anchor tag
                foreach ($anchors as $anchor) {
                    $href = $anchor->getAttribute('href');
                    
                    // Normalize URLs and filter out non-HTTP(s) links
                    $url = isValidUrl($href);
                    
                    if ($url !== false) {
                        $urls[] = $url;
                    }
                    
                }

                // Extract all hyperlinks (URLs) from the crawled HTML and add them to the URL queue.
                $links = $urls;
                foreach ($links as $link) {
                    $urlQueue->enqueue($link);
                }
                print_r($urlQueue);
                // Call the function recursively with the remaining URL queue.
                $nextUrl = $urlQueue->dequeue();
                $this->crawlUrl($nextUrl, $depth + 1, $urlQueue);
            }
            die();
        }
    }
?>
