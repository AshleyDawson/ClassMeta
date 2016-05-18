Class Meta
==========

Apply metadata to classes and their constants via annotation. Handy if you need to attach arbitrary data to 
lookups, enumerations, etc.

[![Build Status](https://travis-ci.org/AshleyDawson/ClassMeta.svg?branch=master)](https://travis-ci.org/AshleyDawson/ClassMeta)

Installation
------------

Install ClassMeta via Composer using the following command:

```
$ composer require ashleydawson/class-meta
```

Basic Usage
-----------

Apply metadata annotation to classes and constants:

```php
<?php

namespace Acme\Enum;

use AshleyDawson\ClassMeta\Annotation\Meta;

/**
 * @Meta(data={"name"="Invoice Status Types"})
 */
class InvoiceStatus
{
    /**
     * @Meta(data={"name"="Draft", "description"="Invoice has not yet been sent to the customer"})
     */
    const DRAFT = 'draft';
    
    /**
     * @Meta(data={"name"="Sent", "description"="Invoice has been sent to the customer"})
     */
    const SENT = 'sent';
    
    /**
     * @Meta(data={"name"="Paid", "description"="Invoice has been paid by the customer"})
     */
    const PAID = 'paid';
    
    /**
     * @Meta(data={"name"="Void", "description"="Invoice is void and no longer billable"})
     */
    const VOID = 'void';
}
```

You can now access the metadata using the class meta manager:

```php
use AshleyDawson\ClassMeta\ClassMetaManager;
use AshleyDawson\ClassMeta\Annotation\Meta;

$manager = new ClassMetaManager();

$classMeta = $manager->getClassMeta('Acme\Enum\InvoiceStatus');

// "Invoice Status Types" will be echoed
echo $classMeta->data['name'];

$constantsMeta = $manager->getClassConstantsMeta('Acme\Enum\InvoiceStatus');

// Echo all constant metadata
foreach ($constantsMeta as $meta) {
    echo $meta->data['name'] . PHP_EOL;
    echo $meta->data['description'] . PHP_EOL;
}

// Get individual meta by value (i.e. the value of the constant)
$meta = $manager->getClassConstantMetaByValue('Acme\Enum\InvoiceStatus', InvoiceStatus::PAID);

// "Paid" will be echoed
echo $meta->data['name'];

// Map the collection of constant meta for use in a select drop-down menu, for example:
$options = $manager->getMappedClassConstantsMeta('Acme\Enum\InvoiceStatus', function (Meta $meta) {
    return [
        $meta->value, 
        $meta->data['name'],
    ];
});

echo '<select>';
foreach ($options as $value => $name) {
    echo "<option value="{$value}">$name</option>";
}
echo '</select>';
```

Grouped Metadata
----------------

Pass optional arbitrary groups to help organise your metadata:

```php
<?php

namespace Acme\Enum;

use AshleyDawson\ClassMeta\Annotation\Meta;

/**
 * @Meta(data={"name"="Invoice Status Types"})
 */
class InvoiceStatus
{
    /**
     * @Meta(data={"name"="Draft", "description"="Invoice has not yet been sent to the customer"}, groups={"admin"})
     */
    const DRAFT = 'draft';
    
    /**
     * @Meta(data={"name"="Sent", "description"="Invoice has been sent to the customer"}, groups={"admin"})
     */
    const SENT = 'sent';
    
    /**
     * @Meta(data={"name"="Paid", "description"="Invoice has been paid by the customer"})
     */
    const PAID = 'paid';
    
    /**
     * @Meta(data={"name"="Void", "description"="Invoice is void and no longer billable"}, groups={"admin"})
     */
    const VOID = 'void';
}
```

You can now access groups of metadata like so:

```php
use AshleyDawson\ClassMeta\ClassMetaManager;

$manager = new ClassMetaManager();

$constantsMeta = $manager->getClassConstantsMeta('Acme\Enum\InvoiceStatus', ['admin']);

// Echo only constant metadata in "admin" group
foreach ($constantsMeta as $meta) {
    echo $meta->data['name'] . PHP_EOL;
    echo $meta->data['description'] . PHP_EOL;
}

$constantsMeta = $manager->getClassConstantsMeta('Acme\Enum\InvoiceStatus', ['Default']);

// Echo only constant metadata in "Default" group (i.e. const PAID = 'paid' metadata)
foreach ($constantsMeta as $meta) {
    echo $meta->data['name'] . PHP_EOL;
    echo $meta->data['description'] . PHP_EOL;
}

$constantsMeta = $manager->getClassConstantsMeta('Acme\Enum\InvoiceStatus', ['Default', 'admin']);

// Echo all constant metadata
foreach ($constantsMeta as $meta) {
    echo $meta->data['name'] . PHP_EOL;
    echo $meta->data['description'] . PHP_EOL;
}
```

*Note:* The "Default" group will contain metadata that is not assigned a group

Cache
-----

All metadata can be cached by simply passing a valid Doctrine cache provider to the class meta manager:

```php
use Doctrine\Common\Cache\FilesystemCache;

$manager = new ClassMetaManager();
$manager->setCache(new FilesystemCache('/path/to/cache/dir'));
```

Cache is invalidated using the class modify time, but you can also pass an optional TTL in seconds to the `ClassMetaManager#setCache()` method:

```php
use Doctrine\Common\Cache\FilesystemCache;

$manager = new ClassMetaManager();
$manager->setCache(new FilesystemCache('/path/to/cache/dir'), 300); // Cache stale after 5 minutes
```

Tests
-----

To run the ClassMeta test suite, install Composer dev dependencies and run:

```
$ bin/phpunit
```