<?php

/*
 * The code in this file was written by Chris Hope <chris@electrictoolbox.com> and made available
 * from my website at http://www.electrictoolbox.com
 *
 * Feel free to use it as you wish but if re-posting it on other websites or using it in your own
 * projects or for your customers, please attribute the base work to me and do not try to pass it 
 * off as your own.
 *
 * This class is based on the documentation here, and from trial and error:
 *   http://code.google.com/apis/analytics/docs/gdata/1.0/gdataProtocol.html
 *
 * The list of dimensions and metrics available can be found here:
 *   http://code.google.com/apis/analytics/docs/gdata/gdataReferenceDimensionsMetrics.html
 *
 * Code examples etc for this class can be found at my website at the following URL:
 *   http://www.electrictoolbox.com/google-analytics-api-and-php/
 * and also in the example.php script
 * 
 * Last updated: May 5th 2009, 11:18am NZST
 * 
 */

//-------------------------------------------------------------------------------------------------
class analytics_api {
//-------------------------------------------------------------------------------------------------

	/**
	 * The authorization token returned when logging in. It's used for subsequent requests.
	 * 
	 * @var string
	 */
	protected $auth;
	
	/**
	 * A list of accounts retrieved from the load_accounts() method. This is an associative array
	 * where the index is the profile name / domain name and the values are tableId, accountId,
	 * accountName, webPropertyId and profileId. The profileId and tableId are synonymous, although
	 * the tableId includes the ga: prefix whereas the accountId does not. It's the tableId that
	 * needs to be passed as the id to the call() method
	 *
	 * @var array
	 */
	public $accounts;
	
	/**
	 * Logs into the Google Analytics API and sets $this->auth to the authorisation token returned
	 *
	 * @param string $email The email address of your Google Analytics account
	 * @param string $password Password for the account
	 * @return boolean True if the login succeeded, false if not
	 */
	//---------------------------------------------------------------------------------------------
	public function login($email, $password) {
	//---------------------------------------------------------------------------------------------
		
		$ch = $this->curl_init("https://www.google.com/accounts/ClientLogin");
		curl_setopt($ch, CURLOPT_POST, true);
		
		$data = array(
			'accountType' => 'GOOGLE',
			'Email' => $email,
			'Passwd' => $password,
			'service' => 'analytics',
			'source' => ''
		);
		
		curl_setopt($ch, CURLOPT_POSTFIELDS, $data);
		$output = curl_exec($ch);
		$info = curl_getinfo($ch);
		curl_close($ch);
		
		$this->auth = '';
		if($info['http_code'] == 200) {
			preg_match('/Auth=(.*)/', $output, $matches);
			if(isset($matches[1])) {
				$this->auth = $matches[1];
			}
		}
		
		return $this->auth != '';
	
	}
	
	/**
	 * Calls an API function using the url passed in and returns either the XML returned from the
	 * call or false on failure
	 *
	 * @param string $url 
	 * @return string or boolean false
	 */
	//---------------------------------------------------------------------------------------------
	public function call($url) {
	//---------------------------------------------------------------------------------------------

		$headers = array("Authorization: GoogleLogin auth=$this->auth");
		
		$ch = $this->curl_init($url);
		curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);
		$output = curl_exec($ch);
		$info = curl_getinfo($ch);
		curl_close($ch);

		// set return value to a default of false; it will be changed to the return string on success
		$return = false;
		
		if($info['http_code'] == 200) {
			$return = $output;
		}
		elseif($info['http_code'] == 400) {
			trigger_error('Badly formatted request to the Google Analytics API; check your profile id is in the format ga:12345, dates are correctly formatted and the dimensions and metrics are correct', E_USER_WARNING);
		}
		elseif($info['http_code'] == 401) {
			trigger_error('Unauthorized request to the Google Analytics API', E_USER_WARNING);
		}
		else {
			trigger_error("Unknown error when accessing the Google Analytics API, HTTP STATUS {$info['http_code']}", E_USER_WARNING);
		}

		return $return;
		
	}
	
	/**
	 * Loads the list of accounts into the $this->accounts associative array. You can then access
	 * the properties by the profile's domain name.
	 */
	//---------------------------------------------------------------------------------------------
	public function load_accounts() {
	//---------------------------------------------------------------------------------------------

		$xml = $this->call('https://www.google.com/analytics/feeds/accounts/default');
		
		$dom = new DOMDocument();
		$dom->loadXML($xml);
		
		$entries = $dom->getElementsByTagName('entry');
		$this->accounts = array();
		foreach($entries as $entry) {
		
			$titles = $entry->getElementsByTagName('title');
			$title = $titles->item(0)->nodeValue;

			$this->accounts[$title] = array();
			
			$tableIds = $entry->getElementsByTagName('tableId');
			$this->accounts[$title]['tableId'] = $tableIds->item(0)->nodeValue;
			
			$properties = $entry->getElementsByTagName('property');
			foreach($properties as $property) {
				switch($property->getAttribute('name')) {
					case 'ga:accountId':
						$this->accounts[$title]['accountId'] = $property->getAttribute('value');
					break;
					case 'ga:accountName':
						$this->accounts[$title]['accountName'] = $property->getAttribute('value');
					break;
					case 'ga:webPropertyId':
						$this->accounts[$title]['webPropertyId'] = $property->getAttribute('value');
					break;
					case 'ga:profileId':
						$this->accounts[$title]['profileId'] = $property->getAttribute('value');
					break;
				}
			}
			
		}
		
	}
	
	/**
	 * Calls the API using the parameters passed in and returns the data in an array.
	 *
	 * @param string $id The profile's id e.g. ga:7426158
	 * @param string $dimension The dimension(s) to use. If more than one dimension is used then
	 *   comma separate the values e.g. ga:pagePath or ga:browser,ga:browserVersion
	 * @param string $metric The metric(s) to use. If more than one metric is used then
	 *   comma separate the values e.g. ga:visits or ga:visits,ga:pageviews
	 * @param string $sort The sort order, one of the metrics fields. Use - in front of the name
	 *   to reverse sort it. The default is to do a -$metric sort.
	 * @param string $start The start date of the data to include in YYYY-MM-DD format. The default
	 *   is 1 month ago.
	 * @param string $end The end date of the data to include in YYYY-MM-DD format. The default is
	 *   yesterday.
	 * @param integer $max_results The maximum number of results to retrieve. If the value is greater
	 *   than 1000 the API will still only return 1000.
	 * @param integer $start_index The index to start from. The first page is 1 (which is the defult)
	 *   and the second page, if getting 1000 results at a time, is 1001.
	 * @return array Returns an array indexed by the first dimension (then second dimension, etc) with
	 *   a value for each metric.
	 */
	//---------------------------------------------------------------------------------------------
	public function data($id, $dimension, $metric, $sort = false, $start = false, $end = false, $max_results = 10, $start_index = 1) {
	//---------------------------------------------------------------------------------------------
		
		if(!$sort) $sort = "-$metric";
		if(!$start) $start = date('Y-m-d', strtotime('1 month ago'));
		if(!$end) $end = date('Y-m-d', strtotime('yesterday'));
				
		$xml = $this->call("https://www.google.com/analytics/feeds/data?ids=$id&dimensions=$dimension&metrics=$metric&sort=$sort&start-date=$start&end-date=$end&max-results=$max_results&start-index=$start_index");

		if(!$xml) {
			return false;
		}

		$dom = new DOMDocument();
		$dom->loadXML($xml);

		$entries = $dom->getElementsByTagName('entry');
		$data = array();
		foreach($entries as $entry) {
		
			$index = array();
			foreach($entry->getElementsByTagName('dimension') as $mydimension) {
				$index[] = $mydimension->getAttribute('value');
			}
		
			// find out how many dimensions are present and have an array index for each dimension
			// if there are no dimensions then the indexes are just the metric names
			// if there's a single dimension the array will be $data['dimension1'] = ...
			// if there's two dimensions the array will be $data['dimension1']['dimension2'] = ...
			// if there's three dimensions the array will be $data['dimension1']['dimension2']['dimension3'] = ...
		
			switch(count($index)) {
		
				case 0:
					foreach($entry->getElementsByTagName('metric') as $metric) {
						$data[$metric->getAttribute('name')] = $metric->getAttribute('value');
					}
				break;
				
				case 1:
					foreach($entry->getElementsByTagName('metric') as $metric) {
						$data[$index[0]][$metric->getAttribute('name')] = $metric->getAttribute('value');
					}
				break;
			
				case 2:
					foreach($entry->getElementsByTagName('metric') as $metric) {
						$data[$index[0]][$index[1]][$metric->getAttribute('name')] = $metric->getAttribute('value');
					}
				break;
			
				case 3:
					foreach($entry->getElementsByTagName('metric') as $metric) {
						$data[$index[0]][$index[1]][$index[2]][$metric->getAttribute('name')] = $metric->getAttribute('value');
					}
				break;
		
			}
				
		}
		
		return $data;
		
	}

	/**
	* Returns an instance from curl_init with all the commonly needed properties set.
	* 
	* @param $url string The $url to open
	*/
	//---------------------------------------------------------------------------------------------
	protected function curl_init($url) {
	//---------------------------------------------------------------------------------------------
		
		$ch = curl_init();
		curl_setopt($ch, CURLOPT_URL, $url);
		curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);

		if($this->auth) {
			curl_setopt($ch, CURLOPT_HTTPHEADER, array("Authorization: GoogleLogin auth=$this->auth"));
		}

		// the following thanks to Kyle from www.e-strategy.net
		// i didn't need these settings myself on a Linux box but he seemed to need them on a Windows one
		curl_setopt($ch, CURLOPT_CONNECTTIMEOUT, 30);
		curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
		curl_setopt($ch, CURLOPT_FOLLOWLOCATION, 1);
		
		return $ch;
		
	}
		
}
