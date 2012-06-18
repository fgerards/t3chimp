<?php

/***************************************************************
 *  Copyright notice
 *
 *  (c) 2012 Mato Ilic <info@matoilic.ch>
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

class Tx_T3chimp_Provider_Session implements t3lib_Singleton {
    const KEY = 'tx_t3chimp';

    /**
     * @var tslib_feUserAuth
     */
    private $feUser;

    /**
     * @var array
     */
    private $sessionData = array();

    public function __construct() {
        //hold a reference to the fe_user object so the destructor has guaranteed access to it
        $this->feUser = $GLOBALS['TSFE']->fe_user;
        $data = $this->feUser->getKey('ses', self::KEY);

        if($data != null) {
            $this->sessionData = unserialize($data);
        }
    }

    public function __destruct() {
        $this->feUser->setKey('ses', self::KEY, serialize($this->sessionData));
        $this->feUser->storeSessionData();
    }

    public function __get($key) {
        return $this->sessionData[$key];
    }

    public function __isset($key) {
        return isset($this->sessionData[$key]);
    }

    public function __set($key, $value) {
        $this->sessionData[$key] = $value;
    }

    public function __unset($key) {
        unset($this->sessionData[$key]);
    }
}
