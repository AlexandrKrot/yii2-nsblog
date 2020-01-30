<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;

/* @var $this yii\web\View */
/* @var $model koperdog\yii2nsblog\models\Category */
/* @var $form yii\widgets\ActiveForm */
$this->registerJsVar('error_message', \Yii::t('nsblog/error', 'The form contains errors'));
?>

<div class="blog-edit">
    
    <div class="section-justify">
        <div class="btn-group" role="group" id="section_tabs">
            <button type="button" class="btn btn-default active" data-section="main"><?=\Yii::t('nsblog', 'Category')?></button>
            <button type="button" class="btn btn-default" data-section="seo"><?=\Yii::t('nsblog', 'SEO')?></button>
        </div>
        
        <div class="zone-section">
            <?= koperdog\yii2sitemanager\widgets\local\DomainList::widget();?>
            <?= koperdog\yii2sitemanager\widgets\local\LanguageList::widget();?>
        </div>
    </div>
    
    <div id="form-errors">
        
    </div>
    
    <?php $form = ActiveForm::begin([
        'id' => 'blog-form',
        ]); ?>
    
    <div id="main" class="active section">
        <?=$this->render('form/main', [
            'form'  => $form,
            'model' => $model,
            'allCategories' => $allCategories,
            'allPages' => $allPages,
        ]) ?>
    </div>
    
    <div id="seo" class="section">
        <?=$this->render('form/seo', [
            'form'  => $form,
            'model' => $model
        ]) ?>
    </div>
    
    <div class="form-group">
        <?= Html::submitButton(Yii::t('nsblog', 'Save'), ['class' => 'btn btn-success']) ?>
    </div>

    <?php ActiveForm::end(); ?>

</div>
<style>
    .blog-edit .section{
        display: none;
    }
    .blog-edit .section.active{
        display:block;
    }
    #section_tabs{
        margin-bottom:20px;
    }
    #section_tabs button{
        outline: none;
    }
    #url-autofill > span{
        margin-right:10px;
    }
    #section_tabs button.error:after{
        content:'!';
        display: inline-flex;
        justify-content: center;
        align-items: center;
        background:#dd4b39;
        color:#fff;
        margin-left:5px;
        text-align:center;
        width: 16px;
        height:16px;
        font-size:11px;
        font-weight:bold;
        border-radius:50%;
    }
</style>

<?php $this->registerJs(
<<<JS

if($('.change-local-zone').length){
    $('.change-local-zone select').change(function(){
        window.location.href = $(this).val();
    });
}
        
$('#section_tabs > button').click(function(){
    let section = $('#'+$(this).data('section'));

    $('.blog-edit > form > .active.section').removeClass('active');
    section.addClass('active');

    $('#section_tabs > button.active').removeClass('active');
    $(this).addClass('active');
});

$('#field-name input').change(function(){
    if($('#field-title input').val() == ''){
        $('#field-title input').val($(this).val());
    }
        
    if($('#field-og_title input').val() == ''){
        $('#field-og_title input').val($(this).val());
    }
        
    if($('#field-url input').val() == ''){
        $('#field-url input').val(nameToUrl($(this).val()));
    }
});
        
$('#url-autofill').click(function(){
    let name = $('#field-name input').val();
        
    $('#field-url input').val(nameToUrl(name));
});

$('#blog-form').on("beforeValidate", function (event) {
    $('#form-errors').html('');
});

$('#blog-form').on("afterValidate", function (event, messages, errorAttributes) {
    console.log(errorAttributes);
    $('#section_tabs button').each(function(){
        let context = $(this);
        let fn = function(){
            if(context.hasClass('error') && !$('#'+context.data('section')).find('.has-error').length){
                context.removeClass('error');
            }
        };
        setTimeout(fn);
    });
    
   
    if(!errorAttributes.length){
        $('#form-errors').html('');
    }
    else{
        for(let attribute of errorAttributes){
            let section = $(attribute.input).closest('.section').attr('id');
            $('#section_tabs button[data-section="'+section+'"]').addClass('error');
        }
        $('#form-errors').html('<div class="alert-danger alert fade in"><i class="icon fa fa-ban"></i>'+error_message+'</div>');
    }
});
JS
, yii\web\View::POS_LOAD);
?>
<script>
function nameToUrl(name){
    let url = name.replace(/[\s]+/g, '-').toLowerCase();
    
    url = transliterate(url);
        
    return url.replace(/[^+a-z0-9\-\_]/g, '');
}
        
var a = {"Ё":"YO","Й":"I","Ц":"TS","У":"U","К":"K","Е":"E","Н":"N","Г":"G","Ш":"SH","Щ":"SCH","З":"Z","Х":"H","Ъ":"'","ё":"yo","й":"i","ц":"ts","у":"u","к":"k","е":"e","н":"n","г":"g","ш":"sh","щ":"sch","з":"z","х":"h","ъ":"'","Ф":"F","Ы":"I","В":"V","А":"a","П":"P","Р":"R","О":"O","Л":"L","Д":"D","Ж":"ZH","Э":"E","ф":"f","ы":"i","в":"v","а":"a","п":"p","р":"r","о":"o","л":"l","д":"d","ж":"zh","э":"e","Я":"Ya","Ч":"CH","С":"S","М":"M","И":"I","Т":"T","Ь":"'","Б":"B","Ю":"YU","я":"ya","ч":"ch","с":"s","м":"m","и":"i","т":"t","ь":"'","б":"b","ю":"yu"};

function transliterate(word){
  return word.split('').map(function (char) { 
    return a[char] || char; 
  }).join("");
}
</script>