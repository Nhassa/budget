<?php

namespace app\models;

use Yii;
use yii\behaviors\TimestampBehavior;
use yii\db\Expression;
use yii\db\ActiveRecord;

/**
 * This is the model class for table "courses".
 *
 * @property integer $id
 * @property integer $currency_id
 * @property double $course
 * @property string $updated_at
 */
class Course extends ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'courses';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['currency_id', 'course'], 'required'],
            [['currency_id'], 'integer'],
            [['course'], 'number'],
            [['updated_at'], 'safe']
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'currency_id' => 'Currency ID',
            'course' => 'Course',
            'updated_at' => 'Update Date',
        ];
    }
	
	public function behaviors()
	{
		return [
			'timestamp' => [
				'class' => TimestampBehavior::className(),
				'attributes' => [
					ActiveRecord::EVENT_BEFORE_INSERT => 'updated_at',
					ActiveRecord::EVENT_BEFORE_UPDATE => 'updated_at',
				],
				'value' => new Expression('CURDATE()'),
			],
		];
	}
	
	public function getCourse($currency_iso)
	{
		$date_today = $this->getDate('d/m/Y');
		
		$url = 'http://www.cbr.ru/scripts/XML_daily.asp?date_req='.$date_today;
		$xml = simplexml_load_file($url);
		
		$course = null;
		
		foreach ($xml->Valute as $item)
		{
			if ($item->CharCode == $currency_iso)
			{
				$course = round((str_replace(',','.',$item->Value) * 100), 0);
			}
		}
		
		return $course;
	}
	
	public function getDate($format = 'Y-m-d')
	{
		$date = getdate();
		$day_of_the_week = $date['wday'];
		
		switch ($day_of_the_week) {
		case 0:  $k1 = 1;  break;   // воскресенье
		case 1:  $k1 = 2;  break;   // понедельник
		default: $k1 = 0;  break;   // вторник, среда, четверг, пятница, суббота
		}
		
		$month = $date['mon'] ;    // месяц
		$day = $date['mday'] ;     // число сегодня
		$today = $day - $k1 ;      // число для получения курса на сегодня
		$year = $date['year'] ;
		
		return date($format, mktime(0, 0, 0, $month, $today, $year ));
	}
	
	public function checkCourse($currency_id)
	{
		$date_today = $this->getDate();
		
		$course = static::findOne([
			'currency_id' => $currency_id,
			'updated_at' => $date_today
		]);
		return !is_null($course);
	}
	
	public function setCourse($currency_id)
	{
		$this->currency_id = $currency_id;
		$currency = Currency::findOne($currency_id);
		$this->course = $this->getCourse($currency->iso);
		
		return $this->save() ? $this : null;
	}
}
