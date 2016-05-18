<?php

namespace AshleyDawson\ClassMeta\Test\Dummy;

use AshleyDawson\ClassMeta\Test\Annotation\MyMeta;

class DummyStatusWithCustomAnnotExtended extends DummyStatusWithCustomAnnot
{
    /**
     * @MyMeta(foo="Baz thingy")
     */
    const BAZ = 'baz';
}
