<?php

namespace mageekguy\atoum\tests\units\test\adapter;

require_once __DIR__ . '/../../../runner.php';

use
	mageekguy\atoum,
	mageekguy\atoum\test\adapter,
	mageekguy\atoum\test\adapter\call\decorator,
	mageekguy\atoum\test\adapter\call as testedClass
;

class call extends atoum\test
{
	public function test__construct()
	{
		$this
			->if($call = new testedClass())
			->then
				->variable($call->getFunction())->isNull()
				->variable($call->getArguments())->isNull()
				->object($call->getDecorator())->isEqualTo(new decorator())
			->if($call = new testedClass($function = uniqid()))
			->then
				->string($call->getFunction())->isEqualTo($function)
				->variable($call->getArguments())->isNull()
				->object($call->getDecorator())->isEqualTo(new decorator())
			->if($call = new testedClass($function = uniqid(), $arguments = array()))
			->then
				->string($call->getFunction())->isEqualTo($function)
				->array($call->getArguments())->isEqualTo($arguments)
				->object($call->getDecorator())->isEqualTo(new decorator())
			->if($call = new testedClass('MD5'))
			->then
				->string($call->getFunction())->isEqualTo('md5')
				->variable($call->getArguments())->isNull()
				->object($call->getDecorator())->isEqualTo(new decorator())
		;
	}

	public function testSetFunction()
	{
		$this
			->if($call = new testedClass())
			->then
				->object($call->setFunction($function = uniqid()))->isIdenticalTo($call)
				->string($call->getFunction())->isEqualTo($function)
		;
	}

	public function testSetArguments()
	{
		$this
			->if($call = new testedClass())
			->then
				->object($call->setArguments($arguments = array()))->isIdenticalTo($call)
				->array($call->getArguments())->isEqualTo($arguments)
		;
	}

	public function testSetDecorator()
	{
		$this
			->if($call = new testedClass())
			->then
				->object($call->setDecorator($decorator = new decorator()))->isIdenticalTo($call)
				->object($call->getDecorator())->isIdenticalTo($decorator)
				->object($call->setDecorator())->isIdenticalTo($call)
				->object($call->getDecorator())
					->isNotIdenticalTo($decorator)
					->isEqualTo(new decorator())
		;
	}

	public function testFind()
	{
		$this
			->if($adapter = new adapter())
			->and($call = new testedClass())
			->then
				->array($call->find($adapter))->isEmpty()
			->if($call = new testedClass(uniqid()))
			->then
				->array($call->find($adapter))->isEmpty()
			->if($call = new testedClass(uniqid(), array()))
			->then
				->array($call->find($adapter))->isEmpty()
			->if($call = new testedClass(uniqid(), array(uniqid())))
			->then
				->array($call->find($adapter))->isEmpty()
			->if($adapter->addCall($function = uniqid(), $arguments = array(uniqid(), uniqid())))
			->then
				->array($call->find($adapter))->isEmpty()
			->if($call = new testedClass(uniqid()))
			->then
				->array($call->find($adapter))->isEmpty()
			->if($call = new testedClass(uniqid(), array()))
			->then
				->array($call->find($adapter))->isEmpty()
			->if($call = new testedClass(uniqid(), array(uniqid())))
			->then
				->array($call->find($adapter))->isEmpty()
			->if($call = new testedClass($function))
			->then
				->array($call->find($adapter))->isEqualTo(array(1 => $arguments))
			->if($call = new testedClass($function, $arguments))
			->then
				->array($call->find($adapter))->isEqualTo(array(1 => $arguments))
			->if($call = new testedClass($function, array()))
			->then
				->array($call->find($adapter))->isEmpty()
			->if($adapter->addCall($otherFunction = uniqid(), $otherArguments = array($object = new \mock\object())))
			->and($call = new testedClass($otherFunction))
			->then
				->array($call->find($adapter))->isEqualTo(array(2 => $otherArguments))
			->if($call = new testedClass($otherFunction, array($object)))
			->then
				->array($call->find($adapter))->isEqualTo(array(2 => $otherArguments))
			->if($call = new testedClass($otherFunction, array(clone $object)))
			->then
				->array($call->find($adapter))->isEqualTo(array(2 => $otherArguments))
		;
	}

	public function testFindFirst()
	{
		$this
			->if($adapter = new adapter())
			->and($call = new testedClass())
			->then
				->variable($call->findFirst($adapter))->isNull()
			->if($call = new testedClass(uniqid()))
			->then
				->variable($call->findFirst($adapter))->isNull()
			->if($call = new testedClass(uniqid(), array()))
			->then
				->variable($call->findFirst($adapter))->isNull()
			->if($call = new testedClass(uniqid(), array(uniqid())))
			->then
				->variable($call->findFirst($adapter))->isNull()
			->if($adapter->addCall($function = uniqid(), $arguments = array(uniqid(), uniqid())))
			->then
				->variable($call->findFirst($adapter))->isNull()
			->if($call = new testedClass(uniqid()))
			->then
				->variable($call->findFirst($adapter))->isNull()
			->if($call = new testedClass(uniqid(), array()))
			->then
				->variable($call->findFirst($adapter))->isNull()
			->if($call = new testedClass(uniqid(), array(uniqid())))
			->then
				->variable($call->findFirst($adapter))->isNull()
			->if($call = new testedClass($function))
			->then
				->array($call->findFirst($adapter))->isEqualTo(array(1 => $arguments))
			->if($call = new testedClass($function, $arguments))
			->then
				->array($call->findFirst($adapter))->isEqualTo(array(1 => $arguments))
			->if($call = new testedClass($function, $arguments))
			->then
				->variable($call->findFirst($adapter))->isEqualTo(array(1 => $arguments))
			->if($adapter->addCall($function, $otherArguments = array(uniqid(), uniqid())))
			->then
				->variable($call->findFirst($adapter))->isEqualTo(array(1 => $arguments))
			->if($call = new testedClass($function))
			->then
				->variable($call->findFirst($adapter))->isEqualTo(array(1 => $arguments))
		;
	}

	public function testFindLast()
	{
		$this
			->if($adapter = new adapter())
			->and($call = new testedClass())
			->then
				->variable($call->findLast($adapter))->isNull()
			->if($call = new testedClass(uniqid()))
			->then
				->variable($call->findLast($adapter))->isNull()
			->if($call = new testedClass(uniqid(), array()))
			->then
				->variable($call->findLast($adapter))->isNull()
			->if($call = new testedClass(uniqid(), array(uniqid())))
			->then
				->variable($call->findLast($adapter))->isNull()
			->if($adapter->addCall($function = uniqid(), $arguments = array(uniqid(), uniqid())))
			->then
				->variable($call->findLast($adapter))->isNull()
			->if($call = new testedClass(uniqid()))
			->then
				->variable($call->findLast($adapter))->isNull()
			->if($call = new testedClass(uniqid(), array()))
			->then
				->variable($call->findLast($adapter))->isNull()
			->if($call = new testedClass(uniqid(), array(uniqid())))
			->then
				->variable($call->findLast($adapter))->isNull()
			->if($call = new testedClass($function))
			->then
				->array($call->findLast($adapter))->isEqualTo(array(1 => $arguments))
			->if($call = new testedClass($function, $arguments))
			->then
				->array($call->findLast($adapter))->isEqualTo(array(1 => $arguments))
			->if($call = new testedClass($function, $arguments))
			->then
				->variable($call->findLast($adapter))->isEqualTo(array(1 => $arguments))
			->if($adapter->addCall($function, $otherArguments = array(uniqid(), uniqid())))
			->then
				->variable($call->findLast($adapter))->isEqualTo(array(1 => $arguments))
			->if($call = new testedClass($function))
			->then
				->variable($call->findLast($adapter))->isEqualTo(array(2 => $otherArguments))
		;
	}

	public function testIsEqualTo()
	{
		$this
			->if($call1 = new testedClass())
			->and($call2 = new testedClass())
			->then
				->boolean($call1->isEqualTo($call2))->isFalse()
				->boolean($call2->isEqualTo($call1))->isFalse()
			->if($call1 = new testedClass(uniqid()))
			->then
				->boolean($call1->isEqualTo($call2))->isFalse()
				->boolean($call2->isEqualTo($call1))->isFalse()
			->if($call2 = new testedClass(uniqid()))
			->then
				->boolean($call1->isEqualTo($call2))->isFalse()
				->boolean($call2->isEqualTo($call1))->isFalse()
			->if($call1 = new testedClass())
			->then
				->boolean($call1->isEqualTo($call2))->isFalse()
				->boolean($call2->isEqualTo($call1))->isFalse()
			->if($call1 = new testedClass($function = uniqid()))
			->and($call2 = new testedClass($function))
			->then
				->boolean($call1->isEqualTo($call2))->isTrue()
				->boolean($call2->isEqualTo($call1))->isTrue()
			->if($call1 = new testedClass($function, array()))
			->then
				->boolean($call1->isEqualTo($call2))->isFalse()
				->boolean($call2->isEqualTo($call1))->isTrue()
			->if($call2 = new testedClass($function, array()))
			->then
				->boolean($call1->isEqualTo($call2))->isTrue()
				->boolean($call2->isEqualTo($call1))->isTrue()
			->if($call1 = new testedClass($function, array($argument = uniqid())))
			->then
				->boolean($call1->isEqualTo($call2))->isFalse()
				->boolean($call2->isEqualTo($call1))->isFalse()
			->if($call2 = new testedClass($function, array($argument)))
			->then
				->boolean($call1->isEqualTo($call2))->isTrue()
				->boolean($call2->isEqualTo($call1))->isTrue()
			->if($call1 = new testedClass($function, $arguments = array(uniqid(), uniqid())))
			->then
				->boolean($call1->isEqualTo($call2))->isFalse()
				->boolean($call2->isEqualTo($call1))->isFalse()
			->if($call2 = new testedClass($function, $arguments))
			->then
				->boolean($call1->isEqualTo($call2))->isTrue()
				->boolean($call2->isEqualTo($call1))->isTrue()
			->if($call1 = new testedClass($function, $arguments = array($arg1 = uniqid(), $arg2 = uniqid(), $arg3 = new \mock\object())))
			->then
				->boolean($call1->isEqualTo($call2))->isFalse()
				->boolean($call2->isEqualTo($call1))->isFalse()
			->if($call2 = new testedClass($function, $arguments))
			->then
				->boolean($call1->isEqualTo($call2))->isTrue()
				->boolean($call2->isEqualTo($call1))->isTrue()
			->if($call2 = new testedClass($function, array($arg1, $arg2, clone $arg3)))
			->then
				->boolean($call1->isEqualTo($call2))->isTrue()
				->boolean($call2->isEqualTo($call1))->isTrue()
			->if($call2 = new testedClass($function, array($arg3, $arg2, $arg1)))
			->then
				->boolean($call1->isEqualTo($call2))->isFalse()
				->boolean($call2->isEqualTo($call1))->isFalse()
			->if($call1 = new testedClass($function = uniqid(), array($arg1 = uniqid(), $arg2 = uniqid(), $arg3 = new \mock\object())))
			->and($call2 = new testedClass($function, array($arg1, $arg2)))
			->then
				->boolean($call1->isEqualTo($call2))->isFalse()
				->boolean($call2->isEqualTo($call1))->isTrue()
		;
	}

	public function testIsIdenticalTo()
	{
		$this
			->if($call1 = new testedClass())
			->and($call2 = new testedClass())
			->then
				->boolean($call1->isIdenticalTo($call2))->isFalse()
				->boolean($call2->isIdenticalTo($call1))->isFalse()
			->if($call1 = new testedClass(uniqid()))
			->then
				->boolean($call1->isIdenticalTo($call2))->isFalse()
				->boolean($call2->isIdenticalTo($call1))->isFalse()
			->if($call2 = new testedClass(uniqid()))
			->then
				->boolean($call1->isIdenticalTo($call2))->isFalse()
				->boolean($call2->isIdenticalTo($call1))->isFalse()
			->if($call1 = new testedClass())
			->then
				->boolean($call1->isIdenticalTo($call2))->isFalse()
				->boolean($call2->isIdenticalTo($call1))->isFalse()
			->if($call1 = new testedClass($function = uniqid()))
			->and($call2 = new testedClass($function))
			->then
				->boolean($call1->isIdenticalTo($call2))->isTrue()
				->boolean($call2->isIdenticalTo($call1))->isTrue()
			->if($call1 = new testedClass($function, array()))
			->then
				->boolean($call1->isIdenticalTo($call2))->isFalse()
				->boolean($call2->isIdenticalTo($call1))->isTrue()
			->if($call2 = new testedClass($function, array()))
			->then
				->boolean($call1->isIdenticalTo($call2))->isTrue()
				->boolean($call2->isIdenticalTo($call1))->isTrue()
			->if($call1 = new testedClass($function, array($argument = uniqid())))
			->then
				->boolean($call1->isIdenticalTo($call2))->isFalse()
				->boolean($call2->isIdenticalTo($call1))->isFalse()
			->if($call2 = new testedClass($function, array($argument)))
			->then
				->boolean($call1->isIdenticalTo($call2))->isTrue()
				->boolean($call2->isIdenticalTo($call1))->isTrue()
			->if($call1 = new testedClass($function, $arguments = array(uniqid(), uniqid())))
			->then
				->boolean($call1->isIdenticalTo($call2))->isFalse()
				->boolean($call2->isIdenticalTo($call1))->isFalse()
			->if($call2 = new testedClass($function, $arguments))
			->then
				->boolean($call1->isIdenticalTo($call2))->isTrue()
				->boolean($call2->isIdenticalTo($call1))->isTrue()
			->if($call1 = new testedClass($function, $arguments = array($arg1 = uniqid(), $arg2 = uniqid(), $arg3 = new \mock\object())))
			->then
				->boolean($call1->isIdenticalTo($call2))->isFalse()
				->boolean($call2->isIdenticalTo($call1))->isFalse()
			->if($call2 = new testedClass($function, $arguments))
			->then
				->boolean($call1->isIdenticalTo($call2))->isTrue()
				->boolean($call2->isIdenticalTo($call1))->isTrue()
			->if($call2 = new testedClass($function, array($arg1, $arg2, clone $arg3)))
			->then
				->boolean($call1->isIdenticalTo($call2))->isFalse()
				->boolean($call2->isIdenticalTo($call1))->isFalse()
			->if($call2 = new testedClass($function, array($arg3, $arg2, $arg1)))
			->then
				->boolean($call1->isIdenticalTo($call2))->isFalse()
				->boolean($call2->isIdenticalTo($call1))->isFalse()
		;
	}
}
