<?php

/**
 * mobilecontent extension for Contao Open Source CMS
 *
 * @author  Kamil Kuzminski <https://github.com/qzminski>
 * @author  derhaeuptling <https://derhaeuptling.com>
 * @author  Martin Schwenzer <mail@derhaeuptling.com>
 * @license LGPL
 */

/**
 * Add global callbacks
 */
$GLOBALS['TL_DCA']['tl_page']['config']['onload_callback'][] = ['Derhaeuptling\MobileContent\EventListener\DataContainer\PageListener', 'onLoadCallback'];

/**
 * Extend palettes
 */
\Haste\Dca\PaletteManipulator::create()
    ->addField('mobileDnsBreakpointDetection', 'dns_legend', \Haste\Dca\PaletteManipulator::POSITION_APPEND)
    ->addField('enableMobileDns', 'dns_legend', \Haste\Dca\PaletteManipulator::POSITION_APPEND)
    ->applyToPalette('root', 'tl_page');

/**
 * Add fields
 */
$GLOBALS['TL_DCA']['tl_page']['fields']['enableMobileDns'] = [
    'label'     => &$GLOBALS['TL_LANG']['tl_page']['enableMobileDns'],
    'exclude'   => true,
    'inputType' => 'checkbox',
    'eval'      => ['submitOnChange' => true, 'tl_class' => 'clr'],
    'sql'       => "char(1) NOT NULL default ''",
];

$GLOBALS['TL_DCA']['tl_page']['fields']['mobileDns'] = [
    'label'         => &$GLOBALS['TL_LANG']['tl_page']['mobileDns'],
    'exclude'       => true,
    'inputType'     => 'text',
    'eval'          => [
        'mandatory'      => true,
        'rgxp'           => 'url',
        'decodeEntities' => true,
        'maxlength'      => 255,
        'tl_class'       => 'w50',
    ],
    'save_callback' => [['tl_page', 'checkDns']],
    'sql'           => "varchar(255) NOT NULL default ''",
];

$GLOBALS['TL_DCA']['tl_page']['fields']['mobileDnsAutoRedirect'] = [
    'label'         => &$GLOBALS['TL_LANG']['tl_page']['mobileDnsAutoRedirect'],
    'exclude'       => true,
    'inputType'     => 'checkbox',
    'eval'          => ['tl_class' => 'clr'],
    'sql'           => "char(1) NOT NULL default ''",
];

$GLOBALS['TL_DCA']['tl_page']['fields']['mobileDnsBreakpointDetection'] = [
    'label'     => &$GLOBALS['TL_LANG']['tl_page']['mobileDnsBreakpointDetection'],
    'exclude'   => true,
    'inputType' => 'checkbox',
    'eval'      => ['submitOnChange' => true, 'tl_class' => 'clr'],
    'sql'       => "char(1) NOT NULL default ''",
];

$GLOBALS['TL_DCA']['tl_page']['fields']['mobileDnsBreakpoint'] = [
    'label'     => &$GLOBALS['TL_LANG']['tl_page']['mobileDnsBreakpoint'],
    'exclude'   => true,
    'inputType' => 'text',
    'eval'      => ['rgxp' => 'digit', 'tl_class' => 'clr'],
    'sql'       => "varchar(4) NOT NULL default ''",
];

$GLOBALS['TL_DCA']['tl_page']['fields']['mobileDnsExplanation'] = [
    'exclude'              => true,
    'input_field_callback' => [
        'Derhaeuptling\MobileContent\EventListener\DataContainer\PageListener',
        'generateExplanation',
    ],
];
