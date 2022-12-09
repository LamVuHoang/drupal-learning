<?php

namespace Drupal\hero_module\Controller;

use Drupal\Core\Controller\ControllerBase;
use Drupal\hero_module\HeroArticleService;
use Symfony\Component\DependencyInjection\ContainerInterface;

class HeroController extends ControllerBase
{
    private $_heroArticleService;

    public function __construct(HeroArticleService $heroArticleService)
    {
        $this->_heroArticleService = $heroArticleService;
    }

    public static function create(ContainerInterface $container)
    {
        return new static(
            $container->get('hero_module.getSalutation')
        );
    }

    /**
     * Returns a simple page.
     *
     * @return array
     */
    public function heroList()
    {
        $superHeroes = [
            ['name' => 'Spider-Man'],
            ['name' => 'Wonder Woman'],
            ['name' => 'Captain America'],
            ['name' => 'Wolverine'],
            ['name' => 'Green Lantern']
        ];

        return [
            '#theme' => 'hero_list',
            '#items' => $superHeroes,
            '#title' => $this->t('Behold Our wonderful Superheroes'),
            '#attached' => [
                'library' => [
                    'hero_module/hero-module-library',
                ],
            ],
        ];
    }

    /**
     * Returns a simple page.
     * @param string
     * 
     * @return array
     */
    public function printName(string $param)
    {
        return [
            '#markup' => $this->t('Params: ' . $param)
        ];
    }

    /**
     * Returns a simple page.
     * @param string param1
     * @param string param2
     * 
     * @return array
     */
    public function doubleName(string $param1, string $param2)
    {
        return [
            '#markup' => $this->t('Params 1: ' . $param1 . '<br>Param 2: ' . $param2)
        ];
    }

    /**
     * Returns a simple page.
     * 
     * @return array
     */
    public function welcome()
    {
        return [
            '#markup' => $this->_heroArticleService->getSalutation()
        ];
    }
}
