<?php
namespace Content\Box;

class Menu {
  public function content() {

    $menu = '
        <nav id="mp-menu" class="mp-menu">
					<div class="mp-level">
						<h2 class="icon icon-world">All Categories</h2>
						<ul>
							<li class="fa icon-arrow-left">
								<a class="fa fa-book" href="#">Books</a>
								<div class="mp-level">
									<h2 class="fa fa-book">Books</h2>
									<a class="mp-back" href="#">back</a>
									<ul>
										<li class="icon icon-arrow-left">
											<a class="icon icon-phone" href="#">Export</a>
											<div class="mp-level">
												<h2>Export</h2>
												<a class="mp-back" href="#">back</a>
												<ul>
												' . $this->getList('export') . '
												</ul>
											</div>
										</li>
										<li class="icon">
											<a class="icon icon-phone" href="#">Downloads</a>
										</li>
									</ul>
								</div>
							</li>
							<li class="icon icon-arrow-left">
								<a class="fa fa-caret-square-o-right" href="#">Actions</a>
								<div class="mp-level">
									<h2 class="fa-caret-square-o-right">Actions</h2>
									<a class="mp-back" href="#">back</a>
									<ul>
										<li><a href="#" class="icon icon-add" id="addBook">Add Book</a></li>
										<li><a href="#" class="icon icon-remove">Delete Books</a></li>
										<li class="icon icon-arrow-left">
											<a class="icon icon-edit" href="#">Edit</a>
											<div class="mp-level">
												<h2>Edit</h2>
												<a class="mp-back" href="#">back</a>
												<ul>
												' . $this->getList('edit') . '
												</ul>
											</div>
										</li>
									</ul>
								</div>
							</li>
							<li><a class="icon icon-photo" href="#">Settings</a></li>
							<li><a class="icon icon-wallet" href="#">Help</a></li>
							<li><a class="icon icon-wallet" href="logout.php">Logout</a></li>
						</ul>

					</div>
				</nav>';
    return $menu;
}

  private function getList($type) {
    $db = \Database\Adapter::getInstance();
    $db->query('select * from book');
    $db->execute();
    $result = $db->fetch();
    $html = '';
    foreach ($result as $book) {
      $html .= '<li class="'.$type.'" id="book_' . $book['id'] . '"><a class="">' . $book['title'] . '</a></li>';
    }
    return $html;
  }

}