<?php


namespace App\Helper;


class TransactionsDictionary
{
    public function getArray()
    {
        return [
            'treatment' => [
                'name' => 'طرح درمان',
            ],
            'customer' => [
                'name' => 'بیمار',
            ],
            'payables' => [
                'name' => 'خرج',
            ],
            'incomes' => [
                'name' => 'درآمد',
            ],
        ];
    }
}