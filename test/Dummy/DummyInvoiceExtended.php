<?php

namespace AshleyDawson\ClassMeta\Test\Dummy;

use AshleyDawson\ClassMeta\Annotation\Meta;

class DummyInvoiceExtended extends DummyInvoice
{
    /**
     * @Meta(data={"name"="Base", "description"="Testing the base class constant meta"})
     */
    const BASE = 'base';
}
