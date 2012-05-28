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
namespace Microdata;

/**
 * Extracts microdata from HTML.
 *
 * Currently supported formats:
 *   - PHP object
 *   - JSON
 */
class Reader
{
    public $dom;

    /**
     * Constructs a Reader object.
     *
     * @param $url
     *   The url of the page to be parsed.
     */
    public function __construct($url)
    {
        $dom = new Reader\Document($url);
        $dom->registerNodeClass('DOMDocument', '\Microdata\Reader\Document');
        $dom->registerNodeClass('DOMElement', '\Microdata\Reader\Element');
        $dom->preserveWhiteSpace = false;
        @$dom->loadHTMLFile($url);

        $this->dom = $dom;
    }

    /**
     * Retrieve microdata as a PHP object.
     *
     * @return
     *   An object with an 'items' property, which is an array of top level
     *   microdata items as objects with the following properties:
     *   - type: An array of itemtype(s) for the item, if specified.
     *   - id: The itemid of the item, if specified.
     *   - properties: An array of itemprops. Each itemprop is keyed by the
     *     itemprop name and has its own array of values. Values can be strings
     *     or can be other items, represented as objects.
     *
     * @todo MicrodataJS allows callers to pass in a selector for limiting the
     *   parsing to one section of the document. Consider adding such
     *   functionality.
     */
    public function read()
    {
        $result = new \stdClass();
        $result->items = array();
        foreach ($this->dom->getItems() as $item) {
            array_push($result->items, $this->_getObject($item, array()));
        }
        return $result;
    }

    /**
     * Retrieve microdata in JSON format.
     *
     * @return
     *   See obj().
     *
     * @todo MicrodataJS allows callers to pass in a function to format the JSON.
     * Consider adding such functionality.
     */
    public function json()
    {
        return json_encode($this->read());
    }

    /**
     * Helper function.
     *
     * In MicrodataJS, this is handled using a closure. PHP 5.3 allows closures,
     * but cannot use $this within the closure. PHP 5.4 reintroduces support for
     * $this. When PHP 5.3/5.4 are more widely supported on shared hosting,
     * this function could be handled with a closure.
     */
    protected function _getObject($item, $memory)
    {
        $result = new \stdClass();
        $result->properties = array();
  
        // Add itemtype.
        if ($itemtype = $item->itemType()) {
            $result->type = $itemtype;
        }
        // Add itemid. 
        if ($itemid = $item->itemid()) {
            $result->id = $itemid;
        }
        // Add properties.
        foreach ($item->properties() as $elem) {
            if ($elem->itemScope()) {
                if (in_array($elem, $memory)) {
                    $value = 'ERROR';
                } else {
                    $memory[] = $item;
                    $value = $this->_getObject($elem, $memory);
                    array_pop($memory);
                }
            } else {
                $value = $elem->itemValue();
            }
            
            foreach ($elem->itemProp() as $prop) {
                $result->properties[$prop][] = $value;
            }
        }

        return $result;
    }
}