<?php

namespace Drupal\hero_module;

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
}
