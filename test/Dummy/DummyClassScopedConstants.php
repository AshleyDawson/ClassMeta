<?php

namespace AshleyDawson\ClassMeta\Test\Dummy;

use AshleyDawson\ClassMeta\Annotation\Meta;

class DummyClassScopedConstants
{
    /**
     * @Meta(data={"label"="Public Label"})
     */
    public const SCOPE_PUBLIC = 'public';

    /**
     * @Meta(data={"label"="Protected Label"})
     */
    protected const SCOPE_PROTECTED = 'protected';

    /**
     * @Meta(data={"label"="Private Label"})
     */
    private const SCOPE_PRIVATE = 'private';
}
