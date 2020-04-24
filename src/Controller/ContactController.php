<?php

namespace App\Controller;

use Nexy\Slack\Client;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;

class ContactController extends AbstractController
{
    /**
     * @Route("/contact", name="contact")
     * @param Request $request
     * @param Client $slack
     * @return \Symfony\Component\HttpFoundation\Response
     * @throws \Http\Client\Exception
     */
    public function index(Request $request, Client $slack)
    {
        if($request->getMethod() == Request::METHOD_POST) {
            $message = $slack->createMessage();

            $message
                ->to('#contact-site')
                ->setText($request->get('contact-message'))
            ;

            $slack->sendMessage($message);

            return new JsonResponse(['status' => true]);
        }

        return $this->render('contact/index.html.twig');
    }
}
