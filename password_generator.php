<?php

interface RandomPasswordGeneratorInterface
{
    public function start();
}

class RandomPasswordGenerator extends Thread implements RandomPasswordGeneratorInterface
{
    const LETTERS = 'abcdefghijklmnopqrstuvwxyz';

    const DIGITS = '0123456789';

    const FILE_NAME = 'passwords.txt';

    private $interval;

    private $passwordLength;

    public function __construct(int $interval, int $passwordLength = 12)
    {
        $this->interval = $interval;
        $this->passwordLength = $passwordLength;
    }

    public function run()
    {
        while (true) {
            $this->saveToFile($this->generatePassword());
            sleep($this->interval);
        }
    }

    private function generatePassword()
    {
        $password = '';

        do {
            $password .= $this->getRandomChar();
        } while (strlen($password) < $this->passwordLength);

        return $password;
    }

    private function getRandomChar()
    {
        $allChars = self::LETTERS . strtoupper(self::LETTERS) . self::DIGITS;

        return $allChars[random_int(0, strlen($allChars) - 1)];
    }

    private function saveToFile(string $password)
    {
        $filePath = __DIR__ . DIRECTORY_SEPARATOR . self::FILE_NAME;

        $result = file_put_contents($filePath, $password . "\n", FILE_APPEND);

        if ($result === false) {
            throw new Exception('Unable save password to file.');
        }
    }
}

$stack = [];
$threadsAmount = 5;

// Initiate multiple thread
foreach (range(1, $threadsAmount) as $step) {
    $stack[] = new RandomPasswordGenerator(20);
}

// Start the threads
foreach ($stack as $thread) {
    $thread->start();
}

