<?php

use PHPUnit\Framework\TestCase;
use piyo2\util\path\Path;

final class PathJoinTest extends TestCase
{
	/**
	 * @test
	 */
	public function testBasic(): void
	{
		$DS = DIRECTORY_SEPARATOR;

		$this->assertEquals('abc' . $DS . 'def', Path::join('abc', 'def'));
		$this->assertEquals('abc' . $DS . 'def', Path::join('abc/', 'def'));
		$this->assertEquals('abc' . $DS . 'def', Path::join('abc', '/def'));
		$this->assertEquals($DS . 'abc' . $DS . 'def', Path::join('/abc', 'def'));
		$this->assertEquals('abc' . $DS . 'def' . $DS . 'ghi', Path::join('abc/', 'def', '/ghi'));
		$this->assertEquals('abc' . $DS . 'def' . $DS . 'ghi', Path::join('abc/def', 'ghi'));
		$this->assertEquals('abc' . $DS . 'def' . $DS . 'ghi', Path::join('abc\\def', 'ghi'));
		$this->assertEquals('abc.ghi', Path::join('', 'abc.ghi'));
		$this->assertEquals('abc' . $DS . '0' . $DS . 'def.ghi', Path::join('abc', '0', 'def.ghi'));
	}

	/**
	 * @test
	 */
	public function testEmpty(): void
	{
		$DS = DIRECTORY_SEPARATOR;

		$this->assertEquals('', Path::join('', ''));
		$this->assertEquals($DS, Path::join('', '/'));
	}

	/**
	 * @test
	 */
	public function testStartsWithSlash(): void
	{
		$DS = DIRECTORY_SEPARATOR;

		$this->assertEquals('a', Path::join('a', ''));
		$this->assertEquals($DS . 'a', Path::join('/', 'a'));
		$this->assertEquals($DS . 'a', Path::join('/', '/a'));
		$this->assertEquals($DS . 'a' . $DS . 'b', Path::join('/a', 'b'));
	}

	/**
	 * @test
	 */
	public function testEndsWithSlash(): void
	{
		$DS = DIRECTORY_SEPARATOR;

		$this->assertEquals('a' . $DS, Path::join('a', '/'));
		$this->assertEquals('a' . $DS . 'b', Path::join('a', 'b'));
		$this->assertEquals('a' . $DS . 'b' . $DS, Path::join('a', 'b/'));
		$this->assertEquals($DS, Path::join('/', '/'));
	}

	/**
	 * @test
	 */
	public function testDot(): void
	{
		$DS = DIRECTORY_SEPARATOR;

		$this->assertEquals('/', Path::join('/', '.'));
		$this->assertEquals('.', Path::join('.'));
		$this->assertEquals('.', Path::join('.', '.'));
		$this->assertEquals('.' . $DS, Path::join('.', '/'));
		$this->assertEquals('.' . $DS, Path::join('./', './'));
		$this->assertEquals('abc' . $DS . 'def.ghi', Path::join('abc', './def.ghi'));
		$this->assertEquals('.' . $DS . 'abc' . $DS . 'def.ghi', Path::join('./abc', './def.ghi'));
	}

	/**
	 * @test
	 */
	public function testParent(): void
	{
		$DS = DIRECTORY_SEPARATOR;

		$this->assertEquals('..' . $DS . 'abc.def', Path::join('..', 'abc.def'));
		$this->assertEquals('def.ghi', Path::join('abc', '../def.ghi'));
		$this->assertEquals('..' . $DS . 'def.ghi', Path::join('abc', '..', '../def.ghi'));
		$this->assertEquals($DS . 'abc' . $DS . 'ghi', Path::join('/abc/def', '../ghi'));
		$this->assertEquals($DS . 'abc' . $DS . 'mno' . $DS . 'pqr', Path::join('/abc/def/ghi', '././../jkl', '../../mno/pqr'));
	}
}
