<?php
//App::uses('Department', 'EncryptionSupport.Test/test_app/Model');
//App::uses('Hobby', 'EncryptionSupport.Test/test_app/Model');
//App::uses('Group', 'EncryptionSupport.Test/test_app/Model');

class AccountsGroup extends CakeTestModel{
    public $useTable = 'accounts_groups';
    public $name = 'AccountsGroup';
    public $actsAs = array(
        'EncryptionSupport.Encryption' => array(),
    );

}