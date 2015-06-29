<?php
namespace EncryptionSupport\Test\TestCase\Model\Table;

use App\Model\Table\AccountsTable;
use Cake\ORM\TableRegistry;
use Cake\TestSuite\TestCase;

/**
 * App\Model\Table\AccountsTable Test Case
 */
class AccountsTableTest extends TestCase
{

    /**
     * Fixtures
     *
     * @var array
     */
    public $fixtures = [
        'plugin.encryption_support.accounts',
    ];

    /**
     * setUp method
     *
     * @return void
     */
    public function setUp()
    {
        parent::setUp();
        $config = TableRegistry::exists('Accounts') ? [] : ['className' => 'EncryptionSupport\Test\App\Model\Table\AccountsTable'];
        $this->Accounts = TableRegistry::get('Accounts', $config);
    }

    /**
     * tearDown method
     *
     * @return void
     */
    public function tearDown()
    {
        unset($this->Accounts);

        parent::tearDown();
    }

    /**
     * Test initialize method
     *
     * @return void
     */
    public function test_saveからfind()
    {
        $save_data = [
            'name' => 'test'
        ];
        $account = $this->Accounts->newEntity($save_data);
        $save_result = $this->Accounts->save($account);
        $this->assertTrue((bool) $save_result);
        
        //保存したデータをfindする
        $data = $this->Accounts->get($save_result->id);
        $this->assertEquals($data->name, $save_data['name']);
        
        //初期保存データが平文でtestと入っているがfindした結果がtestでないことを確認する
        $data2 = $this->Accounts->get(1);
        $this->assertNotEquals($data2->name, 'test');
        
        //findlistのデータについて
        $list_datas = $this->Accounts->find('list')->where(['Accounts.id' => $save_result->id]);
        $list_datas = $this->Accounts->decryptList($list_datas);
        
        $this->assertTrue(
            $list_datas === [$save_result->id => $save_data['name']]
        );
    }
    

}
