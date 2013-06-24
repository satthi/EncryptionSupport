<?php

class EncryptionBehavior extends ModelBehavior {

	var $__settings = array();

	function setup(&$Model, $settings = array()) {
		$default = array(
			//暗号化方式
			/*
			 * rijndael,cipher(cakePHPデフォルトの暗号方式)
			 * その他の名前の場合は該当モデルのメソッドを見に行く。独自暗号化方式は該当モデルもしくはAppModel経由で記述する
			 * その他名前の場合、
			 * 第一引数：単語
			 * 第二引数：暗号化キー(ENCRYPTION_KEY)
			 * 第三引数：暗号化(encrypt)か複号化(decrypt)かを渡す
			 */
			'type' => 'rijndael',
			//findのcondifionに暗号化をかけるか/たとえばrijndaelは暗号化先がランダムなためconditionを暗号化する意味がないなど
			'condition_encrypt' => true,
		);
		$this->__settings = array_merge($default,$settings);

		if ($this->__settings['type'] == 'rijndael' || $this->__settings['type'] == 'cipher'){
			App::uses('Security', 'Utility');
		}
		if (!defined('ENCRYPTION_KEY')){
			if (Configure::read('debug') > 0){
				trigger_error('とりあえず動作しますがENCRYPTION_KEYを必ず指定するようにして下さい。');
			}
			define('ENCRYPTION_KEY','7UniidVg5tFIXcVjyEDmeRPAXzqWc55OEJqdbJXSejfHwyeAICSkYMjgNqPow2ke');
		}
	}

	function beforeSave(&$model,$options = array()){
		parent::beforeSave($model,$options);
		self::__encryptionData($model,true);
		return true;
	}

	public function beforeFind(&$model,$queryData) {
		parent::beforeFind($model,$queryData);
		if ($this->__settings['condition_encrypt'] == true && isset($queryData['conditions'])){
			$queryData['conditions'] = self::__encryptionConditionSet($model,$queryData['conditions'],true);
		}
		return $queryData;
	}

	public function afterFind(&$model,$results, $primary = false) {
		parent::afterFind($model,$results, $primary);
		return self::__conjugatedData($model,$results,true);
	}
	
	private function __encryptionConditionSet($model,$conditions,$first_flag = false){
		foreach ($conditions as $condition_key => $condition){
			if (is_array($condition)){
				$conditions[$condition_key] = self::__encryptionConditionSet($model,$condition);
			}else{
				if (!empty($model->encryption_fields)){
					foreach ($model->encryption_fields as $encryption_field){
						if (preg_match('/^(' . $model->alias . '\.)?' . $encryption_field . '$/iu',$condition_key)){
						//$condition_key == $encryption_field || $condition_key == $model->alias . '.' . $encryption_field){
							$conditions[$condition_key] = self::__encrypt($model,$condition);
						} elseif (preg_match('/^(' . $model->alias . '\.)?' . $encryption_field . ' (i)?like$/iu',$condition_key)){
							//暗号化したものではLIKE検索はどうせ使用できないので%を外して、encode
							$conditions[$condition_key] = self::__encrypt($model,preg_replace('/(^%|%$)/','',$condition));
						}
					}
				}
			}
		}
		
		//belogstoも見に行く
		if (!empty($model->belongsTo)){
			foreach ($model->belongsTo as $belongsToModelName => $belongsToVal){
				//二階層以上は潜らない
				if ($first_flag === false) continue;
				App::uses($belongsToModelName,'Model');
				$belongsToModel = new $belongsToModelName();
				$conditions = self::__encryptionConditionSet($belongsToModel,$conditions);
			}
		}
		
		return $conditions;
	}

	private function __encryptionData($model,$first_flag = false){
		if (!empty($model->encryption_fields)){
			foreach ($model->encryption_fields as $encryption_field){
				if (isset($model->data[$model->alias][$encryption_field])){
					$model->data[$model->alias][$encryption_field] = self::__encrypt($model,$model->data[$model->alias][$encryption_field]);
				}
			}
		}
	}

	private function __conjugatedData($model,$results,$first_flag = false){
		if (!empty($results)){
			foreach ($results as $result_key => $result){
				//自分のモデル
				if (!empty($model->encryption_fields)){
					foreach ($model->encryption_fields as $encryption_field){
						if (isset($result[$model->alias][$encryption_field])){
							$results[$result_key][$model->alias][$encryption_field] = self::__decrypt($model,$result[$model->alias][$encryption_field]);
						}
					}
				}
			}
			//hasmanyとbelogstoも見に行く
			if (!empty($model->hasMany)){
				foreach ($model->hasMany as $hasManyModelName => $hasManyVal){
					//二階層以上は潜らない
					if ($first_flag === false) continue;
					App::uses($hasManyModelName,'Model');
					$hasManyModel = new $hasManyModelName();
					if (!empty($result[$hasManyModelName])){
						foreach($result[$hasManyModelName] as $hasManyResultKey => $hasManyResultVal){
							$hasManyconjugatedData = self::__conjugatedData($hasManyModel,array(0 => array($hasManyModelName =>$hasManyResultVal)));
							$results[$result_key][$hasManyModelName][$hasManyResultKey] = $hasManyconjugatedData[0][$hasManyModelName];
						}
					}
				}
			}
			if (!empty($model->belongsTo)){
				foreach ($model->belongsTo as $belongsToModelName => $belongsToVal){
					//二階層以上は潜らない
					if ($first_flag === false) continue;
					App::uses($belongsToModelName,'Model');
					$belongsToModel = new $belongsToModelName();
					if (!empty($result[$belongsToModelName])){
						$results = self::__conjugatedData($belongsToModel,$results);
					}
				}
			}
			//habtm
			if (!empty($model->hasAndBelongsToMany)){
				foreach ($model->hasAndBelongsToMany as $hasAndBelongsToManyName => $hasAndBelongsToManyVal){
					//二階層以上は潜らない
					if ($first_flag === false) continue;
					App::uses($hasAndBelongsToManyName,'Model');
					$hasAndBelongsToManyModel = new $hasAndBelongsToManyName();
					if (!empty($result[$hasAndBelongsToManyName])){
						foreach($result[$hasAndBelongsToManyName] as $hasAndBelongsToManyResultKey => $hasAndBelongsToManyResultVal){
							$hasAndBelongsToManyconjugatedData = self::__conjugatedData($hasAndBelongsToManyModel,array(0 => array($hasAndBelongsToManyName =>$hasAndBelongsToManyResultVal)));
							$results[$result_key][$hasAndBelongsToManyName][$hasAndBelongsToManyResultKey] = $hasAndBelongsToManyconjugatedData[0][$hasAndBelongsToManyName];
						}
					}
				}
			}
		}
		return $results;
	}
	
	
	private function __encrypt($model,$word){
		if ($this->__settings['type'] == 'rijndael'){
			return base64_encode(Security::rijndael($word,ENCRYPTION_KEY,'encrypt'));
		} elseif ($this->__settings['type'] == 'cipher'){
			return base64_encode(Security::cipher($word,ENCRYPTION_KEY));
		} else {
			return $model->{$this->__settings['type']}($word,ENCRYPTION_KEY,'encrypt');
		}
	}
	
	private function __decrypt($model,$word){
		if ($this->__settings['type'] == 'rijndael'){
			return Security::rijndael(base64_decode($word),ENCRYPTION_KEY,'decrypt');
		} elseif ($this->__settings['type'] == 'cipher'){
			return Security::cipher(base64_decode($word),ENCRYPTION_KEY);
		} else {
			return $model->{$this->__settings['type']}($word,ENCRYPTION_KEY,'decrypt');
		}
	}
	
}
?>