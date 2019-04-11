<?php

namespace Dluwang\Navigator;

use Illuminate\Support\Collection;

interface Navigator
{
    /**
     * Register navigation
     *
     * @param  array|Navigation     $navigation
     * 
     * @return self
     */
    public function register($navigation): Navigator;

    /**
     * Find navigation by id
     *
     * @param  mixed $id
     *
     * @return Navigation|null
     */
    public function navigation($id): ?Navigation;

    /**
     * Retrieve builded navigation
     *
     * @param  mixed $parent
     *
     * @return Collection
     */
    public function navigations($parent = null): Collection;

    /**
     * Retrieve all raw navigations.
     *
     * @return Collection
     */
    public function raw($parent = null): Collection;
}
