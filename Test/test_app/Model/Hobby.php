<?php
class Hobby extends CakeTestModel{
    public $useTable = 'hobbies';
    public $name = 'Hobby';
    public $actsAs = array(
        'EncryptionSupport.Encryption'
    );
    public $encryption_fields = array(
        'name',
    );

	public $belongsTo = array(
		'Account' => array(
			'className' => 'EncryptionSupport.Account',
			'foreignKey' => 'account_id',
			'conditions' => '',
			'fields' => '',
			'order' => ''
		)
	);
}
