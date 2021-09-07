<?php

namespace App\Controller\Dashboard;

use App\Entity\Appointments;
use App\Entity\Clinics;
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
use App\Service\CoreApi\AppointmentApi;
use Doctrine\ORM\EntityManagerInterface;
use Morilog\Jalali\Jalalian;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpClient\HttpClient;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Uid\Uuid;

/**
 * @Route("/dashboard/treatment", name="dashboard.treatment.")
 */
class TreatmentController extends AbstractController
{
    /**
     * @param Request $request
     * @param DoctorsRepository $doctorsRepository
     * @param EntityManagerInterface $manager
     * @param CustomersRepository $repository
     *
     * @Route("/new", name="new")
     *
     * @return Response
     */
    public function new(Request $request, DoctorsRepository $doctorsRepository, EntityManagerInterface $manager, CustomersRepository $repository): Response
    {
        $manager->beginTransaction();
        try {
            $cDate = new DateConverter();
            $customer = $repository->find($request->request->get('customer_id'));
            $to_time = intval($request->request->get('from_time')) + 1;

            $appointment = new Appointments();
            $appointment->setCreateDate(new \DateTime())
                ->setDoctor($doctorsRepository->find($request->request->get('doctor')))
                ->setCustomer($customer)
                ->setStatus(0)
                ->setUid(Uuid::v4())
                ->setClinic($this->getDoctrine()->getRepository(Clinics::class)->find($request->request->get('treatment_clinic')))
                ->setFromTime(new \DateTime('2020-01-01 ' . $request->request->get('from_time') . ':00'))
                ->setToTime(new \DateTime('2020-01-01 ' . (string)$to_time . ':00'))
                ->setDueDate(new \DateTime($cDate->shamsiToMiladi($request->request->get('due_date'))));

            $manager->persist($appointment);
            $manager->flush();

            $treat = new Treatments();
            $treat->setTeethNumber($request->request->get('online_teeth'))
                ->setTotalPrice($request->request->get('price'))
                ->setAppointment($appointment)
                ->setPlanName($request->request->get('plan_name'))
                ->setCreateDate(new \DateTime($cDate->shamsiToMiladi($request->request->get('due_date'))));

            $manager->persist($treat);
            $manager->flush();

            $appointmentApi = new AppointmentApi(HttpClient::create(), $this->getUser());
            $reps = $appointmentApi->create($appointment, $request->request->get('plan_name'), $request->request->get('price'));

            $manager->flush();

            $trans = new Transactions();

            $trans->setCreateDate(new \DateTime())
                ->setRelatedEntity('treatment')
                ->setRelatedEntityId($treat->getId());

            $manager->persist($trans);

            $trans_detail = new TransactionDetails();

            $trans_detail->setCreateDate(new \DateTime())
                ->setType('D')
                ->setRelatedEntity('customer')
                ->setRelatedEntityId($customer->getId())
                ->setPrice($treat->getTotalPrice())
                ->setTransaction($trans)
                ->setDescription('درآمد بابت طرح درمان شماره‌ی ' . $treat->getId());

            $manager->persist($trans_detail);
            $manager->flush();

            $manager->commit();
            $this->addFlash('success', 'طرح درمان با موفقیت افزوده شد');
        } catch (\Exception $e) {
            $manager->rollback();
//                $this->addFlash('error', $e->getMessage());
            dump($e->getMessage());
            die;
        }

        return $this->redirectToRoute('dashboard.customer.preview', [
            'customer' => $customer->getId()
        ]);
    }

    /**
     * @param Appointments $app
     *
     * @Route("/preview/{app}", name="preview")
     *
     * @return Response
     * @throws \Exception
     */
    public function preview(Appointments $app): Response
    {
        $treatments = [];
        $cDate = new DateConverter();

        foreach ($app->getTreatments() as $treat) {
            $treatments[] = [
                'id' => $treat->getId(),
                'number' => $treat->getTeethNumber(),
                'date' => $cDate->miladiToShamsi($treat->getCreateDate()),
                'total_price' => $treat->getTotalPrice(),
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
}
