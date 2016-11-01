<?php

namespace AshleyDawson\ClassMeta\Test\Dummy;

use AshleyDawson\ClassMeta\Annotation\Meta;

/**
 * A lovely class description
 *
 * @author Ashley Dawson
 * @Meta(data={"name"="Invoice"})
 */
class DummyInvoice
{
    /**
     * A lovely constant description
     *
     * @Meta(data={"name"="Draft", "description"="Invoice is new and not yet sent to customer"})
     */
    const DRAFT = 'draft';

    /**
     * @deprecated
     *
     * @Meta(data={"name"="Sent", "description"="Invoice has been sent to the customer"})
     */
    const SENT = 'sent';

    /**
     * @Meta(data={
     *     "name"="Paid",
     *     "description"="Invoice has been paid"
     * }, groups={"customer"})
     */
    const PAID = 'paid';
}
