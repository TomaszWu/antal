<?php

namespace App\Tests\Service;

use App\Service\CreatePath;
use org\bovigo\vfs\vfsStream;
use PHPUnit\Framework\TestCase;
use Symfony\Component\DependencyInjection\ParameterBag\ParameterBagInterface;

class CreatePathTest extends TestCase
{
    private $paramsMock;

    private $vfs;

    public function setUp()
    {
        $this->vfs = vfsStream::setup('root');

        $this->paramsMock = $this->getMockBuilder(ParameterBagInterface::class)
            ->disableOriginalConstructor()
            ->getMock();

        $this->paramsMock->expects($this->any())
            ->method('get')
            ->willReturn(vfsStream::url('root'));

    }

    public function testIfPathBuildCorrectly()
    {
        $pathToTest = 'test/if/created';

        $createPath = new CreatePath($this->paramsMock);

        $createdDir = $createPath->create($pathToTest);

        $this->assertTrue(file_exists($createdDir));
    }
}
