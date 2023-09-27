<?php

interface RandomPasswordGeneratorInterface
{
    public function start(): void;
}

class RandomPasswordGenerator implements RandomPasswordGeneratorInterface
{
    private const LETTERS = 'abcdefghijklmnopqrstuvwxyz';
    private const DIGITS = '0123456789';
    private const FILE_NAME = 'passwords.txt';

    /**
     * Create the random password generator.
     *
     * @param integer $interval
     * @param integer $passwordLength
     */
    public function __construct(
        private readonly int $interval,
        private readonly int $passwordLength = 12
    ) {
    }

    /**
     * Start the random password generation.
     *
     * @return void
     */
    public function start(): void
    {
        while (true) {
            $this->saveToFile($this->generatePassword());
            sleep($this->interval);
        }
    }

    /**
     * Generate random password.
     *
     * @return string
     */
    private function generatePassword(): string
    {
        $password = '';

        do {
            $password .= $this->getRandomChar();
        } while (strlen($password) < $this->passwordLength);

        return $password;
    }

    /**
     * Get random char.
     *
     * @return void
     */
    private function getRandomChar()
    {
        $allChars = self::LETTERS . strtoupper(self::LETTERS) . self::DIGITS;

        return $allChars[random_int(0, strlen($allChars) - 1)];
    }

    /**
     * Appends the password to the file.
     *
     * @param string $password
     * @throws Exception
     * @return void
     */
    private function saveToFile(string $password): void
    {
        $filePath = __DIR__ . DIRECTORY_SEPARATOR . self::FILE_NAME;

        $result = file_put_contents($filePath, $password . "\n", FILE_APPEND);

        if ($result === false) {
            throw new Exception('Unable save password to file.');
        }
    }
}

// Create an instance
$passwordGenerator = new RandomPasswordGenerator(20);

// Start
try {
    $passwordGenerator->start();
} catch (Throwable $e) {
    // Handle error
    echo $e->getMessage();
}
