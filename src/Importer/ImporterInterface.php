<?php declare(strict_types=1);

namespace AlertApi\Importer;

interface ImporterInterface
{
    public function import(string $name, string $path);
}
