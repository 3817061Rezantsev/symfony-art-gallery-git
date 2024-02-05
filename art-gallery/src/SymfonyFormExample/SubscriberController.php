<?php

namespace Sergo\ArtGallery\SymfonyFormExample;

use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface;
use Sergo\ArtGallery\Forms\SubscriberFormType;
use Sergo\ArtGallery\Infrastructure\Controller\ControllerInterface;
use Sergo\ArtGallery\Infrastructure\Http\ServerResponseFactory;
use Sergo\ArtGallery\Infrastructure\SymfonyFormFactory\SymfonyFormFactory;
use Sergo\ArtGallery\Infrastructure\ViewTemplate\ViewTemplateInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Form\FormFactory;
use Symfony\Component\Form\FormRegistry;
use Symfony\Component\Form\ResolvedFormTypeFactory;
use Twig\Error\LoaderError;
use Twig\Error\RuntimeError;
use Twig\Error\SyntaxError;

class SubscriberController extends AbstractController implements ControllerInterface
{
    private ServerResponseFactory $serverResponseFactory;
    private ViewTemplateInterface $viewTemplate;

    /**
     * @param ServerResponseFactory $serverResponseFactory
     * @param ViewTemplateInterface $viewTemplate
     */
    public function __construct(
        ServerResponseFactory $serverResponseFactory,
        ViewTemplateInterface $viewTemplate
    ) {
        $this->serverResponseFactory = $serverResponseFactory;
        $this->viewTemplate = $viewTemplate;
    }

    public function __invoke(ServerRequestInterface $serverRequest): ResponseInterface
    {
        $subscriber = new Subscriber();
        $factory = new FormFactory(new FormRegistry([], new ResolvedFormTypeFactory()));
        $form = $factory->create(SubscriberFormType::class, $subscriber);
        $html = $this->viewTemplate->render('subscriber/show.html.twig', [
            'form' => $form->createView()
        ]);
        return $this->serverResponseFactory->createHtmlResponse(200, $html);
    }
}
