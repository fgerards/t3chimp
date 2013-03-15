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

class Tx_T3chimp_ViewHelpers_Form_Birthday_MonthViewHelper extends Tx_T3chimp_ViewHelpers_Form_NumberViewHelper {
    protected function getName() {
        return $this->prefixFieldName($this->getField()->getName()) . '[month]';
    }

    protected function getValue() {
        return $this->getField()->getMonth();
    }

    /**
     * Renders the month field.
     *
     * @param boolean $required If the field is required or not
     * @param string $placeholder A string used as a placeholder for the value to enter
     * @return string
     */
    public function render($required = FALSE, $placeholder = NULL) {
        return parent::render($required, $placeholder, 1, 12, 1);
    }
}
