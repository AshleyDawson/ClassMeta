<?php

namespace AshleyDawson\ClassMeta;

use AshleyDawson\ClassMeta\Annotation\Meta;
use Doctrine\Common\Annotations\DocParser;
use Doctrine\Common\Annotations\PhpParser;
use Doctrine\Common\Annotations\Reader as AnnotationReaderInterface;
use Doctrine\Common\Cache\Cache as CacheInterface;
use Doctrine\Common\Cache\VoidCache;

/**
 * Class ClassMetaManager
 *
 * @package AshleyDawson\ClassMeta
 * @author Ashley Dawson
 */
class ClassMetaManager implements ClassMetaManagerInterface
{
    /**
     * Default group name
     */
    const DEFAULT_GROUP_NAME = 'Default';

    /**
     * Meta base annotation class
     */
    const META_ANNOTATION_CLASS = 'AshleyDawson\ClassMeta\Annotation\Meta';

    /**
     * @var AnnotationReaderInterface
     */
    private $annotationReader;

    /**
     * @var CacheInterface
     */
    private $cache;

    /**
     * @var int
     */
    private $cacheTtl = 300;

    /**
     * Constructor
     *
     * @param AnnotationReaderInterface $annotationReader
     */
    public function __construct(AnnotationReaderInterface $annotationReader)
    {
        $this->annotationReader = $annotationReader;
        $this->cache = new VoidCache();
    }

    /**
     * Set cache
     *
     * @param CacheInterface $cache
     * @param int $ttl Cache TTL in seconds
     * @return $this
     */
    public function setCache($cache, $ttl = 300)
    {
        $this->cache = $cache;
        $this->cacheTtl = $ttl;

        return $this;
    }

    /**
     * {@inheritdoc}
     */
    public function getClassMeta($class, array $groups = null)
    {
        $class = (is_object($class) ? get_class($class) : $class);

        if (null === $groups) {
            $groups = [self::DEFAULT_GROUP_NAME];
        }

        $reflectionClass = new \ReflectionClass($class);

        $cacheKey = sha1('class_'.$reflectionClass->getName().filemtime($reflectionClass->getFileName()).serialize($groups));

        if ($this->cache->contains($cacheKey)) {
            return $this->cache->fetch($cacheKey);
        }

        /** @var \AshleyDawson\ClassMeta\Annotation\Meta $meta */
        $meta = $this
            ->annotationReader
            ->getClassAnnotation($reflectionClass, self::META_ANNOTATION_CLASS);

        if ($meta) {
            $meta->property = $class;
            $meta->value = null;

            foreach ($groups as $group) {
                if (in_array($group, $meta->groups)) {
                    $this->cache->save($cacheKey, $meta, $this->cacheTtl);

                    return $meta;
                }
            }
        }

        return null;
    }

    /**
     * {@inheritdoc}
     */
    public function getClassConstantsMeta($class, array $groups = null)
    {
        $class = (is_object($class) ? get_class($class) : $class);

        $classes = (@class_parents($class) ?: []);
        $classes[] = $class;

        $meta = [];
        foreach (array_values($classes) as $class) {
            $meta = array_merge($this->getConstantsMetaForSingleClass($class, $groups), $meta);
        }

        return $meta;
    }

    /**
     * {@inheritdoc}
     */
    public function getClassConstantMetaByValue($class, $value, array $groups = null)
    {
        $metas = $this->getClassConstantsMeta($class, $groups);
        foreach ($metas as $meta) {
            if ($meta->value == $value) {
                return $meta;
            }
        }

        return null;
    }

    /**
     * Get class constant meta for a single class
     *
     * @param string $class Class name
     * @param array|null $groups
     * @return array
     */
    protected function getConstantsMetaForSingleClass($class, array $groups = null)
    {
        if (null === $groups) {
            $groups = [self::DEFAULT_GROUP_NAME];
        }

        $class = new \ReflectionClass($class);
        $filename = $class->getFileName();

        $cacheKey = sha1('constants_'.$class->getName().filemtime($filename).serialize($groups));

        if ($this->cache->contains($cacheKey)) {
            return $this->cache->fetch($cacheKey);
        }

        $tokens = token_get_all(file_get_contents($filename));

        $doc = $isConst = $comments = null;
        foreach ($tokens as $token) {
            @list ($type, $value) = $token;
            switch ($type) {
                case T_WHITESPACE:
                case T_COMMENT:
                    break;
                case T_DOC_COMMENT:
                    $doc = $value;
                    break;
                case T_CONST:
                    $isConst = true;
                    break;
                case T_STRING:
                    if ($isConst) {
                        $comments[$value] = $doc;
                    }
                    $doc = null;
                    $isConst = false;
                    break;
                default:
                    $doc = null;
                    $isConst = false;
                    break;
            }
        }

        $phpParser = new PhpParser();
        $docParser = new DocParser();

        $docParser->setImports($phpParser->parseClass($class));

        /** @var Meta[] $constAnnotations */
        $constAnnotations = [];
        $constants = $class->getConstants();

        if (is_array($comments)) {
            foreach ($comments as $const => $comment) {
                $annotations = $docParser->parse($comment);
                if (is_array($annotations) && count($annotations)) {
                    /** @var Meta $annotation */
                    $annotation = $annotations[0];

                    if (!isset($constants[$const])) {
                        throw new \InvalidArgumentException(
                            sprintf('Could not find the constant on the class "%s" based on the name "%s"', $class->getName(), $const)
                        );
                    }

                    $annotation->property = $const;
                    $annotation->value = $constants[$const];
                    $constAnnotations[] = $annotation;
                }
            }
        }

        $meta = [];
        foreach ($constAnnotations as $constAnnotation) {
            foreach ($groups as $group) {
                if (in_array($group, $constAnnotation->groups)) {
                    $meta[$constAnnotation->property] = $constAnnotation;
                }
            }
        }

        $this->cache->save($cacheKey, $meta, $this->cacheTtl);

        return $meta;
    }
}
