<?php

namespace App\Controller\Dashboard;

use App\Entity\Assistants;
use App\Helper\DateConverter;
use App\Helper\Days;
use App\Helper\Months;
use App\Repository\AppointmentsRepository;
use App\Service\CoreApi\AuthApi;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpClient\HttpClient;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

/**
 * @Route("/dashboard", name="dashboard.home.")
 */
class DashboardController extends AbstractController
{
    /**
     * @Route("/test/sms", name="test.sms")
     *
     * @throws \Symfony\Contracts\HttpClient\Exception\TransportExceptionInterface
     */
    public function test()
    {
//        $data_array = [
//            [
//                'name' => 'ریحانه',
//                'phone' => '09167551461',
//            ],
//            [
//                'name' => 'کیمیا',
//                'phone' => '09121484524',
//            ],
//            [
//                'name' => 'نازنین',
//                'phone' => '09134378004',
//            ],
//            [
//                'name' => 'سعیده',
//                'phone' => '09123400621',
//            ],
//            [
//                'name' => 'alma',
//                'phone' => '09309249942',
//            ],
//            [
//                'name' => 'آنا',
//                'phone' => '09166004072',
//            ],
//            [
//                'name' => 'ثریا',
//                'phone' => '09111245143',
//            ],
//            [
//                'name' => 'مهتاب',
//                'phone' => '09303558402',
//            ],
//            [
//                'name' => 'anis',
//                'phone' => '09147573482',
//            ],
//            [
//                'name' => 'آناهیتا',
//                'phone' => '09129316136',
//            ],
//            [
//                'name' => 'پارمیدا',
//                'phone' => '09367718337',
//            ],
//            [
//                'name' => 'asal',
//                'phone' => '09382026281',
//            ],
//            [
//                'name' => 'روشنک',
//                'phone' => '09363491995',
//            ],
//            [
//                'name' => 'ملیکا',
//                'phone' => '09367341417',
//            ],
//            [
//                'name' => 'محبوبه',
//                'phone' => '09384063103',
//            ],
//            [
//                'name' => 'ندا',
//                'phone' => '09132510838',
//            ],
//            [
//                'name' => 'راضیه',
//                'phone' => '09027628800',
//            ],
//            [
//                'name' => 'پرند',
//                'phone' => '09338591411',
//            ],
//            [
//                'name' => 'امین',
//                'phone' => '09382829967',
//            ],
//            [
//                'name' => 'مینه',
//                'phone' => '09388201213',
//            ],
//            [
//                'name' => 'ساینا',
//                'phone' => '09120702933',
//            ],
//            [
//                'name' => 'احمد',
//                'phone' => '09305156482',
//            ],
//            [
//                'name' => 'عرفان',
//                'phone' => '09128571650',
//            ],
//        ];
//        $client = HttpClient::create();
//        $d = [];
//
//        foreach ($data_array as $value)
//        {
//            $resp = $client->request('POST', 'http://ippanel.com/api/select', [
//                'json' => [
//                    'op' => 'pattern',
//                    'user' => 'arsalana0824',
//                    'pass' => 'Arsalan12345',
//                    'fromNum' => '+983000505',
//                    'toNum' => $value['phone'],
//                    'patternCode' => '8ynb60ms8w',
//                    'inputData' => [
//                        [
//                            "name" => $value['name'],
//                            'link' =>  'https://join.skype.com/EvQOLnBzWvOW'
//                        ]
//                    ]
//                ]
//            ]);
//
//            $d[$value['phone']] = [
//                'name' => $value['name'],
//                'code' => $resp->getStatusCode(),
//                'content' => $resp->getContent(false)
//            ];
//        }
//
//dump($d);die;
//        return $resp->getStatusCode();
    }
    /**
     * @param Request $request
     * @param AppointmentsRepository $repository
     *
     * @Route("", name="index")
     *
     * @return Response
     * @throws \Exception
     */
    public function index(Request $request, AppointmentsRepository $repository): Response
    {
        /**
         * @param $user Assistants
         */
        $user = $this->getUser();

        if(empty($user->getToken()))
        {
            $authToken = new AuthApi(HttpClient::create(), $user);

            $response = $authToken->getAuthToken($user);

            $user->setToken($response['data']['token']);

            $this->getDoctrine()->getManager()->persist($user);
            $this->getDoctrine()->getManager()->flush();
        }

        $cDate = new DateConverter();
        if($request->get('date'))
        {
            $date = (new \DateTime($request->get('date')))->format('Y-m-d');
        } else {
            $date = (new \DateTime())->format('Y-m-d');
        }

        $appointments = [];
        $days = [];
        $months_array = (new Months())->getArray();
        $current_month = $cDate->miladiToShamsiPart(null, 'M');
        $current_month_name = '';
        $days_array = (new Days())->getArray();

        foreach ($months_array as $item) {
            if ($item['id'] == $current_month) {
                $current_month_name = $item['name'];
            }
        }

        for ($i = 0; $i < 7; $i++) {
            $day_id = $cDate->miladiToShamsiDayId((new \DateTime($date))->add(new \DateInterval("P" . $i . "D"))) - 1;
            $day_part = $cDate->miladiToShamsiPart((new \DateTime($date))->add(new \DateInterval("P" . $i . "D")), 'dd');
            $gregorian_date = (new \DateTime($date))->add(new \DateInterval("P" . $i . "D"));

            if ($i == 0) {
                $days[] = [
                    'id' => $i,
                    'name' => $days_array[$day_id]['name'],
                    'abbr' => $days_array[$day_id]['abbr'],
                    'date' => $day_part,
                    'gregorian' => $gregorian_date->format('Y-m-d'),
                    'active' => true
                ];
            } else {
                $days[] = [
                    'id' => $i,
                    'name' => $days_array[$day_id]['name'],
                    'abbr' => $days_array[$day_id]['abbr'],
                    'date' => $day_part,
                    'gregorian' => $gregorian_date->format('Y-m-d'),
                    'active' => false
                ];
            }
        }

        foreach ($repository->findBy([
            'due_date' => new \DateTime($date)
        ]) as $item) {
            $appointments[] = [
                'id' => $item->getId(),
                'doctor' => $item->getDoctor()->getName(),
                'customer' => $item->getCustomer()->getFullname(),
                'customer_id' => $item->getCustomer()->getId(),
                'mobile' => $item->getCustomer()->getFullMobile(),
                'due_date' => $cDate->miladiToShamsi($item->getDueDate()),
                'from_time' => $item->getFromTime()->format('G:i'),
                'status' => $item->getStatus(),
            ];
        }

        return $this->render('dashboard/home/index.html.twig', [
            'appointments' => $appointments,
            'current_month' => $current_month_name,
            'days' => $days,
        ]);
    }
}
