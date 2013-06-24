<?php
class Department extends CakeTestModel{
    public $useTable = 'departments';
    public $name = 'Department';
    public $actsAs = array(
        'EncryptionSupport.Encryption'
    );
    public $encryption_fields = array(
        'name',
    );

	public $hasMany = array(
		'Account' => array(
			'className' => 'Account',
			'foreignKey' => 'department_id',
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

}
