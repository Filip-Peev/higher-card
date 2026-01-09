<?php
session_start();
header("Content-Type: application/json");

$ranks = ['2','3','4','5','6','7','8','9','10','J','Q','K','A'];
$suits = ['clubs','diamonds','hearts','spades'];

// Initialize deck on first load or reset
if (!isset($_SESSION['deck']) || empty($_SESSION['deck'])) {
    $deck = [];
    foreach ($suits as $suit) {
        foreach ($ranks as $rank) {
            $deck[] = $suit . "_" . $rank . ".png";
        }
    }
    shuffle($deck);
    $_SESSION['deck'] = $deck;
}

// Initialize score if empty
if (!isset($_SESSION['score'])) {
    $_SESSION['score'] = ['player1'=>0,'player2'=>0];
}

// Handle reset
if (isset($_GET['reset']) && $_GET['reset'] == 1) {
    $_SESSION['score'] = ['player1'=>0,'player2'=>0];
    $_SESSION['deck'] = null;
    echo json_encode([
        'player1'=>'back.png',
        'player2'=>'back.png',
        'result'=>"Score Reset!",
        'score'=>$_SESSION['score'],
        'remaining'=>52,
        'gameOver'=>false,
        'remainingCards'=>[]
    ]);
    exit;
}

// Reference deck
$deck = &$_SESSION['deck'];

// If fewer than 2 cards, game over
if (count($deck) < 2) {
    $deckEmpty = true;
    $player1 = $player2 = 'back.png';
    $result = "Game Over! ";
    if ($_SESSION['score']['player1'] > $_SESSION['score']['player2']) $result .= "Player 1 wins the deck!";
    elseif ($_SESSION['score']['player2'] > $_SESSION['score']['player1']) $result .= "Player 2 wins the deck!";
    else $result .= "It's a Tie!";
    echo json_encode([
        'player1'=>$player1,
        'player2'=>$player2,
        'result'=>$result,
        'score'=>$_SESSION['score'],
        'remaining'=>count($deck),
        'gameOver'=>true,
        'remainingCards'=>$deck
    ]);
    exit;
}

// Draw random cards
$p1Index = array_rand($deck);
$player1 = $deck[$p1Index];
unset($deck[$p1Index]);

$p2Index = array_rand($deck);
$player2 = $deck[$p2Index];
unset($deck[$p2Index]);

$_SESSION['deck'] = array_values($deck);

// Helper functions
function getRankValue($card, $ranks) {
    $parts = explode("_", basename($card, ".png"));
    return array_search($parts[1], $ranks);
}

function getSuitValue($card, $suits) {
    $parts = explode("_", basename($card, ".png"));
    return array_search($parts[0], $suits);
}

// Determine round winner
$p1Rank = getRankValue($player1, $ranks);
$p2Rank = getRankValue($player2, $ranks);

$result = "It's a Tie!";
if ($p1Rank > $p2Rank) {
    $result = "Player 1 Wins!";
    $_SESSION['score']['player1']++;
} elseif ($p2Rank > $p1Rank) {
    $result = "Player 2 Wins!";
    $_SESSION['score']['player2']++;
} else {
    $p1Suit = getSuitValue($player1, $suits);
    $p2Suit = getSuitValue($player2, $suits);
    if ($p1Suit > $p2Suit) {
        $result = "Player 1 Wins by Suit!";
        $_SESSION['score']['player1']++;
    } elseif ($p2Suit > $p1Suit) {
        $result = "Player 2 Wins by Suit!";
        $_SESSION['score']['player2']++;
    }
}

// Check if deck is empty after draw
$deckEmpty = count($deck) === 0;
if ($deckEmpty) {
    if ($_SESSION['score']['player1'] > $_SESSION['score']['player2']) $result = "Game Over! Player 1 wins the deck!";
    elseif ($_SESSION['score']['player2'] > $_SESSION['score']['player1']) $result = "Game Over! Player 2 wins the deck!";
    else $result = "Game Over! It's a Tie!";
}

// Return JSON
echo json_encode([
    'player1'=>$player1,
    'player2'=>$player2,
    'result'=>$result,
    'score'=>$_SESSION['score'],
    'remaining'=>count($_SESSION['deck']),
    'gameOver'=>$deckEmpty,
    'remainingCards'=>$_SESSION['deck']
]);
