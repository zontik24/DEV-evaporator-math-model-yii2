<?php


namespace app\components\evaporator;

/**
 * Математическая модель выпарного аппарата
 */

class EvaporatorMathModel
{

    /**
     *  Расход вторичного пара (m(вт))
     *
     * @type float
     */
    public $secondarySteamConsumption;

    /**
     *  Масса потребления на входе m(вх)
     *
     * @type float
     */
    public $entranceMassConsumption;

    /**
     *  Масса потребления на выходе m(вых)
     *
     * @type float
     */
    public $exitMassConsumption;

    /**
     *  Входная концентрация C(вх)
     *
     * @type float
     */
    public $entranceConcentration;

    /**
     *  Выходная концентрация С(вых)
     *
     * @type float
     */
    public $exitConcentration;

    /**
     *  Температура греющего пара T(п)
     *
     * @type float
     */
    public $heatingSteamTemperature;

    /**
     *  Теплота парообразования вторичного пара
     *
     * @type float
     */
    private static $vaporizationHeatOfSecondarySteam;

    /**
     *  Коэффициент передачи тепла
     *
     * @type float
     */
    private static $heatTransferCoefficient;

    /**
     *  Площадь теплообмена
     *
     * @type float
     */
    private static $heatExchangeArea;

    /**
     *  Температура кипения
     *
     * @type float
     */
    private static $boilingTemperature;

    /**
     *  Теплоемкость раствора
     *
     * @type float
     */
    private static $solutionHeatCapacity;


    /**
     * EvaporatorMathematicalModel constructor.
     * @param $entranceConcentration
     * @param $entranceMassConsumption
     * @param $heatingSteamTemperature
     */
    function __construct(
        $entranceConcentration,
        $entranceMassConsumption,
        $heatingSteamTemperature
    ) {
        $this->entranceConcentration = $entranceConcentration;
        $this->entranceMassConsumption = $entranceMassConsumption;
        $this->heatingSteamTemperature = $heatingSteamTemperature;
        self::$vaporizationHeatOfSecondarySteam = 2.26 * pow(10, 6);
        self::$heatTransferCoefficient = 5000;
        self::$heatExchangeArea = 10;
        self::$boilingTemperature = 90;
        self::$solutionHeatCapacity = 4187;
    }


    /**
     * Ищем значение выходной концентрации аппарата
     *
     * выходная концентрация ищется на основе
     * предварительно решенной системы уравнений
     *
     * @param $entranceConcentration
     * @param $entranceMassConsumption
     * @param $heatingSteamTemperature
     * @return float
     */
    public function solveExitConcentration(
        $entranceConcentration,
        $entranceMassConsumption,
        $heatingSteamTemperature
    ) {
        $vaporizationHeatOfSecondarySteam = self::$vaporizationHeatOfSecondarySteam;
        $heatTransferCoefficient = self::$heatTransferCoefficient;
        $heatExchangeArea = self::$heatExchangeArea;
        $boilingTemperature = self::$boilingTemperature;
        $solutionHeatCapacity =  self::$solutionHeatCapacity;

        return (
            ( ($entranceMassConsumption * $entranceConcentration) *
            ($solutionHeatCapacity * $heatingSteamTemperature - $vaporizationHeatOfSecondarySteam) ) /
            ( $heatTransferCoefficient * $heatExchangeArea * ($heatingSteamTemperature - $boilingTemperature) )
        );
    }
}