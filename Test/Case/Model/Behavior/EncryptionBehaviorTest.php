<?php

//App::uses('Account', 'EncryptionSupport.Test/test_app/Model');
App::uses('EncryptionBehavior', 'EncryptionSupport.Model/Behavior');

require_once(App::pluginPath('EncryptionSupport').'Test'.DS.'test_app'.DS.'Model'.DS.'Account.php');
require_once(App::pluginPath('EncryptionSupport').'Test'.DS.'test_app'.DS.'Model'.DS.'Hobby.php');
require_once(App::pluginPath('EncryptionSupport').'Test'.DS.'test_app'.DS.'Model'.DS.'Department.php');
require_once(App::pluginPath('EncryptionSupport').'Test'.DS.'test_app'.DS.'Model'.DS.'Group.php');
require_once(App::pluginPath('EncryptionSupport').'Test'.DS.'test_app'.DS.'Model'.DS.'AccountsGroup.php');
if (!defined('ENCRYPTION_KEY')){
    define('ENCRYPTION_KEY','7UniidVg5tFIXcVjyEDmeRPAXzqWc55OEJqdbJXSejfHwyeAICSkYMjgNqPow2ke');
}


/**
 * Test Case
 *
 */
class EncryptionBehaviorTest extends CakeTestCase {

    public $fixtures = array(
        'plugin.EncryptionSupport.Account',
        'plugin.EncryptionSupport.Department',
        'plugin.EncryptionSupport.AccountsGroup',
        'plugin.EncryptionSupport.Group',
        'plugin.EncryptionSupport.Hobby',
    );

    public function setUp() {
        parent::setUp();
        $this->Account= new Account();
    }

    public function tearDown() {
        parent::tearDown();
        ClassRegistry::flush();
    }

    public function test通常登録成功() {
        $save_data = array(
            'name' => 'テスト',
        );
        $this->assertFalse(!$this->Account->save($save_data));
        $query = array();
        $lastid = $this->Account->getLastInsertId();
        $query['conditions'] = array(
            'Account.id' => $lastid
        );
        $data = $this->Account->find('first',$query);
        $this->assertEqual($data['Account']['name'],$save_data['name']);
    }

    public function test_hasmany登録成功() {
        $save_data = array(
            'Account' => 
                array('name' => 'テスト'),
            'Hobby' => 
                array(
                    array('name' => '趣味1'),
                    array('name' => '趣味2'),
                    array('name' => '趣味3'),
                ),
        );
        $this->assertFalse(!$this->Account->saveAll($save_data));
        $query = array();
        $lastid = $this->Account->getLastInsertId();
        $query['conditions'] = array(
            'Account.id' => $lastid
        );
        $data = $this->Account->find('first',$query);
        $this->assertEqual($data['Account']['name'],$save_data['Account']['name']);
        $this->assertEqual($data['Hobby'][0]['name'],$save_data['Hobby'][0]['name']);
        $this->assertEqual($data['Hobby'][1]['name'],$save_data['Hobby'][1]['name']);
        $this->assertEqual($data['Hobby'][2]['name'],$save_data['Hobby'][2]['name']);
    }

    public function test_belongsto_find成功() {
        $group_save_data = array(
            'Department' =>
                array('name' => '所属1'),
        );
        $this->assertFalse(!$this->Account->Department->save($group_save_data));
        
        $group_id = $this->Account->Department->getLastInsertId();
        
        $save_data = array(
            'Account' => 
                array(
                    'name' => 'テスト',
                    'department_id' => $group_id
                ),
        );
        
        $this->assertFalse(!$this->Account->save($save_data));
        $query = array();
        $lastid = $this->Account->getLastInsertId();
        $query['conditions'] = array(
            'Account.id' => $lastid
        );
        $data = $this->Account->find('first',$query);
        $this->assertEqual($data['Account']['name'],$save_data['Account']['name']);
        $this->assertEqual($data['Department']['name'],$group_save_data['Department']['name']);
    }

    public function test_habtm_find成功() {
        $group_save_data = array(
            'Group' =>
                array('name' => 'グループ1'),
        );
        $this->assertFalse(!$this->Account->Group->save($group_save_data));
        
        $group_id = $this->Account->Group->getLastInsertId();
        
        $save_data = array(
            'Account' => 
                array(
                    'name' => 'テスト',
                ),
            'Group' => array(
                'Group' => 
                    array(0 => $group_id)
            )
        );
        
        $this->assertFalse(!$this->Account->save($save_data));
        $query = array();
        $lastid = $this->Account->getLastInsertId();
        $query['conditions'] = array(
            'Account.id' => $lastid
        );
        $data = $this->Account->find('first',$query);
        $this->assertEqual($data['Account']['name'],$save_data['Account']['name']);
        $this->assertEqual($data['Group'][0]['name'],$group_save_data['Group']['name']);
    }

}