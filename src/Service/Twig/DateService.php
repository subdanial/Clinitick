<?php


namespace App\Service\Twig;


use App\Helper\DateConverter;

class DateService
{

    private $dateC;

    function __construct(DateConverter $date)
    {
        $this->dateC = $date;
    }

    public function getToday()
    {
        return $this->dateC->miladiToShamsi(null, true);
    }
}