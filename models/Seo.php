<?php

/**
 * This is the model class for table "{{seo}}".
 *
 * The followings are the available columns in table '{{seo}}':
 * @property integer $id
 * @property string $key
 * @property string $title
 * @property string $description
 * @property string $keywords
 * @property string $meta
 *
 * The followings are the available model relations:
 */
class Seo extends CActiveRecord
{
	/**
	 * @return string the associated database table name
	 */
	public function tableName()
	{
		return '{{seo}}';
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
			array('key, title, description, keywords', 'length', 'max'=>255),
			array('meta', 'safe'),
			// The following rule is used by search().
			// @todo Please remove those attributes that should not be searched.
			array('id, key, title, description, keywords, meta', 'safe', 'on'=>'search'),
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
			'title' => 'Title',
			'description' => 'Description',
			'keywords' => 'Keywords',
			'meta' => 'Meta',
		);
	}

	/**
	 * Retrieves a list of models based on the current search/filter conditions.
	 *
	 * Typical usecase:
	 * - Initialize the model fields with values from filter form.
	 * - Execute this method to get CActiveDataProvider instance which will filter
	 * models according to data in model fields.
	 * - Pass data provider to CGridView, CListView or any similar widget.
	 *
	 * @return CActiveDataProvider the data provider that can return the models
	 * based on the search/filter conditions.
	 */
	public function search()
	{
		$criteria=new CDbCriteria;

		$criteria->compare('id',$this->id);
		$criteria->compare('key',$this->key,true);
		$criteria->compare('title',$this->title,true);
		$criteria->compare('description',$this->description,true);
		$criteria->compare('keywords',$this->keywords,true);
		$criteria->compare('meta',$this->meta,true);

		return new CActiveDataProvider($this, array(
			'criteria'=>$criteria,
		));
	}

	/**
	 * Returns the static model of the specified AR class.
	 * Please note that you should have this exact method in all your CActiveRecord descendants!
	 * @param string $className active record class name.
	 * @return Seo the static model class
	 */
	public static function model($className=__CLASS__)
	{
		return parent::model($className);
	}

    public static function ngConfig(){
        return [
            'entities'=>[
                [
                    'name'=>'Product',
                    'fields'=>[
                        'name', 'short_content', 'alias'
                    ],
                    'data'=>db()->createCommand()->select('id, name, short_content, alias')->from('{{product}}')->queryAll(),
                ],
                [
                    'name'=>'Manufacturer',
                    'fields'=>[
                        'name', 'short_content', 'alias'
                    ],
                    'data'=>db()->createCommand()->select('id, name, short_content, alias')->from('{{product_manufacturer}}')->queryAll(),
                ],
            ],
        ];
    }
}
