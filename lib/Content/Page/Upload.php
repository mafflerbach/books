<?php
namespace Content\Page;

class Upload {
  public function content() {
    $html = '
    <form action = "upload.php" method="post" enctype="multipart/form-data">
<input type="file" name="imageURL[]" id="imageURL" multiple="" />
<input type="submit" value="submit" name="submit" />
</form>';
    $html2 = '<div class="">
    <form action="" method="post">
      <input type="text" name="dirname" id="dirname" />
      <button value="Add Dir" name="submit" id="adddir">Add Dir</button>
    </form></div>';

    $tree = '<ul id="filetree"></ul>';

    $js = '
    <script type="text/javascript">
        $("#fooo").tree();
    </script>
    ';

fooo();
//    print_r($result);
    print($html2 . $html );

  }
}

http://mikehillyer.com/articles/managing-hierarchical-data-in-mysql/


function fooo() {
  $db = \Database\Adapter::getInstance();
  $db->query('SELECT node.name, node.lft, node.rgt, (COUNT(parent.name) - 1) AS depth
FROM nested_category AS node,
        nested_category AS parent
WHERE node.lft BETWEEN parent.lft AND parent.rgt
GROUP BY node.name
ORDER BY node.lft;');
  $db->execute();
  $result = $db->fetch();

  $list = '<ul id="fooo">';
  $sequence = new SequenceTreeIterator($result);
$hasChildren = FALSE;
foreach ($sequence as $node) {
  if ($close = $sequence->getCloseLevels()) {
    $list .= str_repeat('</ul></li>', $close);
    $hasChildren = FALSE;
  }
  if (!$node && $hasChildren) {
    $list .= '</li>';
  }
  if (!$node) {
    break;
  }
  $hasChildren = $node->hasChildren();

  $list .= '<li><span>'.$node['name'].'</span>';
  if ($hasChildren) {
    $list .= '<ul>';
  } else {
    $list .= "\n";
  }
}
  $list .= '</ul>';

  print($list);
}
class SequenceTreeIterator extends
  \ArrayIterator {
  private $keyDepth = 'depth';
  private $skipDepth;
  private $depth;
  private $prevDepth;
  private $index;

  public function __construct(array $array) {
    parent::__construct($array);
    parent::append(NULL); // add terminator
  }

  public function rewind() {
    $this->skipDepth = FALSE;
    $this->terminate = FALSE;
    $this->prevDepth = 0;
    $this->index = 0;
    parent::rewind();
  }

  public function current() {
    $current = parent::current();
    if ($current) {
      $current = new Node($current);
      $this->depth = $current[$this->keyDepth];
    } else {
      $this->depth = 0;
    }
    return $current;
  }

  public function next() {
    $current = parent::current();
    $prevDepth = (int)$current[$this->keyDepth];
    assert('$prevDepth>=0');
    $this->prevDepth = $prevDepth;

    $skipDepth = $this->skipDepth;
    $this->skipDepth = FALSE;

    do {
      $this->index++;
      parent::next();
      if (NULL === $next = parent::current()) {
        break;
      }

      $nextDepth = $next[$this->keyDepth];
    } while (FALSE !== $skipDepth && $nextDepth > $skipDepth);
  }

  public function skipChildren() {
    $this->skipDepth = $this->depth;
  }

  public function getPrevDepth() {
    return $this->prevDepth;
  }

  public function getDepth() {
    return $this->depth;
  }

  public function getCloseLevels() {
    return max(0, $this->prevDepth - $this->depth);
  }

  public function getIndex() {
    return $this->index;
  }

  public function hasNext() {
    return ($this->index + 1) < count($this);
  }
}

class Node extends
  \ArrayObject {
  public function __construct(array $node) {
    if (!isset($node['name'])) {
      $node['name'] = '(unnamed)';
    }
    parent::__construct($node);
  }

  public function getLeftRight() {
    return array($this['lft'],
                 $this['rgt']
    );
  }

  public function childCount() {
    list($left, $right) = $this->getLeftRight();
    $count = $right - $left - 1;
    assert('$count > -1');
    return $count >> 1;
  }

  public function hasChildren() {
    return (bool)$this->childCount();
  }

  private function compare($node, $mode) {
    if (is_array($node)) {
      $node = new self($node);
    }
    list($left, $right) = $this->getLeftRight();
    list($nodeLeft, $nodeRight) = $node->getLeftRight();
    switch ($mode) {
      case '<==>':
        return $left <= $nodeLeft && $right >= $nodeRight;

      case '<>':
        return $left < $nodeLeft && $right > $nodeRight;

      case '==':
        return $left == $nodeLeft && $right == $nodeRight;

      case '><':
        return $left > $nodeLeft && $right < $nodeRight;

      default:
        throw new InvalidArgumentException(sprintf('Invalid mode "%s".', $mode));
    }
  }

  public function isParentOf($node) {
    return $this->compare($node, '<>');
  }

  public function isSupersetOf($node) {
    return $this->compare($node, '<==>');
  }

  public function isSame($node) {
    return $this->compare($node, '==');
  }

  public function isChildOf($node) {
    return $this->compare($node, '><');
  }
}

