<?php

namespace Dev\Lennis\Hackit\Challenges;

require_once __DIR__.'/../../core/Challenge.php';
require_once __DIR__.'/../../core/Session.php';

use Dev\Lennis\Hackit\Challenge;
use Dev\Lennis\Hackit\Session;

class SourceCodeDisabled extends Challenge {
    protected string $title = 'Source Code Disabled';
    protected string $description = 'The source code for this challenge is disabled, but you can still solve it.';
    protected int $difficulty = 2;
    protected string $injectHTML = <<<EOL
<!-- Source Code Disabled -->











































































































































































<!-- Yes, I know this is a lot of whitespace, but it's necessary for the challenge -->
EOL;
    protected string $injectJavaScript = <<<EOL
    function validate(code) {
        return code === '[code]';
    }
EOL;

    protected function prepareChallenge(): void {
        $this->injectJavaScript = str_replace('[code]', $this->getCode(), $this->injectJavaScript);
    }
}
