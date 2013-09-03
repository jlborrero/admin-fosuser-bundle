<?php

namespace Jlbs\AdminFOSUserBundle;

use Symfony\Component\HttpKernel\Bundle\Bundle;

class JlbsAdminFOSUserBundle extends Bundle
{
    public function getParent()
    {
        return 'FOSUserBundle';
    }
}
