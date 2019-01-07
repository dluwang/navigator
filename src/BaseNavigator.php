<?php

namespace Nagasari\Navigator;

use Illuminate\Support\Collection;

class BaseNavigator implements Navigator
{
    /**
     * @var array
     */
    protected $navigations;

    /**
     * Create new instance
     *
     * @param array $navigations
     */
    public function __construct(array $navigations = [])
    {
        $this->navigations = collect($navigations);
    }

    /**
     * Register navigation
     *
     * @param  array|Navigation     $navigation
     * @return self
     */
    public function register($navigation)
    {
        if((is_array($navigation)) || ($navigation instanceof Collection)) {
            foreach ($navigation as $value) {
                $this->register($value);
            }
        }else {
            if(!$this->navigation($navigation->id)) {
                $this->navigations->push($navigation);
            }
        }

        return $this;
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
        $navigation = $this->navigations->first(function($value) use ($id){
            return $value->id == $id;
        });

        $parent = (($navigation) && ($navigation->parent)) ? $this->navigation($navigation->parent) : null;

        return $navigation ? $this->build($navigation, $parent) : null;
    }

    /**
     * Retrieve builded navigation
     *
     * @param  mixed $parent
     *
     * @return Collection
     */
    public function navigations($parent = null)
    {
        $navigations = collect([]);
        $raw = $this->raw($parent);

        foreach ($raw as $navigation) {
            if($single = $this->navigation($navigation->id)) {
                $navigations->push($single);
            }
        }

        return $navigations->sortBy->order->values();
    }

    /**
     * Retrieve all raw navigations.
     *
     * @return Collection
     */
    public function raw($parent = null)
    {
        return $this->navigations->filter(function($value) use ($parent){
            return $value->parent == $parent;
        });
    }

    /**
     * Buld navigation.
     *
     * @param Navigation    $navigation
     * @param Navigation    $parent
     *
     * @return Navigation
     */
    protected function build(Navigation $navigation, Navigation $parent = null)
    {
        $accruedChilds = $navigation->childs;
        $deferredChilds = $this->raw($navigation->id);

        $childs = $accruedChilds->merge($deferredChilds)->sortBy->order->values();

        $navigation = new Navigation(
            $navigation->id,
            $navigation->url,
            $navigation->order,
            $parent,
            $navigation->attributes,
            []
        );

        foreach ($childs as $child) {
            $navigation->registerChild($this->build($child, $navigation));
        }

        return $navigation;
    }
}
