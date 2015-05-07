<?php

namespace EncryptionSupport\Model\Entity;

use Cake\ORM\Entity;
use Cake\Utility\Security;

class Encryption extends Entity
{
    private $__settings = array();
    
    public function __construct(array $properties = [], array $options = [])
    {
        $this->__settings();
        
        //����Ńf�[�^�͂������I
        if (!empty($properties)){
            $properties = $this-> __conjugatedData($properties);
        }
        parent::__construct($properties, $options);
    }
    
    private function __settings()
    {
        $default = [];
        
        //�ݒ�l�͂��߂Ă܂Ƃ߂�(Entity���ɂł��E�E�E�E)
        $settings = $this->encryptConfig;
        
        $this->__settings = array_merge($default,$settings);
        if (!defined('ENCRYPTION_KEY')){
            if (Configure::read('debug')){
                trigger_error('�Ƃ肠�������삵�܂���ENCRYPTION_KEY��K���w�肷��悤�ɂ��ĉ������B');
            }
            define('ENCRYPTION_KEY','7UniidVg5tFIXcVjyEDmeRPAXzqWc55OEJqdbJXSejfHwyeAICSkYMjgNqPow2ke');
        }
    }
    
    private function __conjugatedData($properties)
    {
        foreach ($this->__settings['fields'] as $field){
            if (isset($properties[$field])){
                $properties[$field] = $this->__decrypt($properties[$field]);
            }
        }
        return $properties;
    }
    
    private function __decrypt($word)
    {
        if ($this->__settings['type'] == 'default'){
            return Security::decrypt(base64_decode($word),ENCRYPTION_KEY);
        } else {
            //�Ǝ��d�l�͌�ōl���悤�E�E�E
            //return $model->{$this->__settings['type']}($word,ENCRYPTION_KEY,'decrypt');
        }
    }
}
