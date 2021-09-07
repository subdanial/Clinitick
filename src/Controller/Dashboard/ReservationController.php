<?php

namespace App\Controller\Dashboard;

use App\Entity\Appointments;
use App\Entity\Customers;
use App\Entity\TransactionDetails;
use App\Entity\Transactions;
use App\Entity\Treatments;
use App\Helper\DateConverter;
use App\Helper\DaysOfWeek;
use App\Repository\AppointmentsRepository;
use App\Repository\CustomersRepository;
use App\Repository\DoctorsRepository;
use App\Repository\DoctorTimesRepository;
use Doctrine\ORM\EntityManagerInterface;
use Ghasedak\GhasedakApi;
use Morilog\Jalali\Jalalian;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

/**
 * @Route("/dashboard/reservation", name="dashboard.reservation.")
 */
class ReservationController extends AbstractController
{
    /**
     * @param AppointmentsRepository $repository
     *
     * @Route("s", name="index")
     *
     * @return Response
     * @throws \Exception
     */
    public function index(AppointmentsRepository $repository): Response
    {
        $appointments = [];
        $cDate = new DateConverter();

        foreach ($repository->findAll() as $item) {
//            $status = ;
//            if($item->getTreatments()[0])
//            {
//                $status = 1;
//            }

            $appointments[] = [
                'id' => $item->getId(),
                'doctor' => $item->getDoctor()->getName(),
                'customer' => $item->getCustomer()->getFullname(),
                'mobile' => $item->getCustomer()->getFullMobile(),
                'due_date' => $cDate->miladiToShamsi($item->getDueDate()),
                'status' => $item->getStatus(),
            ];
        }

        return $this->render('dashboard/reservation/index.html.twig', [
            'appointments' => $appointments,
        ]);
    }

    /**
     * @param Request $request
     * @param EntityManagerInterface $manager
     * @param CustomersRepository $customersRepository
     * @param DoctorsRepository $doctorsRepository
     *
     * @Route("/new", name="new")
     *
     * @return Response
     */
    public function new(Request $request, EntityManagerInterface $manager, CustomersRepository $customersRepository, DoctorsRepository $doctorsRepository): Response
    {
        if ($request->isMethod('POST')) {
            try {
                $cDate = new DateConverter();

                if ($customersRepository->find($request->request->get('customer'))) {
                    $customer = $customersRepository->find($request->request->get('customer'));
                } else {
                    $customer = new Customers();

                    $customer->setFirstName($request->request->get('customer'));
                    $customer->setLastName($request->request->get('customer'));
                    $customer->setMobile('0');
                    $customer->setAids(false);
                    $customer->setAlergy(false);
                    $customer->setAsm(false);
                    $customer->setBardari(false);
                    $customer->setDiabet(false);
                    $customer->setEneeghad(false);
                    $customer->setEtiad(false);
                    $customer->setFesharKhun(false);
                    $customer->setKolie(false);
                    $customer->setSaratan(false);
                    $customer->setSaar(false);
                    $customer->setGhalb(false);
                    $customer->setRomatism(false);
                    $customer->setHepatit(false);
                    $customer->setShimiDarmani(false);
                    $customer->setCreateDate(new \DateTime());

                    $manager->persist($customer);
                }

                $appointment = new Appointments();
                $appointment->setCreateDate(new \DateTime());
                $appointment->setDoctor($doctorsRepository->find($request->request->get('doctor')));
                $appointment->setCustomer($customer);
                $appointment->setStatus(0);
                $appointment->setFromTime(new \DateTime('2020-01-01 ' . $request->request->get('from_time_input') . ':00:00'));
                $to_time = intval($request->request->get('from_time_input')) + 1;
                $appointment->setToTime(new \DateTime('2020-01-01 ' . (string)$to_time . ':00:00'));
                $appointment->setDueDate(new \DateTime($cDate->shamsiToMiladi($request->request->get('date_input'))));

                $manager->persist($appointment);

                $treatment = new Treatments();
                $treatment->setCreateDate(new \DateTime());
                $treatment->setAppointment($appointment);
                $treatment->setTotalPrice($request->request->get('price'));
                $treatment->setPlanName($request->request->get('plan_name'));
                $treatment->setTeethNumber($request->request->get('teeth'));

                $manager->persist($treatment);

                $manager->flush();

                $this->addFlash('success', 'یادآوری با موفقیت افزوده شد');
            } catch (\Exception $e) {
//                $this->addFlash('error', $e->getMessage());
                dump($e->getMessage());
                die;
            }

            return $this->redirectToRoute('dashboard.reservation.index');
        }

        $customers = [];
        $doctors = [];

        if (empty($request->get('customer'))) {
            foreach ($customersRepository->findAll() as $item) {
                $customers[] = [
                    'id' => $item->getId(),
                    'name' => $item->getFullname() . ' - ' . $item->getFullMobile(),
                ];
            }
        } else {
            $cs = $customersRepository->find($request->get('customer'));

            $customers = [
                [
                    'id' => $cs->getId(),
                    'name' => $cs->getFullname() . ' - ' . $cs->getFullMobile(),
                ]
            ];
        }

        foreach ($doctorsRepository->findBy([
            'assistant' => $this->getUser()
        ]) as $item) {
            $doctors[] = [
                'id' => $item->getId(),
                'name' => $item->getName(),
            ];
        }

        return $this->render('dashboard/reservation/new.html.twig', [
            'customers' => $customers,
            'doctors' => $doctors,
        ]);
    }

    /**
     * @param Request $request
     * @param AppointmentsRepository $repository
     *
     * @Route("/preview", name="preview")
     *
     * @return Response
     */
    public function preview(Request $request, AppointmentsRepository $repository): Response
    {
        /**
         * @var $app Appointments
         */
        $app = $repository->find($request->get('app'));
        $treatments = [];
        $cDate = new DateConverter();

        foreach ($app->getTreatments() as $treat) {
            $treatments[] = [
                'id' => $treat->getId(),
                'number' => $treat->getTeethNumber(),
                'name' => $treat->getPlanName(),
                'date' => $cDate->miladiToShamsi($treat->getCreateDate()),
                'price' => $treat->getTotalPrice(),
            ];
        }

        $appointment_data = [
            'id' => $app->getId(),
            'treatments' => $treatments
        ];

        $customer = $app->getCustomer();
        $customer_data = [
            'id' => $customer->getId(),
            'f_name' => $customer->getFirstName(),
            'l_name' => $customer->getLastName(),
            'mobile' => $customer->getFullMobile(),
            'reason' => $customer->getReason(),
            'diabet' => $customer->getDiabet(),
            'asm' => $customer->getAsm(),
            'hepatit' => $customer->getHepatit(),
            'aids' => $customer->getAids(),
            'kolie' => $customer->getKolie(),
            'saar' => $customer->getSaar(),
            'etiad' => $customer->getEtiad(),
            'bardari' => $customer->getBardari(),
            'romatism' => $customer->getRomatism(),
            'shimiDarmani' => $customer->getShimiDarmani(),
            'eneghad' => $customer->getEneeghad(),
            'saratan' => $customer->getSaratan(),
            'ghalb' => $customer->getGhalb(),
            'fesharKhun' => $customer->getFesharKhun(),
            'alergy' => $customer->getAlergy(),
            'description' => $customer->getDescription(),
        ];

        return $this->render('dashboard/reservation/preview.html.twig', [
            'appointment' => $appointment_data,
            'customer' => $customer_data
        ]);
    }

    /**
     * @param Request $request
     * @param DoctorsRepository $doctorsRepository
     *
     * @Route("/get/doctorDays", name="get_doctor_days_ajax")
     *
     * @return JsonResponse
     */
    public function getDoctorDays(Request $request, DoctorsRepository $doctorsRepository): JsonResponse
    {
        $doctor = $doctorsRepository->find($request->get('d'));
        $doctorTimes = [];
        $days = (new DaysOfWeek())->getArray();

        foreach ($doctor->getDoctorTimes() as $doctorTime) {
            if ($doctorTime->getFromTime()) {
                foreach ($days as $arr) {
                    if ($arr['id'] == $doctorTime->getDayOfWeek()) {
                        $doctorTimes[] = [
                            'id' => $doctorTime->getId(),
                            'day_of_week' => $arr['name'],
                        ];
                    }
                }
            }
        }

        return new JsonResponse([
            'data' => $doctorTimes
        ]);
    }

    /**
     * @param Request $request
     * @param DoctorTimesRepository $repository
     *
     * @Route("/get/doctorTimes", name="get_time_ajax")
     *
     * @return JsonResponse
     */
    public function getTimesAjax(Request $request, DoctorTimesRepository $repository): JsonResponse
    {
        $doctor_time = $repository->find($request->get('d'));
        $day_name = '';
        switch ($doctor_time->getDayOfWeek()) {
            case 1:
                $day_name = 'saturday';
                break;
            case 2:
                $day_name = 'sunday';
                break;
            case 3:
                $day_name = 'monday';
                break;
            case 4:
                $day_name = 'tuesday';
                break;
            case 5:
                $day_name = 'wednesday';
                break;
            case 6:
                $day_name = 'thursday';
                break;
            default:
                $day_name = 'friday';
                break;
        }

        $days = [];
        $from_time = $doctor_time->getFromTime();
        //->format('G:i:s');
        $to_time = $doctor_time->getToTime();
        $count_of_times = date_diff($from_time, $to_time)->h;

        for ($j = 0; $j <= 1; $j++) {
            $times = [];
            for ($i = 0; $i < $count_of_times; $i++) {
                $from_time_data = $from_time->format('G') + $i;
                $to_time_data = $from_time->format('G') + $i + 1;

                $times[] = [
                    'from_time' => $from_time_data,
                    'to_time' => $to_time_data,
                ];

                if ($to_time_data > $to_time->format('G')) {
                    die('error');
                }
            }

            $days[] = [
                'day' => Jalalian::forge($day_name . '+' . $j . 'weeks')->format('%B %d، %Y'),
                'date' => Jalalian::forge($day_name . '+' . $j . 'weeks')->format('Y/m/d'),
                'times' => $times
            ];
        }

        return new JsonResponse([
            'data' => $days
        ]);
    }

    /**
     * @param Request $request
     * @param AppointmentsRepository $repository
     * @param EntityManagerInterface $manager
     *
     * @Route("/change/state", name="change_state")
     *
     * @return Response
     */
    public function changeStatus(Request $request, AppointmentsRepository $repository, EntityManagerInterface $manager): Response
    {
        $app = $repository->find($request->get('app'));

        $app->setStatus($request->get('state'));
        $app->setStatusChangedByAssistant($this->getUser());

        $manager->persist($app);
        $manager->flush();

        return $this->redirectToRoute('dashboard.customer.preview', [
            'customer' => $app->getCustomer()->getId()
        ]);
    }

    /**
     * @param AppointmentsRepository $repository
     *
     * @Route("/check_online", name="check_for_online")
     *
     * @return JsonResponse
     */
    public function checkOnlineAppointment(AppointmentsRepository $repository): JsonResponse
    {
        $data = [];
        $dateC = new DateConverter();

        foreach ($online_order = $repository->findBy([
            'status' => 3
        ]) as $item) {
            $data[] = [
                'id' => $item->getId(),
                'customer_name' => $item->getCustomer()->getFullname(),
                'due_date' => $dateC->miladiToShamsi($item->getDueDate()),
            ];
        }

        return $this->json([
            'data' => $data
        ]);
    }

    /**
     * @param Request $request
     * @param AppointmentsRepository $repository
     *
     * @Route("/fetch_online_app_detail", name="fetch_online_app_detail")
     *
     * @return JsonResponse
     */
    public function fetchOnlineAppointment(Request $request, AppointmentsRepository $repository): JsonResponse
    {
        $data = [];
        $dateC = new DateConverter();
        $online_order = $repository->find($request->get('app_id'));

        $data[] = [
            'id' => $online_order->getId(),
            'doctor' => $online_order->getDoctor()->getName(),
            'clinic' => $online_order->getClinic()->getName(),
            'due_date' => $dateC->miladiToShamsi($online_order->getDueDate()),
            'from_time' => $online_order->getFromTime()->format('H:i:s'),
        ];

        return $this->json([
            'data' => $data
        ]);
    }

    /**
     * @param Request $request
     * @param EntityManagerInterface $manager
     * @param AppointmentsRepository $repository
     *
     * @Route("/submit/online_appointment", name="online_order")
     *
     * @return Response
     */
    public function submitOnlineAppointment(Request $request, EntityManagerInterface $manager,
                                            AppointmentsRepository $repository): Response
    {
        $cDate = new DateConverter();
        $appointment = $repository->find($request->request->get('online_app_id'));

        try {

            if (empty($request->request->get('online_cancel'))) {
                $status = 1;
                $this->addFlash('success', 'طرح درمان ایجاد گردید');
                $message = 'نوبت شما برای مراجعه به دکتر ' . $appointment->getDoctor()->getName() . ' در تاریخ ' . $cDate->shamsiToMiladi($request->request->get('online_due_date')) . ' ساعت ' . $request->request->get('online_from_time') . ' با موفقیت تایید شد.';
            } else {
                $status = 2;
                $this->addFlash('danger', 'طرح درمان لغو گردید');
                $message = 'نوبت شما برای مراجعه به دکتر ' . $appointment->getDoctor()->getName() . ' در تاریخ ' . $cDate->shamsiToMiladi($request->request->get('online_due_date')) . ' لغو گردید.';
            }

            $appointment->setStatusChangedByAssistant($this->getUser())
                ->setStatus($status)
                ->setDueDate(new \DateTime($cDate->shamsiToMiladi($request->request->get('online_due_date'))))
                ->setFromTime(new \DateTime('2020-01-01 ' . $request->request->get('online_from_time')));
            $to_time = intval($request->request->get('online_from_time')) + 1;
            $appointment->setToTime(new \DateTime('2020-01-01 ' . (string)$to_time . ':00'));


            $manager->persist($appointment);
            $manager->flush();
            if ($status == 1) {
                $treat = new Treatments();

                $treat->setTeethNumber($request->request->get('online_teeth'))
                    ->setTotalPrice($request->request->get('online_price'))
                    ->setAppointment($appointment)
                    ->setPlanName($request->request->get('online_plan_name'))
                    ->setCreateDate(new \DateTime($cDate->shamsiToMiladi($request->request->get('online_due_date'))));

                $manager->persist($treat);
                $manager->flush();

                $trans = new Transactions();

                $trans->setCreateDate(new \DateTime());
                $trans->setRelatedEntity('treatment');
                $trans->setRelatedEntityId($treat->getId());

                $manager->persist($trans);

                $trans_detail = new TransactionDetails();

                $trans_detail->setCreateDate(new \DateTime())
                    ->setType('D')
                    ->setRelatedEntity('customer')
                    ->setRelatedEntityId($appointment->getCustomer()->getId())
                    ->setPrice($treat->getTotalPrice())
                    ->setTransaction($trans)
                    ->setDescription('درآمد بابت طرح درمان شماره‌ی ' . $treat->getId());

                $manager->persist($trans_detail);
                $manager->flush();


                $sms = new GhasedakApi('0a96134ddf11a8cc3ca87b10c47b5b67148e6cff05e25c72e1d2218692c7f22d');

                $resp = $sms->SendSimple('09127371356', $message, '10008566');
            }

        } catch (\Exception $exception) {
            $this->addFlash('danger', $exception->getMessage());
        }

        return $this->redirectToRoute('dashboard.customer.preview', [
            'customer' => $appointment->getCustomer()->getId()
        ]);
    }
}
