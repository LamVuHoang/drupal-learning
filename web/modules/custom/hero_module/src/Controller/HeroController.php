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
     *   A simple renderable array.
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

        $heroList = '<ol>';

        foreach ($superHeroes as $hero) {
            $heroList .= '<li>' . $hero['name'] . '</li>';
        }

        $heroList .= '</ol>';

        return [
            '#markup' => $heroList
        ];
    }

    public function welcome()
    {
        return [
            '#markup' => $this->_heroArticleService->getSalutation()
        ];
    }
}
