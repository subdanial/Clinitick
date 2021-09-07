<?php


namespace App\Service\CoreApi;

use App\Entity\Customers;
use App\Entity\CustomerMedias;
use App\Entity\Doctors;
use App\Entity\TransactionDetails;
use App\Entity\Transactions;

final class FinanceApi extends RequestCalls
{
    const CREATE_FINANCE_PATH = '/finance';
    const UPDATE_FINANCE_PATH = '/finance';
    const DELETE_FINANCE_PATH = '/finance/{$finance_id}';
    const GET_SINGLE_FINANCE_PATH = '/finance/single/{$finance_id}';

    public function create(Transactions $transaction, TransactionDetails $detail)
    {
        $response = $this->doRequest('POST', self::CREATE_FINANCE_PATH, [
            'finance' => [
                "id" => $transaction->getUid(),
                "title" => $detail->getDescription(),
                "is_cost" => $detail->getType() == 'C' ? false : true,
                "amount" => $detail->getPrice(),
                "date" => $detail->getCreateDate()->format('Y-m-d')
            ],
            'dentist_id' => $transaction->getRelatedEntityId()
        ]);

        return $response;
    }

    public function update(Customers $customer)
    {
        $response = $this->doRequest('PATCH', self::UPDATE_FINANCE_PATH, [
            'patient' => [
                "id" => $customer->getUid(),
                "full_name" => $customer->getFullname(),
                "allergies" => [
                    $customer->getDiabet() ? "دیابت" : null,
                    $customer->getAsm() ? "آسم" : null,
                    $customer->getHepatit() ? "هپاتیت" : null,
                    $customer->getKolie() ? "بیماری کلیوی" : null,
                    $customer->getSaar() ? "صرع" : null,
                    $customer->getEtiad() ? "اعتیاد" : null,
                    $customer->getBardari() ? "بارداری" : null,
                    $customer->getAids() ? "ایدز" : null,
                    $customer->getRomatism() ? "سابقه تب روماتیسمی" : null,
                    $customer->getShimiDarmani() ? "شیمی درمانی/پرتو درمانی" : null,
                    $customer->getEneeghad() ? "بیماری انعقادی" : null,
                    $customer->getSaratan() ? "سرطان" : null,
                    $customer->getGhalb() ? "بیماری قلبی عروقی" : null,
                    $customer->getFesharKhun() ? "فشار خون" : null,
                    $customer->getAlergy() ? "آلرژی" : null,
                ],
                "phone" => $customer->getMobile()
            ]
        ]);

        return $response;
    }

    public function delete(Transactions $transactions)
    {
        $response = $this->doRequest('DELETE', self::DELETE_FINANCE_PATH, []);

        return $response;
    }

    public function getSingleFinance(Transactions $transactions)
    {
        $response = $this->doRequest('GET', str_replace('{$finance_id}', $transactions->getUid(),
            self::GET_SINGLE_FINANCE_PATH), []);

        return $response;
    }
}