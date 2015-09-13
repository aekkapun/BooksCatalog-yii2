<?php

use yii\helpers\Html;
use yii\grid\GridView;
use app\models\Books;
use yii\bootstrap\Modal;
use yii\helpers\Url;
use yii\web\View;

/* @var $this yii\web\View */
/* @var $searchModel app\models\BooksSearch */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title = 'Books';
$this->params['breadcrumbs'][] = $this->title;

// Скрипт открытия модальных окон
$this->registerJs('
    // Просмотр записи
    $("a[data-target=#viewModal]").click(function() 
    { 
        var elementID = $(this).attr("data-id");
        $.get(
            "' . Url::toRoute(["/books/view"]) .'" + "&id=" + elementID,
            function(data){
                $("#viewModal .modal-dialog").html(data);
            }
        );
    });

    // Просмотр изображения
    $("a[data-target=#modalImage]").click(function() 
    { 
        var imgSrc = $(this).find("img").attr("src");
        var imgAlt = $(this).find("img").attr("alt");

        $("#modalImage").find(".modal-title").html(imgAlt);
        $("#modalImage").find(".modal-body img").attr("src", imgSrc);
        $("#modalImage").find(".modal-body img").attr("alt", imgAlt);
    });
');

?>

<!-- Модальное окно для просмотра записи -->
<div class="modal fade" id="viewModal" tabindex="-1" role="dialog" aria-labelledby="myModalLabel">
  <div class="modal-dialog" role="document">

  </div>
</div>

<!-- Модальное окно для просмотра изображения -->
<div class="modal fade" id="modalImage" tabindex="-1" role="dialog" aria-labelledby="myModalLabel2">
  <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                <h4 class="modal-title" id="myModalLabel">jjj</h4>
            </div>
            <div class="modal-body">
                <img src=""  class="img-responsive" alt="">
            </div>
                <div class="modal-footer">
                <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
            </div>
        </div>
  </div>
</div>



<div class="books-index">

    <h1><?= Html::encode($this->title) ?></h1>
    <?php  echo $this->render('_search', ['model' => $searchModel]); ?>

    <?= GridView::widget([
        'dataProvider' => $dataProvider,
        // 'filterModel' => $searchModel,
        'layout'=> '{items}{pager}',
        'columns' => [
            'id',
            'name',

            [
                'label' => 'Превью',
                // 'attribute'=>'preview',
                'format' => 'raw',
                'value' => function($data) {
                    if (!empty($data->preview)) {
                        $image = Html::img($data->getUplUrl().$data->preview, [
                            'alt'=>$data->name,
                            'style' => 'width:70px;'
                        ]);

                        return Html::a($image,'#', [
                            'title' => Yii::t('yii', 'View'),
                            'data-toggle' => 'modal',
                            'data-target' => '#modalImage',
                            'data-id' => $data->id,
                        ]);
                    } else {
                        return "Нет изображения";
                    }
                },
            ],

            [
                'attribute'=>'author_id',
                'content'=> function($data){
                                return $data->getAuthorName();
                            },
            ],

            [
                'attribute'=>'date',
                'content'=> function($data){
                                return $data->dateRu($data->date);
                            },
            ],

            [
                'attribute'=>'date_create',
                'content'=> function($data){
                                return $data->dateText($data->date_create);
                            },
            ],

            ['class' => 'yii\grid\ActionColumn',
                'template' => '{view}',
                'header' => 'Кнопки действий',
                'headerOptions'=> [
                    'colspan'=>'3',
                ],
                'buttons' => [
                    'view' => function ($url, $model, $key) {
                        return Html::a('<span class="glyphicon glyphicon-eye-open"></span>','#', [
                            'title' => Yii::t('yii', 'View'),
                            'data-toggle' => 'modal',
                            'data-target' => '#viewModal',
                            'data-id' => $key,
                        ]);
                    },
                ],
            ],
            ['class' => 'yii\grid\ActionColumn',
                'template' => '{update}',
                'headerOptions'=> [
                    'style'=>'display:none',
                ],
            ],
            ['class' => 'yii\grid\ActionColumn',
                'template' => '{delete}',
                'headerOptions'=> [
                    'style'=>'display:none',
                ],
            ], 
            
        ],
    ]); ?>
    
    <p>
        <?= Html::a('Добавить книгу', ['create'], ['class' => 'btn btn-success']) ?>
    </p>

</div>

