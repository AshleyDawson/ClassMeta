<?php

namespace AshleyDawson\ClassMeta\Test;

use AshleyDawson\ClassMeta\ClassMetaManager;
use AshleyDawson\ClassMeta\ClassMetaManagerInterface;
use Doctrine\Common\Annotations\AnnotationReader;
use Doctrine\Common\Annotations\AnnotationRegistry;
use Doctrine\Common\Cache\FilesystemCache;

/**
 * Class AbstractTestCase
 *
 * @package AshleyDawson\ClassMeta\Test
 * @author Ashley Dawson
 */
abstract class AbstractTestCase extends \PHPUnit_Framework_TestCase
{
    /**
     * @param bool $isCached
     * @return ClassMetaManagerInterface
     */
    protected function getMetaManager($isCached = false)
    {
        AnnotationRegistry::registerLoader('class_exists');

        $reader = new AnnotationReader();
        $manager = new ClassMetaManager($reader);

        if ($isCached) {
            $manager->setCache(new FilesystemCache(__DIR__.'/../var/cache'));   
        }
        
        return $manager;
    }
}
