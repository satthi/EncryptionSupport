<?php

namespace EncryptionSupport\Model\Behavior;

use Cake\ORM\Behavior;
use Cake\Event\Event;
use Cake\ORM\Entity;

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
    
}
