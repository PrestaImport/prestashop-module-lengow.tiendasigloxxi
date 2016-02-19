<?php
/**
 * Copyright 2016 Lengow SAS.
 *
 * Licensed under the Apache License, Version 2.0 (the "License"); you may
 * not use this file except in compliance with the License. You may obtain
 * a copy of the License at
 *
 *   http://www.apache.org/licenses/LICENSE-2.0
 *
 * Unless required by applicable law or agreed to in writing, software
 * distributed under the License is distributed on an "AS IS" BASIS, WITHOUT
 * WARRANTIES OR CONDITIONS OF ANY KIND, either express or implied. See the
 * License for the specific language governing permissions and limitations
 * under the License.
 *
 * @author    Team Connector <team-connector@lengow.com>
 * @copyright 2016 Lengow SAS
 * @license   http://www.apache.org/licenses/LICENSE-2.0
 */


class LengowTranslation
{
    protected static $translation = null;
    protected static $fallbackTranslation = null;
    public $fallbackIsoCode = 'en';

    public function __construct()
    {

    }

    /**
     * v3-test
     * Translate message
     * @param $message localization key
     * @param array $args replace word in string
     * @return mixed
     */
    public function t($message, $args = array())
    {
        if (self::$translation === null) {
            $this->loadFile();
        }
        if (isset(self::$translation[$message])) {
            return $this->translateFinal(self::$translation[$message], $args);
        } else {
            if (self::$fallbackTranslation === null) {
                $this->loadFile(true);
            }
            if (isset(self::$fallbackTranslation[$message])) {
                return $this->translateFinal(self::$fallbackTranslation[$message], $args);
            } else {
                return 'Missing Translation ['.$message.']';
            }
        }
    }

    /**
     * v3-test
     * Translate string
     * @param $text
     * @param $args
     * @return string Final Translate string
     */
    protected function translateFinal($text, $args)
    {
        if ($args) {
            return vsprintf($text, $args);
        } else {
            return $text;
        }
    }

    /**
     * v3-test
     * Load csv file
     * @param bool $fallback use fallback translation
     * @param string $filename file location
     * @return boolean
     */
    public function loadFile($fallback = false, $filename = null)
    {
        $isoCode = $fallback ? $this->fallbackIsoCode : Context::getContext()->language->iso_code;
        if (!$filename) {
            $filename = _PS_MODULE_DIR_.'lengow'.DIRECTORY_SEPARATOR.'translations'.
                DIRECTORY_SEPARATOR.$isoCode.'.csv';
        }
        $translation = array();
        if (file_exists($filename)) {
            if (($handle = fopen($filename, "r")) !== false) {
                while (($data = fgetcsv($handle, 1000, "|")) !== false) {
                    $translation[$data[0]] = $data[1];
                }
                fclose($handle);
            }
        }

        if ($fallback) {
            self::$fallbackTranslation = $translation;
        } else {
            self::$translation = $translation;
        }
        return count($translation)>0;
    }
}
