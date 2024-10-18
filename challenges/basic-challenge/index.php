<?php


namespace Dev\Lennis\Hackit\Challenges;

require_once __DIR__.'/../../core/Challenge.php';

use Dev\Lennis\Hackit\Challenge;

class BasicChallenge extends Challenge {
    protected string $title = 'Basic Challenge';
    protected string $description = 'I heard there\'s a technique for encoding files safely in the web, something 64?';
    protected int $difficulty = 2;
    protected string $injectJavaScript = <<<EOL
function validate(code){
    if(btoa(code) === "QkFTRUxPTkE="){
        return true;
    }else{
        return false;
    }
}
EOL;
    protected function prepareChallenge(): void {
        $this->setCode('BASELONA');
    }
}
