<?php

namespace TapestryCloud\Database\Hydrators;

use TapestryCloud\Database\Entities\Taxonomy as Model;

class Taxonomy extends Hydrator
{
    /**
     * Taxonomy Hydration.
     *
     * @param Model $model
     * @param \Tapestry\Entities\Taxonomy $taxonomy
     */
    public function hydrate(Model $model, \Tapestry\Entities\Taxonomy $taxonomy)
    {
        $model->setName($taxonomy->getName());
    }
}