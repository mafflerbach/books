<?php


if (isset($_GET['cmd']) && $_GET['cmd'] == 'getTree') {
    $pdo = new PDO('mysql:host=localhost;dbname=books', 'root', '');

    $stm = $pdo->prepare('select * from book');
    $stm->execute();
    $books = $stm->fetchAll(PDO::FETCH_ASSOC);

    $treeArray = array();

    foreach ($books as $book) {

        $stm = $pdo->prepare('select * from chapter where bookid = :id order by sort');
        $stm->bindParam(':id', $book['id']);
        $stm->execute();
        $chapters = $stm->fetchAll(PDO::FETCH_ASSOC);
        $bookTmp = array(
            'id'=>$book['id'],
            'text'=>$book['titel'],
            'book'=>$book['id'],
        );
        foreach ($chapters as $chapter) {
            $chapterTmp = array(
                'id'=>$chapter['id'],
                'text'=>$chapter['titel'],
                'chapter'=>$chapter['id'],
            );
            $bookTmp['children'][] = $chapterTmp;
        }
        $treeArray[] = $bookTmp;
    }

    print(json_encode($treeArray));

}





