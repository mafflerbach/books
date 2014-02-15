<?php

interface Command {
    function onCommand($name, $args);
}