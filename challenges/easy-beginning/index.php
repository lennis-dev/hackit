<?php

namespace Dev\Lennis\Hackit\Challenges;

require_once __DIR__.'/../../core/Challenge.php';

use Dev\Lennis\Hackit\Challenge;

class EasyBeginning extends Challenge {
    protected string $title = 'Easy Beginning';
    protected string $description = 'This is the first challenge. It is very easy.';
    protected int $difficulty = 1;
    protected string $injectJavaScript = <<<EOL
function validate(code){
    if(code === "P4SSW0RD"){
        return true;
    }else{
        return false;
    }
}
EOL;
    protected function prepareChallenge(): void {
        $this->setCode('P4SSW0RD');
    }
}
