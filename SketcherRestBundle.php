<?php

namespace Sketcher\Bundle\RestBundle;

use Doctrine\Common\EventManager;
use Symfony\Component\HttpKernel\Bundle\Bundle;

class SketcherRestBundle extends Bundle
{


//    /**
//     * SketcherRestBundle constructor.
//     */
//    public function __construct()
//    {
//        $this->em = new EventManager();
//    }
//
//    public function getEntityNamespace($entityName)
//    {
//        $metas = $this->em->getMetadataFactory()->getAllMetadata();
//        foreach ($metas as $meta) {
//            $namespace = $meta->getName();
//            $name = substr($namespace, strrpos($namespace, '\\') + 1);
//            if (strtolower($entityName) == strtolower($name)) {
//                return $namespace;
//            }
//        }
//        return null;
//    }
}
