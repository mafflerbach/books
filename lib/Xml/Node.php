<?php

namespace Xml;
interface Node {
  public function appendElement($name, array $attributes = array(), $content = NULL);
  public function appendXml($content);
}