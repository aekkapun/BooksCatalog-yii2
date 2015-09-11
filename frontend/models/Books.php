<?php

namespace app\models;

use Yii;

/**
 * This is the model class for table "books".
 *
 * @property integer $id
 * @property string $name
 * @property string $date_create
 * @property string $date_update
 * @property string $preview
 * @property string $date
 * @property integer $author_id
 */
class Books extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'books';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['name'], 'required'],
            [['date_create', 'date_update', 'date'], 'date', 'format' => 'yyyy-mm-dd',],
            [['author_id'], 'integer'],
            [['name', 'preview'], 'string', 'max' => 255]
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'name' => 'Название',
            'date_create' => 'Дата создания',
            'date_update' => 'Дата обновления',
            'preview' => 'Превью',
            'date' => 'Дата выхода',
            'author_id' => 'Автор',
        ];
    }

    public function getAuthor()
    {
        return $this->hasOne(Authors::className(), ['id' => 'author_id']);
    }
     
    /* Геттер для автора */
    public function getAuthorName() {
        return $this->author->firstname." ".$this->author->lastname;
    }

    public static function getAuthorList()
    {
        $list = array(); 
        $authors = Authors::find()->all(); //Все авторы

        foreach ($authors as $value) {
            $list[$value->id] = $value->firstname." ".$value->lastname; 
        }
        return $list;
    }
    
}
