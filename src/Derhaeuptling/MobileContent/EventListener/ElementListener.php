<?php

/**
 * mobilecontent extension for Contao Open Source CMS
 *
 * @author  Kamil Kuzminski <https://github.com/qzminski>
 * @author  derhaeuptling <https://derhaeuptling.com>
 * @author  Martin Schwenzer <mail@derhaeuptling.com>
 * @license LGPL
 */

namespace Derhaeuptling\MobileContent\EventListener;

use Contao\Model;

class ElementListener
{
    /**
     * Return true if the element is visible
     *
     * @param Model $element
     * @param bool  $visible
     *
     * @return bool
     */
    public function onIsVisibleElement(Model $element, $visible)
    {
        if (TL_MODE === 'FE' && $visible) {
            $visible = $this->getElementVisibility($element);
        }

        return $visible;
    }

    /**
     * Get the element visibility
     *
     * @param Model $element
     *
     * @return bool
     */
    private function getElementVisibility(Model $element)
    {
        $isMobile = $GLOBALS['objPage']->isMobile;

        // Hide on mobile
        if ($isMobile && $element->hideOnMobile) {
            return false;
        }

        // Hide on desktop
        if (!$isMobile && $element->hideOnDesktop) {
            return false;
        }

        return true;
    }
}
