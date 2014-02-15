<?php
class BookCommand implements Command
{
    public function onCommand($name, $args)
    {
        switch ($name) {
            case 'create':
                $this->create($args);
                break;
            case 'delete' :
                $this->delete($args);
                break;
            default:
                print('unknown command');
                break;
        }
    }

    private function create(DomainObjectAbstract $args) {
        $db = MySQLAdapter::getInstance(array('localhost', 'root', '', 'books'));
        $db->query('insert into '.$args->tableName.' (titel)values("'.$args->titel.'")');
    }

    private function delete(DomainObjectAbstract $args) {
        $db = MySQLAdapter::getInstance(array('localhost', 'root', '', 'books'));
        $db->query('delete from '.$args->tableName.' where id = '.$args->id.'');
        $db->query('delete from chapter where bookid = '.$args->id.'');
    }

}