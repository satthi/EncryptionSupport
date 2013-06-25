<?php
class AccountsGroup extends CakeTestModel{
    public $useTable = 'accounts_groups';
    public $name = 'AccountsGroup';
    public $actsAs = array(
        'EncryptionSupport.Encryption' => array(),
    );

}