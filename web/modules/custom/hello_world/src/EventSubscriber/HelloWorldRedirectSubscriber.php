<?php

namespace Drupal\hello_world\EventSubscriber;

use Drupal\Core\Session\AccountProxyInterface;
use Symfony\Component\EventDispatcher\EventSubscriberInterface;
use Symfony\Component\HttpKernel\Event\GetResponseEvent;
use Symfony\Component\HttpKernel\KernelEvents;
use Drupal\Core\Url;
use Drupal\Core\Routing\LocalRedirectResponse;
use Drupal\Core\Routing\CurrentRouteMatch;

/**
 * Subscribes to the Kernel Request event and redirects to the
homepage
 * when the user has the "non_grata" role.
 */
class HelloWorldRedirectSubscriber implements
    EventSubscriberInterface
{
    /**
     * @var \Drupal\Core\Session\AccountProxyInterface
     */
    protected $currentUser;

    /**
     * @var \Drupal\Core\Routing\CurrentRouteMatch
     */
    protected $currentRouteMatch;
    /**
     * HelloWorldRedirectSubscriber constructor.
     *
     * @param \Drupal\Core\Session\AccountProxyInterface $currentUser
     */
    public function __construct(
        AccountProxyInterface $currentUser,
        CurrentRouteMatch $currentRouteMatch
    ) {
        $this->currentUser = $currentUser;
        $this->currentRouteMatch = $currentRouteMatch;
    }
    /**
     * {@inheritdoc}
     */
    public static function getSubscribedEvents()
    {
        // We SHOULD NOT HARDCODE the kernel
        // $events['kernel.request'][] = ['onRequest', 0];

        $events[KernelEvents::REQUEST][] = ['onRequest', 0];
        return $events;
    }
    /**
     * Handler for the kernel request event.
     *
     * @param \Symfony\Component\HttpKernel\Event\GetResponseEvent $event
     */
    public function onRequest(GetResponseEvent $event)
    {
        // Should Not Hardcode link this
        // $request = $event->getRequest();
        // $path = $request->getPathInfo();
        // if ($path !== '/hello') {
        //     return;
        // }
        // $roles = $this->currentUser->getRoles();
        // if (in_array('non_grata', $roles)) {
        //     $event->setResponse(new RedirectResponse('/'));
        // }

        $route_name = $this->currentRouteMatch->getRouteName();
        if ($route_name !== 'hello_world.hello') {
            return;
        }
        $roles = $this->currentUser->getRoles();
        if (in_array('non_grata', $roles)) {
            $url = Url::fromUri('internal:/');
            $event->setResponse(new LocalRedirectResponse($url->toString()));
        }
    }
}
