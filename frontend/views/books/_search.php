<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;
use app\models\Books;

/* @var $this yii\web\View */
/* @var $model app\models\BooksSearch */
/* @var $form yii\widgets\ActiveForm */
?>

<div class="books-search">

    <?php $form = ActiveForm::begin([
        'action' => ['index'],
        'method' => 'get',
    ]); ?>

    <?= $form->field($model, 'author_id')->dropDownList(
        [''=>'Автор'] + Books::getAuthorList()
    ); ?>

    <?= $form->field($model, 'name', ['template'=>'{input}{error}'])->textInput(['placeholder' => 'Название книги']) ?>

    <?= $form->field($model, 'firstDate', ['template'=>'{label}{input}{error}'])->textInput(['placeholder' => '31/01/2015']) ?>

    <?= $form->field($model, 'secondDate', ['template'=>'{label}{input}{error}'])->textInput(['placeholder' => '31/01/2015']) ?>

    <div class="form-group">
        <?= Html::submitButton('Search', ['class' => 'btn btn-primary']) ?>
    </div>

    <?php ActiveForm::end(); ?>

</div>
