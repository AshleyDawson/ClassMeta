<?php

namespace AshleyDawson\ClassMeta\Annotation;

use AshleyDawson\ClassMeta\ClassMetaManager;

/**
 * Class Meta
 *
 * @package AshleyDawson\ClassMeta\Annotation
 * @author Ashley Dawson
 *
 * @Annotation
 */
class Meta
{
    /**
     * @var string
     */
    public $property;

    /**
     * @var mixed
     */
    public $value;

    /**
     * @var mixed
     */
    public $data;

    /**
     * @var array
     */
    public $groups = [ClassMetaManager::DEFAULT_GROUP_NAME];
}
