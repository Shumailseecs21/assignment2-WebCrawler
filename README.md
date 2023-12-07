# Web Search Crawler

This is a simple web search crawler implemented in PHP. The crawler is designed to explore and index web pages starting from a seed URL. It consists of three main components: the crawler initializer, the crawler, and the search functionality.

## Components

### 1. **Crawler Initializer**

File: `crawler_initialize.php`

This script initiates the web crawling process. It includes the necessary configurations and starts the crawling by calling the main crawler script (`crawler.php`). The crawler follows links, fetches web pages, and stores the content along with the URL in text files.

### 2. **Crawler**

File: `crawler.php`

The core of the crawler logic is implemented in this script. It defines a class `UrlQueue` to manage the queue of URLs to be crawled. The crawler adheres to the rules defined in `robots.txt` files, fetches web page content, and stores it in text files. It also extracts links from the pages and enqueues them for further crawling.

### 3. **Search Functionality**

File: `index.php`

This script provides a simple web interface for users to search the crawled content. Users can input a search term, and the script will display matching results along with the corresponding file names and content. The search results are retrieved from a MySQL database (`webassignment2`) where the crawler stores the crawled data.

### 4. **Database Configuration**

File: `db_config.php`

This script contains the configuration for the MySQL database. It establishes a connection to the database and provides a class to manage the URL table.

## Instructions

1. **Crawling Initialization:**
   - Execute `crawler_initialize.php` to start the web crawling process.

2. **Searching:**
   - After crawling, users can search for specific terms using the search form in `index.php`.
   - The search results are displayed below the search form, showing file names, matching status, and content.

3. **Database Configuration:**
   - Modify `db_config.php` to update the MySQL database connection details if needed.

## Dependencies

- **MySQL:** Ensure that a MySQL server is running and accessible.
- **PHP:** The scripts are written in PHP, so a PHP environment is required.

## Notes

- The crawler follows the rules defined in `robots.txt` files to respect website crawling policies.
- Crawled data is stored in the `results` directory and indexed in the MySQL database.
- The search interface (`index.php`) provides a simple way to interact with the crawled data.

Feel free to explore, modify, and extend this web search crawler according to your requirements!