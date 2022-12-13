<?php

namespace Drupal\hero_module;

use Drupal\hero_module\Repository\HeroRepository;

class HeroArticleService
{
    public function getSalutation()
    {
        $time = new \DateTime();
        $now = (int) $time->format('G');
        if ($now >= 00 && $now < 12) {
            return 'Good morning world';
        }
        if ($now >= 12 && $now < 18) {
            return 'Good afternoon world';
        }
        if ($now >= 18) {
            return 'Good evening world';
        }
    }

    public function testRepo()
    {
        $repo = new HeroRepository;
        return $repo->testRepo();

        // return $this->_heroRepository->testRepo();
    }
}
