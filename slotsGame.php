<?php

class SlotMachine
{
    private int $width;
    private int $height;
    private int $balance;
    private int $bet;
    private array $twoDArray;
    private int $winnings;

    public function __construct($width = 3, $height = 3, $bet = 10)
    {
        $this->width = $width;
        $this->height = $height;
        $this->balance = intval(readline("Enter your starting balance: "));
        $this->bet = $bet;
        $this->twoDArray = [];
        $this->winnings = 0;
    }
    private function randomizeCharacter(): string
    {
        $rand = mt_rand(1, 100);
        if ($rand <= 50) {
            $characters = "A";
        } elseif ($rand <= 75) {
            $characters = "K";
        } elseif ($rand <= 95) {
            $characters = "X";
        } else {
            $characters = "7";
        }
        return $characters[rand(0, strlen($characters) - 1)];
    }
    private function createSlot()
    {
        for ($i = 0; $i < $this->height; $i++) {
            for ($j = 0; $j < $this->width; $j++) {
                $this->twoDArray[$i][$j] = $this->randomizeCharacter();
            }
        }
    }
    private function showSlot()
    {
        for ($i = 0; $i < $this->height; $i++) {
            for ($j = 0; $j < $this->width; $j++) {
                echo "|" . $this->twoDArray[$i][$j];
            }
            echo "|";
            echo PHP_EOL;
        }
    }
    private function calculateWinAmount($string, $int): float
    {
        $multiplier = [
              'A' => 0.5,
              'K' => 1,
              'X' => 2,
              '7' => 10
        ];
        return ($int * $multiplier[$string]) / ($this->height + $this->width);
    }
    private function checkWin(): float
    {
        $winAmount = 0;

        //horizontal
        for ($i = 0; $i < $this->height; $i++) {
            $count = 0;
            for ($j = 0; $j < $this->width; $j++) {
                if ($this->twoDArray[$i][0] !== $this->twoDArray[$i][$j]) {
                    break;
                } else {
                    $count++;
                }
            }
            if ($count >= 3) {
                $winAmount += $this->bet * $this->calculateWinAmount($this->twoDArray[$i][0], $count);
            }
        }
        // vertical
        for ($j = 0; $j < $this->width; $j++) {
            $count = 0;
            for ($i = 0; $i < $this->height; $i++) {
                if ($this->twoDArray[0][$j] !== $this->twoDArray[$i][$j]) {
                    break;
                } else {
                    $count++;
                }
            }
            if ($count >= 3) {
                $winAmount += $this->bet * $this->calculateWinAmount($this->twoDArray[0][$j], $count);
            }
        }
        return $winAmount;
    }

    private function clearConsole()
    {
        echo "\n \n \n \n \n \n";
    }
    public function play()
    {
        while (true) {
            $this->clearConsole();
            if (!empty($this->twoDArray)) {
                $this->showSlot();
                echo "Winnings: " . $this->winnings . "$\n";
            }
            $this->winnings = 0;
            echo "Total Bet: $this->bet $ \n";
            echo "Balance: $this->balance $\n";
            $userInput = strtoupper(readline("Raise bet: + / Decrease bet: - / Spin: press enter / Quit Game: Q/ your choice:"));
            switch ($userInput) {
                case '+':
                    if ($this->bet + 10 <= $this->balance) {
                        $this->bet += 10;
                    } else {
                        echo "Insufficient balance to increase bet." . PHP_EOL;
                    }
                    break;
                case '-':
                    if ($this->bet - 10 > 0) {
                        $this->bet -= 10;
                    } else {
                        echo "Insufficient balance to decrease bet." . PHP_EOL;
                    }
                    break;
                case '':
                    if ($this->balance - $this->bet < 0) {
                        break;
                    }
                    $this->createSlot();
                    $this->balance -= $this->bet;
                    $winAmount = $this->checkWin();
                    if ($winAmount != 0) {
                        echo "Winnings: " . $winAmount . "\n";
                        $this->balance += $winAmount;
                        $this->winnings = $winAmount;
                    }
                    break;
                case 'Q':
                    echo "Quit Game" . PHP_EOL;
                    exit();
                default:
                    echo "Invalid choice" . PHP_EOL;
            }
        }
    }
}

$slotMachine = new SlotMachine();
$slotMachine->play();


