<?php

namespace App\Controller\Dashboard;

use App\Entity\Assistants;
use App\Entity\Doctors;
use App\Entity\TransactionDetails;
use App\Entity\Transactions;
use App\Helper\DateConverter;
use App\Helper\TransactionsDictionary;
use App\Repository\CustomersRepository;
use App\Repository\TransactionsRepository;
use App\Service\CoreApi\FinanceApi;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpClient\HttpClient;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Uid\Uuid;

/**
 * @Route("/dashboard/accounting", name="dashboard.accounting.")
 */
class AccountingController extends AbstractController
{
    /**
     * @param TransactionsRepository $transactionsRepository
     *
     * @Route("s", name="index")
     *
     * @return Response
     */
    public function index(TransactionsRepository $transactionsRepository): Response
    {
        /**
         * @var Assistants $assistant
         */
        $assistant = $this->getUser();
        $transactions = [];
        $doctors = [];
        $cDate = new DateConverter();
        $transDictionary = new TransactionsDictionary();
        $doctorRepo = $this->getDoctrine()->getRepository(Doctors::class);
        $transDictionary_arr = $transDictionary->getArray();

        foreach ($assistant->getClinicAssistants() as $cln)
        {
            foreach ($cln->getClinic()->getClinicDoctors() as $doctor)
            {
                $doctors[] = [
                    'id' => $doctor->getDoctor()->getId(),
                    'name' => $doctor->getDoctor()->getName(),
                ];
            }
        }

        foreach ($transactionsRepository->findAll() as $item) {
//            $financeApi = new FinanceApi(HttpClient::create(), $this->getUser());
//            $resp = $financeApi->getSingleFinance($item);
//
//            dump($resp);die;
//            $details = [];
            foreach ($item->getTransactionDetails() as $detail) {
                $details[] = [
                    'id' => $detail->getId(),
                    'description' => $detail->getDescription(),
                    'price' => $detail->getPrice(),
                    'related' => null,//$transDictionary_arr[$detail->getRelatedEntity()]['name'],
                    'related_id' => $detail->getRelatedEntityId(),
                    'type' => $detail->getType() == 'C' ? 'درآمد' : 'خرج',
                    'date' => $cDate->miladiToShamsi($detail->getCreateDate()),
                ];
            }

            $transactions[] = [
                'id' => $item->getId(),
                'related' => 'دکتر',//$transDictionary_arr[$item->getRelatedEntity()]['name'],
                'related_id' => !empty($item->getRelatedEntityId()) ? ' - ' . $doctorRepo->find($item->getRelatedEntityId())->getName() : null,
                'date' => $cDate->miladiToShamsi($item->getCreateDate()),
                'details' => $details
            ];
        }

        return $this->render('dashboard/accounting/index.html.twig', [
            'transactions' => $transactions,
            'doctors' => $doctors
        ]);
    }

    /**
     * @param Request $request
     * @param EntityManagerInterface $manager
     * @param TransactionsRepository $transactionsRepository
     *
     * @Route("/new", name="new")s
     *
     * @return Response
     */
    public function new(Request $request, EntityManagerInterface $manager, TransactionsRepository $transactionsRepository): Response
    {
        if ($request->isMethod('POST')) {
            try {
                $cDate = new DateConverter();

                if ($request->request->get('isManual')) {
                    $trans = new Transactions();

                    $trans->setRelatedEntity('doctor')
                        ->setRelatedEntityId($request->request->get('doctor'))
                        ->setCreateDate(new \DateTime())
                        ->setUid(Uuid::v4());

                    $manager->persist($trans);

                    $transDetail = new TransactionDetails();

                    $transDetail->setCreateDate(new \DateTime($cDate->shamsiToMiladi($request->request->get('due_date'))))
                        ->setDescription($request->request->get('description'))
                        ->setTransaction($trans)
                        ->setPrice($request->request->get('price'))
                        ->setRelatedEntity($request->request->get('type') == 'C' ? 'incomes' : 'payables')
                        ->setType($request->request->get('type'));

                    $manager->persist($transDetail);
                    $manager->flush();
                } else {
                    $trans = $transactionsRepository->findOneBy([
                        'related_entity' => 'treatment',
                        'related_entity_id' => $request->request->get('treatment')
                    ]);

                    $transDetail = new TransactionDetails();

                    $transDetail->setCreateDate(new \DateTime())
                        ->setDescription('سند خودکار پرداخت')
                        ->setTransaction($trans)
                        ->setPrice($request->request->get('price'))
                        ->setRelatedEntity('customer')
                        ->setRelatedEntityId($request->request->get('customer'))
                        ->setType('C');

                    $manager->persist($transDetail);
                    $manager->flush();
                }

                $financeApi = new FinanceApi(HttpClient::create(), $this->getUser());
                $financeApi->create($trans, $transDetail);

                $this->addFlash('success', 'سند با موفقیت افزوده شد');
            } catch (\Exception $e) {
                $this->addFlash('error', $e->getMessage());
            }

            return $this->redirectToRoute('dashboard.accounting.index');
        }

        return $this->render('dashboard/accounting/new.html.twig', [
            'controller_name' => 'CustomerController',
        ]);
    }
}
