<?php
namespace Content\Box;

class Menu {
  public function content() {


    $d = new \Xml\Document();
    $nav = $d->appendElement('nav', array('class' => 'mp-menu', 'id'=>'mp-menu'));
    $level = $nav->appendElement('div', array('class' => 'mp-level'));
    $level->appendElement('h2', array('class' => 'fa fa-certificate'), 'All Categories');

    $ul = $level->appendElement('ul');

    $li = $ul->appendElement('li', array('class'=>'icon icon-arrow-left'));
    $a = $li->appendElement('a', array('href'=>'#'), 'Actions');
    $a->appendElement('span', array('class'=>'fa fa-caret-square-o-right'));
    $div = $li->appendElement('div', array('class'=>'mp-level'));
    $h2 = $div->appendElement('h2', array(), 'Actions');
    $h2->appendElement('span', array('class' =>'fa fa-caret-square-o-right'));
    $div->appendElement('a', array('class'=>'mp-back', 'href'=>'#'), 'back');

    $ul2 = $div->appendElement('ul');
    $this->addSubmenu($ul2, 'fa-edit', 'Edit', 'edit');
    $li2 = $ul2->appendElement('li');
    $a = $li2->appendElement('a', array('id'=>'addBook'), 'Add Book');
    $a->appendElement('span', array('class'=>'fa fa-plus'));
    $this->addSubmenu($ul2, 'fa-minus', 'Delete Books', 'Delete');

    $li3 = $ul->appendElement('li', array('class'=>'fa icon-arrow-left'));
    $a = $li3->appendElement('a', array('href'=>'#'), 'Books');
    $a->appendElement('span', array('class'=>'fa fa-book'));
    $div2 = $li3->appendElement('div', array('class'=>'mp-level'));
    $h2 = $div->appendElement('h2', array(), 'Books');
    $h2->appendElement('span', array('class' =>'fa fa-book'));
    $div2->appendElement('a', array('class'=>'mp-back', 'href'=>'#'), 'back');

    $ul3 = $div2->appendElement('ul');
    $this->addSubmenu($ul3, 'fa-cogs', 'Generate', 'export');

    $li = $ul3->appendElement('li');
    $a = $li->appendElement('a',array('href'=>'#') , 'Downloads');
    $a->appendElement('span', array('class'=>'fa fa-download'));

    $li = $ul->appendElement('li', array(''));
    $a = $li->appendElement('a',array('href'=>'#') , 'Settings');
    $a->appendElement('span', array('class'=>'fa fa-cog'));

    $li = $ul->appendElement('li', array(''));
    $a = $li->appendElement('a',array('href'=>'#') , 'Help');
    $a->appendElement('span', array('class'=>'fa fa-question'));

    $li = $ul->appendElement('li', array(''));
    $a = $li->appendElement('a',array('href'=>'logout.php') , 'Logout');
    $a->appendElement('span', array('class'=>'fa fa-sign-out'));

    print($d->saveXML());


    $menu = '
        <nav id="mp-menu" class="mp-menu">
					<div class="mp-level">
						<h2 class="icon icon-world">All Categories</h2>
						<ul>
						<li class="icon icon-arrow-left">
								<a href="#"><span class="fa fa-caret-square-o-right"></span>Actions</a>
								<div class="mp-level">
									<h2><span class="fa fa-caret-square-o-right"></span>Actions</h2>
									<a class="mp-back" href="#">back</a>
									<ul>
                    <li class="icon icon-arrow-left">
											<a href="#"><span class="fa fa-edit"></span>Edit</a>
											<div class="mp-level">
												<h2><span class="fa fa-edit"></span>Edit</h2>
												<a class="mp-back" href="#">back</a>
												<ul>
												' . $this->getList('edit') . '
												</ul>
											</div>
										</li>
										<li><a href="#" id="addBook"><span class="fa fa-plus"></span>Add Book</a></li>
										<li class="icon icon-arrow-left">
											<a href="#"><span class="fa fa-minus"></span>Delete Books</a>
											<div class="mp-level">
												<h2><span class="fa fa-edit"></span>Edit</h2>
												<a class="mp-back" href="#">back</a>
												<ul>
												' . $this->getList('delete') . '
												</ul>
											</div>
										</li>
									</ul>
								</div>
							</li>
							<li class="fa icon-arrow-left">
								<a href="#"><span class="fa fa-book"></span>Books</a>
								<div class="mp-level">
									<h2><span class="fa fa-book"></span>Books</h2>
									<a class="mp-back" href="#">back</a>
									<ul>
										<li>
											<a href="#"><span class="fa fa-cogs"></span>Generate</a>
											<div class="mp-level">
												<h2><span class="fa fa-cogs"></span>Generate</h2>
												<a class="mp-back" href="#">back</a>
												<ul>
												' . $this->getList('export') . '
												</ul>
											</div>
										</li>
										<li class="icon">
											<a><span class="fa fa-download"></span>Downloads</a>
										</li>
									</ul>
								</div>
							</li>
							<li><a href="#"><span class="fa fa-cog"></span>Settings</a></li>
							<li><a href="#"><span class="fa fa-question"></span>Help</a></li>
							<li><a href="logout.php"><span class="fa fa-sign-out"></span>Logout</a></li>
						</ul>
					</div>
				</nav>';




//    return $menu;
}

  private function addSubmenu ($parent, $icon, $content, $mode) {
    $li = $parent->appendElement('li', array('class'=>'icon icon-arrow-left'));
    $a = $li->appendElement('a', array('href'=>'#'), $content);
    $a->appendElement('span', array('class' =>'fa '.$icon));
    $div = $li->appendElement('div', array('class'=>'mp-level'));
    $h2 = $div->appendElement('h2', array(), $content);
    $h2->appendElement('span', array('class'=>'fa '.$icon));
    $div->appendElement('a', array('class'=>'mp-back', 'href'=>'#'));
    $ul = $div->appendElement('ul');
    $this->addListElements($ul, $mode);
  }


  private function addListElements($parent, $type) {
    $db = \Database\Adapter::getInstance();
    $db->query('select * from book');
    $db->execute();
    $result = $db->fetch();

    foreach ($result as $book) {
      $li = $parent->appendElement('li', array('class'=>$type, 'id'=>'book_' . $book['id']));
      $a = $li->appendElement('a',array() , $book['title']);
      $a->appendElement('span', array('class'=>'fa fa-file-text-o'));
    }
  }

  private function addListElement($parent, $icon, $content, $href) {
    $li = $parent->appendElement('li');
    $a = $li->appendElement('a',array('href'=>$href) , $content);
    $a->appenElement('span', array('class'=>'fa '.$icon));
  }
}