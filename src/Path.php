<?php

/**
 * Class Path
 */
class Path
{
    private const SEPARATOR = '/';

    /**
     * @var array
     */
    private $currentPath;


    /**
     * Path constructor.
     * @param string $path
     * @throws Exception
     */
    public function __construct(string $path)
    {
        if (!$this->isAbsolute($path)) {
            throw new \Exception();
        }

        $path = $this->sanitify($path);
        $paths = $this->assertIsValidPath($path);
        $this->setAbsolutePath($paths);
    }


    /**
     * @return string
     */
    public function getCurrentPath(): string
    {
        return self::SEPARATOR . implode(self::SEPARATOR, $this->currentPath);
    }


    /**
     * @param string $newPath
     * @throws Exception
     */
    public function cd(string $newPath): void
    {
        if ($this->isAbsolute($newPath)) {
            $newPath = $this->sanitify($newPath);
            $newPath = $this->assertIsValidPath($newPath);
            $this->setAbsolutePath($newPath);
        } else {
            $newPath = $this->sanitify($newPath);
            $newPath = $this->assertIsValidPath($newPath);

            foreach ($newPath as $path) {
                if ($path === '..') {
                    array_pop($this->currentPath);
                } else {
                    array_push($this->currentPath, $path);
                }
            }
        }
    }

    /**
     * @param array $paths
     */
    private function setAbsolutePath(array $paths)
    {
        $this->currentPath = $paths;
    }

    /**
     * @param string $path
     * @return array
     * @throws Exception
     */
    private function assertIsValidPath(string $path)
    {
        $paths = explode(self::SEPARATOR, $path);

        foreach ($paths as $dirName) {
            if (!preg_match('/^(\.\.)$/', $dirName) && preg_match('/[^a-z]+/i', $dirName)) {
                throw new \Exception();
            }
        }

        return $paths;
    }

    /**
     * @param string $path
     * @return string
     */
    private function sanitify(string $path): string
    {
        return trim($path, '\t\n\r' . self::SEPARATOR);
    }

    /**
     * @param $path
     * @return bool
     */
    private function isAbsolute($path): bool
    {
        return substr($path, 0, 1) === self::SEPARATOR;
    }
}
