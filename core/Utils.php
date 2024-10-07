<?php

namespace Dev\Lennis\Hackit;

require_once __DIR__.'/Challenge.php';

use \Dev\Lennis\Hackit\Challenge;

const CHALLENGE_NAMESPACE = "\Dev\Lennis\Hackit\Challenges\\";

class Utils {

    private static string $CHARS = "0123456789abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ";
    private static string $allowedChars = 'a-z-';

    private static function filterChallengeName(string $string): string {
        return preg_replace('/[^'.self::$allowedChars.']/', '', $string);
    }

    public static function getChallenge(string $string): Challenge {
        $string = self::filterChallengeName($string);
        if(file_exists(__DIR__.'/../challenges/'.$string.'/index.php')) {
            require_once __DIR__.'/../challenges/'.$string.'/index.php';
        } else {
            throw new \Exception('Challenge not found');
        }
        $className = CHALLENGE_NAMESPACE.self::toClassName($string);
        return new $className();
    }

    public static function toClassName(string $string): string {
        $words = explode('-', $string);
        $words = array_map('ucfirst', $words);
        return implode('', $words);
    }

    public static function fromClassName(string $string): string {
        // Split the camel case string into words
        $words = preg_split('/(?=[A-Z])/', $string, -1, PREG_SPLIT_NO_EMPTY);
        // Convert the words to lowercase
        $words = array_map('lcfirst', $words);
        // Join the words with hyphens
        return implode('-', $words);
    }


    public static function addIntendation(string $string, int $intendation): string {
        $lines = explode("\n", $string);
        $lines = array_map(function ($line) use ($intendation) {
            return str_repeat(' ', $intendation).$line;
        }, $lines);
        return implode("\n", $lines);
    }

    public static function getChallenges(): array {
        $challenges = [];
        $challengeFiles = glob(__DIR__.'/../challenges/*', GLOB_ONLYDIR);
        foreach($challengeFiles as $challengeFile) {
            $challengeName = basename($challengeFile);
            $challenges[] = self::getChallenge($challengeName);
        }
        return $challenges;
    }

    public static function randomString(int $length): string {
        $string = '';
        for($i = 0; $i < $length; $i++) {
            $string .= self::$CHARS[rand(0, strlen(self::$CHARS) - 1)];
        }
        return $string;
    }

    public static function getChars(): string {
        return self::$CHARS;
    }
}
