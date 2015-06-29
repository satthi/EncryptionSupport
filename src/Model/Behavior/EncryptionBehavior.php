<?php

namespace EncryptionSupport\Model\Behavior;

use Cake\ORM\Behavior;
use Cake\Event\Event;
use Cake\ORM\Entity;
use Cake\ORM\Query;
use Cake\ORM\Table;

class EncryptionBehavior extends Behavior {

/*
    public function beforeSave(Event $event, Entity $entity)
    {
        //これでいじれるか・・・
        $entity->encryptionData();
        return true;
    }
    */
    
    public function decryptDataAll($dataAll){
        foreach ($dataAll as $entity){
            $entity->decryptData();
        }
    }
    
    public function decryptList($dataLists) {
        if (is_object($dataLists)){
            $dataLists = $dataLists->toArray();
        }
        foreach ($dataLists as $k => $v){
            $entity = $this->_table->newEntity();
            $entity->__encryptionSettings();
            $dataLists[$k] = $entity->decrypt($v);
        }
        return $dataLists;
    }
    
}
