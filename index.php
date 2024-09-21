<?php

require_once __DIR__.'/core/Utils.php';
require_once __DIR__.'/core/Render.php';
require_once __DIR__."/core/Session.php";

use Dev\Lennis\Hackit\Utils;
use Dev\Lennis\Hackit\Render;
use Dev\Lennis\Hackit\Session;

Session::start();

$splitUrl = explode('/', $_SERVER['REQUEST_URI']);

if($splitUrl[1] === "c") {
    try {
        $splitUrl[2] = explode('?', $splitUrl[2])[0];
        $challenge = Utils::getChallenge($splitUrl[2]);
        if(isset($_GET["code"])) {
            if($challenge->validate($_GET["code"])) {
                $challenge->setSolved(true);
                header("Location: /");
            } else {
                Render::renderChallenge($challenge, $_GET["code"]);
            }
            exit;
        }
        Render::renderChallenge($challenge);
        return;
    } catch (Exception $e) {
        header("Location: /");
        exit;
    }
} else if($splitUrl[1] === "about") {
    Render::renderAboutPage();
} else if($splitUrl[1] === "sitemap") {
    Render::renderSitemap();
} else if($splitUrl[1] === "") {
    Render::renderChallengeList(Utils::getChallenges());
}
