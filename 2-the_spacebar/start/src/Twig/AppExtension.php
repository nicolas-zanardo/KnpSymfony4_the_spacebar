<?php

namespace App\Twig;

use App\Service\MarkdownHelper;
use Psr\Container\ContainerInterface;
use Symfony\Contracts\Service\ServiceSubscriberInterface;
use Twig\Extension\AbstractExtension;
use Twig\TwigFilter;
use Twig\TwigFunction;

class AppExtension extends AbstractExtension implements ServiceSubscriberInterface
{
    /**
     * @var ContainerInterface
     */
    private $container;

    public function __construct(ContainerInterface $container){
        $this->container = $container;
    }

    public function getFilters(): array
    {
        return [
            // If your filter generates SAFE HTML, you should add a third
            // parameter: ['is_safe' => ['html']]
            // Reference: https://twig.symfony.com/doc/2.x/advanced.html#automatic-escaping
            new TwigFilter('cached_markdown', [$this, 'processMarkdown'], ['is_safe' => ['html']]),
        ];
    }


    /**
     * @param $value
     * @return string
     */
    public function processMarkdown($value): string
    {
        return $this->container
                    ->get('foo')
                    ->parse($value);
    }

    public static function getSubscribedServices(): array
    {
        return [
            'foo' => MarkdownHelper::class
        ];
    }
}
