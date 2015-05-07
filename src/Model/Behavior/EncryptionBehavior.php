<?php

namespace EncryptionSupport\Model\Behavior;

use Cake\ORM\Behavior;
use Cake\Event\Event;
use Cake\ORM\Query;
use Cake\ORM\Entity;
use Cake\Core\Configure;
use Cake\Utility\Security;

class EncryptionBehavior extends Behavior {

    private $__settings = array();

    public function initialize(array $config)
    {
        $default = [];
        
        //設定値はせめてまとめる(Entity側にでも・・・・)
        $settings = $this->_table->newEntity()->encryptConfig;
        
        $this->__settings = array_merge($default,$settings);
        if (!defined('ENCRYPTION_KEY')){
            if (Configure::read('debug')){
                trigger_error('とりあえず動作しますがENCRYPTION_KEYを必ず指定するようにして下さい。');
            }
            define('ENCRYPTION_KEY','7UniidVg5tFIXcVjyEDmeRPAXzqWc55OEJqdbJXSejfHwyeAICSkYMjgNqPow2ke');
        }
    }

    public function beforeSave(Event $event, Entity $entity)
    {
        //これでいじれるか・・・
        $this->__encryptionData($entity);
        return true;
    }
    
    private function __encryptionData($entity)
    {
        foreach ($this->__settings['fields'] as $field){
            if (isset($entity->{$field})){
                $entity->{$field} = $this->__encrypt($entity->{$field});
            }
        }
    }
    
    private function __encrypt($word)
    {
        if ($this->__settings['type'] == 'default'){
            return base64_encode(Security::encrypt($word,ENCRYPTION_KEY));
        } else {
            //独自仕様は後で考える
            //return $model->{$this->__settings['type']}($word,ENCRYPTION_KEY,'encrypt');
        }
    }
}
?>