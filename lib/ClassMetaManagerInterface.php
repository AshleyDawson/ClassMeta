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

    /**
     * Get collection constant metadata using a mapper:
     *
     * <code>
     * // Map the constant meta for use in a select menu, for example
     * $choices = $manager->getMappedClassConstantsMeta('Acme\Invoice\InvoiceStatus', function (Meta $meta) {
     *     // First value in return array is the key and the second is the value
     *     return [$meta->value, $meta->data['name']];
     * });
     *
     * // Produces a list something like:
     * Array (
     *     draft => 'Draft',
     *     sent => 'Sent',
     *     paid => 'Paid',
     *     void => 'Void'
     * )
     * <code>
     *
     * @param string $class
     * @param \Closure $mapper
     * @param array|null $groups
     * @return array
     */
    public function getMappedClassConstantsMeta($class, \Closure $mapper, array $groups = null);
}
