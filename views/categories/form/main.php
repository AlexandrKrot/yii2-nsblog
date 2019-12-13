<?php
/**
 * @var $this yii\web\View
 * @var form yii\widgets\ActiveForm;
 * @var $model koperdog\yii2nsblog\models\Category
 */
?>

<div class="row">
<?= $form->field($model, 'name', ['options' => ['class' => 'col-md-4', 'id' => 'field-name']])->textInput(['maxlength' => true]) ?>
<?= $form->field($model, 'url', [
    'options' => ['class' => 'col-md-4', 'id' => 'field-url'], 
    'template' => "{label}\n"
            . "<div class='input-group'>"
            . "{input}"
            . "<span class='input-group-btn'>"
            . "<button class='btn btn-default' id='url-autofill' type='button'>"
            . "<span class='glyphicon glyphicon-repeat'></span>".\Yii::t('nsblog','Autofill').""
            . "</button>"
            . "</span>"
            . "</div>\n"
            . "{hint}\n{error}"
    ])->textInput(['maxlength' => true]) ?>
<?= $form->field($model, 'status', ['options' => ['class' => 'col-md-2']])->dropDownList([0 => "Archive", 1 => "Publish", 3 => "Defeat"]) ?>
<?= $form->field($model, 'publish_at', ['options' => ['class' => 'col-md-2']])->widget(kartik\datetime\DateTimePicker::classname(), [
	'options' => ['placeholder' => \Yii::t('nsblog', 'Select date'), 'autocomplete' => 'off'],
    'removeButton' => false,
	'pluginOptions' => [
		'autoclose' => true
	]
]); ?>
</div>
<?php debug($model->publish_at);?>

<?= $form->field($model, 'parent_id')->dropDownList(
    koperdog\yii2nsblog\models\Category::getTree($model->id), 
    ['prompt' => Yii::t('nsblog','No Category'), 'class' => 'form-control']
);?>

<?= $form->field($model, 'h1')->textInput(['maxlength' => true]) ?>

<?= $form->field($model, 'image')->textInput(['maxlength' => true]) ?>

<?= $form->field($model, 'preview_text')->widget(vova07\imperavi\Widget::className(), [
    'settings' => [
        'lang' => 'ru',
        'minHeight' => 200,
        'plugins' => [
            'fullscreen',
        ],
        'buttons' => [
            'html',
            'formatting',
            'bold',
            'italic',
            'deleted',
            'unorderedlist',
            'orderedlist',
            'outdent',
            'indent',
            'image',
            'file',
            'link',
            'alignment',
            'horizontalrule'
        ],
    ],
]); ?>

<?= $form->field($model, 'full_text')->widget(vova07\imperavi\Widget::className(), [
    'settings' => [
        'lang' => 'ru',
        'minHeight' => 200,
        'plugins' => [
            'fullscreen',
        ],
        'buttons' => [
            'html',
            'formatting',
            'bold',
            'italic',
            'deleted',
            'unorderedlist',
            'orderedlist',
            'outdent',
            'indent',
            'image',
            'file',
            'link',
            'alignment',
            'horizontalrule'
        ],
    ],

]); ?>    

<?= $form->field($model, 'addCategories')->widget(\kartik\select2\Select2::className(), [
    'data' => $allCategories,   
    'language' => 'ru',
    'options' => ['placeholder' => Yii::t('nsblog', 'Select a category'), 'multiple' => true],
    'pluginOptions' => [
        'allowClear' => true
    ],
]);?>

<?= $form->field($model, 'addPages')->widget(\kartik\select2\Select2::className(), [
    'data' => $allPages,   
    'language' => 'ru',
    'options' => ['placeholder' => Yii::t('nsblog', 'Select a page'), 'multiple' => true],
    'pluginOptions' => [
        'allowClear' => true
    ],
]);?>

<?= $form->field($model, 'rltCategories')->widget(\kartik\select2\Select2::className(), [
    'data' => $allCategories,   
    'language' => 'ru',
    'options' => ['placeholder' => Yii::t('nsblog', 'Select a category'), 'multiple' => true],
    'pluginOptions' => [
        'allowClear' => true
    ],
]);?>

<?= $form->field($model, 'rltPages')->widget(\kartik\select2\Select2::className(), [
    'data' => $allPages,   
    'language' => 'ru',
    'options' => ['placeholder' => Yii::t('nsblog', 'Select a page'), 'multiple' => true],
    'pluginOptions' => [
        'allowClear' => true
    ],
]);?>

<?= $form->field($model, 'access_read')->dropDownList([0 => \Yii::t('nsblog', 'Every One'), 1 => \Yii::t('nsblog', 'No One'), 2 => \Yii::t('nsblog', 'Admin')]) ?>