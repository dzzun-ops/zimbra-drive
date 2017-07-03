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

namespace OCA\ZimbraDrive\Settings;

use OCP\AppFramework\Http\TemplateResponse;
use OCP\IConfig;
use OCP\Settings\ISettings;
use OCP\Template;

class Admin implements ISettings
{
    const SECTION_PRIORITY = 0;
    const SECTION_ID = 'zimbradrive';
    /** @var IConfig */
    private $config;

    /**
     * @param IConfig $config
     */
    public function __construct(IConfig $config)
    {
        $this->config = $config;
    }

    /**
     * @return TemplateResponse
     */
    public function getForm()
    {
        $appSettings = new AppSettings($this->config);
        $adminTemplate = new AdminTemplate($this->config, $appSettings);
        $template = $adminTemplate->getTemplate();
        return $template;
    }

    /**
     * @return string the section ID, e.g. 'sharing'
     */
    public function getSection()
    {
        return self::SECTION_ID;
    }

    /**
     * @return int whether the form should be rather on the top or bottom of
     * the admin section. The forms are arranged in ascending order of the
     * priority values. It is required to return a value between 0 and 100.
     *
     * keep the server setting at the top, right after "server settings"
     */
    public function getPriority()
    {
        return self::SECTION_PRIORITY;
    }

    /**
     * The panel controller method that returns a template to the UI
     * @since ownCloud 10.0
     * @return TemplateResponse | Template
     */
    public function getPanel()
    {
        return $this->getForm();
    }

    /**
     * A string to identify the section in the UI / HTML and URL
     * @since ownCloud 10.0
     * @return string
     */
    public function getSectionID()
    {
        return $this->getSection();
    }
}
