<?php

namespace tests\Container;

use PHPUnit\Framework\TestCase;
use my\Container\DIContainer;
use my\Exceptions\ContainerNotFoundException;
use my\Repositories\InMemoryUserRepository;
use my\Repositories\UserRepository;
use my\Repositories\UserRepositoryInterface;

class DIContainerTests extends TestCase
{
    public function testItThrowCannotResolveType(): void
    {
        $container = new DIContainer();

        $this->expectException(ContainerNotFoundException::class);
        $this->expectExceptionMessage('Cannot resolve type: tests\Container\SomeClass');

        $container->get(SomeClass::class);
    }

    public function testItResolvesClassWithoutDependencies(): void
    {
        $container = new DIContainer();
        $object = $container->get(SomeClassWithoutDependencies::class);
        $this->assertInstanceOf(SomeClassWithoutDependencies::class, $object);
    }

    public function testItResolvesClassByContract(): void
    {
        $container = new DIContainer;

        $container->bind(UserRepositoryInterface::class, InMemoryUserRepository::class);

        $object = $container->get(InMemoryUserRepository::class);

        $this->assertInstanceOf(InMemoryUserRepository::class, $object);
    }

    public function testItReturnPredefinedObject(): void
    {
        $container = new DIContainer();

        $container->bind(SomeClassWithParameter::class, new SomeClassWithParameter(444));

        $object = $container->get(SomeClassWithParameter::class);

        $this->assertInstanceOf(SomeClassWithParameter::class, $object);

        $this->assertSame(444, $object->getValue());
    }

    public function testItResolvesClassWithDependencies(): void
    {
        $container = new DIContainer;

        $container->bind(SomeClassWithParameter::class, new SomeClassWithParameter(123));

        $object = $container->get(ClassDependingOnAnother::class);

        $this->assertInstanceOf(ClassDependingOnAnother::class, $object);
    }
}