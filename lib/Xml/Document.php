<?php

namespace Xml;

class Document
  extends
  \DOMDocument
  implements
  Node {

  private $_xpath = NULL;
  private $_namespaces = array();
  private $_activateEntityLoader = FALSE;
  private $_canDisableEntityLoader = TRUE;

  public function __construct($version = '1.0', $encoding = 'UTF-8') {
    parent::__construct($version, $encoding);
    $this->registerNodeClass('DOMElement', '\Xml\Element');
    $this->_canDisableEntityLoader = function_exists('libxml_disable_entity_loader');
  }

  public function xpath() {
    if (is_null($this->_xpath) || $this->_xpath->document != $this) {
      $this->_xpath = new Xpath($this);
      foreach ($this->_namespaces as $prefix => $namespace) {
        $this->_xpath->registerNamespace($prefix, $namespace);
      }
    }
    return $this->_xpath;
  }

  public function appendElement($name, array $attributes = array(), $content = NULL) {
    $node = self::createElementNode($this, $name, $attributes, $content);
    $this->appendChild($node);
    return $node;
  }

  public function appendXml($content, PapayaXmlElement $target = NULL) {
    if (is_null($target)) {
      $target = $this;
    }
    $fragment = $this->createDocumentFragment();
    $fragment->appendXml($content);
    if ($fragment->firstChild) {
      if ($target->ownerDocument == NULL) {
        if ($fragment->firstChild->firstChild) {
          $target->appendChild($fragment->firstChild->firstChild->cloneNode(TRUE));
        }
      } else {
        foreach ($fragment->firstChild->childNodes as $node) {
          $target->appendChild($node->cloneNode(TRUE));
        }
      }
    }
    return $target;
  }

  public function createElement($name, $content = NULL) {
    $node = parent::createElement($name);
    if (!is_null($content)) {
      $node->appendChild($this->createTextNode($content));
    }
    return $node;
  }

  public static function createElementNode(Document $document,
                                           $name,
                                           array $attributes = array(),
                                           $content = NULL
  ) {
    $node = $document->createElement($name);
    foreach ($attributes as $name => $value) {
      $node->setAttribute($name, $value);
    }
    if (!is_null($content)) {
      $node->appendChild($document->createTextNode($content));
    }
    return $node;
  }

  public function loadXml($source, $options = 0) {
    $status = ($this->_canDisableEntityLoader)
      ? libxml_disable_entity_loader(!$this->_activateEntityLoader) : FALSE;
    $result = parent::loadXML($source, $options);
    if ($this->_canDisableEntityLoader) {
      libxml_disable_entity_loader($status);
    }
    return $result;
  }
}