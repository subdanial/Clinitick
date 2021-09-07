<?php

namespace App\Controller\Api;

use App\Entity\Assistants;
use App\Entity\ClinicAssistants;
use App\Entity\ClinicDoctors;
use App\Entity\Clinics;
use App\Entity\Doctors;
use App\Entity\DoctorTimes;
use App\Helper\DateConverter;
use App\Helper\SlugGenerator;
use App\Repository\AssistantsRepository;
use App\Repository\ClinicAssistantsRepository;
use App\Repository\ClinicDoctorsRepository;
use App\Repository\ClinicsRepository;
use App\Repository\DoctorsRepository;
use App\Service\CoreApi\AuthApi;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpClient\HttpClient;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Security\Core\Encoder\UserPasswordEncoderInterface;

/**
 * @Route("/api/sync", name="api.sync.")
 */
class SyncController extends AbstractController
{
    /**
     * @param EntityManagerInterface $manager
     * @param UserPasswordEncoderInterface $passwordEncoder
     * @param DoctorsRepository $doctorsRepository
     * @param AssistantsRepository $assistantsRepository
     * @param ClinicsRepository $clinicsRepository
     * @param ClinicAssistantsRepository $clinicAssistantsRepository
     * @param ClinicDoctorsRepository $clinicDoctorsRepository
     *
     * @Route("/subscription", name="subscription")
     *
     * @return JsonResponse
     */
    public function new(EntityManagerInterface $manager, UserPasswordEncoderInterface $passwordEncoder,
                        DoctorsRepository $doctorsRepository, AssistantsRepository $assistantsRepository,
                        ClinicsRepository $clinicsRepository, ClinicAssistantsRepository $clinicAssistantsRepository,
                        ClinicDoctorsRepository $clinicDoctorsRepository): JsonResponse
    {
        try {
            $data = json_decode(file_get_contents('php://input'), false);
            $slugG = new SlugGenerator();

            foreach ($data->dentists as $dentist) {
                if (!$doctor = $doctorsRepository->find($dentist->id)) {
                    $doctor = new Doctors();

                    $doctor->setId($dentist->id)
                        ->setCreateDate(new \DateTime())
                        ->setSlug($slugG->generate());

                    for ($i = 1; $i <= 7; $i++) {
                        $doctorTime = new DoctorTimes();

                        $doctorTime->setDoctor($doctor)
                            ->setDayOfWeek($i);

                        $manager->persist($doctorTime);
                    }
                }

                $doctor->setUpdateDate(new \DateTime())
                    ->setTitle($dentist->speciality)
                    ->setTelegram($dentist->phone)
                    ->setName($dentist->first_name . ' ' . $dentist->last_name);

                $manager->persist($doctor);
            }

            foreach ($data->secretaries as $secretary) {
                if (!$assisant = $assistantsRepository->find($secretary->id)) {
                    $assisant = new Assistants();
                    $assisant->setId($secretary->id)
                        ->setCreateDate(new \DateTime())
                        ->setRoles(['ROLE_ASSISTANT']);

                    $manager->persist($assisant);
                }

                $assisant->setPassword($passwordEncoder->encodePassword(
                    $assisant,
                    $secretary->password
                ));
                $assisant->setPlainPassword($secretary->password);
                $assisant->setFirstName($secretary->first_name);
                $assisant->setLastName($secretary->last_name);
                $assisant->setMobile($secretary->phone);

                $manager->persist($assisant);
            }

            foreach ($data->clinics as $item) {
                if (!$clinic = $clinicsRepository->findOneBy(['uid' => $item->id])) {
                    $clinic = new Clinics();
                    $clinic->setUid($item->id);
                }
                $clinic->setAddress($item->address)
                    ->setName($item->title);

                $manager->persist($clinic);

                if (!$clinicDoctor = $clinicDoctorsRepository->findClinicAndDoctorPair($item->dentist_id, $clinic->getId())) {
                    $clinicDoctor = new ClinicDoctors();

                    $clinicDoctor->setClinic($clinic)
                        ->setDoctor($doctorsRepository->find($item->dentist_id));

                    $manager->persist($clinicDoctor);
                }
            }
            $manager->flush();

            foreach ($data->secretaryClinics as $item) {
                $binded_assistant = $assistantsRepository->find($item->secretary_id);
                $binded_clinic = $clinicsRepository->findOneBy(['uid' => $item->clinic_id]);

                if (!$clinicAssistant = $clinicAssistantsRepository->findOneBy([
                    'assistant' => $binded_assistant,
                    'clinic' => $binded_clinic,
                ])) {
                    $clinicAssistant = new ClinicAssistants();
                    $clinicAssistant->setClinic($binded_clinic)
                        ->setAssistant($binded_assistant);
                }
                $manager->persist($clinicAssistant);
            }

            $manager->flush();

            return new JsonResponse([
                'data' => 'ok'
            ], 200);
        } catch (\Exception $exception) {
            return new JsonResponse([
                'data' => 'error',
                'error' => $exception->getMessage()
            ], 500);
        }
    }
}
