<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;
use app\models\Books;

/* @var $this yii\web\View */
/* @var $model app\models\Books */
/* @var $form yii\widgets\ActiveForm */
?>

<div class="books-form">

    <?php $form = ActiveForm::begin([
        'options' => ['enctype'=>'multipart/form-data'],
    ]); ?>

    <?= $form->field($model, 'name')->textInput(['maxlength' => true]) ?>

    <?//= $form->field($model, 'preview')->textInput(['maxlength' => true]) ?>
    <?= $form->field($model, 'image')->fileInput() ?>

    <?= $form->field($model, 'date', ['template'=>'{label}{input}{error}'])->textInput(['placeholder' => 'дд/мм/гггг']) ?>

    <?= $form->field($model, 'author_id')->dropDownList(
        [''=>'Автор'] + Books::getAuthorList()
    ); ?>

    <div class="form-group">
        <?= Html::submitButton($model->isNewRecord ? 'Create' : 'Update', ['class' => $model->isNewRecord ? 'btn btn-success' : 'btn btn-primary']) ?>
    </div>

    <?php ActiveForm::end(); ?>

</div>
