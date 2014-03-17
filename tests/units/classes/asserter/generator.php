<?php

namespace mageekguy\atoum\tests\units\asserter;

use
	mageekguy\atoum,
	mageekguy\atoum\asserter,
	mageekguy\atoum\test\assertion
;

require_once __DIR__ . '/../../runner.php';

class generator extends atoum\test
{
	public function test__construct()
	{
		$this
			->given($this->newTestedInstance)
			->then
				->object($this->testedInstance->getLocale())->isEqualTo(new atoum\locale())
				->object($this->testedInstance->getResolver())->isEqualTo(new asserter\resolver())
				->object($this->testedInstance->getAliaser())->isEqualTo(new assertion\aliaser())

			->given($this->newTestedInstance($locale = new atoum\locale(), $resolver = new asserter\resolver(), $aliaser = new assertion\aliaser()))
			->then
				->object($this->testedInstance->getLocale())->isIdenticalTo($locale)
				->object($this->testedInstance->getResolver())->isIdenticalTo($resolver)
				->object($this->testedInstance->getAliaser())->isIdenticalTo($aliaser)
		;
	}

	public function test__get()
	{
		$this
			->given($generator = $this->newTestedInstance)
			->then
				->exception(function() use ($generator, & $asserter) { $generator->{$asserter = uniqid()}; })
					->isInstanceOf('mageekguy\atoum\exceptions\logic\invalidArgument')
					->hasMessage('Asserter \'' . $asserter . '\' does not exist')

				->object($generator->variable)->isInstanceOf('mageekguy\atoum\asserters\variable')
		;
	}

	public function test__set()
	{
		$this
			->given($generator = $this->newTestedInstance)
			->then
				->when(function() use ($generator, & $alias, & $asserter) { $generator->{$alias = uniqid()} = ($asserter = uniqid()); })
					->array($generator->getAliases())->isEqualTo(array($alias => $asserter))

				->when(function() use ($generator, & $otherAlias, & $otherAsserter) { $generator->{$otherAlias = uniqid()} = ($otherAsserter = uniqid()); })
					->array($generator->getAliases())->isEqualTo(array($alias => $asserter, $otherAlias => $otherAsserter))
		;
	}

	public function test__call()
	{
		$this
			->given($generator = $this->newTestedInstance)
			->then
				->exception(function() use ($generator, & $asserter) { $generator->{$asserter = uniqid()}(); })
					->isInstanceOf('mageekguy\atoum\exceptions\logic\invalidArgument')
					->hasMessage('Asserter \'' . $asserter . '\' does not exist')

				->object($asserter = $generator->variable($variable = uniqid()))->isInstanceOf('mageekguy\atoum\asserters\variable')
				->string($asserter->getValue())->isEqualTo($variable)
		;
	}

	public function testSetBaseClass()
	{
		$this
			->given($this->newTestedInstance)

			->if($this->testedInstance->setResolver($resolver = new \mock\atoum\asserter\resolver()))
			->then
				->object($this->testedInstance->setBaseClass($baseClass = uniqid()))->isTestedInstance
				->mock($resolver)->call('setBaseClass')->withArguments($baseClass)->once
		;
	}

	public function testGetBaseClass()
	{
		$this
			->given($this->newTestedInstance)

			->if(
				$this->testedInstance->setResolver($resolver = new \mock\atoum\asserter\resolver()),
				$this->calling($resolver)->getBaseClass = $baseClass = uniqid()
			)
			->then
				->string($this->testedInstance->getBaseClass())->isEqualTo($baseClass)
		;
	}

	public function testAddNamespace()
	{
		$this
			->given($this->newTestedInstance)

			->if($this->testedInstance->setResolver($resolver = new \mock\atoum\asserter\resolver()))
			->then
				->object($this->testedInstance->addNamespace($namespace = uniqid()))->isTestedInstance
				->mock($resolver)->call('addNamespace')->withArguments($namespace)->once
		;
	}

	public function testGetNamespaces()
	{
		$this
			->given($this->newTestedInstance)

			->if(
				$this->testedInstance->setResolver($resolver = new \mock\atoum\asserter\resolver()),
				$this->calling($resolver)->getNamespaces = $namespaces = range(1, 5)
			)
			->then
				->array($this->testedInstance->getNamespaces())->isEqualTo($namespaces)
		;
	}

	public function testSetLocale()
	{
		$this
			->given($this->newTestedInstance)
			->then
				->object($this->testedInstance->setLocale($locale = new atoum\locale()))->isTestedInstance
				->object($this->testedInstance->getLocale())->isIdenticalTo($locale)

				->object($this->testedInstance->setLocale())->isTestedInstance
				->object($this->testedInstance->getLocale())
					->isNotIdenticalTo($locale)
					->isEqualTo(new atoum\locale())
		;
	}

	public function testSetResolver()
	{
		$this
			->given($this->newTestedInstance)
			->then
				->object($this->testedInstance->setResolver($resolver = new asserter\resolver()))->isTestedInstance
				->object($this->testedInstance->getResolver())->isIdenticalTo($resolver)

				->object($this->testedInstance->setResolver())->isTestedInstance
				->object($this->testedInstance->getResolver())
					->isEqualTo(new asserter\resolver())
					->isNotIdenticalTo($resolver)
		;
	}

	public function testSetAliaser()
	{
		$this
			->given($this->newTestedInstance)
			->then
				->object($this->testedInstance->setAliaser($aliaser = new assertion\aliaser()))->isTestedInstance
				->object($this->testedInstance->getAliaser())->isIdenticalTo($aliaser)

				->object($this->testedInstance->setAliaser())->isTestedInstance
				->object($this->testedInstance->getAliaser())
					->isEqualTo(new assertion\aliaser())
					->isNotIdenticalTo($aliaser)
		;
	}

	public function testSetAlias()
	{
		$this
			->given($this->newTestedInstance)
			->then
				->object($this->testedInstance->setAlias($alias = uniqid(), $asserter = uniqid()))->isTestedInstance
				->array($this->testedInstance->getAliases())->isEqualTo(array($alias => $asserter))

				->object($this->testedInstance->setAlias($otherAlias = 'FOO', $otherAsserter = uniqid()))->isTestedInstance
				->array($this->testedInstance->getAliases())->isEqualTo(array($alias => $asserter, 'foo' => $otherAsserter))
		;
	}

	public function testResetAliases()
	{
		$this
			->given($this->newTestedInstance)

			->if($this->testedInstance->setAlias(uniqid(), uniqid()))
			->then
				->object($this->testedInstance->resetAliases())->isTestedInstance
				->array($this->testedInstance->getAliases())->isEmpty()
		;
	}

	public function testGetAsserterClass()
	{
		$this
			->given($this->newTestedInstance->setResolver($resolver = new \mock\atoum\asserter\resolver()))

			->if($this->calling($resolver)->resolve = null)
			->then
				->variable($this->testedInstance->getAsserterClass($asserter = uniqid()))->isNull()
				->mock($resolver)->call('resolve')->withArguments($asserter)->once

			->if($this->calling($resolver)->resolve = $class = uniqid())
			->then
				->string($this->testedInstance->getAsserterClass($asserter = uniqid()))->isEqualTo($class)
				->mock($resolver)->call('resolve')->withArguments($asserter)->once

			->if($this->testedInstance->setAlias($alias = uniqid(), $asserter = uniqid()))
			->then
				->string($this->testedInstance->getAsserterClass($alias))->isEqualTo($class)
				->mock($resolver)->call('resolve')->withArguments($asserter)->once
		;
	}
}
