<?php

namespace App\Controller;

use App\Repository\ProjectRepository;
use Doctrine\Migrations\Tools\Console\Exception\VersionAlreadyExists;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\VarDumper\VarDumper;

class ProjectsController extends AbstractController
{
    /**
     * @Route("/projects", name="projects")
     */
    public function index()
    {
        return $this->render('projects/index.html.twig');
    }

    /**
     * @Route("/get-projects", name="get_projects")
     * @param Request $request
     * @param ProjectRepository $projectRepository
     * @return Response
     */
    public function getProjects(Request $request, ProjectRepository $projectRepository)
    {
        $page = $request->get('page', 1);
        $projects = $projectRepository->findBy(['enabled' => true], ['ended_on' => 'desc'], 3, (($page - 1) * 3));

        return $this->render('projects/_projects_area.html.twig', ['projects' => $projects]);
    }
}
