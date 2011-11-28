<?php
/**
 * ImdbScraper.php
 */

/**
 * ImdbScraper
 * Scrape ""MOVIEmeter Top 10"" toplist from imdb.com.
 */
class ImdbScraper {
	private $chartUrl;
	private $imdbUrl;
	private $cacheFile;
	private $cacheTime;
	
	public function __construct() {
		// Scraper settings.
		$this->chartUrl = 'http://www.imdb.com/chart/'; // Webpage to scrape.
		$this->imdbUrl = 'http://www.imdb.com';
		$this->cacheFile = dirname(__FILE__) . '/cache/imdb_cache.txt';
		$this->cacheTime = 24 * 60 * 60; // 24 hours;
	}
	
	/**
	 * Retrieve scraped document and extract information about the top 10 movies from the scraped source code.
	 * @return array Top 10 movies data (imdb link and movie title w/ year).
	 */
	public function getTop10Data() {
		$top10Data = array();

		// Load scraped source into a DOMDocument for easier manipulation.
		$doc = new DOMDocument();
		if ( !@$doc->loadHTML( $this->getDocumentSource() ) ) {
			return false;
		}

		// Extract the movies from the MOVIEmeter top 10 list.
		$xpath = new DOMXPath( $doc );
		$movies = $xpath->query( '//*/td[@id=\'moviemeter\']/*/tr[@class=\'chart_even_row\' or @class=\'chart_odd_row\']' );

		// Save data about each movie in an array.
		$movieCount = 0;
		foreach ( $movies as $movie ) {
			// Extract the movie title.
			$movieTitle = $movie->lastChild;
			while ( $movieTitle && $movieTitle->nodeType !== 1 ) {
				$movieTitle = $movieTitle->previousSibling;
			}

			// Extract the movie imdb-link.
			$movieLink = $movieTitle->firstChild;
			while ( $movieLink && $movieTitle->nodeType !== 1 ) {
				$movieLink = $movieLink->nextSibling;
			}

			$top10Data[$movieCount]['title'] = utf8_decode( $movieTitle->nodeValue );
			$top10Data[$movieCount]['link'] = utf8_decode( $this->imdbUrl . $movieLink->attributes->getNamedItem( "href" )->nodeValue );

			$movieCount++;
		}


		return $top10Data;
	}
	
	/**
	 * Perform a scraping of the top 10 chart using "cURL" and return data about the transfer.
	 * @return array Scraped Data.
	 */
	private function scrapeImdbToplist() {
		$scrapedData = array();
		
		$scrapedData['error'] = false;
		$scrapedData['status'] = null;
		$scrapedData['content'] = null;
		
		if ( $ch = curl_init( $this->chartUrl ) ) {
			curl_setopt( $ch, CURLOPT_RETURNTRANSFER, true );
			$scrapedData['content'] = curl_exec( $ch );
			$scrapedData['status'] =  curl_getinfo( $ch, CURLINFO_HTTP_CODE );
			curl_close( $ch );
		} else {
			$scrapedData['error'] = true;
		}
		
		return $scrapedData;
	}
	
	/**
	 * Retrieve the scraped document from the cache if it is found and fresh enough.
	 * Otherwise call the scraping function for a fresh scrape, which will be cached.
	 * @return string The source code of the scraped website.
	 */
	private function getDocumentSource() {
		$documentSource = null;
		
		// Check for fresh cache, otherwise perform a new scraping and then cache.
		if ( file_exists( $this->cacheFile ) && ( time() - filemtime( $this->cacheFile ) ) < $this->cacheTime ) {
			// Retrieve cached data.
			if ( $content = @file_get_contents( $this->cacheFile ) ) {
				$documentSource = $content;
			}
		} else {
			// Retrieve fresh data.
			$scrapedData = $this->scrapeImdbToplist();
			if ( $scrapedData['status'] === 200 ) {
				$documentSource = $scrapedData['content'];
				
				// Cache new data.
				if ( $fh = @fopen( $this->cacheFile, "w" ) ) {
					fwrite( $fh, $documentSource );
					fclose( $fh );
				}
			}
		}
		
		return $documentSource;
	}
}
