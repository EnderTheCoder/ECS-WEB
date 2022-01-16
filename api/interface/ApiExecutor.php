<?php
interface ApiExecutor{
    public function checkFields():bool;
    public function run();
    public function data();
}