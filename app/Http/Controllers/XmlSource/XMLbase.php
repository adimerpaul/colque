<?php

namespace App\Http\Controllers\XmlSource;

use DOMDocument;
use SimpleXMLElement;

class XMLbase
{

    /**
     * @param String $name
     * @param String $nameXsd
     * @param $request
     * @return DOMDocument
     */
    protected static function getXmlDom(string $name, string $nameXsd, $request): DOMDocument
    {
        $xml = new SimpleXMLElement("<?xml version='1.0' encoding='UTF-8' standalone='yes'?><$name xsi:noNamespaceSchemaLocation='$nameXsd.xsd' xmlns:xsi='http://www.w3.org/2001/XMLSchema-instance'></$name>");
        foreach (array_keys($request) as $column) {
            $header = $xml->addChild($column);
            $count = 0;
            foreach (array_keys($request[$column]) as $item) {
                $count++;
                if (is_array($request[$column][$item])) {
                    foreach (array_keys($request[$column][$item]) as $key) {
                        $child = $header->addChild($key, $request[$column][$item][$key]);
                        if ($request[$column][$item][$key] === null) {
                            $child->addAttribute("xsi:nil", "true", "http://www.w3.org/2001/XMLSchema-instance");
                        }
                    }
                    if (count($request[$column]) > 1) {
                        if ($count < count($request[$column])) {
                            $header = $xml->addChild($column);
                        }
                    }
                } else {
                    $child = $header->addChild($item, $request[$column][$item]);
                    if ($request[$column][$item] === null) {
                        $child->addAttribute("xsi:nil", "true", "http://www.w3.org/2001/XMLSchema-instance");
                    }
                }
            }
        }

        Header('Content-type: text/xml');

        $dom = new DOMDocument('1.0');
        $dom->preserveWhiteSpace = false;
        $dom->formatOutput = true;
        $dom->loadXML($xml->asXML());
        return $dom;
    }
}
