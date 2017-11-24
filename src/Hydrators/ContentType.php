<?php

namespace TapestryCloud\Database\Hydrators;

use TapestryCloud\Database\Entities\Environment;
use TapestryCloud\Database\Entities\ContentType as Model;

class ContentType extends Hydrator
{
    /**
     * ContentType Hydration.
     *
     * @param Model $model
     * @param \Tapestry\Entities\ContentType $contentType
     * @param Environment|null $environment
     */
    public function hydrate(Model $model, \Tapestry\Entities\ContentType $contentType, Environment $environment = null)
    {
        $model->setName($contentType->getName());
        $model->setPath($contentType->getPath());
        $model->setTemplate($contentType->getTemplate());
        $model->setPermalink($contentType->getPermalink());
        $model->setEnabled($contentType->isEnabled());

        if (!is_null($environment)) {
            $model->setEnvironment($environment);
        }
    }
}