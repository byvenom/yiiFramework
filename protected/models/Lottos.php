<?php

/**
 * This is the model class for table "lottos".
 *
 * The followings are the available columns in table 'lottos':
 * @property integer $no
 * @property integer $num1
 * @property integer $num2
 * @property integer $num3
 * @property integer $num4
 * @property integer $num5
 * @property integer $num6
 */
class Lottos extends CActiveRecord
{
	/**
	 * @return string the associated database table name
	 */
	public function tableName()
	{
		return 'lottos';
	}

	/**
	 * @return array validation rules for model attributes.
	 */
	public function rules()
	{
		// NOTE: you should only define rules for those attributes that
		// will receive user inputs.
		return array(
			array('num1, num2, num3, num4, num5, num6', 'numerical', 'integerOnly'=>true),
			// The following rule is used by search().
			// @todo Please remove those attributes that should not be searched.
			array('no, num1, num2, num3, num4, num5, num6', 'safe', 'on'=>'search'),
		);
	}

	/**
	 * @return array relational rules.
	 */
	public function relations()
	{
		// NOTE: you may need to adjust the relation name and the related
		// class name for the relations automatically generated below.
		return array(
		);
	}

	/**
	 * @return array customized attribute labels (name=>label)
	 */
	public function attributeLabels()
	{
		return array(
			'no' => 'No',
			'num1' => 'Num1',
			'num2' => 'Num2',
			'num3' => 'Num3',
			'num4' => 'Num4',
			'num5' => 'Num5',
			'num6' => 'Num6',
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
		// @todo Please modify the following code to remove attributes that should not be searched.

		$criteria=new CDbCriteria;

		$criteria->compare('no',$this->no);
		$criteria->compare('num1',$this->num1);
		$criteria->compare('num2',$this->num2);
		$criteria->compare('num3',$this->num3);
		$criteria->compare('num4',$this->num4);
		$criteria->compare('num5',$this->num5);
		$criteria->compare('num6',$this->num6);

		return new CActiveDataProvider($this, array(
			'criteria'=>$criteria,
		));
	}

	/**
	 * Returns the static model of the specified AR class.
	 * Please note that you should have this exact method in all your CActiveRecord descendants!
	 * @param string $className active record class name.
	 * @return Lottos the static model class
	 */
	public static function model($className=__CLASS__)
	{
		return parent::model($className);
	}
}
