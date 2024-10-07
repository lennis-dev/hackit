<?php

namespace Dev\Lennis\Hackit;

require_once 'core/Utils.php';
require_once 'core/Challenge.php';

use Dev\Lennis\Hackit\Utils;
use Dev\Lennis\Hackit\Challenge;

class Render {
    private string $page = <<<EOL
<!DOCTYPE html>
<html lang="en">
<head>
    <!--
     _                     _          _
    | |    ___ _ __  _ __ (_)___   __| | _____   __
    | |   / _ \ '_ \| '_ \| / __| / _` |/ _ \ \ / /
    | |__|  __/ | | | | | | \__ \| (_| |  __/\ V /
    |_____\___|_| |_|_| |_|_|___(_)__,_|\___| \_/

    -->
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=0.8, maximum-scale=1.0, user-scalable=no">
    <title>Hackit</title>
    <link rel="stylesheet" href="/static/style.css">
</head>
<body>
    <header>
        <nav>
            <a href="/">Home</a>
            <a href="/about" target="_blank">About</a>
            <a href="https://github.com/lennis-dev/hackit" target="_blank">GitHub</a>
        </nav>
        <div id="menu-icon" onclick="document.querySelector('nav').classList.toggle('active')">
            <div></div>
            <div></div>
            <div></div>
        </div>
            <pre id="ascii-art" class="ascii-large"> _                     _          _            <br />| |    ___ _ __  _ __ (_)___   __| | _____   __<br />| |   / _ \ '_ \| '_ \| / __| / _` |/ _ \ \ / /<br />| |__|  __/ | | | | | | \__ \| (_| |  __/\ V / <br />|_____\___|_| |_|_| |_|_|___(_)__,_|\___| \_/  </pre>
    </header>
    <main>      
[main]
    </main>
    <div id="copyright">
        (c) <a href="https://www.lennis.dev/">Lennis</a> and <a href="https://github.com/lennis-dev/hackit/graphs/contributors">contributors</a>
    </div>
</body>
</html>
EOL;

    private static string $aboutHTML = <<<EOL
<h1>About</h1>
<p>
    Hackit is a collection of challenges designed to teach you about web security, making the learning process both fun and educational. These challenges are intended solely for educational purposes and should not be used for any malicious activities.
</p>
<p>
    This project is open-source and available on <a href="https://github.com/lennis-dev/hackit">GitHub</a>. If you have any questions or suggestions, feel free to open an issue or submit a pull request. Contributions are always welcome! By participating in Hackit, you will gain practical experience in identifying and mitigating security threats, enhancing your skills as a web security professional. Join our community of learners and contributors to stay updated with the latest challenges and improvements.
</p>
EOL;

    private string $title = 'Hackit';
    private string $body = '';

    private function setTitle(string $title): void {
        $this->title = " | ".$title;
    }

    private function setBody(string $body): void {
        $this->body = $body;
    }

    private function prepareChallenge(string $title, string $description, string $injectHTML, string $injectJavaScript): void {
        $this->setTitle("Challenge ".$title);
        $description = nl2br($description);
        $injectHTML = Utils::addIntendation($injectHTML, 4);
        $injectJavaScript = Utils::addIntendation($injectJavaScript, 4);
        $this->body = <<<EOL

<div id="challenge-description">
    <h1>$title</h1>
    <p>$description</p>
</div>
<div id="challenge-inject">
$injectHTML
</div>
        
<div id="code-input"></div>

<script src="/static/script.js"></script>
<script type="text/javascript" id="inject-javascript">
$injectJavaScript
</script>

EOL;
    }

    private function prepareChallengeList(array $challenges): void {
        $this->setTitle("Challenges");
        $challengesHTML = <<<EOL
    <table>
        <thead>
            <tr>
                <th></th>
                <th>Title</th>
                <th>Description</th>
                <th></th>
            </tr>
        </thead>
        <tbody>
    EOL;
    
        foreach($challenges as $challenge) {
            $id = $challenge->getID();
            $title = $challenge->getTitle();
            $description = $challenge->getDescription();
            $difficulty = $challenge->getDifficulty();
            $solved = $challenge->isSolved() ? '<span style="color:#0f0">✔</span>' : '<span style="color:#f00">✘</span>';
            $challengesHTML .= <<<EOL
            <tr style='cursor: pointer;' onclick="window.location= '/c/$id';" class="challenge-entry">
                <td class="challenge-solve">[$solved]</td>
                <td class="challenge-title">$title</td>
                <td class="challenge-description">$description</td> <!-- Add class here -->
                <td>$difficulty</td>
            </tr>
    EOL;
        }
    
        $challengesHTML .= <<<EOL
        </tbody>
    </table>
    EOL;
        $this->setBody($challengesHTML);
    }
    
    public function render(): void {
        $page = $this->page;
        $page = str_replace('</title>', $this->title.'</title>', $page);
        $page = str_replace('[main]', Utils::addIntendation($this->body, 8), $page);
        echo $page;
    }

    public static function renderChallenge(Challenge $challenge, string $wrongCode = ""): void {
        $render = new Render();
        if ($wrongCode !== "") {
            $wrongCode = "\nwrongCode('$wrongCode');";
        }
        $render->prepareChallenge($challenge->getTitle(), $challenge->getDescription(), $challenge->getInjectHTML(), $challenge->getInjectJavaScript() . $wrongCode);
        $render->render();
    }

    public static function renderChallengeList(array $challenges): void {
        $render = new Render();
        usort($challenges, fn($a, $b) => $a->getDifficulty() <=> $b->getDifficulty());
        $render->prepareChallengeList($challenges);
        $render->render();
    }

    public static function renderAboutPage(): void {
        $render = new Render();
        $render->setTitle("About");
        $render->setBody(self::$aboutHTML);
        $render->render();
    }

    public static function renderSitemap(): void {
        header("Content-Type: application/xml");
        echo <<<EOL
<?xml version="1.0" encoding="UTF-8"?>
<urlset xmlns="http://www.sitemaps.org/schemas/sitemap/0.9">
    <url>
        <loc>https://hackit.lennis.dev/</loc>
        <changefreq>weekly</changefreq>
        <priority>1.0</priority>
    </url>
    <url>
        <loc>https://hackit.lennis.dev/about</loc>
        <changefreq>monthly</changefreq>
        <priority>1.0</priority>
    </url>
EOL;
        foreach (Utils::getChallenges() as $challenge) {
            echo <<<EOL
    <url>
        <loc>https://hackit.lennis.dev/c/{$challenge->getID()}</loc>
        <changefreq>monthly</changefreq>
        <priority>0.8</priority>
    </url>
EOL;
        }
        echo <<<EOL
</urlset>
EOL;
    }
}
    