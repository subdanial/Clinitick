<?php


namespace App\Service\CoreApi;

use App\Entity\Appointments;
use App\Entity\Clinics;

final class AppointmentApi extends RequestCalls
{
    const CREATE_APPOINTMENT_PATH = '/appointment';
    const UPDATE_APPOINTMENT_PATH = '/appointment';
    const DELETE_APPOINTMENT_PATH = '/appointment/{$appointment_id}';
    const GET_SINGLE_APPOINTMENT_PATH = '/appointment/single/{$appointment_id}';
    const GET_CLINIC_APPOINTMENT_PATH = '/appointment/clinic/{$clinic_id}';
    const STATUS_ARRAY = [0 => 'unknown', 1 => 'done', 2 => 'canceled'];

    public function create(Appointments $appointment, $plan_name, $price)
    {
        $customer = $appointment->getCustomer();
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


        $status = "unknown";
        foreach (self::STATUS_ARRAY as $key => $value) {
            if ($appointment->getStatus() == $key) {
                $status = $value;
            }
        }

        $response = $this->doRequest('POST', self::CREATE_APPOINTMENT_PATH, [
            'appointments' => [
                [
                    'id' => $appointment->getUid(),
                    'price' => $price,
                    'state' => $status,
                    'visit_time' => $appointment->getDueDate()->format('Y-m-d') . ' ' . $appointment->getFromTime()->format('G:i:s'),
                    'disease' => $plan_name,
                    'clinic_id' => $appointment->getClinic()->getUid(),
                ]
            ],
            'patient' => [
                "id" => $customer->getUid(),
                "full_name" => $customer->getFullname(),
                "allergies" => array_values(array_filter($allergies)),
                "phone" => $customer->getMobile()
            ]
        ]);

        return $response;
    }

    public function update(Appointments $appointment)
    {
        $status = 'undone';
        foreach (self::STATUS_ARRAY as $key => $value) {
            if ($appointment->getStatus() == $key) {
                $status = $value;
            }
        }

        $response = $this->doRequest('PATCH', self::UPDATE_APPOINTMENT_PATH, [
            'appointment' => [
                'id' => $appointment->getUid(),
                'price' => $appointment->getTreatments()[0]->getTotalPrice(),
                'status' => $status,
                'visit_time' => $appointment->getDueDate()->format('Y-m-d G:i:s'),
                'disease' => $appointment->getTreatments()[0]->getPlanName(),
                'clinic_id' => $appointment->getClinic()->getUid(),
            ]
        ]);

        return $response;
    }

    public function delete(Appointments $appointment)
    {
        $response = $this->doRequest('DELETE', str_replace('{$appointment_id}', $appointment->getUid(),
            self::DELETE_APPOINTMENT_PATH), []);

        return $response;
    }

    public function getSingleAppointment(Appointments $appointment)
    {
        $response = $this->doRequest('GET', str_replace('{$appointment_id}', $appointment->getUid(),
            self::GET_SINGLE_APPOINTMENT_PATH), []);

        return $response;
    }

    public function getClinicAppointments(Clinics $clinic)
    {
        $response = $this->doRequest('GET', str_replace('{$clinic_id}', $clinic->getUid(),
            self::GET_CLINIC_APPOINTMENT_PATH), []);

        return $response;
    }
}