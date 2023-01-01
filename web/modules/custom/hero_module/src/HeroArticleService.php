<?php

namespace Drupal\hero_module;

use Drupal\Core\StringTranslation\StringTranslationTrait;
use Drupal\hero_module\Repository\HeroRepository;
use Drupal\Core\Config\ConfigFactoryInterface;

// This service should have an Interface,
//  It will be the best practice
class HeroArticleService
{
    use StringTranslationTrait;

    /**
     * @var \Drupal\Core\Config\ConfigFactoryInterface
     */
    protected $configFactory;

    /**
     * * HelloWorldSalutation constructor.
     *
     * @param \Drupal\Core\Config\ConfigFactoryInterface $config_factory
     */
    public function __construct(ConfigFactoryInterface $config_factory)
    {
        $this->configFactory = $config_factory;
    }

    public function clock()
    {
        $config = $this->configFactory->get('hello_world.custom_salutation');
        $salutation = $config->get('salutation');
        if ($salutation !== "" && $salutation) {
            return $salutation;
        }

        $time = new \DateTime();
        $now = (int) $time->format('G');
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

    public function testRepo()
    {
        $repo = new HeroRepository;
        return $repo->testRepo();

        // return $this->_heroRepository->testRepo();
    }
}
