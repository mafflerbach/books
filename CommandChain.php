<?php
class CommandChain {
    private $_commands = array();

    public function addCommand($cmd) {
        $this->_commands [] = $cmd;
    }

    public function runCommand($name, $args) {
        foreach ($this->_commands as $cmd) {
            if ($cmd->onCommand($name, $args))
                return;
        }
    }
}