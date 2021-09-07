<?php


namespace App\Service\CoreApi;

use App\Entity\Customers;
use App\Entity\CustomerMedias;
use App\Entity\Doctors;

final class PatientApi extends RequestCalls
{
    const CREATE_PATIENT_PATH = '/patient';
    const UPDATE_PATIENT_PATH = '/patient';
    const DELETE_PATIENT_PATH = '/patient/single/{$patient_id}';
    const GET_SINGLE_PATIENT_PATH = '/patient/images/{$patient_id}';
    const GET_CLINIC_PATIENT_PATH = '/patient/clinic/{$patient_id}';
    const GET_DENTIST_PATIENTS_PATH = '/patient/dentists/{$dentist_id}';

    public function create(Customers $customer, $doctor_id)
    {
        $allergies = [
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
        ];

        $response = $this->doRequest('POST', self::CREATE_PATIENT_PATH, [
            'patient' => [
                "id" => $customer->getUid(),
                "full_name" => $customer->getFullname(),
                "allergies" => array_values(array_filter($allergies)),
                "phone" => $customer->getMobile()
            ],
            'dentist_id' => $doctor_id
        ]);

        return $response;
    }

    public function update(Customers $customer)
    {
        $response = $this->doRequest('PATCH', self::UPDATE_PATIENT_PATH, [
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

    public function delete(Customers $customer)
    {
        $response = $this->doRequest('DELETE', self::DELETE_PATIENT_PATH, []);

        return $response;
    }

    public function getSingleAppointment(Customers $customer)
    {
        $response = $this->doRequest('GET', self::GET_SINGLE_APPOINTMENT_PATH, []);

        return $response;
    }

    public function getDentistPatients()
    {
        $response = $this->doRequest('GET', str_replace('{$dentist_id}', 1,
            self::GET_DENTIST_PATIENTS_PATH), []);

        return $response;
    }
}