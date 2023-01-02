<?php

namespace Drupal\hello_world\Controller;

use Drupal\Core\Controller\ControllerBase;
use Symfony\Component\DependencyInjection\ContainerInterface;
use Drupal\hello_world\HelloWorldSalutation;
use Drupal\Core\Ajax\AjaxResponse;
use Drupal\Core\Ajax\RemoveCommand;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;

/**
 * Controller for the salutation message.
 */
class HelloWorldController extends ControllerBase
{
    /**
     * @var \Drupal\hello_world\HelloWorldSalutation
     */
    protected $salutation;
    /**
     * HelloWorldController constructor.
     *
     * @param \Drupal\hello_world\HelloWorldSalutation $salutation
     */
    public function __construct(HelloWorldSalutation $salutation)
    {
        $this->salutation = $salutation;
    }
    /**
     * {@inheritdoc}
     */
    public static function create(ContainerInterface $container)
    {
        return new static(
            $container->get('hello_world.salutation')
        );
    }
    /**
     * Hello World.
     *
     * @return array
     * Our message.
     */
    public function helloWorld()
    {
        return $this->salutation->getSalutationComponent();
    }

    /**
     * Route callback for hiding the Salutation block.
     * Only works for Ajax calls.
     *
     * @param \Symfony\Component\HttpFoundation\Request $request
     *
     * @return \Drupal\Core\Ajax\AjaxResponse
     */
    public function hideBlock(Request $request)
    {
        if (!$request->isXmlHttpRequest()) {
            throw new NotFoundHttpException();
        }
        $response = new AjaxResponse();
        $command = new RemoveCommand('.block-hello-world');
        $response->addCommand($command);
        return $response;
    }
}
