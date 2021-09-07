<?php

namespace App\Controller\Website;

use App\Entity\Appointments;
use App\Entity\Customers;
use App\Helper\DateConverter;
use App\Helper\DaysOfWeek;
use App\Helper\NumericConverter;
use App\Repository\ClinicsRepository;
use App\Repository\CustomersRepository;
use App\Repository\DoctorsRepository;
use App\Repository\DoctorTimesRepository;
use App\Service\CoreApi\PatientApi;
use Doctrine\ORM\EntityManagerInterface;
use Morilog\Jalali\Jalalian;
use PhpParser\Node\Expr\Throw_;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpClient\HttpClient;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

/**
 * @Route("/reservation", name="website.reservation.")
 */
class ReservationController extends AbstractController
{
    /**
     * @param DoctorsRepository $repository
     * @param string $slug
     *
     * @Route("/{slug}", name="new")
     *
     * @return Response
     */
    public function new(string $slug, DoctorsRepository $repository): Response
    {
        if (!$doctor = $repository->findOneBy([
            'slug' => $slug
        ])) {
            return $this->render('');
        }

        $data = [
            'id' => $doctor->getId(),
            'name' => $doctor->getName(),
            'title' => $doctor->getTitle(),
            'telegram' => $doctor->getTelegram(),
            'whatsapp' => $doctor->getWhatsapp(),
            'google_map' => $doctor->getGoogleMap(),
            'slug' => $doctor->getSlug(),
            'waze' => $doctor->getWaze(),
            'avatar' => $doctor->getAvatar()
        ];

        return $this->render('website/reservation/new.html.twig', [
            'doctor' => $data,
        ]);
    }

    /**
     * @param Request $request
     * @param DoctorsRepository $repository
     * @param string $slug
     * @param CustomersRepository $customersRepository
     * @param DoctorsRepository $doctorsRepository
     * @param EntityManagerInterface $manager
     * @param ClinicsRepository $clinicsRepository
     *
     * @Route("/reserve/{slug}", name="new_reservation")
     *
     * @return Response
     */
    public function newReservation(Request $request, string $slug, DoctorsRepository $repository,
                                   CustomersRepository $customersRepository, DoctorsRepository $doctorsRepository,
                                   EntityManagerInterface $manager, ClinicsRepository $clinicsRepository): Response
    {
        $clinics = [];
        if (!$doctor = $repository->findOneBy([
            'slug' => $slug
        ])) {
            return $this->render('website/reservation/doctor_not_found.html.twig');
        }

        foreach ($doctor->getClinicDoctors() as $clinicDoctor)
        {
            $clinics[] = [
                'id' => $clinicDoctor->getClinic()->getId(),
                'name' => $clinicDoctor->getClinic()->getName(),
            ];
        }

        $doctorTimes = [];
        $days = (new DaysOfWeek())->getArray();
        $numeric = new NumericConverter();
        $mobile = $numeric->englishPreview($request->request->get('mobile'));
        $to_time = intval($request->request->get('from_time_input')) + 1;

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

        $data = [
            'id' => $doctor->getId(),
            'name' => $doctor->getName(),
            'title' => $doctor->getTitle(),
            'telegram' => $doctor->getTelegram(),
            'whatsapp' => $doctor->getWhatsapp(),
            'slug' => $doctor->getSlug(),
            'google_map' => $doctor->getGoogleMap(),
            'waze' => $doctor->getWaze(),
            'avatar' => $doctor->getAvatar(),
            'days' => $doctorTimes,
            'clinics' => $clinics
        ];

        if ($request->isMethod('POST')) {
            try {
                $cDate = new DateConverter();
                $app = new Appointments();

                if (!$customer = $customersRepository->findOneBy(['mobile' => $mobile])) {
                    $customer = new Customers();
                    $customer->setFirstName($request->request->get('name'))
                        ->setLastName($request->request->get('name'))
                        ->setMobile($request->request->get('mobile'))
                        ->setReason($request->request->get('reason'))
                        ->setCreateDate(new \DateTime())
                        ->setAids(false)
                        ->setAlergy(false)
                        ->setAsm(false)
                        ->setBardari(false)
                        ->setDiabet(false)
                        ->setEneeghad(false)
                        ->setEtiad(false)
                        ->setFesharKhun(false)
                        ->setKolie(false)
                        ->setSaratan(false)
                        ->setSaar(false)
                        ->setGhalb(false)
                        ->setRomatism(false)
                        ->setHepatit(false)
                        ->setShimiDarmani(false);

                    $manager->persist($customer);
                }

                $app->setCreateDate(new \DateTime())
                    ->setCustomer($customer)
                    ->setClinic($clinicsRepository->find($request->request->get('clinic')))
                    ->setStatus(3)
                    ->setDueDate(new \DateTime($cDate->shamsiToMiladi($request->request->get('date_input'))))
                    ->setDoctor($doctorsRepository->findOneBy(['slug' => $request->get('slug')]))
                    ->setToTime(new \DateTime('2020-01-01 ' . (string)$to_time . ':00:00'))
                    ->setFromTime(new \DateTime('2020-01-01 ' . $request->request->get('from_time_input') . ':00:00'));

                $manager->persist($app);
                $manager->flush();

                return $this->render('website/reservation/successful_time.html.twig', [
                    'doctor' => $data
                ]);
            } catch (\Exception $e) {
                dump($e->getMessage());
                die;
            }
        }

        return $this->render('website/reservation/new_reservation.html.twig', [
            'doctor' => $data,
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
