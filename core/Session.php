<?php

namespace Dev\Lennis\Hackit;

require_once __DIR__.'/Utils.php';

use \Dev\Lennis\Hackit\Utils;

class Session {
    private static string $sessionName = 'hackit';

    public static function start(): void {
        session_name(self::$sessionName);
        session_start();
    }

    private static function set(string $key, $value): void {
        $_SESSION[$key] = $value;
    }

    private static function get(string $key) {
        return $_SESSION[$key] ?? null;
    }

    private static function remove(string $key): void {
        unset($_SESSION[$key]);
    }

    private static function destroy(): void {
        session_destroy();
    }

    private static function regenerate(): void {
        session_regenerate_id();
    }

    public static function setChallengeSolved(string $challenge, bool $solved): void {
        $cData = self::get($challenge);
        self::set($challenge, ['solved' => $solved, 'code' => $cData['code'] ?? Utils::randomString(8), 'data' => $cData['data'] ?? []]);
    }

    public static function setChallengeCode(string $challenge, string $code): void {
        $cData = self::get($challenge);
        self::set($challenge, ['solved' => $cData['solved'] ?? false, 'code' => $code, 'data' => $cData['data'] ?? []]);
    }

    public static function setChallengeData(string $challenge, array $data = []): void {
        $cData = self::get($challenge);
        self::set($challenge, ['solved' => $cData['solved'] ?? false, 'code' => $cData['code'] ?? Utils::randomString(8), 'data' => $data]);
    }

    public static function getChallengeData(string $challenge): array {
        return self::get($challenge)['data'] ?? [];
    }

    public static function getChallengeCode(string $challenge): string|null {
        return self::get($challenge)['code'] ?? null;
    }

    public static function getChallengeSolved(string $challenge): bool {
        return self::get($challenge)['solved'] ?? false;
    }


}
