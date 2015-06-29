<?php
namespace EncryptionSupport\Test\App\Model\Entity;

use Cake\ORM\Entity;
use EncryptionSupport\Model\Entity\EncryptionTrait;

/**
 * Account Entity.
 */
class Account extends Entity
{
use EncryptionTrait;
    /**
     * Fields that can be mass assigned using newEntity() or patchEntity().
     *
     * @var array
     */
    protected $_accessible = [
        'name' => true,
        'department_id' => true,
        'department' => true,
        'hobbies' => true,
        'groups' => true,
    ];
    
    public $encryptConfig = [
        'type' => 'default',
        'fields' => [
            'name'
        ],
    ];
    
    //&getメソッドをoverride
    public function &__get($property){
        $value = parent::__get($property);
        
        $value = $this->getDecrypt($property, $value);
        
        return $value;
    }
    
    //setメソッドをoverride
    
    public function set($property, $value = null, array $options = []){
        
        parent::set($property, $value , $options);
        
        $this->setEncrypt();
        return $this;
    }

}
