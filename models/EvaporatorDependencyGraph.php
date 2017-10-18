<?php

namespace app\models;

use app\components\evaporator\EvaporatorMathModel;
use yii\base\Exception;

/**
 * Данный класс позволяет сформировать график
 * для анализа зависимых значений выпарного аппарата
 */

class EvaporatorDependencyGraph
{

    /**
     *  Модель выпарного аппарата для инжекта в контроллер
     *
     * @type EvaporatorMathModel object
     */
    private $evaporatorMathModel;


    /**
     * EvaporatorDependencyGraph constructor.
     * @param EvaporatorMathModel $evaporatorMathModel
     */
    function __construct(EvaporatorMathModel $evaporatorMathModel)
    {
        $this->evaporatorMathModel = $evaporatorMathModel;
    }


    /**
     * Функция формирует массив из значений по X и Y
     *
     * Возвращает json для последующей обработки на javascript
     * и построения графика
     *
     * @param $dependencyValue
     * @param $lower
     * @param $higher
     * @param $step
     * @return string json
     */
    public function getGraphCoordinates($dependencyValue, $lower, $higher, $step)
    {
        $lower = (float)$lower;
        $higher = (float)$higher;
        $step = (float)$step;
        $dependencyValue = (string)$dependencyValue;

        $entranceConcentration = $this->evaporatorMathModel->entranceConcentration;
        $entranceMassConsumption = $this->evaporatorMathModel->entranceMassConsumption;
        $heatingSteamTemperature = $this->evaporatorMathModel->heatingSteamTemperature;

        $coordinates = array();

        try {
            if (strtolower($dependencyValue) === strtolower('entranceConcentration')) {
                for ($i=$lower; $i<$higher+$step; $i+=$step) {
                    $result = $this->evaporatorMathModel->solveExitConcentration($i, $entranceMassConsumption, $heatingSteamTemperature);
                    $coordinates[ $result ] = $entranceConcentration;
                }
            } elseif (strtolower($dependencyValue) === strtolower('entranceMassConsumption')) {
                for ($i=$lower; $i<$higher+$step; $i+=$step) {
                    $result = $this->evaporatorMathModel->solveExitConcentration($entranceConcentration, $i, $heatingSteamTemperature);
                    $coordinates[ $result ] = $entranceMassConsumption;
                }
            } elseif (strtolower($dependencyValue) === strtolower('heatingSteamTemperature')) {
                for ($i=$lower; $i<$higher+$step; $i+=$step) {
                    $result = $this->evaporatorMathModel->solveExitConcentration($entranceConcentration, $entranceMassConsumption, $i);
                    $coordinates[ $result ] = $heatingSteamTemperature;
                }
            }
        } catch (Exception $e) {
            die('EXCEPTION : ' . $e->getMessage() . 'ON LINE '. $e->getLine());
        }

        return json_encode($coordinates);
    }

}