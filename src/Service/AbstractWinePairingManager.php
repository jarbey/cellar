<?php
/**
 * Created by PhpStorm.
 * User: jarbey
 * Date: 25/12/2017
 * Time: 12:15
 */

namespace App\Service;

use App\Entity\WineBottle;
use Psr\Log\LoggerInterface;
use GuzzleHttp\Psr7;

abstract class AbstractWinePairingManager extends AbstractManager implements IWinePairingManager {


    /**
     * @param \DOMElement $node
     * @return array
     */
    protected function getClass(\DOMElement $node) {
        return preg_split('/\s+/s', $node->getAttribute('class'));
    }

    /**
     * @param \DOMNode $element
     * @return string
     */
    protected function getInnerHtml(\DOMNode $element) {
        $innerHTML = '';
        $children = $element->childNodes;

        foreach ($children as $child) {
            $innerHTML .= $element->ownerDocument->saveHTML($child);
        }

        return $innerHTML;
    }
}