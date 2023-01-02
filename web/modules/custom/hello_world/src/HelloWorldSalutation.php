<?php

namespace Drupal\hello_world;

use Drupal\Core\StringTranslation\StringTranslationTrait;
use Drupal\Core\Config\ConfigFactoryInterface;
use Symfony\Component\EventDispatcher\EventDispatcherInterface;

/**
 * Prepares the salutation to the world.
 */
class HelloWorldSalutation
{
    use StringTranslationTrait;

    /**
     * @var \Drupal\Core\Config\ConfigFactoryInterface
     */
    protected $configFactory;

    /**
     * @var \Symfony\Component\EventDispatcher\EventDispatcherInterface
     */
    protected $eventDispatcher;
    /**
     * HelloWorldSalutation constructor.
     *
     * @param \Drupal\Core\Config\ConfigFatoryInterface $config_factory
     * @param \Symfony\Component\EventDispatcher\EventDispatcherInterface $eventDispatcher
     */
    public function __construct(
        ConfigFactoryInterface $config_factory,
        EventDispatcherInterface $eventDispatcher
    ) {
        $this->configFactory = $config_factory;
        $this->eventDispatcher = $eventDispatcher;
    }
    /**
     * * Returns the salutation
     */
    public function getSalutation()
    {
        $config = $this->configFactory->get('hello_world.custom_salutation');
        $salutation = $config->get('salutation');


        // if ($salutation !== "" && $salutation) {
        //     return $salutation;
        // }
        if ($salutation !== "" && $salutation) {
            $event = new SalutationEvent();
            $event->setValue($salutation);
            $event = $this->eventDispatcher
                ->dispatch(SalutationEvent::EVENT, $event);
            return $event->getValue();
        }

        $now = $this->timeNow();

        if ($now >= 00 && $now < 12) {
            return $this->t('Good morning world');
        }
        if ($now >= 12 && $now < 18) {
            return $this->t('Good afternoon world');
        }
        if ($now >= 18) {
            return $this->t('Good evening world');
        }
    }

    /**
     * Returns the Salutation render array.
     */
    public function getSalutationComponent()
    {
        $render = [
            '#theme' => 'hello_world_salutation',
        ];
        $config = $this->configFactory->get('hello_world.custom_salutation');
        $salutation = $config->get('salutation');

        if ($salutation !== "" && $salutation) {
            $event = new SalutationEvent();
            $event->setValue($salutation);
            $this->eventDispatcher->dispatch(SalutationEvent::EVENT, $event);
            $render['#salutation'] = $event->getValue();
            $render['#overridden'] = TRUE;
            return $render;
        }

        $now = $this->timeNow();
        $render['#target'] = $this->t('world');
        $render['#attached'] = [
            'library' => [
                'hello_world/hello_world_clock'
            ]
        ];

        if ($now >= 00 && $now < 12) {
            $render['#salutation']['#markup'] = $this->t('Good morning');
            $render['#attached']['drupalSettings']['hello_world']['hello_world_clock']['morning'] = TRUE;
        }
        if ($now >= 12 && $now < 18) {
            $render['#salutation']['#markup'] = $this->t('Good afternoon');
            $render['#attached']['drupalSettings']['hello_world']['hello_world_clock']['afternoon'] = TRUE;
        }
        if ($now >= 18) {
            $render['#salutation']['#markup'] = $this->t('Good evening');
            $render['#attached']['drupalSettings']['hello_world']['hello_world_clock']['evening'] = TRUE;
        }

        return $render;
    }

    private function timeNow()
    {
        $time = new \DateTime();
        return ((int) $time->format('G'));
    }
}
