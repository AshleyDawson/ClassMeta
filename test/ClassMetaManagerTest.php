<?php

namespace AshleyDawson\ClassMeta\Test;

use AshleyDawson\ClassMeta\Annotation\Meta;

/**
 * Class ClassMetaManagerTest
 *
 * @package AshleyDawson\ClassMeta\Test
 * @author Ashley Dawson
 */
class ClassMetaManagerTest extends AbstractTestCase
{
    const META_ANNOTATION_CLASS = 'AshleyDawson\ClassMeta\Annotation\Meta';

    const CUSTOM_META_ANNOTATION_CLASS = 'AshleyDawson\ClassMeta\Test\Annotation\MyMeta';

    public function testGetClassMeta()
    {
        $meta = $this
            ->getMetaManager()
            ->getClassMeta('AshleyDawson\ClassMeta\Test\Dummy\DummyInvoice');

        $this->assertInstanceOf(self::META_ANNOTATION_CLASS, $meta);

        $this->assertEquals('Invoice', $meta->data['name']);
    }

    public function testGetClassMetaCached()
    {
        $meta = $this
            ->getMetaManager(true)
            ->getClassMeta('AshleyDawson\ClassMeta\Test\Dummy\DummyInvoice');

        $this->assertInstanceOf(self::META_ANNOTATION_CLASS, $meta);

        $this->assertEquals('Invoice', $meta->data['name']);

        $meta = $this
            ->getMetaManager(true)
            ->getClassMeta('AshleyDawson\ClassMeta\Test\Dummy\DummyInvoice');

        $this->assertInstanceOf(self::META_ANNOTATION_CLASS, $meta);

        $this->assertEquals('Invoice', $meta->data['name']);
    }

    public function testGetClassMetaOnClassWithoutMeta()
    {
        $meta = $this
            ->getMetaManager()
            ->getClassMeta('AshleyDawson\ClassMeta\Test\Dummy\DummyClass');

        $this->assertNull($meta);
    }

    public function testGetClassConstantsMeta()
    {
        $metas = $this
            ->getMetaManager()
            ->getClassConstantsMeta('AshleyDawson\ClassMeta\Test\Dummy\DummyInvoice');

        $this->assertInternalType('array', $metas);

        $this->assertInstanceOf(self::META_ANNOTATION_CLASS, $metas['DRAFT']);
        $this->assertEquals('Draft', $metas['DRAFT']->data['name']);
        $this->assertEquals('Invoice is new and not yet sent to customer', $metas['DRAFT']->data['description']);

        $this->assertInstanceOf(self::META_ANNOTATION_CLASS, $metas['SENT']);
        $this->assertEquals('Sent', $metas['SENT']->data['name']);
        $this->assertEquals('Invoice has been sent to the customer', $metas['SENT']->data['description']);
    }

    public function testGetClassConstantsMetaCached()
    {
        $metas = $this
            ->getMetaManager(true)
            ->getClassConstantsMeta('AshleyDawson\ClassMeta\Test\Dummy\DummyInvoice');

        $this->assertInternalType('array', $metas);

        $this->assertInstanceOf(self::META_ANNOTATION_CLASS, $metas['DRAFT']);
        $this->assertEquals('Draft', $metas['DRAFT']->data['name']);
        $this->assertEquals('Invoice is new and not yet sent to customer', $metas['DRAFT']->data['description']);

        $this->assertInstanceOf(self::META_ANNOTATION_CLASS, $metas['SENT']);
        $this->assertEquals('Sent', $metas['SENT']->data['name']);
        $this->assertEquals('Invoice has been sent to the customer', $metas['SENT']->data['description']);
    }

    public function testGetClassConstantsMetaWithoutMeta()
    {
        $metas = $this
            ->getMetaManager()
            ->getClassConstantsMeta('AshleyDawson\ClassMeta\Test\Dummy\DummyClass');

        $this->assertInternalType('array', $metas);

        $this->assertEmpty($metas);
    }

    public function testGetClassConstantsMetaByGroup()
    {
        $metas = $this
            ->getMetaManager()
            ->getClassConstantsMeta('AshleyDawson\ClassMeta\Test\Dummy\DummyInvoice', ['customer']);

        $this->assertInternalType('array', $metas);
        
        $this->assertInstanceOf(self::META_ANNOTATION_CLASS, $metas['PAID']);
        $this->assertEquals('Paid', $metas['PAID']->data['name']);
        $this->assertEquals('Invoice has been paid', $metas['PAID']->data['description']);

        $this->assertFalse(isset($metas['DRAFT']));
    }

    public function testGetClassConstantsMetaByGroupIncludingDefaultGroup()
    {
        $metas = $this
            ->getMetaManager()
            ->getClassConstantsMeta('AshleyDawson\ClassMeta\Test\Dummy\DummyInvoice', ['customer', 'Default']);

        $this->assertInternalType('array', $metas);

        $this->assertInstanceOf(self::META_ANNOTATION_CLASS, $metas['PAID']);
        $this->assertEquals('Paid', $metas['PAID']->data['name']);
        $this->assertEquals('Invoice has been paid', $metas['PAID']->data['description']);

        $this->assertInstanceOf(self::META_ANNOTATION_CLASS, $metas['DRAFT']);
        $this->assertEquals('Draft', $metas['DRAFT']->data['name']);
        $this->assertEquals('Invoice is new and not yet sent to customer', $metas['DRAFT']->data['description']);
    }

    public function testGetClassConstantsMetaByGroupUsingAllKeyword()
    {
        $metas = $this
            ->getMetaManager()
            ->getClassConstantsMeta('AshleyDawson\ClassMeta\Test\Dummy\DummyInvoice', ['_all']);

        $this->assertCount(3, $metas);
    }
    
    public function testGetClassConstantsMetaOnSuperclass()
    {
        $metas = $this
            ->getMetaManager()
            ->getClassConstantsMeta('AshleyDawson\ClassMeta\Test\Dummy\DummyInvoiceExtended');

        $this->assertInternalType('array', $metas);

        $this->assertInstanceOf(self::META_ANNOTATION_CLASS, $metas['DRAFT']);
        $this->assertEquals('Draft', $metas['DRAFT']->data['name']);
        $this->assertEquals('Invoice is new and not yet sent to customer', $metas['DRAFT']->data['description']);

        $this->assertInstanceOf(self::META_ANNOTATION_CLASS, $metas['SENT']);
        $this->assertEquals('Sent', $metas['SENT']->data['name']);
        $this->assertEquals('Invoice has been sent to the customer', $metas['SENT']->data['description']);

        $this->assertInstanceOf(self::META_ANNOTATION_CLASS, $metas['BASE']);
        $this->assertEquals('Base', $metas['BASE']->data['name']);
        $this->assertEquals('Testing the base class constant meta', $metas['BASE']->data['description']);
    }

    public function testGetClassConstantsMetaOnSuperclassCached()
    {
        $metas = $this
            ->getMetaManager(true)
            ->getClassConstantsMeta('AshleyDawson\ClassMeta\Test\Dummy\DummyInvoiceExtended');

        $this->assertInternalType('array', $metas);

        $this->assertInstanceOf(self::META_ANNOTATION_CLASS, $metas['DRAFT']);
        $this->assertEquals('Draft', $metas['DRAFT']->data['name']);
        $this->assertEquals('Invoice is new and not yet sent to customer', $metas['DRAFT']->data['description']);

        $this->assertInstanceOf(self::META_ANNOTATION_CLASS, $metas['SENT']);
        $this->assertEquals('Sent', $metas['SENT']->data['name']);
        $this->assertEquals('Invoice has been sent to the customer', $metas['SENT']->data['description']);

        $this->assertInstanceOf(self::META_ANNOTATION_CLASS, $metas['BASE']);
        $this->assertEquals('Base', $metas['BASE']->data['name']);
        $this->assertEquals('Testing the base class constant meta', $metas['BASE']->data['description']);

        $metas = $this
            ->getMetaManager(true)
            ->getClassConstantsMeta('AshleyDawson\ClassMeta\Test\Dummy\DummyInvoiceExtended');

        $this->assertInternalType('array', $metas);

        $this->assertInstanceOf(self::META_ANNOTATION_CLASS, $metas['DRAFT']);
        $this->assertEquals('Draft', $metas['DRAFT']->data['name']);
        $this->assertEquals('Invoice is new and not yet sent to customer', $metas['DRAFT']->data['description']);

        $this->assertInstanceOf(self::META_ANNOTATION_CLASS, $metas['SENT']);
        $this->assertEquals('Sent', $metas['SENT']->data['name']);
        $this->assertEquals('Invoice has been sent to the customer', $metas['SENT']->data['description']);

        $this->assertInstanceOf(self::META_ANNOTATION_CLASS, $metas['BASE']);
        $this->assertEquals('Base', $metas['BASE']->data['name']);
        $this->assertEquals('Testing the base class constant meta', $metas['BASE']->data['description']);
    }

    public function testGetClassMetaCustomAnnotation()
    {
        $meta = $this
            ->getMetaManager()
            ->getClassMeta('AshleyDawson\ClassMeta\Test\Dummy\DummyStatusWithCustomAnnot');

        $this->assertInstanceOf(self::CUSTOM_META_ANNOTATION_CLASS, $meta);

        $this->assertEquals('Class meta here', $meta->foo);
    }

    public function testGetClassMetaCustomAnnotationCached()
    {
        $meta = $this
            ->getMetaManager(true)
            ->getClassMeta('AshleyDawson\ClassMeta\Test\Dummy\DummyStatusWithCustomAnnot');

        $this->assertInstanceOf(self::CUSTOM_META_ANNOTATION_CLASS, $meta);

        $this->assertEquals('Class meta here', $meta->foo);

        $meta = $this
            ->getMetaManager(true)
            ->getClassMeta('AshleyDawson\ClassMeta\Test\Dummy\DummyStatusWithCustomAnnot');

        $this->assertInstanceOf(self::CUSTOM_META_ANNOTATION_CLASS, $meta);

        $this->assertEquals('Class meta here', $meta->foo);
    }

    public function testGetClassConstantsMetaCustomAnnotation()
    {
        $metas = $this
            ->getMetaManager()
            ->getClassConstantsMeta('AshleyDawson\ClassMeta\Test\Dummy\DummyStatusWithCustomAnnot');

        $this->assertInternalType('array', $metas);

        $this->assertInstanceOf(self::CUSTOM_META_ANNOTATION_CLASS, $metas['FOO']);
        $this->assertEquals('Foo thingy', $metas['FOO']->foo);

        $this->assertInstanceOf(self::CUSTOM_META_ANNOTATION_CLASS, $metas['BAR']);
        $this->assertEquals('Bar thingy', $metas['BAR']->foo);
    }

    public function testGetClassConstantsMetaCustomAnnotationCached()
    {
        $metas = $this
            ->getMetaManager(true)
            ->getClassConstantsMeta('AshleyDawson\ClassMeta\Test\Dummy\DummyStatusWithCustomAnnot');

        $this->assertInternalType('array', $metas);

        $this->assertInstanceOf(self::CUSTOM_META_ANNOTATION_CLASS, $metas['FOO']);
        $this->assertEquals('Foo thingy', $metas['FOO']->foo);

        $this->assertInstanceOf(self::CUSTOM_META_ANNOTATION_CLASS, $metas['BAR']);
        $this->assertEquals('Bar thingy', $metas['BAR']->foo);

        $metas = $this
            ->getMetaManager(true)
            ->getClassConstantsMeta('AshleyDawson\ClassMeta\Test\Dummy\DummyStatusWithCustomAnnot');

        $this->assertInternalType('array', $metas);

        $this->assertInstanceOf(self::CUSTOM_META_ANNOTATION_CLASS, $metas['FOO']);
        $this->assertEquals('Foo thingy', $metas['FOO']->foo);

        $this->assertInstanceOf(self::CUSTOM_META_ANNOTATION_CLASS, $metas['BAR']);
        $this->assertEquals('Bar thingy', $metas['BAR']->foo);
    }

    public function testGetClassConstantsMetaCustomAnnotationExtended()
    {
        $metas = $this
            ->getMetaManager()
            ->getClassConstantsMeta('AshleyDawson\ClassMeta\Test\Dummy\DummyStatusWithCustomAnnotExtended');

        $this->assertInternalType('array', $metas);

        $this->assertInstanceOf(self::CUSTOM_META_ANNOTATION_CLASS, $metas['BAZ']);
        $this->assertEquals('Baz thingy', $metas['BAZ']->foo);
    }

    public function testGetClassConstantsMetaCustomAnnotationExtendedCached()
    {
        $metas = $this
            ->getMetaManager(true)
            ->getClassConstantsMeta('AshleyDawson\ClassMeta\Test\Dummy\DummyStatusWithCustomAnnotExtended');

        $this->assertInternalType('array', $metas);

        $this->assertInstanceOf(self::CUSTOM_META_ANNOTATION_CLASS, $metas['BAZ']);
        $this->assertEquals('Baz thingy', $metas['BAZ']->foo);

        $metas = $this
            ->getMetaManager(true)
            ->getClassConstantsMeta('AshleyDawson\ClassMeta\Test\Dummy\DummyStatusWithCustomAnnotExtended');

        $this->assertInternalType('array', $metas);

        $this->assertInstanceOf(self::CUSTOM_META_ANNOTATION_CLASS, $metas['BAZ']);
        $this->assertEquals('Baz thingy', $metas['BAZ']->foo);
    }

    public function testGetMappedClassConstantsMeta()
    {
        $choices = $this
            ->getMetaManager()
            ->getMappedClassConstantsMeta('AshleyDawson\ClassMeta\Test\Dummy\DummyInvoice', function (Meta $meta) {
                return [$meta->value, $meta->data['name']];
            });

        $this->assertInternalType('array', $choices);

        $this->assertEquals('Draft', $choices['draft']);
        $this->assertEquals('Sent', $choices['sent']);

        $choices = $this
            ->getMetaManager()
            ->getMappedClassConstantsMeta('AshleyDawson\ClassMeta\Test\Dummy\DummyInvoice', function (Meta $meta, $i) {
                return [$i, $meta->data['name']];
            });

        $this->assertInternalType('array', $choices);

        $this->assertEquals('Draft', $choices[0]);
        $this->assertEquals('Sent', $choices[1]);
    }

    public function testScopedConstantsClassMeta()
    {
        $meta = $this
            ->getMetaManager()
            ->getClassConstantsMeta('AshleyDawson\ClassMeta\Test\Dummy\DummyClassScopedConstants')
        ;

        $this->assertArrayHasKey('SCOPE_PUBLIC', $meta);
        $this->assertArrayHasKey('SCOPE_PROTECTED', $meta);
        $this->assertArrayHasKey('SCOPE_PRIVATE', $meta);

        $this->assertEquals('SCOPE_PUBLIC', $meta['SCOPE_PUBLIC']->property);
        $this->assertEquals('public', $meta['SCOPE_PUBLIC']->value);
        $this->assertEquals('Public Label', $meta['SCOPE_PUBLIC']->data['label']);
        $this->assertContains('Default', $meta['SCOPE_PUBLIC']->groups);

        $this->assertEquals('SCOPE_PROTECTED', $meta['SCOPE_PROTECTED']->property);
        $this->assertEquals('protected', $meta['SCOPE_PROTECTED']->value);
        $this->assertEquals('Protected Label', $meta['SCOPE_PROTECTED']->data['label']);
        $this->assertContains('Default', $meta['SCOPE_PROTECTED']->groups);

        $this->assertEquals('SCOPE_PRIVATE', $meta['SCOPE_PRIVATE']->property);
        $this->assertEquals('private', $meta['SCOPE_PRIVATE']->value);
        $this->assertEquals('Private Label', $meta['SCOPE_PRIVATE']->data['label']);
        $this->assertContains('Default', $meta['SCOPE_PRIVATE']->groups);
    }
}
