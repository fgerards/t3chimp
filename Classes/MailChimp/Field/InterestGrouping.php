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

namespace MatoIlic\T3Chimp\MailChimp\Field;

use MatoIlic\T3Chimp\MailChimp\MailChimpException;

class InterestGrouping extends Checkboxes {
    public function getApiValue() {
        //commas in interest group names should be escaped with a backslash
        $selection = str_replace(',', '\\,', $this->getValue());
        return implode(',', $selection);
    }


    public function getGroupingId() {
        return $this->definition['groupingId'];
    }

    public function getDisplayAsDropdown() {
        return $this->definition['form_field'] == 'dropdown';
    }

    public function getDisplayAsCheckboxes() {
        return $this->definition['form_field'] == 'checkboxes';
    }

    public function getDisplayAsRadios() {
        return $this->definition['form_field'] == 'radio';
    }

    public function getIsHidden() {
        return $this->definition['form_field'] == 'hidden';
    }

    public function getIsInterestGroup() {
        return TRUE;
    }

    public function getTag() {
        return $this->definition['name'];
    }

    public function setApiValue($value) {
        if(strlen($value) == 0) {
            return;
        }

        if($this->getDisplayAsCheckboxes()) {
            $values = preg_split('/(?<!\\\\),/', $value);
            for($i = 0, $n = count($values); $i < $n; $i++) {
                $values[$i] = trim(str_replace('\,', ',', $values[$i]));
            }

            $this->setValue($values);
        } else {
            $this->setValue($value);
        }
    }

    public function setValue($value) {
        if($value != NULL && !is_array($value)) {
            parent::setValue(array($value));
            return;
        }

        if(!$this->getDisplayAsCheckboxes() && !$this->getIsHidden() && count($value) > 1) {
            throw new MailChimpException('Interest groupings with a field type other than checkboxes can only have one value');
        }

        parent::setValue($value);
    }
}
