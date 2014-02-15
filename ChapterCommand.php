<?php
class ChapterCommand implements Command
{
    public function onCommand($name, $args)
    {
        switch ($name) {
            case 'create':
                break;
            default:
                print('unknown command');
                break;
        }

    }
}