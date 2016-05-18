<?php

namespace AshleyDawson\ClassMeta\Test\Dummy;

use AshleyDawson\ClassMeta\Test\Annotation\MyMeta;

/**
 * @MyMeta(foo="Class meta here")
 */
class DummyStatusWithCustomAnnot
{
    /**
     * @MyMeta(foo="Foo thingy")
     */
    const FOO = 'foo';

    /**
     * @MyMeta(foo="Bar thingy")
     */
    const BAR = 'bar';
}
