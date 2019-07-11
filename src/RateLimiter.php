<?php

namespace App;

use Symfony\Component\Cache\Adapter\AdapterInterface;

class RateLimiter {

    /**
     * @var AdapterInterface
     */
    private $cache;

    public function __construct(AdapterInterface $cache)
    {
        $this->cache = $cache;
    }

    public function set($type, $user)
    {
        $item = $this->cache->getItem($this->getCacheKey($type, $user));
        $item->set('1');
        $item->expiresAfter(new \DateInterval('PT15S'));
        $this->cache->save($item);
    }

    public function isLimited($type, $user)
    {
        $item = $this->cache->getItem($this->getCacheKey($type, $user));

        return $item->isHit();
    }

    private function getCacheKey($type, $user)
    {
        return sprintf($type.'.%s', $user->getName());
    }
}