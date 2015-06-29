***********************************************************************
EncriptionSupportPlugin

このプラグインは可逆暗号化支援のプラグインです。
***********************************************************************

使い方

①composerにてインストール
```
"require": {
    "satthi/encryption-support": "3.0"
},

```

②該当Table及びEntityに以下を記述(必要分しか記載していません)

Table
```
<?php

class AccountsTable extends Table
{

    public function initialize(array $config)
    {
        //behavior読み込み
        $this->addBehavior('EncryptionSupport.Encryption');
    }
```

Entity
```
<?php
//Traitの読み込み
use EncryptionSupport\Model\Entity\EncryptionTrait;

/**
 * Account Entity.
 */
class Account extends Entity
{
//Traitの読み込み
use EncryptionTrait;
    
    public $encryptConfig = [
        'type' => 'default',
        'fields' => [
            //暗号化を行いたいフィールド
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
```

bootstrap.php
```
<?php
//適当なキー
define('ENCRYPTION_KEY','7UniidVg5tFIXcVjyEDmeRPAXzqWc55OEJqdsJXSejfHwyeAICSkYMjgNqPow2ke');
```


## License ##

The MIT Lisence

Copyright (c) 2013 Fusic Co., Ltd. (http://fusic.co.jp)

Permission is hereby granted, free of charge, to any person obtaining a copy of this software and associated documentation files (the "Software"), to deal in the Software without restriction, including without limitation the rights to use, copy, modify, merge, publish, distribute, sublicense, and/or sell copies of the Software, and to permit persons to whom the Software is furnished to do so, subject to the following conditions:

The above copyright notice and this permission notice shall be included in all copies or substantial portions of the Software.

THE SOFTWARE IS PROVIDED "AS IS", WITHOUT WARRANTY OF ANY KIND, EXPRESS OR IMPLIED, INCLUDING BUT NOT LIMITED TO THE WARRANTIES OF MERCHANTABILITY, FITNESS FOR A PARTICULAR PURPOSE AND NONINFRINGEMENT. IN NO EVENT SHALL THE AUTHORS OR COPYRIGHT HOLDERS BE LIABLE FOR ANY CLAIM, DAMAGES OR OTHER LIABILITY, WHETHER IN AN ACTION OF CONTRACT, TORT OR OTHERWISE, ARISING FROM, OUT OF OR IN CONNECTION WITH THE SOFTWARE OR THE USE OR OTHER DEALINGS IN THE SOFTWARE.

## Author ##

Satoru Hagiwara
