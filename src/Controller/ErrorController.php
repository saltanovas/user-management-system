<?php

namespace App\Controller;

use Throwable;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpKernel\Log\DebugLoggerInterface;

class ErrorController extends AbstractController
{
    public function index(Throwable $exception, DebugLoggerInterface $logger = null)
    {
        return $this->render('shared/error.html.twig');
    }
}