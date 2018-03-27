<?php
namespace hotel\models;
use Yii;
use yii\db\ActiveRecord;
use yii\filters\RateLimitInterface;

class HomeModel extends ActiveRecord implements RateLimitInterface{

    public $allowance;
    public $allowance_updated_at;
    /**
     * 限速部分. RateLimitInterface
     */
    public function getRateLimit($request, $action) {
        return [10,10]; // 10秒10次
    }

    public function loadAllowance($request, $action){
        $cache = Yii::$app -> cache;
        $this->allowance = $cache -> get('allowance');
        $this->allowance_updated_at = $cache -> get('allowance_updated_at');
        return [$this->allowance,$this->allowance_updated_at];
    }

    public function saveAllowance($request, $action, $allowance, $timestamp){
//        $this->allowance=$allowance;
//        $this->allowance_updated_at=$timestamp;
        $cache = Yii::$app -> cache;
        $cache -> set('allowance',$allowance);
        $cache -> set('allowance_updated_at',$timestamp);
        //$this->save();
    }
}