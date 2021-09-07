<?php


namespace App\Helper;

class DateConverter
{

    /**
     * @param null $date
     * @param bool $displayDay
     *
     * Returns Converted date from Miladi to Shamsi
     *
     * @return mixed
     * @throws \Exception
     */
    function miladiToShamsi($date = null, $displayDay = false)
    {
        if (empty($date)) {
            $_date = new \DateTime();
        } else {
            $_date = $date;
        }

        if (empty($displayDay)) {
            $formatter = new \IntlDateFormatter(
                "fa_IR@calendar=persian",
                \IntlDateFormatter::FULL,
                \IntlDateFormatter::FULL,
                'Asia/Tehran',
                \IntlDateFormatter::TRADITIONAL,
                "yyyy/MM/dd");
        } else {
            $formatter = new \IntlDateFormatter(
                "fa_IR@calendar=persian",
                \IntlDateFormatter::FULL,
                \IntlDateFormatter::FULL,
                'Asia/Tehran',
                \IntlDateFormatter::TRADITIONAL,
                "EEEE \n yyyy/MM/dd");
        }

        return $formatter->format($_date);
    }

    /**
     * @param null $date
     *
     * Returns Converted date from Miladi to Shamsi
     *
     * @return mixed
     * @throws \Exception
     */
    function miladiToShamsiDayId($date = null)
    {
        if (empty($date)) {
            $_date = new \DateTime();
        } else {
            $_date = $date;
        }

        $formatter = new \IntlDateFormatter(
            "fa_IR@calendar=persian",
            \IntlDateFormatter::FULL,
            \IntlDateFormatter::FULL,
            'Asia/Tehran',
            \IntlDateFormatter::TRADITIONAL,
            "e");

        return (int)$this->englishPreview($formatter->format($_date));
    }

    /**
     * @param null $date
     * @param string $part
     *
     * Returns Converted date from Miladi to Shamsi
     *
     * @return mixed
     * @throws \Exception
     */
    function miladiToShamsiPart($date = null, string $part = 'M')
    {
        if (empty($date)) {
            $_date = new \DateTime();
        } else {
            $_date = $date;
        }

        $formatter = new \IntlDateFormatter(
            "fa_IR@calendar=persian",
            \IntlDateFormatter::FULL,
            \IntlDateFormatter::FULL,
            'Asia/Tehran',
            \IntlDateFormatter::TRADITIONAL,
            "$part");

        return (int)$this->englishPreview($formatter->format($_date));
    }

    /**
     * @param null $date
     * @param false $displayDay
     *
     * Returns Converted date from Shamsi to Miladi
     *
     * @return mixed
     * @throws \Exception
     */
    function shamsiToMiladi($date = null, $displayDay = false)
    {
//        if(empty($date))
//        {
//            $_date = new \DateTime();
//        } else {
//            $_date = $this->englishPreview($date);
//        }

        $_date = $this->englishPreview($date);
        $time = \IntlCalendar::createInstance("Asia/Tehran", "en_US@calendar=persian");
        $time->set((integer)date('Y', strtotime($_date)), (integer)date('m', strtotime($_date)) - 1, (integer)date('d', strtotime($_date)));

        if (empty($displayDay)) {
            $formatter = \IntlDateFormatter::create("en_US@calendar=english",
                \IntlDateFormatter::FULL,
                \IntlDateFormatter::FULL,
                'America/Chicago',
                \IntlDateFormatter::TRADITIONAL,
                "yyyy/MM/dd");
        } else {
            $formatter = \IntlDateFormatter::create("en_US@calendar=english",
                \IntlDateFormatter::FULL,
                \IntlDateFormatter::FULL,
                'America/Chicago',
                \IntlDateFormatter::TRADITIONAL,
                "EEEE yyyy/MM/dd");
        }


        return $formatter->format($time);
    }

    private function englishPreview($string)
    {
        $persian = ['۰', '۱', '۲', '۳', '۴', '۵', '۶', '۷', '۸', '۹'];
        $arabic = ['٩', '٨', '٧', '٦', '٥', '٤', '٣', '٢', '١', '٠'];

        $num = range(0, 9);
        $convertedPersianNums = str_replace($persian, $num, $string);
        $englishNumbersOnly = str_replace($arabic, $num, $convertedPersianNums);

        return $englishNumbersOnly;
    }
}