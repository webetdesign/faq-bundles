<?php

namespace WebEtDesign\FaqBundle;

use Symfony\Component\DependencyInjection\Extension\ExtensionInterface;
use Symfony\Component\HttpKernel\Bundle\Bundle;
use WebEtDesign\FaqBundle\DependencyInjection\FaqBundleExtension;

class FaqBundle extends Bundle
{
    public function getContainerExtension(): ?ExtensionInterface
    {
        return new FaqBundleExtension();
    }
}
