<?php

namespace Dev\Lennis\Hackit\Challenges;

require_once __DIR__.'/../../core/Challenge.php';

use Dev\Lennis\Hackit\Challenge;

class ColorfulMessage extends Challenge {
    protected string $title = 'Colorful message';
    protected string $description = 'You know, sometimes, you gotta look behind the colors to find out the solution.';
    protected int $difficulty = 2;
    protected string $injectJavaScript = <<<EOL
function c_rgb2hex(rgb){
   return "#" + rgb.match(/^rgb\((\d+),\s*(\d+),\s*(\d+)\)$/).slice(1).map(n => parseInt(n, 10).toString(16).padStart(2, '0')).join('');
}

function validate(code){
    if(c_rgb2hex(document.getElementById("c-message").style.color).replace("#", "0x") == code)
        return true;
    return false;
}
EOL;
    protected string $injectHTML = <<<EOL
<div style="text-align:center">
    I <strong style="color:#ff0000; text-width:bold"><3</strong> <span id="c-message" style="color: [color]">colors</span>!
</div>
EOL;
    protected function prepareChallenge(): void {
        if(!preg_match('/^0x[0-9a-f]{6}$/', $this->getCode())) {
            $this->setCode('0x'.dechex(rand(0x000000, 0xFFFFFF)));
        }
        $this->injectHTML = str_replace('[color]', str_replace("0x", "#", $this->getCode()), $this->injectHTML);
    }
}