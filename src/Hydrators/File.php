<?php

namespace TapestryCloud\Database\Hydrators;

use Tapestry\Modules\Content\FrontMatter as TapestryFrontMatter;
use TapestryCloud\Database\Entities\Environment;
use TapestryCloud\Database\Entities\FrontMatter;
use TapestryCloud\Database\Entities\File as Model;


class File extends Hydrator
{
    /**
     * File Hydration.
     *
     * @param Model $model
     * @param \Tapestry\Entities\File $file
     * @param \TapestryCloud\Database\Entities\ContentType $contentType
     * @param null|Environment $environment
     */
    public function hydrate(Model $model, \Tapestry\Entities\File $file, \TapestryCloud\Database\Entities\ContentType $contentType = null, Environment $environment = null)
    {
        $model->setUid($file->getUid());
        $model->setLastModified($file->getLastModified());
        $model->setFilename($file->getFilename());
        $model->setExt($file->getExt());
        $model->setPath($file->getPath());
        $model->setToCopy($file->isToCopy());

        if (!$file->isToCopy()) {
            $frontMatter = new TapestryFrontMatter($file->getFileContent());
            $model->setContent($frontMatter->getContent());
            foreach ($frontMatter->getData() as $key => $value) {
                $fmRecord = new FrontMatter();
                $fmRecord->setName($key);
                $fmRecord->setValue(json_encode($value));
                $model->addFrontMatter($fmRecord);

                $this->entityManager->persist($fmRecord);
            }
        }

        if (!is_null($contentType)) {
            $model->setContentType($contentType);
        }

        if (!is_null($environment)) {
            $model->setEnvironment($environment);
        }
    }

}