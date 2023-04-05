<?php

namespace App\Controller;

use App\Entity\Task;
use App\Form\TaskType;
use App\Repository\TaskRepository;
use Doctrine\Persistence\ManagerRegistry;
use mysql_xdevapi\Exception;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class TaskController extends AbstractController
{
    private TaskRepository $taskRepository;

    public function __construct(ManagerRegistry $doctrine)
    {
        $this->taskRepository = $doctrine->getRepository(Task::class);
    }

    #[Route('/task', name: 'app_task')]
    public function index(): Response
    {
        return $this->render('task/index.html.twig', [
            'controller_name' => 'TaskController',
        ]);
    }

    #[Route('/task/create', name: 'app_task_create')]
    public function new(Request $request): Response
    {
        $task = new Task();

        $form = $this->createForm(TaskType::class, $task);

        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {
            $task = $form->getData();
            $this->taskRepository->save($task, true);
            return $this->redirectToRoute('app_task_list');
        }

        return $this->render('task/create.html.twig', ['form' => $form->createView()]);
    }

    #[Route('/task/list', name: 'app_task_list')]
    public function list(): Response
    {
        $tasks = $this->taskRepository->findAll();
        return $this->render('task/list.html.twig', [
            'tasks' => $tasks,
        ]);
    }

    #[Route('/task/edit/{id}', name: 'app_task_edit')]
    public function edit(Request $request, Task $task): Response
    {
        //dd($task);
        $form = $this->createForm(TaskType::class, $task);

        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {
            $this->taskRepository->save($task, true);
            return $this->redirectToRoute('app_task_list');
        }

        return $this->render('task/edit.html.twig', [
            'form' => $form->createView(),
            'task' => $task,
        ]);
    }


    #[Route('/task/delete/{id}', name: 'app_task_delete')]
    public function delete(int $id): Response
    {
        try {
            $tasks = $this->taskRepository->find($id);
            $this->taskRepository->remove($tasks, true);
        } catch (Exception $e) {
            error_log($e->getMessage());
        }
        $this->addFlash('success', 'Tâche supprimée');
        return $this->redirectToRoute('app_task_list');
    }

    //TODO
    // améliorer la façon de supprimer (avec une vérification?)
    // faire la page delete de twig
}
