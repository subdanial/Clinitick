<?php

namespace App\Controller\Dashboard;

use App\Entity\Assistants;
use App\Repository\DoctorsRepository;
use App\Repository\DoctorTimesRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

/**
 * @Route("/dashboard/settings", name="dashboard.settings.")
 */
class SettingController extends AbstractController
{
    /**
     * @param Request $request
     * @param DoctorsRepository $repository
     *
     * @Route("", name="index")
     *
     * @return Response
     */
    public function index(Request $request, DoctorsRepository $repository): Response
    {
        if($request->get('doctor'))
        {
            $doctor = $repository->find($request->get('doctor'));
        } else {
            $doctor = $this->getUser()->getDoctors[0];
        }

        $data = [
            'id' => $doctor->getId(),
            'title' => $doctor->getTitle(),
            'avatar' => $doctor->getAvatar(),
            'google_map' => $doctor->getGoogleMap(),
            'whatsapp' => $doctor->getWhatsapp(),
            'telegram' => $doctor->getTelegram(),
        ];

        return $this->render('dashboard/setting/index.html.twig', [
            'data' => $data,
        ]);
    }

    /**
     * @param Request $request
     * @param EntityManagerInterface $manager
     * @param DoctorsRepository $repository
     * @param DoctorTimesRepository $doctorTimesRepository
     *
     * @Route("/doctor", name="doctor")
     *
     * @return Response
     */
    public function doctorSettings(Request $request, EntityManagerInterface $manager, DoctorsRepository $repository, DoctorTimesRepository $doctorTimesRepository): Response
    {
        /**
         * @var $assistant Assistants
         */
        $assistant = $this->getUser();
        $times = [];
        $assistantDoctors = [];

        if($request->isMethod('POST'))
        {
            // Doctor Times
            if($request->request->get('is_doctor_times'))
            {
                foreach ($request->request->get('day') as $key => $item) {
                    $doctor_time = $doctorTimesRepository->find($key);
                    $from_time = new \DateTime('1970-01-01 '.$item['from_time']);
                    $to_time = new \DateTime('1970-01-01 '.$item['to_time']);

                    if(!empty($item['from_time']) && !empty($item['to_time']))
                    {
                        if(date_diff($from_time, $to_time)->h <= 0)
                        {
                            $this->addFlash('danger', 'زمان اولیه نمی‌تواند از زمان ثانویه بیشتر باشد');

                            return $this->redirectToRoute('dashboard.setting.doctor_times');
                        }

                        $doctor_time->setFromTime($from_time);
                        $doctor_time->setToTime($to_time);
                    }

                    $manager->persist($doctor_time);
                    $this->addFlash('success', 'زمانبندی شما با موفقیت تغییر کرد');
                }
            }

            // Doctor Contacts
            if($request->request->get('is_doctor_contact'))
            {
                $doctor = $repository->find($request->request->get('doctor'));

                $doctor->setUpdateDate(new \DateTime());
                $doctor->setName($request->request->get('name'));
                $doctor->setGoogleMap($request->request->get('google_map'));
                $doctor->setTitle($request->request->get('title'));
                $doctor->setWhatsapp($request->request->get('whatsapp'));
                $doctor->setTelegram($request->request->get('telegram'));
                $doctor->setWaze($request->request->get('waze'));

                $manager->persist($doctor);
                $this->addFlash('success', 'اطلاعات شما با موفقیت تغییر کرد');
            }

            // Doctor Profile
            $manager->flush();
        }

        if($request->get('doctor'))
        {
            $doctor = $repository->find($request->get('doctor'));
        } else {
            $doctor = $assistant->getClinicAssistants()[0]->getClinic()->getClinicDoctors()[0]->getDoctor();
        }

        foreach ($doctor->getDoctorTimes() as $item)
        {
            $times[] = [
                'id' => $item->getId(),
                'day' => $item->getDayOfWeek(),
                'from_time' => $item->getFromTime() ? $item->getFromTime()->format('G:i:s') : '',
                'to_time' => $item->getToTime() ? $item->getToTime()->format('G:i:s') : ''
            ];
        }

        foreach ($assistant->getClinicAssistants() as $clinic)
        {
            foreach ($clinic->getClinic()->getClinicDoctors() as $item)
            {
                $assistantDoctors[] = [
                    'id' => $item->getDoctor()->getId(),
                    'name' => $item->getDoctor()->getName(),
                ];
            }
        }

        $contact = [
            'id' => $doctor->getId(),
            'title' => $doctor->getTitle(),
            'avatar' => $doctor->getAvatar(),
            'google_map' => $doctor->getGoogleMap(),
            'waze' => $doctor->getWaze(),
            'whatsapp' => $doctor->getWhatsapp(),
            'telegram' => $doctor->getTelegram(),
            'name' => $doctor->getName(),
            'link' => 'https://dastyar.clinitick.com/reservation/'.$doctor->getSlug()
        ];

        return $this->render('dashboard/setting/doctor.html.twig', [
            'contact' => $contact,
            'times' => $times,
            'active_doctor' => $doctor->getId(),
            'assistantDoctors' => $assistantDoctors
        ]);
    }
}
