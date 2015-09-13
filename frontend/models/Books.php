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
    public $image;
    public $uplDir = '@frontend/web/uploads/';
    public $uplUrl = '@web/uploads/';

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
            [['date_create', 'date_update', 'date'], 'date', 'format' => 'dd/mm/yyyy',],
            [['author_id'], 'integer'],
            [['name', 'preview'], 'string', 'max' => 255],
            [['image'], 'image',  'mimeTypes' => 'image/jpeg, image/png', 'skipOnEmpty' => true],
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
            'image' => 'Превью',
            'date' => 'Дата выхода книги',
            'author_id' => 'Автор',
        ];
    }

    public function getAuthor()
    {
        return $this->hasOne(Authors::className(), ['id' => 'author_id']);
    }
     
    /* 
        Имя автора 
    */
    public function getAuthorName() {
        return $this->author->firstname." ".$this->author->lastname;
    }

    /*
        Список всех авторов
    */
    public static function getAuthorList()
    {
        $list = array(); 
        $authors = Authors::find()->all(); //Все авторы

        foreach ($authors as $value) {
            $list[$value->id] = $value->firstname." ".$value->lastname; 
        }
        return $list;
    }
 
    /**
     * @inheritdoc
     */
    public function beforeSave($insert)
    {
        if (parent::beforeSave($insert)) {
            if ($this->isNewRecord) {
                $this->date_create=$this->date_update = date('Y-m-d', time());
            } else {
                $this->date_update = date('Y-m-d', time());
                $this->date_create = $this->dateFormating($this->date_create);
            }

            $this->date = $this->dateFormating($this->date);

            return true;
        } else {
            return false;
        }

    }
 
    /**
     * @inheritdoc
     */
    public function afterFind()
    {
        // Перевод всех дат в формат d/m/Y
        $this->date_create =$this->dateUnFormating($this->date_create);
        $this->date_update = $this->dateUnFormating($this->date_update);
        $this->date = $this->dateUnFormating($this->date);
    }
 
    /**
     * @inheritdoc
     */
    public function afterDelete()
    {
        if (file_exists($this->getUplDir() . $this->preview)) {
            @unlink($this->getUplDir() . $this->preview);  
        }
    }

    /*
    * Возвращает каталог для загрузки изображений
    */
    public function getUplDir()
    {
        return Yii::getAlias($this->uplDir);
    }

    /*
    * Возвращает путь до каталога с изображениями
    */
    public function getUplUrl()
    {
        return Yii::getAlias($this->uplUrl);
    }

    /*
    * Принимает дату в формате d/m/Y
    * Возвращает дату в формате Y-m-d
    */
    public function dateFormating($date) 
    {
        if ($date) {
            $date = str_replace("/", "-", $date);
            return date('Y-m-d', strtotime($date));
        }
    }

    /*
    * Принимает дату в формате Y-m-d
    * Возвращает дату в формате d/m/Y
    */
    public function dateUnFormating($date) 
    {
        return date('d/m/Y', strtotime($date));
    }

    /*
    * Принимает дату в формате d/m/Y
    * Возвращает дату с названием месяца на русском
    */
    public function dateRu($date)
    {
        $Month=array("Января", "Февраля", "Марта", "Апреля", "Мая", "Июня", "Июля", "Августа", "Сентября", "Октября", "Ноября", "Декабря");
        $dateEx = explode('/', $date);
        return $dateEx[0].' '.$Month[$dateEx[1]-1].' '.$dateEx[2];
    }

    /*
    * Принимает дату в формате d/m/Y
    * Возвращает дату виде вчера/позавчера
    */
    public function dateText($date)
    {
        $formatDate = $this->dateFormating($date);
        $days = date_diff(new \DateTime(), new \DateTime($formatDate))->days;

        switch ($days) {
            case 0:
                return "Сегодня";
                break;
            case 1:
                return "Вчера";
                break;
            case 2:
                return "Позавчера";
                break;

            default:
                return $days." дней назад";
                break;
        }
    }

}
