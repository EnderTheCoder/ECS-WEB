<?php

use JetBrains\PhpStorm\NoReturn;
use JetBrains\PhpStorm\Pure;

class RegisterExecutor implements ApiExecutor {

    protected string $returnType = "success";

    #[Pure] public function checkFields(): bool
    {
        $requiredList = ['username', 'password', 'email', 'qq'];
        return Tools::checkFields($_POST, $requiredList);
    }

    #[NoReturn] public function data()
    {
        $return = new DataSender();
        $return->easyRun($this->returnType);
    }

    public function run()
    {
        $m = new MySQL();

        $m->prepareSQL("SELECT username FROM user WHERE username = ?");
        $m->bindValue(1, $_POST['username']);
        $m->execute();
        if (count($m->result()) != 0) {
            $this->returnType = "dupVal";
            return;
        }

        $m->prepareSQL("INSERT INTO user (username, password, regTime, level, qq, email) VALUES (?, ?, ?, ?, ?, ?)");
        $m->bindValue(1, $_POST['username']);
        $m->bindValue(2, $_POST['password']);
        $m->bindValue(3, time());
        $m->bindValue(4, 1);
        $m->bindValue(5, $_POST['qq']);
        $m->bindValue(6, $_POST['email']);
        $m->execute();
    }
}