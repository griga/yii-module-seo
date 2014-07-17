<?php

/**
 * This is the model class for table "{{seo_config}}".
 *
 * The followings are the available columns in table '{{seo_config}}':
 * @property integer $id
 * @property string $key
 * @property string $value
 *
 */
class SeoConfig extends CrudActiveRecord
{
	/**
	 * @return string the associated database table name
	 */
	public function tableName()
	{
		return '{{seo_config}}';
	}

	/**
	 * @return array validation rules for model attributes.
	 */
	public function rules()
	{
		// NOTE: you should only define rules for those attributes that
		// will receive user inputs.
		return array(
			array('key', 'required'),
			array('key, value', 'length', 'max'=>255),
			array('id, key, value', 'safe', 'on'=>'search'),
		);
	}

	/**
	 * @return array customized attribute labels (name=>label)
	 */
	public function attributeLabels()
	{
		return array(
			'id' => 'ID',
			'key' => 'Key',
			'value' => 'Value',
		);
	}

	/**
	 * Returns the static model of the specified AR class.
	 * Please note that you should have this exact method in all your CActiveRecord descendants!
	 * @param string $className active record class name.
	 * @return SeoConfig the static model class
	 */
	public static function model($className=__CLASS__)
	{
		return parent::model($className);
	}


    /**
     * return behaviors of component merged with parent component behaviors
     * @return array CBehavior[]
     */

    public function behaviors(){
        return CMap::mergeArray(
            parent::behaviors(),
            [
                'ml' => [
                    'class' => 'MultilingualBehavior',
                    'langTableName' => 'seo_config_lang',
                    'langForeignKey' => 'entity_id',
                    'localizedAttributes' => [
                        'value',
                    ],
                    'languages' => Lang::getLanguages(), // array of your translated languages. Example : ['fr' => 'Français', 'en' => 'English')
                    'dynamicLangClass' => true,
                ],
            ]);
    }

    private static $configCache;
    public static function getCache(){
        if(!isset(self::$configCache)){
            $valueKey  = 'value_'.Lang::get();
            self::$configCache = array();
            foreach(self::model()->multilang()->findAll() as $config){
                self::$configCache[$config->key] = $config->{$valueKey};
            }
        }
        return self::$configCache;
    }


    public static function get($key){
        $config = self::getCache();
        if(isset($config[$key]))
            return $config[$key];
        return null;
    }
}
