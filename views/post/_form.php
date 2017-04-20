<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;

/* @var $this yii\web\View */
/* @var $model app\models\Post */
/* @var $form yii\widgets\ActiveForm */
?>

<div class="post-form">

        <?php $form = ActiveForm::begin([
            'method' => 'post',
            'fieldConfig' => [
                'template' => "<div class='col-md-12'>{label}{input}</div>
                               <div class='col-md-12'>{error}</div>",
            ]
    ]); ?>

    <div class="content">
        <div class="row">
            <div class="col-md-6 col-md-offset-3">
                <div class="panel panel-default">
                    <div class="panel-heading">
                        <div class="panel-title text-center">
                            <em>Create your post</em>
                        </div>
                    </div>
                    <div class="panel-body bg-color">
                        <div class="form-group">
                            <?= $form->field($model, 'title')->textInput(['autofocus' => true]) ?>
                        </div>
                        <div class="form-group">
                            <?= $form->field($model, 'content')->textarea(['rows' => '10','style' => 'text-align: justify']) ?>
                        </div>
                        <div class="col-md-4" style="padding-left:8.5%">
                            <?= Html::dropDownList('tag-list',
                                                    '',
                                                    ['1' => 'PHP', '2' => 'JavaScript', '3' => 'CSharp', '4' => 'Java', '5' => 'AJAX' , '6' => 'jQuery'],
                                                    [
                                                            'prompt' => '',
                                                            'class' => 'form-control dd-list',
                                                    ]) ?>
                        </div>
                        <div class="col-md-7" style="margin-left: 2.5%">
                            <div class="tag-container"></div>
                        </div>
                    </div>
                    <div class="panel-footer">
                        <?= Html::submitButton('Post', ['class' => 'btn btn-primary form-control create-post']) ?>
                   </div>
                </div>
            </div>
        </div>
    </div>

    <?php ActiveForm::end(); ?>

    <?php
        $this->registerJs("
                                           
        ");
    ?>

</div>
