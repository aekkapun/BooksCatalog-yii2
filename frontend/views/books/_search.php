<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;
use app\models\Books;

/* @var $this yii\web\View */
/* @var $model app\models\BooksSearch */
/* @var $form yii\widgets\ActiveForm */
$options = ['tag'=>'div', 'class'=>''];
?>

<div class="books-search">
    <?php $form = ActiveForm::begin([
        'action' => ['index'],
        'method' => 'get',
    ]); ?>
    
    <div class="row">
        <div class="col-md-4">
            <?= $form->field($model, 'author_id', ['template'=>'{input}'])->dropDownList(
                [''=>'Автор'] + Books::getAuthorList()
            ); ?>
        </div>
        <div class="col-md-4">
            <?= $form->field($model, 'name', ['template'=>'{input}'])->textInput(['placeholder' => 'Название книги']) ?>
        </div>
    </div>

    <div class="row">
        <div class="col-md-8">
            <div class="row">
                <?= $form->field($model, 'firstDate', [
                    'template'=>'<div class="col-sm-3">{label}</div> <div class="col-sm-3" style="padding: 0">{input}</div>',
                    'options'=> $options,
                ])
                ->textInput(['placeholder' => 'дд/мм/гггг']) ?>

                <?= $form->field($model, 'secondDate', [
                    'template'=>'<div class="col-sm-1">{label}</div> <div class="col-sm-3" style="padding: 0">{input}</div>',
                    'options'=> $options,
                ])
                ->textInput(['placeholder' => 'дд/мм/гггг']) ?>
            </div>
        </div>
        <div class="col-md-2 col-md-offset-2">
            <div style="float:right">
                <?= Html::submitButton('Искать', ['class' => 'btn btn-primary']) ?>
            </div>
        </div>
    </div>

    <?= $form->field($model, 'firstDate', ['template'=>'{error}']); ?>
    <?= $form->field($model, 'secondDate', ['template'=>'{error}']); ?>


    <?php ActiveForm::end(); ?>

</div>
