<?php

namespace App\Controller;

use SunCat\MobileDetectBundle\DeviceDetector\MobileDetector;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\VarDumper\VarDumper;

class HomepageController extends AbstractController
{
    /**
     * @Route("/", name="homepage")
     */
    public function index(MobileDetector $mobileDetector)
    {
        $embedOptions = ['autoplay' => 1, 'title' => 0, 'byline' => 0, 'portrait' => 0, 'background' => 1, 'loop' => 1];
        if($mobileDetector->isMobile()) {
            $embedOptions['background'] = 0;
        }

        $embedOptionsUrl = http_build_query($embedOptions);

        return $this->render('homepage/index.html.twig', ['embed_options_url' => $embedOptionsUrl]);
    }
}
