<?php

namespace App\Twig;

use Twig\Extension\AbstractExtension;
use Twig\TwigFilter;

class AppExtension extends AbstractExtension
{
    public function getFilters()
    {
        return array(
            new TwigFilter('sortbyfield', array($this, 'sortByFieldFilter')),
        );
    }

    public function sortByFieldFilter($content)
    {
        sort($content);

        return $content;
    }
}