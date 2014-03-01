<?php
namespace Xml;
class Xpath extends
  \DOMXpath {

  private $_registerNodeNamespaces = FALSE;

  public function __construct(\DOMDocument $dom) {
    parent::__construct($dom);
    $this->registerNodeNamespaces(version_compare(PHP_VERSION, '<', '5.3.3'));
  }

  public function registerNamespace($prefix, $namespaceUri) {
    $result = parent::registerNamespace($prefix, $namespaceUri);
    if ($result && $this->document instanceOf Document) {
      $this->document->registerNamespaces(
                     array($prefix => $namespaceUri),
                     FALSE
      );
    }
    return $result;
  }

  public function registerNodeNamespaces($enabled = NULL) {
    if (isset($enabled)) {
      $this->_registerNodeNamespaces = (boolean)$enabled;
    }
    return $this->_registerNodeNamespaces;
  }

  public function evaluate($expression, \DOMNode $contextnode = NULL, $registerNodeNS = NULL) {
    if ($registerNodeNS || (NULL === $registerNodeNS && $this->_registerNodeNamespaces)) {
      return isset($contextnode)
        ? parent::evaluate($expression, $contextnode)
        : parent::evaluate($expression);
    }
    return parent::evaluate($expression, $contextnode, FALSE);
  }

  public function query($expression, \DOMNode $contextnode = NULL, $registerNodeNS = NULL) {
    throw new \Exception('"query()" should not be used, use "evaluate()".');
  }

}
