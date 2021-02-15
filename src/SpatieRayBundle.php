<?php

namespace Spatie\RayBundle;

use Spatie\RayBundle\DependencyInjection\SpatieRayExtension;
use Symfony\Component\HttpKernel\Bundle\Bundle;

class SpatieRayBundle extends Bundle
{
    public function getContainerExtension()
    {
        if (null === $this->extension) {
            $this->extension = new SpatieRayExtension();
        }

        return $this->extension;
    }
}
