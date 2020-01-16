<?php

namespace WebEtDesign\FaqBundle;

use Symfony\Component\HttpKernel\Bundle\Bundle;
use WebEtDesign\FaqBundle\DependencyInjection\FaqBundleExtension;

class FaqBundle extends Bundle
{
    /**
     * @inheritDoc
     */
    public function getContainerExtension()
    {
        return new FaqBundleExtension();
    }

}
