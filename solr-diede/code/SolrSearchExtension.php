<?php

/**
 * 		Extension to provide a search interface for Solr search
 *
 * 		@package ...
 * 		@subpackage ...
 *
 */

class SolrSearchExtension extends Extension {

	private static $allowed_actions = array(
		'SearchForm', 'results'
	);

	/**
	 * Process and render search results
	 */
	
	private	function getResults($data = null, $form = null) { 

		$query = $data->getQuery();
		echo $query;
		return $query;

	}

	/**
	 * Site search form
	 */
	public function SearchForm() {
		$searchText =  _t('SearchForm.SEARCH', 'Search');

		if($this->owner->request && $this->owner->request->getVar('Search')) {
			$searchText = $this->owner->request->getVar('Search');
		}

		$fields = new FieldList(
			new TextField('Search', false, $searchText)
		);
		$actions = new FieldList(
			new FormAction('results', _t('SearchForm.GO', 'Go'))
		);
		$form = new SearchForm($this->owner, 'SearchForm', $fields, $actions);
		return $form;
	}

	/**
	 * Process and render search results.
	 *
	 * @param array $data The raw request data submitted by user
	 * @param SearchForm $form The form instance that was submitted
	 * @param SS_HTTPRequest $request Request generated for this action
	 */
	public function results($data, $form, $request) {
		$data = array(
			'Results' => $this->getResults($data, $form),
			'Query' => $form->getSearchQuery(),
			'Title' => _t('SearchForm.SearchResults', 'Search Results')
		);
		return $this->owner->customise($data)->renderWith(array('Page_results', 'Page'));
	}

}