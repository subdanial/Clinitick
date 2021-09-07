<?php

namespace App\Controller\Dashboard;

use App\Entity\Assistants;
use App\Entity\Customers;
use App\Entity\DoctorCustomers;
use App\Entity\Doctors;
use App\Helper\DateConverter;
use App\Helper\NumericConverter;
use App\Repository\CustomersRepository;
use App\Service\CoreApi\PatientApi;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpClient\HttpClient;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Uid\Uuid;

/**
 * @Route("/dashboard/patient", name="dashboard.customer.")
 */
class CustomerController extends AbstractController
{
    /**
     * @param CustomersRepository $repository
     *
     * @Route("s", name="index")
     *
     * @return Response
     */
    public function index(CustomersRepository $repository): Response
    {
        $patientApi = new PatientApi(HttpClient::create(), $this->getUser());
        $respo = $patientApi->getDentistPatients();

        dd($respo);
        $customers = [];
        foreach ($repository->findAll() as $item) {
            $customers[] = [
                'id' => $item->getId(),
                'name' => $item->getFullname(),
                'mobile' => $item->getMobile(),
                'email' => $item->getEmail(),
            ];
        }

        return $this->render('dashboard/customer/index.html.twig', [
            'customers' => $customers,
        ]);
    }

    /**
     * @param Request $request
     * @param EntityManagerInterface $manager
     * @param CustomersRepository $customersRepository
     *
     * @Route("/new", name="new")
     *
     * @return Response
     */
    public function new(Request $request, EntityManagerInterface $manager, CustomersRepository $customersRepository): Response
    {
        if ($request->isMethod('POST')) {
            $doctor = $this->getDoctrine()->getRepository(Doctors::class)->find($request->request->get('doctor'));
            $numC = new NumericConverter();

            try {
                if ($customersRepository->findOneBy([
                    'mobile' => $numC->englishPreview($request->request->get('mobile'))
                ])) {
                    $this->addFlash('danger', 'بیماری با این شماره موبایل قبلا ثبت شده است');

                    return $this->redirectToRoute('dashboard.customer.new');
                }

                $customer = new Customers();

                $customer->setFirstName($request->request->get('fname'))
                    ->setLastName($request->request->get('lname'))
                    ->setMobile($numC->englishPreview($request->request->get('mobile')))
                    ->setReason($request->request->get('reason'))
                    ->setAids(empty($request->request->get('aids')) ? false : true)
                    ->setAlergy(empty($request->request->get('alergy')) ? false : true)
                    ->setAsm(empty($request->request->get('asm')) ? false : true)
                    ->setBardari(empty($request->request->get('bardari')) ? false : true)
                    ->setDiabet(empty($request->request->get('diabet')) ? false : true)
                    ->setEneeghad(empty($request->request->get('eneghad')) ? false : true)
                    ->setEtiad(empty($request->request->get('etiad')) ? false : true)
                    ->setFesharKhun(empty($request->request->get('feshar_khun')) ? false : true)
                    ->setKolie(empty($request->request->get('kolie')) ? false : true)
                    ->setSaratan(empty($request->request->get('saratan')) ? false : true)
                    ->setSaar(empty($request->request->get('saar')) ? false : true)
                    ->setGhalb(empty($request->request->get('ghalb')) ? false : true)
                    ->setRomatism(empty($request->request->get('romastism')) ? false : true)
                    ->setHepatit(empty($request->request->get('hepatit')) ? false : true)
                    ->setShimiDarmani(empty($request->request->get('shimi_darmani')) ? false : true)
                    ->setDescription($request->request->get('description'))
                    ->setUid(Uuid::v4())
                    ->setCreateDate(new \DateTime());

                $manager->persist($customer);

                $doctorCustomer = new DoctorCustomers();

                $doctorCustomer->setDoctor($doctor)
                    ->setCustomer($customer);

                $manager->persist($doctorCustomer);
                $manager->flush();

                $patientApi = new PatientApi(HttpClient::create(), $this->getUser());
                $patientApi->create($customer, $doctor->getId());

                $this->addFlash('success', 'بیمار جدید با موفقیت افزوده شد');
            } catch (\Exception $e) {
                $this->addFlash('error', 'خطایی در ثبت مشتری جدید بوجود آمده');
            }

            return $this->redirectToRoute('dashboard.customer.index');
        }

        $doctors = [];
        /**
         * @var Assistants $user
         */
        $user = $this->getUser();

        foreach ($user->getClinicAssistants() as $val) {
            foreach ($val->getClinic()->getClinicDoctors() as $doctor) {
                $doctors[] = [
                    'id' => $doctor->getDoctor()->getId(),
                    'name' => $doctor->getDoctor()->getName(),
                ];
            }
        }

        return $this->render('dashboard/customer/new.html.twig', [
            'doctors' => $doctors
        ]);
    }


    /**
     * @param Customers $customer
     *
     * @Route("/preview/{customer}", name="preview")
     *
     * @return Response
     * @throws \Exception
     */
    public function preview(Customers $customer): Response
    {
        /**
         * @param Assistants $assistant
         */
        $assistant = $this->getUser();
        $treatments = [];
        $doctors = [];
        $cDate = new DateConverter();

        foreach ($assistant->getClinicAssistants() as $clinicAssistant) {
            $clinics = [];
            foreach ($clinicAssistant->getClinic()->getClinicDoctors() as $doctor) {
                $clinics[] = [
                    'id' => $doctor->getClinic()->getId(),
                    'name' => $doctor->getClinic()->getName(),
                ];

                $doctors[$doctor->getId()] = [
                    'id' => $doctor->getDoctor()->getId(),
                    'name' => $doctor->getDoctor()->getName(),
                    'clinics' => $clinics
                ];
            }
        }

        foreach ($customer->getAppointments() as $appointment) {
            foreach ($appointment->getTreatments() as $treat) {
                $treatments[] = [
                    'id' => $treat->getId(),
                    'name' => $treat->getPlanName(),
                    'number' => $treat->getTeethNumber(),
                    'date' => $cDate->miladiToShamsi($treat->getCreateDate()),
                    'price' => $treat->getTotalPrice(),
                ];
            }
        }

        $appointment_data = [
            'treatments' => $treatments,
            'customer_id' => $customer->getId()
        ];

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

        return $this->render('dashboard/customer/preview.html.twig', [
            'appointment' => $appointment_data,
            'customer' => $customer_data,
            'doctors' => $doctors,
            'clinics' => $clinics
        ]);
    }
}
