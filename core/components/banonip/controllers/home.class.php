<?php

/**
 * The home manager controller for BanOnIP.
 *
 */
class BanOnIPHomeManagerController extends modExtraManagerController
{
    /** @var BanOnIP $BanOnIP */
    public $BanOnIP;


    /**
     *
     */
    public function initialize()
    {
        $this->BanOnIP = $this->modx->getService('BanOnIP', 'BanOnIP', MODX_CORE_PATH . 'components/banonip/model/');
        #$this->BanOnIP = $this->modx->getService('BanOnIP', 'BanOnIP', MODX_CORE_PATH . 'components/banonip/model/');
        parent::initialize();
    }


    /**
     * @return array
     */
    public function getLanguageTopics()
    {
        return ['banonip:default'];
    }


    /**
     * @return bool
     */
    public function checkPermissions()
    {
        return true;
    }


    /**
     * @return null|string
     */
    public function getPageTitle()
    {
        return $this->modx->lexicon('banonip');
    }


    /**
     * @return void
     */
    public function loadCustomCssJs()
    {
        $this->addCss('../BanOnIP/assets/components/banonip/css/mgr/main.css');
        $this->addJavascript('../BanOnIP/assets/components/banonip/js/mgr/dist/app.js');
    /*
        $this->addCss($this->BanOnIP->config['cssUrl'] . 'mgr/main.css');
        $this->addCss($this->BanOnIP->config['jsUrl'] . 'mgr/dist/css/app.css');
        $this->addCss($this->BanOnIP->config['jsUrl'] . 'mgr/dist/css/chunk-vendors.css');

        $this->addJavascript($this->BanOnIP->config['jsUrl'] . 'mgr/dist/js/chunk-vendors.js');
        $this->addJavascript($this->BanOnIP->config['jsUrl'] . 'mgr/dist/js/app.js');
    */
        $this->addHtml('<script type="text/javascript">
            var BanOnIP = {
                connector_url: "' . $this->BanOnIP->config['connectorUrl'] . '",
                modAuth: "' . $this->modx->user->getUserToken($this->modx->context->get('key')) . '",
            };
        </script>');

    }


    /**
     * @return string
     */
    public function getTemplateFile()
    {
        $this->content .= '<div id="banonip-panel-home-div"></div>';
        return '';
    }
}