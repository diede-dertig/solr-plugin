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
	
	public function run($request) {

		$type = Convert::raw2sql($request->getVar('type'));

		if($type) {
			$this->types[] = $type;
		} elseif(!$this->types) {
			foreach(ClassInfo::subclassesFor('DataObject') as $class) {
				if($class::has_extension('SolrIndexable')) {
					$this->types[] = $class;
				}
			}
		}

		$solrSearchService = singleton('SolrSearchService');
		$client = $solrSearchService->getClient();

		$count = 0;
		
		foreach ($this->types as $type) {

			$update = $client->createUpdate();

			$search->getSolr()->deleteByQuery('ClassName_as:' . $type);
			$search->getSolr()->commit();

			// get the holders first, see if we have any that AREN'T in the root (ie we've already partitioned everything...)
			$objects = DataObject::get($type);

			/* @var $search SolrSearchService */

			foreach ($objects as $page) {

				//  index object
				$search->index($object);
				echo "<p>Reindexed $type ID#$object->ID</p>\n";
				$count++;
				
			}
			
		}
		
		echo "Reindex complete, $count objects re-indexed<br/>";
	}
}
