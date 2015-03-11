<?php

class SolrSearchService {

	public static $config = array(
		'endpoint' => array(
			'localhost' => array(
				'host' => '127.0.0.1',
				'port' => 8983,
				'path' => '/solr/',
			)
		)
	);

	/**
	 * Get the solr service client
	 * 
	 * @return Apache_Solr_Service
	 */
	public function getSolr() {
		// if (!$this->client) {
		// 	if (!$this->solrTransport) {
		// 		$this->solrTransport = new Apache_Solr_HttpTransport_Curl;
		// 	}
		// 	$this->client = new Apache_Solr_Service(self::$solr_details['host'], self::$solr_details['port'], self::$solr_details['context'], $this->solrTransport);
		// }

		// return $this->client;

		// echo "Solarium version: " . Solarium\Client::VERSION;
		$this->client = new Solarium\Client(self::$config);
		// $ping = $client->createPing();
		// $client->ping($ping);
		// echo "Succesful ping";
	}

}