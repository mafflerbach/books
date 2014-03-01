<?php
namespace Xml;
class Element
  extends
  \DOMElement
  implements
  Node {

  public function append(Appendable $object) {
    return $object->appendTo($this);
  }

  public function appendElement($name, array $attributes = array(), $content = NULL) {
    $node = Document::createElementNode(
                             $this->ownerDocument, $name, $attributes, $content
    );
    $this->appendChild($node);
    return $node;
  }

  public function appendText($content) {
    $node = $this->ownerDocument->createTextNode($content);
    $this->appendChild($node);
    return $this;
  }

  public function appendXml($content) {
    return $this->ownerDocument->appendXml($content, $this);
  }

  public function appendTo(\DOMNode $target) {
    if ($target instanceof \DOMElement) {
      $document = $target->ownerDocument;
    } elseif ($target instanceof \DOMDocument) {
      $document = $target;
    } else {
      throw new \Exception(
        'Can only append to DOMDocument or DOMElement objects.'
      );
    }
    if ($document != $this->ownerDocument) {
      $source = $document->importNode($this, TRUE);
    } else {
      $source = $this;
    }
    $target->appendChild($source);
  }

  public function saveXml() {
    return $this->ownerDocument->saveXml($this);
  }

  public function saveFragment() {
    $result = '';
    foreach ($this->childNodes as $childNode) {
      $result .= $childNode->ownerDocument->saveXml($childNode);
    }
    return $result;
  }

  public function setAttribute($name, $value) {
    if (isset($value) && $value !== '') {
      parent::setAttribute($name, (string)$value);
    } else {
      parent::removeAttribute($name);
    }
  }
}