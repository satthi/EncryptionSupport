<?php

class Group extends CakeTestModel{
    public $useTable = 'groups';
    public $name = 'Group';
    public $actsAs = array(
        'EncryptionSupport.Encryption'
    );
    public $encryption_fields = array(
        'name',
    );

	public $hasAndBelongsToMany = array(
		'Account' => array(
			'className' => 'Account',
			'joinTable' => 'accounts_groups',
			'foreignKey' => 'group_id',
			'associationForeignKey' => 'account_id',
			'unique' => 'keepExisting',
			'conditions' => '',
			'fields' => '',
			'order' => '',
			'limit' => '',
			'offset' => '',
			'finderQuery' => '',
			'deleteQuery' => '',
			'insertQuery' => ''
		)
	);

}
