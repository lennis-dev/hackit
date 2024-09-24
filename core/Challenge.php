<?php

namespace Dev\Lennis\Hackit;

require_once __DIR__."/Utils.php";
require_once __DIR__."/Session.php";

use Dev\Lennis\Hackit\Utils;

abstract class Challenge {
    protected string $title = "";
    protected string $description = "";
    protected int $difficulty = 0;
    protected string $injectHTML = "";
    protected string $injectJavaScript = "";

    function __construct() {
        if($this->getCode() === null)
            $this->setRandomCode();
        $this->prepareChallenge();
    }

    public final function getID(): string {
        $class = get_class($this);
        $class = explode("\\", $class);
        $class = end($class);
        return Utils::fromClassName($class);
    }

    public final function getTitle(): string {
        return htmlspecialchars($this->title);
    }

    public final function getDescription(): string {
        return htmlspecialchars($this->description);
    }

    public final function getDifficulty(): int {
        return $this->difficulty;
    }

    public final function getInjectHTML(): string {
        return $this->injectHTML;
    }

    public final function getInjectJavaScript(): string {
        return $this->injectJavaScript;
    }

    protected final function setCode(string $code): void {

        Session::setChallengeCode($this->getID(), $code);
    }

    protected final function setRandomCode() {
        $this->setCode(Utils::randomString(8));
    }

    public final function isSolved(): bool {
        return Session::getChallengeSolved($this->getID());
    }

    public final function setSolved(bool $solved): void {
        Session::setChallengeSolved($this->getID(), $solved);
    }

    protected final function getCode(): string|null {
        return Session::getChallengeCode($this->getID());
    }

    protected function prepareChallenge(): void {
        // Override this method to prepare the challenge
    }

    protected final function getChallengeData(): array {
        return Session::getChallengeData($this->getID());
    }

    protected final function getChallengeFile(string $filename): string {
        if(!preg_match('/^[a-zA-Z0-9.]+$/', $filename))
            return null;
        return file_get_contents(__DIR__."/../challenges/".$this->getID()."/".$filename);
    }

    protected final function getChallengeFileMimeType(string $filename): string {
        if(!preg_match('/^[a-zA-Z0-9.]+$/', $filename))
            return null;
        return mime_content_type(__DIR__."/../challenges/".$this->getID()."/".$filename);
    }

    public final function renderFile(string $filename) {
        header("Content-Type: ".$this->getChallengeFileMimeType($filename));
        echo $this->getChallengeFile($filename);
    }

    protected final function listChallengeFiles(): array {
        $files = scandir(__DIR__."/../challenges/".$this->getID());
        $files = array_filter($files, function ($file) {
            return !in_array($file, [".", "..", "index.php"]);
        });
        return $files;
    }

    protected final function setChallengeData(array $data): void {
        Session::setChallengeData($this->getID(), $data);
    }

    public function validate(string $code): bool {
        // You can also override this method to validate the code
        return $code === $this->getCode();
    }
}
