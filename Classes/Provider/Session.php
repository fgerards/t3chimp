<?php

/***************************************************************
 *  Copyright notice
 *
 *  (c) 2014 Mato Ilic <info@matoilic.ch>
 *  All rights reserved
 *
 *  This script is part of the TYPO3 project. The TYPO3 project is
 *  free software; you can redistribute it and/or modify
 *  it under the terms of the GNU General Public License as published by
 *  the Free Software Foundation; either version 2 of the License, or
 *  (at your option) any later version.
 *
 *  The GNU General Public License can be found at
 *  http://www.gnu.org/copyleft/gpl.html.
 *  A copy is found in the textfile GPL.txt and important notices to the license
 *  from the author is found in LICENSE.txt distributed with these scripts.
 *
 *
 *  This script is distributed in the hope that it will be useful,
 *  but WITHOUT ANY WARRANTY; without even the implied warranty of
 *  MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
 *  GNU General Public License for more details.
 *
 *  This copyright notice MUST APPEAR in all copies of the script!
 ***************************************************************/

namespace MatoIlic\T3Chimp\Provider;

use TYPO3\CMS\Core\SingletonInterface;

class Session implements SingletonInterface {
    const KEY = 'tx_t3chimp';

    /**
     * @var string
     */
    private $context;

    /**
     * @var tslib_feUserAuth
     */
    private $feUser;

    /**
     * @var array
     */
    private $sessionData = array();

    public function __construct() {
        $this->feUser = $GLOBALS['TSFE']->fe_user;
        $data = (is_object($this->feUser)) ? $this->feUser->getKey('ses', 't3chimp') : array();

        if($data != NULL) {
            $this->sessionData = unserialize($data);
        }
    }

    public function __get($key) {
        $this->ensureDataArray();
        return $this->sessionData[$this->context][$key];
    }

    public function __isset($key) {
        $this->ensureDataArray();
        return isset($this->sessionData[$this->context][$key]);
    }

    public function __set($key, $value) {
        $this->ensureDataArray();
        $this->sessionData[$this->context][$key] = $value;
        if(is_object($this->feUser)) {
            $this->feUser->setKey('ses', self::KEY, serialize($this->sessionData));
            $this->feUser->storeSessionData();
        }
    }

    public function __unset($key) {
        $this->ensureDataArray();
        unset($this->sessionData[$this->context][$key]);
    }

    private function ensureDataArray() {
        if(!array_key_exists($this->context, $this->sessionData)) {
            $this->sessionData[$this->context] = array();
        }
    }

    public function setContext($context) {
        $this->context = $context;
    }
}
