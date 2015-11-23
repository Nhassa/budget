<?php

namespace app\models;

use Yii;
use yii\behaviors\TimestampBehavior;
use yii\db\Expression;
use yii\db\ActiveRecord;
use app\models\Currency;
use app\models\Category;

/**
 * This is the model class for table "operations".
 *
 * @property integer $id
 * @property integer $user_id
 * @property integer $category_id
 * @property integer $currency_id
 * @property string $summ
 * @property string $created_at
 * @property string $description
 */
class Operation extends ActiveRecord
{
	public $date_from;
	public $date_to;
	
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'operations';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['user_id', 'category_id', 'currency_id', 'summ'], 'required'],
            [['user_id', 'category_id', 'currency_id'], 'integer'],
			[['summ'], 'number'],
            [['created_at'], 'safe'],
            [['description'], 'string', 'max' => 100],
			[['date_from', 'date_to'], 'date']
        ];
    }
	
	public function behaviors()
	{
		 return [
			'timestamp' => [
				'class' => TimestampBehavior::className(),
				'attributes' => [
					ActiveRecord::EVENT_BEFORE_INSERT => 'created_at',
					ActiveRecord::EVENT_BEFORE_UPDATE => '',
				],
				'value' => new Expression('NOW()'),
			],
		];
	}
	
	public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'summ' => 'Сумма',
            'created_at' => 'Дата',
			'description' => 'Описание'
        ];
    }
	
	public function getReportByDates()
	{
		$db = Yii::$app->db;
		
		$totalSumm = $db->createCommand("SELECT 
			categories.name,
			categories.type,
			sum((operations.summ *IFNULL(courses.course, 1))) as 'sum' 
		FROM operations 
		LEFT JOIN users ON operations.user_id = users.id 
		LEFT JOIN categories on operations.category_id = categories.id 
		LEFT JOIN courses on operations.currency_id = courses.currency_id and DATE(operations.created_at) = DATE(courses.updated_at) 
		WHERE  
			created_at BETWEEN :date_from and :date_to 
		GROUP BY categories.id")
			->bindValue(':date_from', $this->date_from)
			->bindValue(':date_to', $this->date_to)
			->queryAll();
			
		return $totalSumm;
	}
	
	public function getCurrency()
	{
		return $this->hasOne(Currency::className(), ['id' => 'currency_id']);
	}
	
	public function getCategory()
	{
		return $this->hasOne(Category::className(), ['id' => 'category_id']);
	}
	
	public function getSummForOut($summ, $t)
	{
		 switch($t)
		{
			case 'income': return $summ / 100; break;
			case 'expense': return $summ / -100; break;
			default: return null;
		}
	}
	
	public function getSummForIn($summ, $t)
	{
		switch($t)
		{
			case 'income': return $summ * 100; break;
			case 'expense': return $summ * -100; break;
			default: return null;
		}
	}
}
