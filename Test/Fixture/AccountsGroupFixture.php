<?php
class AccountsGroupFixture extends CakeTestFixture {

	public $name = 'AccountsGroup';

	public $fields = array(
		'id' => array('type' => 'integer', 'null' => false, 'default' => null, 'length' => 11, 'key' => 'primary'),
		'account_id' => array('type' => 'integer', 'null' => true),
		'group_id' => array('type' => 'integer', 'null' => true),
		'created' => array('type' => 'datetime', 'null' => true),
		'modified' => array('type' => 'datetime', 'null' => true),
	);
}
