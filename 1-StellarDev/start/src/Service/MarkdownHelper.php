<?php


namespace App\Service;


use Michelf\MarkdownInterface;
use Psr\Cache\InvalidArgumentException;
use Psr\Log\LoggerInterface;
use Symfony\Component\Cache\Adapter\AdapterInterface;

class MarkdownHelper
{

    private $logger;
    private $cache;
    private $markdown;
    private $isDebug;

    public function __construct(
        AdapterInterface $cache,
        MarkdownInterface $markdown,
        $markdownLogger,
        bool $isDebug)
    {
        $this->logger = $markdownLogger;
        $this->cache = $cache;
        $this->markdown = $markdown;
        $this->isDebug = $isDebug;
    }

    public function parse(string $source): string
    {
        if (stripos($source, 'bacon') !== false ) {
            $this->logger->info('They are talking about bacon again!');
        }

        if ($this->isDebug) {
            return $this->markdown->transform($source);
        }

        try {
            $item = $this->cache->getItem('markdown_' . md5($source));
        } catch (InvalidArgumentException $e) {
            throw new \UnexpectedValueException("There is no cache");
        }
        if (!$item->isHit()) {
            $item->set($this->markdown->transform($source));
            $this->cache->save($item);
        }

        return $item->get();
    }
}