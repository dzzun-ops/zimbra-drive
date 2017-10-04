<?php
/**
 * Copyright 2017 Zextras Srl
 *
 * Licensed under the Apache License, Version 2.0 (the "License");
 * you may not use this file except in compliance with the License.
 * You may obtain a copy of the License at
 *
 *     http://www.apache.org/licenses/LICENSE-2.0
 *
 * Unless required by applicable law or agreed to in writing, software
 * distributed under the License is distributed on an "AS IS" BASIS,
 * WITHOUT WARRANTIES OR CONDITIONS OF ANY KIND, either express or implied.
 * See the License for the specific language governing permissions and
 * limitations under the License.
 */

namespace OCA\ZimbraDrive\Service;

use OCP\IConfig;


class ZimbraAuthentication
{
    const USER_BACKEND_VAR_NAME = 'user_backends';
    const ZIMBRA_USER_BACKEND_CLASS_VALUE = 'OCA\ZimbraDrive\Auth\ZimbraUsersBackend';
    private $logger;
    /**
     * @var IConfig
     */
    private $config;

    /**
     * @param IConfig $config
     * @param LogService $logger
     */
    public function __construct(
        IConfig $config,
        LogService $logger
    )
    {
        $this->logger = $logger;
        $this->config = $config;
    }

    public function enableZimbraAuthentication()
    {
        $userBackends = $this->config->getSystemValue(self::USER_BACKEND_VAR_NAME, array());

        $zimbraUserBackend = array(
            'class' => self::ZIMBRA_USER_BACKEND_CLASS_VALUE,
            'arguments' => array (),
        );
        $userBackends[] = $zimbraUserBackend;

        $this->config->setSystemValue(self::USER_BACKEND_VAR_NAME, $userBackends);
    }

    public function disableZimbraAuthentication()
    {
        $userBackends = $this->config->getSystemValue(self::USER_BACKEND_VAR_NAME, array());

        $userBackendsWithoutZimbra = array();
        foreach($userBackends as $userBackend)
        {
            if($userBackend['class'] !== self::ZIMBRA_USER_BACKEND_CLASS_VALUE)
            {
                $userBackendsWithoutZimbra[] = $userBackend;
            }
        }
        if(count($userBackendsWithoutZimbra) === 0)
        {
            $this->config->deleteSystemValue(self::USER_BACKEND_VAR_NAME);
        }else
        {
            $this->config->setSystemValue(self::USER_BACKEND_VAR_NAME, $userBackendsWithoutZimbra);
        }
    }

    public function isZimbraAuthenticationEnabled()
    {
        $userBackends = $this->config->getSystemValue(self::USER_BACKEND_VAR_NAME, array());
        foreach($userBackends as $userBackend){
            $class = $userBackend['class'];
            if(isset($class) && $class === ZimbraAuthentication::ZIMBRA_USER_BACKEND_CLASS_VALUE)
            {
                return true;
            }
        }
        return false;
    }
}