<?php

/***************************************************************
 *  Copyright notice
 *
 *  (c) 2013 Mato Ilic <info@matoilic.ch>
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

class Tx_T3chimp_MailChimp_Field_Action extends Tx_T3chimp_MailChimp_Field_Radio {
    /**
     * @var array
     */
    protected static $_choices = array('subscribe', 'unsubscribe');

    /**
     * @param array $definition
     * @param Tx_T3chimp_MailChimp_Form $form
     */
    public function __construct(array $definition, Tx_T3chimp_MailChimp_Form $form) {
        $definition['choices'] = self::$_choices;
        $definition['req'] = TRUE;
        parent::__construct($definition, $form);
    }

    public function getDefaultValue() {
        return self::$_choices[0];
    }

    public function getIsActionField() {
        return TRUE;
    }

    public function getLabel() {
        return 'Action';
    }

    public function getTag() {
        return 'FORM_ACTION';
    }

    public function setApiValue($value) { }
}
