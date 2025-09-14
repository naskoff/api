<?php

declare(strict_types=1);

namespace App;

use Symfony\Bundle\FrameworkBundle\Kernel\MicroKernelTrait;
use Symfony\Component\HttpKernel\Kernel as BaseKernel;

class Kernel extends BaseKernel
{
    use MicroKernelTrait;

    public function getLogDir(): string
    {
        if (!isset($_SERVER['VERCEL'])) {
            return parent::getLogDir();
        }

        return '/tmp/logs/';
    }

    public function getCacheDir(): string
    {
        if (!isset($_SERVER['VERCEL'])) {
            return parent::getCacheDir();
        }

        return '/tmp/cache/';
    }
}
