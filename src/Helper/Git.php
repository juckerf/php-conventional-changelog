<?php

namespace ConventionalChangelog\Helper;

use DateTime;

class Git
{
    /**
     * @var string
     */
    protected static $delimiter = '----DELIMITER---';

    /**
     * Run shell command on working dir.
     *
     * @param $string
     */
    protected static function run($string): string
    {
        $value = shell_exec($string);
        $value = Formatter::clean((string)$value);

        // Fix for some git versions
        $value = trim($value, "'");
        $value = str_replace(self::$delimiter . "'\n'", self::$delimiter . "\n", $value);

        return $value;
    }

    /**
     * Is inside work tree.
     */
    public static function isInsideWorkTree(): bool
    {
        $result = self::run('git rev-parse --is-inside-work-tree');

        return $result === 'true';
    }

    /**
     * Get first commit hash.
     */
    public static function getFirstCommit(): string
    {
        return self::run('git rev-list --max-parents=0 HEAD');
    }

    /**
     * Get last tag.
     */
    public static function getLastTag(): string
    {
        return self::run("git for-each-ref refs/tags --sort=-creatordate --format='%(refname:strip=2)' --count=1");
    }

    /**
     * Get commit date.
     */
    public static function getCommitDate($hash): string
    {
        $date = self::run("git log -1 --format=%aI {$hash}");
        $today = new DateTime($date);

        return $today->format('Y-m-d');
    }

    /**
     * Get last tag commit hash.
     */
    public static function getLastTagCommit(): string
    {
        $lastTag = self::getLastTag();

        return self::run("git rev-parse --verify {$lastTag}");
    }

    /**
     * Get remote url.
     */
    public static function getRemoteUrl(): string
    {
        $url = self::run('git config --get remote.origin.url');
        $url = preg_replace("/\.git$/", '', $url);
        $url = preg_replace('/^(https?:\/\/)([0-9a-z.\-_:%]+@)/i', '$1', $url);

        return $url;
    }

    /**
     * Get commits.
     */
    public static function getCommits(string $options = ''): array
    {
        $commits = self::run("git log --pretty=format:'%B%H" . self::$delimiter . "' {$options}") . "\n";
        $commitsArray = explode(self::$delimiter . "\n", $commits);
        array_pop($commitsArray);

        return $commitsArray;
    }

    /**
     * Get tags.
     */
    public static function getTags(): array
    {
        $tags = self::run("git tag --sort=-creatordate --list --format='%(refname:strip=2)" . self::$delimiter . "'") . "\n";
        $tagsArray = explode(self::$delimiter . "\n", $tags);
        array_pop($tagsArray);

        $tagsArray = array_reverse($tagsArray);

        return $tagsArray;
    }

    /**
     * Commit.
     *
     * @return string
     */
    public static function commit(string $message, array $files = [], bool $amend = false, bool $verify = true)
    {
        foreach ($files as $file) {
            system("git add \"{$file}\"");
        }
        $message = str_replace('"', "'", $message); // Escape
        $command = "git commit -m \"{$message}\"";
        if ($amend) {
            $command .= ' --amend';
        }
        if (!$verify) {
            $command .= ' --no-verify';
        }

        return exec($command);
    }

    /**
     * Tag.
     *
     * @return string
     */
    public static function tag(string $name)
    {
        return exec("git tag {$name}");
    }
}
