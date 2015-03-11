<?php

class SolrService {

	public static $config = array(
		'endpoint' => array(
			'localhost' => array(
				'host' => '127.0.0.1',
				'port' => 8983,
				'path' => '/solr/',
			)
		)
	);

	public function __construct() {

		$this->client = new Solarium\Client(self::$config);

	}

	/**
	 * 		function convertObjectToDocument($object)
	 *		converts object to Solr document
	 *		@param $update			Solarium update object (NOT REALLY BUT HEY)
	 *		@param $dataObject		SS dataObject
	 */

	private function dataObjectToDocument($update, $dataObject) {
		
		$document = $update->createDocument();
		$fields = $this->dataObjectToFields($dataObject);

		foreach ($fields as $field => $value) {
			$document->$field = $value;
		}

		return $document;

	}

	/**
	 * 		Pull out all the fields that should be indexed for a particular object
	 *
	 * 		@param DataObject $dataObject
	 * 		@return array
	 */

	private function dataObjectToFields($dataObject) {
		$return = array();

		$fields = Config::inst()->get($dataObject->ClassName, 'db');
		$fields["ClassName"] = $dataObject->ClassName;

		foreach ($fields as $name => $type) {			
			$value = $dataObject->$name;
			$name = $this->getSolrFieldName($name, $type);
			$return[$name] = $value;		
		}

		return $return;

	}

	/**
	 *		function getSolrFieldName($type)
	 * 		Map a SilverStripe types to a Solr field
	 *
	 * 		@param $name	name of field
	 *		@param $type	type of field
	 *		@return $field;
	 */
	public function getSolrFieldName($name, $type) {
		
		switch ($type) {
			case 'MultiValueField': {
					return $name . '_ms';
			}
			case 'Text':
			case 'HTMLText': {
					return $name . '_t';
			}
			case 'SS_Datetime': {
					return $name . '_dt';
			}
			case 'Str':
			case 'Enum': {
					return $name . '_ms';
			}
			case 'Attr': {
				return 'attr_' . $name;
			}
			case 'Double':
			case 'Decimal':
			case 'Currency':
			case 'Float':
			case 'Money': {
				return $name . '_f';
			}
			case 'Int':
			case 'Integer': {
				return $name . '_i';
			}
			case 'SolrGeoPoint': {
				return $name . '_p';
			}
			case 'String': {
				return $name . '_s';
			}
			case 'Varchar':
			default: {
				return $name . '_as';
			}
		}
	}

	/**
	 * 		function deleteAll() 
	 *		@param $className 	name of objects to be deleted
	 *		@return $result
	 */

	public function deleteAll($className) {
		
		$update = $this->client->createUpdate();

		$update	->addDeleteQuery('ClassName_as:' . $className)
				->addCommit();

		// execute query
		$result = $this->client->update($update);

		return $result;
	}

	/**
	 * 		function index() 
	 *		@param $dataObjects 	dataObjects to be indexed
	 *		@return $result
	 */

	public function index($dataObjects) {
		$documents = array();

		$update = $this->client->createUpdate();

		foreach ($dataObjects as $dataObject) {

			$documents[] = $this->dataObjectToDocument($update, $dataObject);

		}

		// to the Solr server!
		$update->addDocuments($documents);
		$update->addCommit();		
		$result = $this->client->update($update);

		echo "Query time: " . $result->getQueryTime() . "\n";
		echo "Query status: " . $result->getStatus() . "\n";

	}

}