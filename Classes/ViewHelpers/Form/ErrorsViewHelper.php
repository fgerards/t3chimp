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

class Tx_T3chimp_ViewHelpers_Form_ErrorsViewHelper extends Tx_Fluid_Core_ViewHelper_AbstractViewHelper {
    /**
     * @var Tx_T3chimp_MailChimp_Field
     */
    protected $field = null;

    /**
     * @var Tx_T3chimp_MailChimp_Form
     */
    private $form = null;

    /**
     * @param string $property
     * @return Tx_T3chimp_MailChimp_Field
     * @throws Exception
     */
    protected function getField($property) {
        if($this->field === null) {
            $this->field = $this->getForm()->getField($property);
            if($this->field === null) {
                throw new Exception('Unknown field ' . htmlentities($property) . ' referenced in template');
            }
        }

        return $this->field;
    }

    /**
     * @return Tx_T3chimp_MailChimp_Form
     */
    protected function getForm() {
        if($this->form === null) {
            if(class_exists('TYPO3\CMS\Fluid\ViewHelpers\FormViewHelper')) {
                $this->form = $this->viewHelperVariableContainer->get('TYPO3\\CMS\\Fluid\\ViewHelpers\\FormViewHelper', 'formObject');
            } else { // <6.0 compatibility
                $this->form = $this->viewHelperVariableContainer->get('Tx_Fluid_ViewHelpers_FormViewHelper', 'formObject');
            }
        }

        return $this->form;
    }

    /**
     * @param string $property
     * @return string
     */
    public function render($property) {
        $errors = $this->getField($property)->getErrors();

        if(count($errors) > 0) {
            $renderer = new Tx_Fluid_ViewHelpers_RenderViewHelper();
            $renderer->setControllerContext($this->controllerContext);
            $renderer->setTemplateVariableContainer($this->templateVariableContainer);
            $renderer->setViewHelperVariableContainer($this->viewHelperVariableContainer);

            return $renderer->render(null, 'Errors', array('errors' => $errors));
        }

        return '';
    }
}
