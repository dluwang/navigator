<?php

namespace Dluwang\Navigator\Laravel;

use Dluwang\Navigator\Navigator as Contract;
use Dluwang\Navigator\BaseNavigator;
use Illuminate\Contracts\Cache\Repository as Cache;

class Navigator extends BaseNavigator implements Contract
{
    /**
     * @var Illuminate\Contract\Cache\Store
     */
    protected $cache;

    /**
     * Create new instance
     *
     * @param Cache       $cache
     * @param array       $navigations
     */
    public function __construct(Cache $cache, array $navigations = [])
    {
        parent::__construct($navigations);

        $this->cache = $cache;
    }

    /**
     * Find navigation by id
     *
     * @param  mixed $id
     *
     * @return Navigation|null
     */
    public function navigation($id)
    {
        $key = md5('nav-' . $id);

        if(!$navigation = $this->cache->get($key)) {
            $navigation = parent::navigation($id);

            $this->cache->forever($key, $navigation);
        }

        return $navigation;
    }
}
