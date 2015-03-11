<?php

global $project;
$project = 'mysite';

global $databaseConfig;
$databaseConfig = array(
	"type" => 'MySQLDatabase',
	"server" => 'localhost',
	"username" => 'root',
	"password" => 'root',
	"database" => 'solr',
	"path" => '',
);

// Set the site locale
i18n::set_locale('en_US');

SolrSearchService::$config = array(
		'endpoint' => array(
			'localhost' => array(
				'host' => 'localhost',
				'port' => '8983',
				'path' => '/solr/diede/'				
			)
			
		)
	);

Object::add_extension('Aap', 'SolrIndexable');
Object::add_extension('Page_Controller', 'SolrSearchExtension');