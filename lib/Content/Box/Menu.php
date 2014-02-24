<?php
namespace Content\Box;

class Menu {
  public function content() {

    $menu = '<p><a href="#" id="trigger" class="menu-trigger">Open/Close Menu</a></p>
			<!-- Push Wrapper -->

				<!-- mp-menu -->
				<nav id="mp-menu" class="mp-menu">
					<div class="mp-level">
						<h2 class="icon icon-world">All Categories</h2>
						<ul>
							<li class="icon icon-arrow-left">
								<a class="icon icon-display" href="#">Books</a>
								<div class="mp-level">
									<h2 class="icon icon-display">Books</h2>
									<a class="mp-back" href="#">back</a>
									<ul>
										<li class="icon icon-arrow-left">
											<a class="icon icon-phone" href="#">Export</a>
											<div class="mp-level">
												<h2>Export</h2>
												<a class="mp-back" href="#">back</a>
												<ul>
												' . $this->getList() . '
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
								<a class="icon icon-news" href="#">Actions</a>
								<div class="mp-level">
									<h2 class="icon icon-news">Actions</h2>
									<a class="mp-back" href="#">back</a>
									<ul>
										<li><a href="#" id="addBook">Add Book</a></li>
										<li><a href="#">Delete Books</a></li>
									</ul>
								</div>
							</li>
							<li><a class="icon icon-photo" href="#">Settings</a></li>
							<li><a class="icon icon-wallet" href="#">Help</a></li>
						</ul>

					</div>
				</nav>';
    return $menu;
}

  private function getList() {
    $db = \Database\Adapter::getInstance();
    $db->query('select * from book');
    $db->execute();
    $result = $db->fetch();
    $html = '';
    foreach ($result as $book) {
      $html .= '<li class="export" id="book_' . $book['id'] . '"><a class="">' . $book['title'] . '</a></li>';
    }
    return $html;
  }
}