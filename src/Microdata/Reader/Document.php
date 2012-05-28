<?php
/**
 * @package     Microdata
 * @author      Axel Etcheverry <axel@etcheverry.biz>
 * @copyright   Copyright (c) 2011 Axel Etcheverry (http://www.axel-etcheverry.com)
 * Displays     <a href="http://creativecommons.org/licenses/MIT/deed.fr">MIT</a>
 * @license     http://creativecommons.org/licenses/MIT/deed.fr    MIT
 * 
 * Based on MicrodataPHP
 */

/**
 * @namespace
 */
namespace Microdata\Reader;

/**
 * Extend the DOMDocument class with the Microdata API functions.
 */
class Document extends \DOMDocument 
{
    /**
     * Retrieves a list of microdata items.
     *
     * @return
     *   A DOMNodeList containing all top level microdata items.
     *
     * @todo Allow restriction by type string.
     */
    public function getItems()
    {
        // Return top level items.
        return $this->xpath()->query('//*[@itemscope and not(@itemprop)]');
    }

    /**
     * Creates a DOMXPath to query this document.
     *
     * @return
     *   DOMXPath object.
     */
    public function xpath()
    {
        return new \DOMXPath($this);
    }
}