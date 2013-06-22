***********************************************************************
EncriptionSupportPlugin

このプラグインは可逆暗号化支援のプラグインです。
***********************************************************************

使い方

①通常通りPluginを設置。

②Appモデルで(使用しているモデルだけだとJOIN先からのfindなどで適応できません)
<pre>
public $actsAs = array('EncryptionSupport.Encryption');
</pre>
を記述。

③暗号化を行いたいテーブルのモデルで暗号化したいフィールドを指定
<pre>
public $encryption_fields = array(
	'name',
	'email',
	'password',
);
</pre>

④ENCRYPTION_KEYを暗号化キーに指定しているので、bootstrapなどでENCRYPTION_KEYを指定する。


以上で、基本的に暗号化・復号化処理が可能です。

②の際にオプションが指定可能です。
<pre>
public $actsAs = array(
	'EncryptionSupport.Encryption' => array('type' => 'cipher','condition_search' => false),
	//typeは暗号化の種類。デフォルトはrijndaelで、cipherも使用可能です。
	//condition_searchはfindのconditionsを暗号化するかどうか。デフォルトはtrueで暗号化します。
);
</pre>

<pre>
public $actsAs = array(
	'EncryptionSupport.Encryption' => array('type' => 'originalEncrypt'),
	//typeに「rijndael」、「cipher」以外を指定すると独自の暗号化・復号化メソッドを使用可能です。
);

/*
 * これは簡単な例です。実際はここで独自のプラグインなりbehaviorなりを呼んで暗号化、複号化するのが良いでしょう。
 * 第一引数：単語
 * 第二引数：暗号化キー(ENCRYPTION_KEY)
 * 第三引数：暗号化(encrypt)か複号化(decrypt)かを渡す
 */
function originalEncrypt($word,$key,$type){
	if ($type == 'encrypt'){
		return base64_encode($word);
	}elseif ($type == 'decrypt'){
		return base64_decode($word);
	}
}

</pre>
