<?php

namespace App\Tests\Service;

use App\Service\SaveArrayContentToFile;
use PHPUnit\Framework\TestCase;
use org\bovigo\vfs\vfsStream;
use Symfony\Component\Filesystem\Exception\FileNotFoundException;

class SaveArrayContentToFileTest extends TestCase
{
    private $vfs;

    protected $runTestInSeparateProcess;

    public function setUp()
    {
        $this->vfs = vfsStream::setup('root');

        $this->runTestInSeparateProcess = new SaveArrayContentToFile();
    }

    /**
     * @param $structure
     * @param $expected
     * @param $given
     * @param $mode
     * @dataProvider dataProvider
     */
    public function testIfContentOfTheFileWillCorrect($structure, $expected, $given, $mode)
    {
        vfsStream::create($structure, $this->vfs);

        $filesDir = vfsStream::url('root/reports');

        $this->runTestInSeparateProcess->save(
            $given,
            $filesDir,
            'report.xml',
            $mode);

        $contentAfterChanges = file(vfsStream::url('root/reports/report.xml'));

        $this->assertEquals($expected, $contentAfterChanges);
    }

    public function dataProvider()
    {
        return [
            [
                [
                    'reports' => []
                ],
                [
                    "test@test.com2\n",
                    "test@test.com3\n",
                    "test@test.com4\n",
                ],
                [
                    "test@test.com2\n",
                    "test@test.com3\n",
                    "test@test.com4\n",
                ],
                'w+'
            ],
            [
                [
                    'reports' => [
                        'report.xml' =>
                            "test@test.com5" . "\n".
                            "test@test.com6" . "\n".
                            "test@test.com7" . "\n"

                    ]
                ],
                [
                    "test@test.com2\n",
                    "test@test.com3\n",
                    "test@test.com4\n",
                ],
                [
                    "test@test.com2\n",
                    "test@test.com3\n",
                    "test@test.com4\n",
                ],
                'w+'
            ],
        ];
    }
}
