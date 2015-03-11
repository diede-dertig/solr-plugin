<?php

/**
 * Reindex the entire content of the current system in the solr backend
 *
 * @author Marcus Nyeholt <marcus@silverstripe.com.au>
 * @license http://silverstripe.org/bsd-license/
 */

class SolrIndexTask extends BuildTask {

	protected $title = "Reindex all content within Solr";

	protected $description = "Iterates through all content within the system, re-indexing it in solr";
	
	protected $types = array();
	
	/**
	 * @todo: change $type to $class
	 */

	public function run($request) {

		// parse a type parameter 
		$type = Convert::raw2sql($request->getVar('type')); 
		if ($type && !ClassInfo::exists($type)) {
			echo "type does not exist; returning\n";
			return;
		}

		if($type) {
			$this->types[] = $type;
		} elseif(!$this->types) {
			foreach(ClassInfo::subclassesFor('DataObject') as $class) {
				if($class::has_extension('SolrIndexable')) {
					$this->types[] = $class;
				}
			}
		}

		$solrService = singleton('solrService');
		
		foreach ($this->types as $type) {

			// delete all existing objects of type
			$solrService->deleteAll($type);
			
			// get all dataObjects of type
			$dataObjects = DataObject::get($type);
			$solrService->index($dataObjects);

			echo "Reindexed $type's, {$dataObjects->Count()} objects indexed\n";

		}
		
	}
}
