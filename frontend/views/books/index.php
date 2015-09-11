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

$this->registerJs('
    $("a[data-toggle=modal]").click(function() 
    { 
        var elementID = $(this).attr("data-id");
        $.get(
            "' . Url::toRoute(["/books/view", "id" => ""]) .'" + elementID,
            function(data){
                $(".modal-dialog").html(data);
            }
        );
    });
');

?>

<!-- Modal -->
<div class="modal fade" id="viewModal" tabindex="-1" role="dialog" aria-labelledby="myModalLabel">
  <div class="modal-dialog" role="document">

  </div>
</div>



<div class="books-index">

    <h1><?= Html::encode($this->title) ?></h1>
    <?php  echo $this->render('_search', ['model' => $searchModel]); ?>

    <p>
        <?= Html::a('Create Books', ['create'], ['class' => 'btn btn-success']) ?>
    </p>

    <?= GridView::widget([
        'dataProvider' => $dataProvider,
        // 'filterModel' => $searchModel,
        'columns' => [
            ['class' => 'yii\grid\SerialColumn'],

            'id',
            'name',
            'preview',
            [
                'attribute'=>'author_id',
                'content'=> function($data){
                                return $data->getAuthorName();
                            },
            ],
            'date',
            'date_create',

            ['class' => 'yii\grid\ActionColumn',
                'buttons' => [
                    'view' => function ($url, $model, $key) {
                        return Html::a('<span class="glyphicon glyphicon-eye-open"></span>','#', [
                            'id' => 'activity-view-link',
                            'title' => Yii::t('yii', 'View'),
                            'data-toggle' => 'modal',
                            'data-target' => '#viewModal',
                            'data-id' => $key,
                            // 'data-pjax' => '0',

                        ]);
                    },
                ],
            ],
        ],
    ]); ?>

</div>

