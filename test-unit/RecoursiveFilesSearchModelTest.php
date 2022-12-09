<?php

namespace Pantagruel74\RecursiveFilesSearchTestUnit;

use Pantagruel74\RecursiveFilesSearch\RecoursiveFilesSearchModel;
use PHPUnit\Framework\TestCase;

class RecoursiveFilesSearchModelTest extends TestCase
{
    /**
     * @return void
     */
    public function testSearchWithoutNormalize(): void
    {
        $ds = DIRECTORY_SEPARATOR;
        $filesDir = __DIR__ . $ds . 'files';
        $model = new RecoursiveFilesSearchModel($filesDir);
        $files = $model->findFiles();
        $nominalFiles = [
            $filesDir . $ds . 'file1.txt',
            $filesDir . $ds . 'dir3.dir' . $ds . 'file1.txt',
            $filesDir . $ds . 'dir3.dir' . $ds . 'file2.jpg',
        ];
        $this->assertEquals([], array_diff($files, $nominalFiles));
        $this->assertEquals([], array_diff($nominalFiles, $files));
    }

    /**
     * @return void
     */
    public function testSearchWithNormalize(): void
    {
        $ds = DIRECTORY_SEPARATOR;
        $filesDir = __DIR__ . $ds . 'files';
        $model = new RecoursiveFilesSearchModel($filesDir . $ds);
        $files = $model->findFiles();
        $nominalFiles = [
            $filesDir . $ds . 'file1.txt',
            $filesDir . $ds . 'dir3.dir' . $ds . 'file1.txt',
            $filesDir . $ds . 'dir3.dir' . $ds . 'file2.jpg',
        ];
        $this->assertEquals([], array_diff($files, $nominalFiles));
        $this->assertEquals([], array_diff($nominalFiles, $files));
    }

    /**
     * @return void
     */
    public function testNormalizePath(): void
    {
        $ds = DIRECTORY_SEPARATOR;
        $filesDir = __DIR__ . $ds . 'files';
        $model = new class($filesDir) extends RecoursiveFilesSearchModel
        {
            public function normalizePath(string $path): string
            {
                return parent::normalizePath($path);
            }
        };
        $this->assertEquals($filesDir, $model->normalizePath($filesDir . $ds));
    }
}