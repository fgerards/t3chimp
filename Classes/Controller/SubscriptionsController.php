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

class Tx_T3chimp_Controller_SubscriptionsController extends Tx_Extbase_MVC_Controller_ActionController {
    /**
     * @var int
     */
    private $listId;

    /**
     * @var Tx_T3chimp_Service_MailChimp
     */
    private $mailChimpService;

    /**
     * @var Tx_T3chimp_Session_Provider
     */
    protected $session;

    protected function checkCsrfToken() {
        if($_SERVER['HTTP_X_CSRF_TOKEN'] !== $this->session->csrfToken && $_POST['CSRF_TOKEN'] !== $this->session->csrfToken) {
            throw new Exception('t3chimp: invalid CRSF token');
        }
    }

    public function initializeAction() {
        parent::initializeAction();

        if(!isset($this->session->csrfToken)) {
            $this->session->csrfToken = md5(rand() . $GLOBALS['TYPO3_CONF_VARS']['SYS']['encryptionKey']);
        }

        $this->response->addAdditionalHeaderData('<meta name="t3chimp:csrf-token" content="' . $this->session->csrfToken . '" />');
        $this->response->addAdditionalHeaderData('<meta name="t3chimp:lang" content="' . $GLOBALS['TSFE']->sys_language_uid . '" />');
        $this->response->addAdditionalHeaderData('<meta name="t3chimp:lang-iso" content="' . $GLOBALS['TSFE']->sys_language_isocode . '" />');
        $this->response->addAdditionalHeaderData('<meta name="t3chimp:pid" content="' . $GLOBALS['TSFE']->id . '" />');
    }


    public function indexAction() {
        if($this->settings['debug']) {
            echo '<!--';
            var_dump($this->mailChimpService->getFieldsFor($this->settings['subscriptionList']));
            echo '-->';
        }
        
        $this->view->assign('form', $this->mailChimpService->getForm());
    }

    /**
     * @param Tx_Extbase_Configuration_ConfigurationManager $configurationManager
     */
    public function injectConfigurationManager(Tx_Extbase_Configuration_ConfigurationManager $configurationManager) {
        $this->configurationManager = $configurationManager;
    }


    /**
     * @param Tx_T3chimp_Service_MailChimp $service
     */
    public function injectMailChimpService(Tx_T3chimp_Service_MailChimp $service) {
        $this->mailChimpService = $service;
    }

    /**
     * @param Tx_T3chimp_Provider_Session $provider
     */
    public function injectSessionProvider(Tx_T3chimp_Provider_Session $provider) {
        $this->session = $provider;
    }

    /**
     * @param Tx_T3chimp_Provider_Settings $provider
     */
    public function injectSettingsProvider(Tx_T3chimp_Provider_Settings $provider) {
        $this->settings = $provider->getAll();
    }



    public function processAction() {
        //$this->checkCsrfToken();

        $form = $this->mailChimpService->getFormFor($this->request->getArgument('list'));
        $form->bindRequest($this->request);

        if($form->isValid()) {
            try {
                $performedAction = $this->mailChimpService->saveForm($form);

                if($performedAction == Tx_T3chimp_Service_MailChimp::ACTION_SUBSCRIBE) {
                    if($this->settings['doubleOptIn']) {
                        $message = $this->translate('t3chimp.form.almostSubscribed');
                    } else {
                        $message = $this->translate('t3chimp.form.subscribed');
                    }
                } else if($performedAction == Tx_T3chimp_Service_MailChimp::ACTION_UPDATE) {
                    $message = $this->translate('t3chimp.form.updated');
                } else {
                    $message = $this->translate('t3chimp.form.unsubscribed');
                }
            } catch(Tx_T3chimp_Service_MailChimp_InvalidEmailException $ex) {
                $message = $this->translate('t3chimp.exception.invalidEmail');
            } catch(Tx_T3chimp_Service_MailChimp_Exception $ex) {
                $message = $ex->getMessage();
            }

            return json_encode(array('html' => $message), JSON_HEX_TAG | JSON_HEX_QUOT);
        }

        $this->view->assign('form', $form);

        return json_encode(array('html' => $this->view->render()), JSON_HEX_TAG | JSON_HEX_QUOT);
    }

    /**
     * @param $key the key for the label
     * @param null|array $arguments
     * @param string $default
     * @return string
     */
    protected function translate($key, $arguments = null, $default = 'MISSING TRANSLATION') {
        $extensionName = $this->request->getControllerExtensionName();
        $value = Tx_Extbase_Utility_Localization::translate($key, $extensionName, $arguments);

        return ($value != NULL) ? $value : $default;
    }
}
