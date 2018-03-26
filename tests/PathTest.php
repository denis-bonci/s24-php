<?php
declare(strict_types=1);

use PHPUnit\Framework\TestCase;

/**
 * Class PathTest
 */
class PathTest extends TestCase
{
    /**
     * @var Path
     */
    private $path;

    protected function setUp(): void
    {
        $this->path = new Path('/a/b/c/d');
    }

    /**
     * @test
     */
    public function canBeCreated(): void
    {
        $this->assertInstanceOf(Path::class, $this->path);
    }

    /**
     * @test
     * @dataProvider validCharactersDataProvider
     * Write a function that provides change directory (cd) function for an abstract file system
     *
     * @param $validChar
     */
    public function canChangeDirectory($validChar): void
    {
        $this->path->cd("../$validChar");
        $this->assertEquals("/a/b/c/$validChar", $this->path->getCurrentPath());
    }

    /**
     * @return array
     */
    public function validCharactersDataProvider(): array
    {
        return [
            ['x'],
            ['e'],
            ['T'],
            ['p'],
            ['PP'],
            ['asd'],
            ['gHJ'],
        ];
    }

    /**
     * @test
     * root path is '/'
     */
    public function rootPath(): void
    {
        $this->path->cd('../../../../../../../../');
        $this->assertEquals('/', $this->path->getCurrentPath());
    }

    /**
     * @test
     * @expectedException \Exception
     * path separator is '/'
     */
    public function pathSeparatorIsSlashCharacters(): void
    {
        $this->path->cd('e\f\g');
    }

    /**
     * @test
     * parent directory is addressable as '..'
     */
    public function parentDirectoryIsAddressableAsDoubleDot(): void
    {
        $this->path->cd('..');
        $this->assertEquals('/a/b/c', $this->path->getCurrentPath());
    }

    /**
     * @test
     * @dataProvider invalidCharactersDataProvider
     * @expectedException \Exception
     * directory names consist only of English alphabet letters (A-Z and a-z)
     * the function will not be passed any invalid paths
     *
     * @param $invalidChar
     */
    public function directoryNamesConsistOnlyEnglishAlphaCharacters($invalidChar): void
    {
        $this->path->cd($invalidChar);
    }

    /**
     * @return array
     */
    public function invalidCharactersDataProvider(): array
    {
        return [
            ['è'],
            ['ò'],
            ['ù'],
            ['&'],
            ['!'],
            ['ì'],
            ['+'],
            ['%'],
            ['_'],
            ['-'],
            ['0'],
            ['007'],
            ['h.w'],
            [' '],
        ];
    }

    /**
     * @test
     */
    public function newAbsolutePath(): void
    {
        $this->path->cd('/z/v/u');
        $this->assertEquals('/z/v/u', $this->path->getCurrentPath());
    }

    /**
     * @test
     */
    public function lastCharSlash(): void
    {
        $this->path->cd('../g/');
        $this->assertEquals('/a/b/c/g', $this->path->getCurrentPath());
    }

    /**
     * @test
     * @expectedException \Exception
     */
    public function initialPathIsNotAbs(): void
    {
        new Path('a/b/c');
    }
}
