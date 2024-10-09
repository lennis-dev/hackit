<?php

namespace Dev\Lennis\Hackit\Challenges;

require_once __DIR__.'/../../core/Challenge.php';

use Dev\Lennis\Hackit\Challenge;

class PercentUniqueSessionIds extends Challenge {
    protected string $title = '100% Unique Session IDs';
    protected string $description = 'I found a way to generate 100% unique sessions, believe me?';
    protected int $difficulty = 3;
    protected string $user = 'admin';
    protected string $injectHTML = <<<EOL
<style>
    .c-error{
        color: #f00;
    }

    .c-success{
        color: #0f0;
    }
</style>
<div style="font-family: monospace;">
    <div>PHP Code:</div>
    <div><span style="color: #dcdcaa;">dechex</span><span style="color: #d4d4d4;">(</span><span style="color: #9cdcfe;">\$date</span><span style="color: #d4d4d4;">)</span><span style="color: #d4d4d4;">.</span><span style="color: #dcdcaa;">bin2hex</span><span style="color: #d4d4d4;">(</span><span style="color: #569cd6;">\$this</span><span style="color: #d4d4d4;">-&gt;</span><span style="color: #9cdcfe;">user</span><span style="color: #d4d4d4;">);</span></div>
</div>
<p>[text]</p>
<div style="font-family: monospace;">
    [logs]
</div>
EOL;
    protected string $injectJavaScript = <<<EOL
    function validate(code) {
        // validate the code on the server side
        return true;
    }
EOL;

    protected function prepareChallenge(): void {
        $challengeData = $this->getChallengeData();
        if(!isset($challengeData["sessionID"]) || !isset($challengeData["startDate"])) {
            $date = time();
            $challengeData = [];
            $challengeData["sessionID"] = dechex($date).bin2hex($this->user);
            $challengeData["startDate"] = $date;
            $this->setChallengeData($challengeData);
        }

        $formattedDate = date('Y-m-d H:i:s', $challengeData["startDate"]);
        $logLine = "Logs:<br />[{$formattedDate}] User ".$this->user." logged in<br />[{$formattedDate}] Setting cookie 'unique-session-ids-100-percent' with sessionID '[REMOVED]'";
        $this->injectHTML = str_replace('[logs]', $logLine, $this->injectHTML);
        if(isset($_COOKIE["unique-session-ids-100-percent"])) {
            if($_COOKIE["unique-session-ids-100-percent"] === $challengeData["sessionID"])
                $this->injectHTML = str_replace('[text]', '<h2 class="c-success">Hey '.$this->user.',<br />here the password: '.$this->getCode().'</h2>', $this->injectHTML);
            else
                $this->injectHTML = str_replace('[text]', '<h2 class="c-error">Session not found!</h2>', $this->injectHTML);
        } else
            $this->injectHTML = str_replace('[text]', '<h2 class="c-error">You are not a '.$this->user.'!</h2>', $this->injectHTML);

    }
}
