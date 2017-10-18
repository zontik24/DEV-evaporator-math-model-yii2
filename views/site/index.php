<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;
use app\assets\AppAsset;

/* @var $this yii\web\View */
/* @var $evaporatorFormModel app\models\EvaporatorStaticCharacteristicForm */
/* @var $form ActiveForm */
/* @var $evaporatorFormModel */
/* @var $valuesList array app\controllers\SiteController */
/* @var $graphCoordinates string json app\controllers\SiteController */
/* @var $selectedDependency string app\controllers\SiteController */

?>
<div class="col-lg-3 site-index">
    <?php $form = ActiveForm::begin(['method' => 'POST', 'id' => 'EvaporatorStaticCharacteristicForm']); ?>

        <?= $form->field($evaporatorFormModel, 'entranceConcentration')
            ->textInput(['value' => 14])
            ->label('Входная концентрация (const)') ?>

        <?= $form->field($evaporatorFormModel, 'entranceMassConsumption')
            ->textInput(['value' => 4.6])
            ->label('Масса потребления на входе (const)') ?>

        <?= $form->field($evaporatorFormModel, 'heatingSteamTemperature')
            ->textInput(['value' => 140])
            ->label('Температура греющего пара (const)') ?>
        <br>
        <h4><i>Построение графика</i></h4>
        <div>
            <?= $form->field($evaporatorFormModel, 'graphDependencyValuesList')
                ->dropDownList($valuesList, [
                'id' => 'graph_dependency_select' ,
                'class' => 'form-control'
            ])->label('Температура греющего пара (const)') ?>
        </div>

        <?= $form->field($evaporatorFormModel, 'lowerLimit')
            ->textInput(['value' => 12 ])
            ->label('Нижняя граница изменения значения') ?>

        <?= $form->field($evaporatorFormModel, 'higherLimit')
            ->textInput(['value' => 16 ])
            ->label('Верхняя граница изменения значения') ?>

        <?= $form->field($evaporatorFormModel, 'step')
            ->textInput(['value' => 0.4 ])
            ->label('Шаг') ?>

        <div class="form-group">
            <?= Html::submitButton('Построить график зависимости', [
                'class' => 'btn btn-primary btn-block'
            ]) ?>
        </div>
    <?php ActiveForm::end(); ?>
</div><!-- site-index -->
<div>
    <script type="text/javascript">
        window.onload = function () {
            var dataPoints = [];
            <?php if($graphCoordinates): ?>
                var coord = JSON.parse('<?= $graphCoordinates; ?>');
                var coordX = Object.keys(coord);
                var coordY = Object.values(coord);
                for (var i = 0; i < coordX.length; i++) {
                    dataPoints[i] = {
                        x: parseInt(coordX[i]),
                        y: parseInt(coordY[i])
                    };
                }
            <?php endif; ?>
            var chart = new CanvasJS.Chart("chartContainer", {
                animationEnabled: true,
                title:{
                    text: "График зависимости величин"
                },
                axisY: {title: "<?= ($selectedDependency) ? $selectedDependency : 'С(вх)' ?>"},
                axisX: {title: "С(вых)"},
                data: [{
                    yValueFormatString: "#,#",
                    type: "spline",
                    dataPoints:  dataPoints
                }]
            });
            chart.render();
        }
    </script>
    <div id="chartContainer" style="height: 370px; width: 50%;"></div>
    <script src="web/js/canvasjs.min.js"></script>
</div>
