<?php
//App::uses('Department', 'EncryptionSupport.Test/test_app/Model');
//App::uses('Hobby', 'EncryptionSupport.Test/test_app/Model');
//App::uses('Group', 'EncryptionSupport.Test/test_app/Model');

class Account extends CakeTestModel{
    public $useTable = 'accounts';
    public $name = 'Account';
    public $actsAs = array(
        'EncryptionSupport.Encryption' => array(),
    );
    public $encryption_fields = array(
        'name',
    );
    

	public $belongsTo = array(
		'Department' => array(
			'className' => 'EncryptionSupport.Department',
			'foreignKey' => 'department_id',
			'conditions' => '',
			'fields' => '',
			'order' => ''
		)
	);

	public $hasMany = array(
		'Hobby' => array(
			'className' => 'Hobby',
			'foreignKey' => 'account_id',
			'dependent' => false,
			'conditions' => '',
			'fields' => '',
			'order' => '',
			'limit' => '',
			'offset' => '',
			'exclusive' => '',
			'finderQuery' => '',
			'counterQuery' => ''
		)
	);

	public $hasAndBelongsToMany = array(
		'Group' => array(
			'className' => 'Group',
			'joinTable' => 'accounts_groups',
			'foreignKey' => 'account_id',
			'associationForeignKey' => 'group_id',
			'unique' => 'keepExisting',
			'conditions' => '',
			'fields' => '',
			'order' => '',
			'limit' => '',
			'offset' => '',
			'finderQuery' => '',
			'deleteQuery' => '',
			'insertQuery' => '',
			'with' => 'AccountsGroup',
		)
	);

	
}