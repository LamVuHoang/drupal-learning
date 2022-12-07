<?php

namespace Drupal\hero_module\Controller;

use Drupal\Core\Controller\ControllerBase;

class HeroController extends ControllerBase
{
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
}
