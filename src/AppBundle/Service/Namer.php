<?php

namespace AppBundle\Service;

use Vich\UploaderBundle\Naming\NamerInterface;
use Symfony\Component\VarDumper\VarDumper;

class Namer implements NamerInterface
{

    /**
     * Creates a name for the file being uploaded.
     *
     * @param object $object The object the upload is attached to.
     * @param \Vich\UploaderBundle\Mapping\PropertyMapping $mapping The mapping to use to manipulate the given object.
     *
     * @return string The file name.
     */
    public function name($object, \Vich\UploaderBundle\Mapping\PropertyMapping $mapping)
    {
        return $object->getName();
    }
}