<?php

namespace Dev\Lennis\Hackit\Challenges;

require_once __DIR__.'/../../core/Challenge.php';
require_once __DIR__.'/../../core/Utils.php';

use Dev\Lennis\Hackit\Challenge;
use Dev\Lennis\Hackit\Utils;

class WaitForIt extends Challenge {
    protected int $base = 32;
    protected string $title = 'Wait for it ...';
    protected string $description = 'Are you familiar with How I Met Your Mother\'s Barney, who is always saying "... wait for it ...", but what if it takes a little longer and you cannot wait for it?';
    protected int $difficulty = 4;
    protected string $injectHTML = <<<EOL
<div>
    <div style="text-align: center;font-size:30px;"><span id="c-known"></span><span id="c-unknown" style="color: #0f0"></span></div>
</div>

<div id="menu-icon" onclick="document.querySelector('nav').classList.toggle('active')">
        <div></div>
        <div></div>
        <div></div>
</div>

EOL;
    protected string $injectJavaScript = <<<EOL
let c_code = [[code]], c_knownLength = 0, c_count = 0, c_interval;

function c_rotate() {
    if (c_count === [base] ** c_knownLength) {
        c_knownLength++;
        c_count = 0;
    }
    c_code[c_knownLength]++;
    if (c_code[c_knownLength] === [charsl]) {
        c_code[c_knownLength] = 0;
    }

    if (c_knownLength === 8) clearInterval(c_interval);
    c_count++;
    const c_codeStr = c_code.map(i => "[chars]"[i]).join('');
    document.getElementById('c-known').innerText = c_codeStr.substr(0, c_knownLength);
    document.getElementById('c-unknown').innerText = c_codeStr.substr(c_knownLength, 1);
    document.getElementById('c-unknown').innerText += "?".repeat(c_codeStr.length - c_knownLength - (c_knownLength === 8 ? 0 : 1));
}

c_rotate();

function validate(code){
    // validate the code on the server side
    return true;
}

c_interval = setInterval(c_rotate, 100);
EOL;
    protected function reverseChallenge(string $code): string {
        $initCode = [];
        $charsLength = strlen(Utils::getChars());

        for($i = 0; $i < 8; $i++) {
            $initCode[] = strpos(Utils::getChars(), $code[$i]);
        }

        for($knownLength = 0; $knownLength < 8; $knownLength++) {
            $reqCount = pow($this->base, $knownLength);
            $initCode[$knownLength] = ($initCode[$knownLength] - $reqCount + $charsLength * $reqCount) % $charsLength;
        }
        return implode(', ', $initCode);
    }

    protected function prepareChallenge(): void {
        $this->injectJavaScript = str_replace('[code]', $this->reverseChallenge($this->getCode()), $this->injectJavaScript);
        $this->injectJavaScript = str_replace('[base]', $this->base, $this->injectJavaScript);
        $this->injectJavaScript = str_replace('[chars]', Utils::getChars(), $this->injectJavaScript);
        $this->injectJavaScript = str_replace('[charsl]', strlen(Utils::getChars()), $this->injectJavaScript);
    }
}
