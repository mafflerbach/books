<?php
namespace Content\Box;

class Menu {
  public function content() {

    $menu = '
        <nav id="mp-menu" class="mp-menu">
					<div class="mp-level">
						<h2 class="fa fa-certificate">All Categories</h2>
						<ul>
						<li class="fa fa-arrow-left">
								<a href="#"><span class="fa fa-caret-square-o-right"></span>Actions</a>
								<div class="mp-level">
									<h2 class="icon fa-caret-square-o-right"><span class="fa fa-caret-square-o-right"></span>Actions</h2>
									<a class="mp-back" href="#">back</a>
									<ul>
                    <li class="fa fa-arrow-left">
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
										<li class="fa fa-arrow-left">
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
							<li class="fa fa-arrow-left">
								<a href="#"><span class="fa fa-book"></span>Books</a>
								<div class="mp-level">
									<h2><span class="fa fa-book"></span>Books</h2>
									<a class="mp-back" href="#">back</a>
									<ul>
										<li class="fa fa-arrow-left">
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
											<a href="#" data-page="download"><span class="fa fa-download"></span>Downloads</a>
										</li>
									</ul>
								</div>
							</li>
							<li class="fa fa-arrow-left">
								<a href="#" data-page="media"><span class="fa fa-book"></span>Images</a>
								<div class="mp-level">
									<h2><span class="fa fa-book"></span>Images</h2>
									<a class="mp-back" href="#">back</a>
									<ul>
										<li class="icon">
											<a href="#" data-page="upload"><span class="fa fa-archive"></span>Upload</a>
										</li>
									</ul>
								</div>
							</li>
							<li><a href="#" data-page="settings"><span class="fa fa-cog"></span>Settings</a></li>
							<li><a href="#" data-page="help"><span class="fa fa-question"></span>Help</a></li>
							<li><a href="logout.php"><span class="fa fa-sign-out"></span>Logout</a></li>
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
      $html .= '<li class="'.$type.'" id="book_' . $book['id'] . '"><a class=""><span class="fa fa-file-text-o"></span>' . $book['title'] . '</a></li>';
    }
    return $html;
  }

}