<?php
    echo "Running...\n";
    require "./crawler.php";
    require "./config.php";

    // Initialize the URL queue
    $urlQueue = new UrlQueue();
    $urlQueue->enqueue(SEED_URL);
    echo "Start crawling...\n";
    // Start crawling
    while (!$urlQueue->isEmpty()) {
        $currentUrl = $urlQueue->dequeue();
        $urlQueue->crawlUrl($currentUrl, 1, $urlQueue);  // Start with depth 
    }
    die();
?>