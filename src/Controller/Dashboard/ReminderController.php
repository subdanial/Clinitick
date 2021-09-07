<?php

namespace App\Controller\Dashboard;

use App\Entity\Assistants;
use App\Entity\ReminderLists;
use App\Entity\Reminders;
use App\Helper\DateConverter;
use App\Repository\ReminderListsRepository;
use App\Repository\RemindersRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

/**
 * @Route("/dashboard/reminder", name="dashboard.reminder.")
 */
class ReminderController extends AbstractController
{
    /**
     * @param Request $request
     * @param ReminderListsRepository $listsRepository
     *
     * @Route("s", name="index")
     *
     * @return Response
     * @throws \Exception
     */
    public function index(Request $request, ReminderListsRepository $listsRepository): Response
    {
        /**
         * @var $assistant Assistants
         */
        $assistant = $this->getUser();
        $reminders = [];
        $reminderLists = [];
        $important_count = 0;
        $due_count = 0;
        $today_count = 0;
        $cDate = new DateConverter();
        $activeList = '';

        foreach ($assistant->getReminderLists() as $reminderList) {
            $cnt = 0;
            foreach ($reminderList->getReminders() as $item) {
                if(!$item->getIsDone())
                {
                    $cnt++;
                }
            }

            $reminderLists[] = [
                'id' => $reminderList->getId(),
                'name' => $reminderList->getName(),
                'cnt' => $cnt
            ];
        }

        if($request->get('fetchList'))
        {
            $list = $listsRepository->find($request->get('fetchList'));
            $activeList = $list->getName();

            foreach ($list->getReminders() as $reminder)
            {
                $is_today = false;
                if ($reminder->getIsImportant() == true) {
                    $important_count++;
                }

                if (!empty($reminder->getDueDate())) {
                    $due_count++;
                }

                if ($reminder->getDueDate() && $reminder->getDueDate()->format('Y-m-d') == (new \DateTime())->format('Y-m-d')) {
                    $is_today = true;
                    $today_count++;
                }

                $reminders[] = [
                    'id' => $reminder->getId(),
                    'description' => $reminder->getDescription(),
                    'important' => $reminder->getIsImportant(),
                    'done' => $reminder->getIsDone(),
                    'due_date' => empty($reminder->getDueDate()) ? false : $cDate->miladiToShamsi($reminder->getDueDate()),
                    'is_today' => $is_today
                ];
            }
        } else {
            if($list = $assistant->getReminderLists()[0])
            {
                $activeList = $list->getName();
                foreach ($assistant->getReminderLists()[0]->getReminders() as $reminder)
                {
                    $is_today = false;
                    if ($reminder->getIsImportant() == true) {
                        $important_count++;
                    }

                    if (!empty($reminder->getDueDate())) {
                        $due_count++;
                    }

                    if ($reminder->getDueDate() && $reminder->getDueDate()->format('Y-m-d') == (new \DateTime())->format('Y-m-d')) {
                        $is_today = true;
                        $today_count++;
                    }

                    $reminders[] = [
                        'id' => $reminder->getId(),
                        'description' => $reminder->getDescription(),
                        'important' => $reminder->getIsImportant(),
                        'done' => $reminder->getIsDone(),
                        'due_date' => empty($reminder->getDueDate()) ? false : $cDate->miladiToShamsi($reminder->getDueDate()),
                        'is_today' => $is_today
                    ];
                }
            }
        }

        return $this->render('dashboard/reminder/index.html.twig', [
            'reminder_lists' => $reminderLists,
            'reminders' => array_reverse($reminders),
            'due_count' => $due_count,
            'important_count' => $important_count,
            'today_count' => $today_count,
            'active_list' => $activeList
        ]);
    }

    /**
     * @param Request $request
     * @param EntityManagerInterface $manager
     * @param ReminderListsRepository $listsRepository
     *
     * @Route("/new", name="new")
     *
     * @return Response
     */
    public function new(Request $request, EntityManagerInterface $manager, ReminderListsRepository $listsRepository): Response
    {
        if ($request->isMethod('POST')) {
            try {
                $reminder = new Reminders();
                $cDate = new DateConverter();

                $reminder->setDescription($request->request->get('description'));
                $reminder->setIsDone(false);
                $reminder->setIsImportant(!empty($request->request->get('important')));
                $reminder->setReminderList($listsRepository->find($request->request->get('reminder_list')));
                $reminder->setDueDate(new \DateTime($cDate->shamsiToMiladi($request->request->get('due_date'))));
                $reminder->setCreateDate(new \DateTime());

                $manager->persist($reminder);
                $manager->flush();

                $this->addFlash('success', 'یادآوری با موفقیت افزوده شد');
            } catch (\Exception $e) {
                $this->addFlash('error', $e->getMessage());
            }

            return $this->redirectToRoute('dashboard.reminder.index');
        }

        return $this->render('dashboard/reminder/new.html.twig', [
            'controller_name' => 'CustomerController',
        ]);
    }

    /**
     * @param Request $request
     * @param EntityManagerInterface $manager
     * @param RemindersRepository $repository
     *
     * @Route("/change_state", name="change_state")
     *
     * @return JsonResponse
     */
    public function changeState(Request $request, EntityManagerInterface $manager, RemindersRepository $repository): JsonResponse
    {
        $reminder = $repository->find($request->get('rid'));

        $reminder->setIsDone($request->get('val'));

        $manager->persist($reminder);
        $manager->flush();

        return new JsonResponse([
            'message' => 'OK'
        ]);
    }

    /**
     * @param Request $request
     * @param EntityManagerInterface $manager
     *
     * @Route("/new/list", name="new.list")
     *
     * @return Response
     */
    public function newList(Request $request, EntityManagerInterface $manager): Response
    {
        $list = new ReminderLists();
        $list->setCreateDate(new \DateTime());
        $list->setAssistant($this->getUser());
        $list->setName($request->request->get('name'));

        $manager->persist($list);
        $manager->flush();

        $this->addFlash('success', 'لیست شما با موفقیت افزوده شد');
        return $this->redirectToRoute('dashboard.reminder.index');
    }
}
