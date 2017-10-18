<?php

namespace app\models;
use yii\base\Model;


class EvaporatorStaticCharacteristicForm extends Model
{

    /**
     *  Нижняя граница изменения параметра
     *
     * @type float
     */
    public $entranceConcentration;

    /**
     *  Нижняя граница изменения параметра
     *
     * @type float
     */
    public $entranceMassConsumption;

    /**
     *  Нижняя граница изменения параметра
     *
     * @type float
     */
    public $heatingSteamTemperature;

    /**
     *  Нижняя граница изменения параметра
     *
     * @type float
     */
    public $lowerLimit;

    /**
     *  Нижняя граница изменения параметра
     *
     * @type float
     */
    public $higherLimit;

    /**
     *  Нижняя граница изменения параметра
     *
     * @type float
     */
    public $step;

    /**
     *  Нижняя граница изменения параметра
     *
     * @type float
     */
    public $graphDependencyValuesList;

    /**
     * EvaporatorStaticCharacteristicForm constructor.
     * @param array $config
     */
    function __construct(array $config=[])
    {
        parent::__construct($config);
        $this->graphDependencyValuesList = [
            'entranceConcentration' => 'C(вх)',
            'entranceMassConsumption' => 'm(вх)',
            'heatingSteamTemperature' => 'T(п)'
        ];
    }

    /**
     * @return array
     */
    public function attributeLabels()
    {
        return [
            'entranceConcentration' => 'Входная концентрация',
            'entranceMassConsumption' => 'Масса потребления на входе',
            'heatingSteamTemperature',
            'lowerLimit' => 'Нижняя граница изменения параметра',
            'higherLimit' => 'Верхняя граница изменения параметра',
            'step' => 'Шаг изменения параметра',
        ];
    }

    /**
     * @return array
     */
    public function rules()
    {
        return [
            [ ['entranceConcentration'], 'number'],
            [ ['entranceMassConsumption'], 'number' ],
            [ ['heatingSteamTemperature'], 'number' ],
            [ ['lowerLimit'], 'number' ],
            [ ['higherLimit'], 'number' ],
            [ ['step'], 'number' ]
        ];
    }


}