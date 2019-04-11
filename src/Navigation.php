<?php

namespace Dluwang\Navigator;

use JsonSerializable;
use Illuminate\Support\Collection;

class Navigation implements JsonSerializable
{
    /**
     * @var mixed
     */
    protected $id;

    /**
     * @var string
     */
    protected $url;

    /**
     * @var integer
     */
    protected $order;

    /**
     * @var null|string
     */
    protected $parent;

    /**
     * @var array
     */
    protected $attributes;

    /**
     * @var array
     */
    protected $childs = [];

    /**
     * Create new instance.
     *
     * @param mixed     $id
     * @param string    $url
     * @param integer   $order
     * @param string    $parent
     * @param array     $attributes
     * @param array     $childs
     */
    public function __construct($id, $url, $order = 1, $parent = null, array $attributes = [], array $childs = [])
    {
        $this->id = $id;
        $this->url = $url;
        $this->order = $order;
        $this->parent = $parent;
        $this->attributes = $attributes;
        $this->childs = collect($childs);
    }

    /**
     * Register child navigation
     *
     * @param  array|Navigation  $navigation
     *
     * @return self
     */
    public function registerChild($navigation): Navigation
    {
        if((is_array($navigation)) || ($navigation instanceof Collection)) {
            foreach ($navigation as $value) {
                $this->registerChild($value);
            }
        }else {
            if(!$this->child($navigation->id)){
                $this->childs->push($navigation);
            }
        }

        return $this;
    }

    /**
     * Retrieve child by id
     *
     * @param  mixed $id
     *
     * @return Navigation | null
     */
    public function child($id): ?Navigation
    {
        return $this->childs->first(function($value, $key) use ($id){
            return $value->id == $id;
        });
    }

    /**
     * Retrieve all childs
     *
     * @return Collection
     */
    public function childs(): Collection
    {
        return $this->childs;
    }

    /**
     * Get the collection of items as JSON.
     *
     * @param  int  $options
     * @return string
     */
    public function toJson($options = 0): string
    {
        return json_encode($this->jsonSerialize(), $options);
    }

    /**
     * Serialize json
     *
     * @return array
     */
    public function jsonSerialize(): array
    {
        return [
            'id' => $this->id,
            'url' => $this->url,
            'order' => $this->order,
            'attributes' => $this->attributes,
            'childs' => $this->childs
        ];
    }

    /**
     * Convert the collection to its string representation.
     *
     * @return string
     */
    public function __toString(): string
    {
        return $this->toJson();
    }


    /**
     * Dynamically retrieve properties
     *
     * @param  mixed $prop
     *
     * @return mixed
     */
    public function __get($prop)
    {
        return $this->$prop;
    }
}
