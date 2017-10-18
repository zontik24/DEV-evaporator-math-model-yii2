<?php

namespace app\controllers;

use app\models\EvaporatorDependencyGraph;
use app\components\evaporator\EvaporatorMathModel;
use app\models\EvaporatorStaticCharacteristicForm;
use Yii;
use yii\web\Controller;

class SiteController extends Controller
{
    /**
     * @return string
     */
    public function actionIndex()
    {
        $model = new EvaporatorStaticCharacteristicForm();
        $valuesList = $model->graphDependencyValuesList;
        $graphCoordinates = null;

        if($model->load(Yii::$app->request->post())) {
            if($model->validate()) {
                $evaporatorStaticCharacteristicForm = Yii::$app->request->post('EvaporatorStaticCharacteristicForm');

                //Получаем ввведенные значения полей (константы)
                $entranceConcentration = $evaporatorStaticCharacteristicForm['entranceConcentration'];
                $entranceMassConsumption = $evaporatorStaticCharacteristicForm['entranceMassConsumption'];
                $heatingSteamTemperature = $evaporatorStaticCharacteristicForm['heatingSteamTemperature'];

                //Получаем ввведенные значения полей (секция построения графика)
                $graphDependencyValuesListSelected = $evaporatorStaticCharacteristicForm['graphDependencyValuesList'];
                $lowerLimit = $evaporatorStaticCharacteristicForm['lowerLimit'];
                $higherLimit = $evaporatorStaticCharacteristicForm['higherLimit'];
                $step = $evaporatorStaticCharacteristicForm['step'];

                //В мат. модель аппарата помещаем константы
                $evaporatorMathModel = new EvaporatorMathModel($entranceConcentration, $entranceMassConsumption, $heatingSteamTemperature);

                //Исходя из мат. модели, строим график зависимости
                $edg = new EvaporatorDependencyGraph($evaporatorMathModel);
                $graphCoordinates = $edg->getGraphCoordinates($graphDependencyValuesListSelected, $lowerLimit, $higherLimit, $step);

                return $this->render('index', [
                    'evaporatorFormModel' => $model,
                    'valuesList' => $valuesList,
                    'graphCoordinates' => $graphCoordinates,

                    //выбранное в данный моммент значение
                    // выпадающего списка зависимостей
                    'selectedDependency' => $valuesList[$graphDependencyValuesListSelected]
                ]);
            }
        }

        return $this->render('index', [
            'evaporatorFormModel' => $model,
            'valuesList' => $valuesList
        ]);
    }
}