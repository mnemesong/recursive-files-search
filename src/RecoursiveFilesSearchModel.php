<?php

namespace Pantagruel74\RecursiveFilesSearch;

class RecoursiveFilesSearchModel
{
    protected $path;

    /**
     * @param string $path
     */
    public function __construct(string $path)
    {
        $this->path = $path;
    }

    /**
     * @return string[]
     */
    public function findFiles(): array
    {
        return $this->scanDir($this->normalizePath($this->path));
    }

    /**
     * @param string $path
     * @return string[]
     */
    protected function scanDir(string $path): array
    {
        $items = scandir($path);
        $items = array_filter($items, function ($i) {return !in_array($i, ['.', '..']);});
        $items = array_map(function ($i) use ($path) {
            return $path . DIRECTORY_SEPARATOR . $i;
        }, $items);
        $result = [];
        foreach ($items as $i)
        {
            if(is_dir($i)) {
                $result = array_merge($result, $this->scanDir($i));
            } else {
                $result[] = $i;
            }
        }
        return $result;
    }

    /**
     * @param string $path
     * @return string
     */
    protected function normalizePath(string $path): string
    {
        $ds = DIRECTORY_SEPARATOR;
        $divPath = explode($ds, $path);
        if(empty(end($divPath))) array_pop($divPath);
        return implode($ds, $divPath);
    }
}