<?php

namespace AshleyDawson\ClassMeta;

use AshleyDawson\ClassMeta\Annotation\Meta;

/**
 * Interface ClassMetaManagerInterface
 *
 * @package AshleyDawson\ClassMeta
 * @author Ashley Dawson
 */
interface ClassMetaManagerInterface
{
    /**
     * Get meta for a given class/object
     *
     * @param string|object $class
     * @param array|null $groups
     * @return Meta|null
     */
    public function getClassMeta($class, array $groups = null);

    /**
     * Get class constants meta
     *
     * @param string|object $class
     * @param array|null $groups
     * @return Meta[]
     */
    public function getClassConstantsMeta($class, array $groups = null);

    /**
     * Get class constant meta by constant value
     *
     * @param string|object $class
     * @param mixed $value
     * @param array|null $groups
     * @return Meta
     */
    public function getClassConstantMetaByValue($class, $value, array $groups = null);
}
