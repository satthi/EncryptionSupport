<?php

namespace EncryptionSupport\Model\Entity;

use Cake\Utility\Security;
use Cake\Core\Configure;

trait EncryptionTrait
{
    private $__encryptionSettings = array();
    private $__enableDecrypt = true;
    
    public function __encryptionSettings()
    {
        
        $default = [];
        
        //�ݒ�l�͂��߂Ă܂Ƃ߂�(Entity���ɂł��E�E�E�E)
        $settings = $this->encryptConfig;
        
        $this->__encryptionSettings = array_merge($default,$settings);
        if (!defined('ENCRYPTION_KEY')){
            if (Configure::read('debug')){
                trigger_error('�Ƃ肠�������삵�܂���ENCRYPTION_KEY��K���w�肷��悤�ɂ��ĉ������B');
            }
            define('ENCRYPTION_KEY','7UniidVg5tFIXcVjyEDmeRPAXzqWc55OEJqdbJXSejfHwyeAICSkYMjgNqPow2ke');
        }
    }
    
    public function getDecrypt($property, $value){
        $this->__encryptionSettings();
        if (
            $this->__enableDecrypt == true && 
            in_array($property, $this->__encryptionSettings['fields'])
        ){
            $value = $this->decrypt($value);
        }
        
        return $value;
    }
    
    public function setEncrypt(){
        
        $this->__encryptionSettings();
        $this->encryptionData();
        return $this;
    }
    
    public function decryptData(){
        $this->__encryptionSettings();
        foreach ($this->__encryptionSettings['fields'] as $field){
            if (isset($this->{$field})){
                $this->{$field} = $this->decrypt($this->{$field});
            }
        }
        return $this;
    }
    
    public function decrypt($word)
    {
        if ($this->__encryptionSettings['type'] == 'default'){
            //������base64�������̂��ǂ����`�F�b�N����
            $base64_decode = base64_decode($word, true);
            if (!empty($base64_decode)){
                return Security::decrypt($base64_decode,ENCRYPTION_KEY);
            } else {
                return $word;
            }
        } else {
            //@todo:�Ǝ��d�l�Ή�
            //return $model->{$this->__encryptionSettings['type']}($word,ENCRYPTION_KEY,'decrypt');
        }
    }
    
    public function encryptionData()
    {
        $this->__encryptionSettings();
        //�����Ŏ��o���ۂɂ͕������������Ă͂����Ȃ�
        $this->__enableDecrypt = false;
        foreach ($this->__encryptionSettings['fields'] as $field){
            if (isset($this->{$field})){
                $this->{$field} = $this->encrypt($this->{$field});
            }
        }
        $this->__enableDecrypt = true;
    }
    
    public function encrypt($word)
    {
        if ($this->__encryptionSettings['type'] == 'default'){
            return base64_encode(Security::encrypt($word,ENCRYPTION_KEY));
        } else {
            //@todo:�Ǝ��d�l�Ή�
            //return $model->{$this->__encryptionSettings['type']}($word,ENCRYPTION_KEY,'encrypt');
        }
    }
    
}
