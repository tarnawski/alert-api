<?php declare(strict_types=1);

namespace App\Importer;

interface ImporterInterface
{
    public function import(string $name, string $path);
}
