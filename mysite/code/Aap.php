<?php

class Aap extends DataObject {
	
	private static $db = array(

		'Name' => 'Varchar(300)',
		'Age' => 'Int',
		'Weight' => 'Int',
		'Color' => 'Enum("red, blue, green")',
		'Dangerous' => 'Boolean'

	);
		
}