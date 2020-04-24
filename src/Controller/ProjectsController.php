<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Routing\Annotation\Route;

class ProjectsController extends AbstractController
{
    /**
     * @Route("/projects", name="projects")
     */
    public function index()
    {
        $em = $this->getDoctrine()->getManager();
        $projects = $em->getRepository('App:Project')->findBy(['enabled' => true], ['ended_on' => 'desc']);

        return $this->render('projects/index.html.twig', ['projects' => $projects]);
    }
}
