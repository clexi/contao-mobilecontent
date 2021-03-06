<?php

/**
 * mobilecontent extension for Contao Open Source CMS
 *
 * @author  Kamil Kuzminski <https://github.com/qzminski>
 * @author  derhaeuptling <https://derhaeuptling.com>
 * @author  Martin Schwenzer <mail@derhaeuptling.com>
 * @license LGPL
 */

namespace Derhaeuptling\MobileContent\FrontendModule;

use Contao\BackendTemplate;
use Contao\Environment;
use Contao\Module;
use Contao\PageModel;
use Haste\Util\Url;

class MobileSwitchModule extends Module
{
    /**
     * Template
     * @var string
     */
    protected $strTemplate = 'mod_mobile_switch';

    /**
     * Root page
     * @var PageModel
     */
    private $rootPage;

    /**
     * Display a wildcard in the back end
     *
     * @return string
     */
    public function generate()
    {
        if (TL_MODE === 'BE') {
            $template = new BackendTemplate('be_wildcard');

            $template->wildcard = '### ' . utf8_strtoupper($GLOBALS['TL_LANG']['FMD']['mobile_switch'][0]) . ' ###';
            $template->title = $this->headline;
            $template->id = $this->id;
            $template->link = $this->name;
            $template->href = 'contao/main.php?do=themes&amp;table=tl_module&amp;act=edit&amp;id=' . $this->id;

            return $template->parse();
        }

        if (($this->rootPage = PageModel::findByPk($GLOBALS['objPage']->rootId)) === null
            || (!$this->rootPage->enableMobileDns && !$this->rootPage->mobileDnsBreakpointDetection)
        ) {
            return '';
        }

        return parent::generate();
    }

    /**
     * Generate the module
     */
    protected function compile()
    {
        if ($this->rootPage->enableMobileDns) {
            $url = preg_replace('@https?://[^/]+@', '', Environment::get('uri'));

            $this->Template->desktopUrl = ($this->rootPage->useSSL ? 'https://' : 'http://') . ($this->rootPage->desktopDns ?: $this->rootPage->dns) . $url;
            $this->Template->mobileUrl = ($this->rootPage->useSSL ? 'https://' : 'http://') . ($this->rootPage->mobileDns ?: $this->rootPage->dns) . $url;
            $this->Template->isMobile = Environment::get('host') === $this->rootPage->mobileDns;
        } else {
            $this->Template->desktopUrl = Url::addQueryString('toggle_view=desktop');
            $this->Template->mobileUrl = Url::addQueryString('toggle_view=mobile');
            $this->Template->isMobile = $GLOBALS['objPage']->isMobile;
        }

        $this->Template->autoRedirect = $this->rootPage->mobileDnsAutoRedirect ? true : false;
        $this->Template->breakpoint = false;

        if ($this->rootPage->mobileDnsBreakpointDetection) {
            $this->Template->breakpoint = $this->rootPage->mobileDnsBreakpoint;
        }
    }
}
