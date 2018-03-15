<?php

/* @var $this \yii\web\View */
/* @var $content string */

use app\widgets\Alert;
use yii\helpers\Html;
use yii\bootstrap\Nav;
use yii\bootstrap\NavBar;
use yii\widgets\Breadcrumbs;
use app\assets\AppAsset;

AppAsset::register($this);
?>
<?php $this->beginPage() ?>
<!DOCTYPE html>
<html lang="<?= Yii::$app->language ?>">
<head>
    <meta charset="<?= Yii::$app->charset ?>">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <?= Html::csrfMetaTags() ?>
    <title><?= Html::encode($this->title) ?></title>
    <?php $this->head() ?>

<style>
.container-fluid {
    padding-right: 0px;
    padding-left: 0px;
    }

    .wrap {
    min-height: 100%;
    height: auto;
    margin: 0 auto -60px;
    padding: 0;
    }

    #main-container {
    padding-top:60px;
    padding-left: 0px;
    }

</style>
</head>
<body>
<?php $this->beginBody() ?>

<div class="wrap">
    <?php
    NavBar::begin([
        'brandLabel' => 'RESTAURANT',
        'brandUrl' => Yii::$app->homeUrl,
        'options' => [
            'class' => 'navbar-inverse navbar-fixed-top',
        ],
    ]);
    echo Nav::widget([
        'options' => ['class' => 'navbar-nav navbar-right'],
        'items' => [
            ['label' => 'REPORTS', 'url' => ['/site/report']],
            ['label' => 'KOTS', 'url' => ['kot/index']],
            ['label' => 'BILLS', 'url' => ['bill/index']],
            ['label' => 'ITEM', 'url' => ['/item/index']],
            ['label' => 'TABLE', 'url' => ['/r-table/index']],

        ],
    ]);
    NavBar::end();
    ?>

</script>
    <div class="container-fluid">
    <div id = 'main-container' class = 'container'>
    <?= $content ?>
    </div>
    </div>
</div>


<?php $this->endBody() ?>
<!-- <footer class="footer">
    <div class="container">
        <p class="pull-left">&copy; <?= date('Y') ?> AJ's </p>
        <p class="pull-right">Developed By Chowgule FOSS Club</p>
    </div>
</footer> -->
</body>

</html>
<?php $this->endPage() ?>
