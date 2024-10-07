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

    // Correct method definition
    protected function prepareChallenge(): void {
        $this->setCode('P4SSW0RD');
    }

    public function render() {
        echo '<!-- Menu button and menu content -->';
        echo '<div id="menu-icon" onclick="document.querySelector(\'nav\').classList.toggle(\'active\')">';
        echo '    <div></div>';
        echo '    <div></div>';
        echo '    <div></div>';
        echo '</div>';

       
        echo '<script>';
        echo $this->injectJavaScript;
        echo '</script>';
    }
}


$challenge = new EasyBeginning();

$challenge->render(); 
