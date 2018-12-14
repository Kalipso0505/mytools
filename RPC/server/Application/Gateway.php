<?php
/**
 * Gateway.php
 * Namespace: Application\Gateway
 * Author: aachim
 * Created: 24.07.13
 * This file is property of net mobile AG
 */

namespace Application;

use Simplon\Jr\Interfaces\InterfaceGateway;

class Gateway extends \Simplon\Jr\Gateway implements InterfaceGateway
{
    /**
     * @return bool
     */
    public function isEnabled()
    {
        return TRUE;
    }

    // ##########################################

    /**
     * @return bool
     */
    public function getNamespace()
    {
        return __NAMESPACE__;
    }

    // ##########################################

    /**
     * @return bool
     */
    public function hasAuth()
    {
        return FALSE;
    }

    // ##########################################

    /**
     * @return bool
     */
    public function getValidServices()
    {
        return ['web.Base.hello'];
    }
}