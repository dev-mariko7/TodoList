<?php

namespace App\Controller;

use App\Controller\services\AnonymeUser;
use App\Entity\Task;
use App\Entity\User;
use App\Form\TaskType;
use App\Repository\TaskRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Security\Core\Encoder\UserPasswordEncoderInterface;

class TaskController extends AbstractController
{
    public function __construct(AnonymeUser $anonymeUser, EntityManagerInterface $entityManager, UserPasswordEncoderInterface $passwordEncoder)
    {
        $anonymeUser->update($entityManager, $passwordEncoder);
    }

    /**
     * @Route("/tasks", name="task_list")
     */
    public function listAction()
    {
        //sleep(5);
        return $this->render('task/list.html.twig', [
            'tasks' => $this->getDoctrine()->getRepository(Task::class)->findBy(['isDone' => 0], ['last_modification' => 'DESC']),
        ]);
    }

    /**
     * @Route("/tasks-is-done", name="task_is_done")
     */
    public function taskListIsDone()
    {
        return $this->render('task/tasksdone.html.twig', [
            'tasks' => $this->getDoctrine()->getRepository('App:Task')->findBy(['isDone' => 1], ['last_modification' => 'DESC']),
        ]);
    }

    /**
     * @Route("/tasks/create", name="task_create")
     */
    public function createAction(Request $request)
    {
        $task = new Task();
        $form = $this->createForm(TaskType::class, $task);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $em = $this->getDoctrine()->getManager();
            $user = new User();
            $timezone = new \DateTimeZone('Europe/Paris');
            $time = new \DateTime('now', $timezone);
            $getuser = $this->getDoctrine()->getManager()->getRepository(User::class)->find($this->getUser()->getId());
            $task->setUser($getuser);
            $task->setLastModification($time);
            $em->persist($task);
            $em->flush();

            $this->addFlash('success', 'La t??che a ??t?? bien ??t?? ajout??e.');

            return $this->redirectToRoute('task_list');
        }

        return $this->render('task/create.html.twig', ['form' => $form->createView()]);
    }

    /**
     * @Route("/tasks/{id}/edit", name="task_edit")
     */
    public function editAction(Request $request, $id)
    {
        $entityManager = $this->getDoctrine()->getManager();
        $task = $entityManager->getRepository(Task::class)->find($id);

        if (!$task) {
            throw $this->createNotFoundException('Aucun produit trouv?? pour l\id : '.$id);
        }

        $form = $this->createForm(TaskType::class, $task);
        $timezone = new \DateTimeZone('Europe/Paris');
        $time = new \DateTime('now', $timezone);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            //$getuser = $this->getDoctrine()->getManager()->getRepository(User::class)->find($this->getUser()->getId());
            //$task->setUser($getuser);
            $task->setLastModification($time);
            $this->getDoctrine()->getManager()->flush();

            $this->addFlash('success', 'La t??che a bien ??t?? modifi??e.');

            return $this->redirectToRoute('task_list');
        }

        return $this->render('task/edit.html.twig', [
            'form' => $form->createView(),
            'task' => $task,
        ]);
    }

    /**
     * @Route("/tasks/{id}/toggle", name="task_toggle")
     */
    public function toggleTaskAction(TaskRepository $taskRepository, $id)
    {
        $entityManager = $this->getDoctrine()->getManager();
        $task = $entityManager->getRepository(Task::class)->find($id);

        if (!$task) {
            throw $this->createNotFoundException('Aucun produit trouv?? pour l\id : '.$id);
        }

        $task->isDone() ? $task->toggle(0) : $task->toggle(1);
        $entityManager->flush();

        $this->addFlash('success', sprintf('La t??che %s a bien ??t?? marqu??e comme faite.', $task->getTitle()));

        return $this->redirectToRoute('task_list');
    }

    /**
     * @Route("/tasks/{id}/delete", name="task_delete")
     *
     * @param Task $task
     */
    public function deleteTaskAction($id, TaskRepository $taskRepository): Response
    {
        $task = $taskRepository->find($id);
        $em = $this->getDoctrine()->getManager();
        $em->remove($task);
        $em->flush();

        $this->addFlash('success', 'La t??che a bien ??t?? supprim??e.');

        return $this->redirectToRoute('task_list');
    }
}
