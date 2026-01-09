<?php session_start(); ?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <title>Simple Card Game</title>
    <meta name="viewport" content="width=device-width,initial-scale=1" />
    <link rel="stylesheet" href="style.css">
</head>

<body>

    <h1>Simple Card Game</h1>

    <div class="buttons">
        <button id="drawBtn">Draw Cards</button>
        <button id="resetBtn">Reset Score</button>
    </div>

    <div id="game">
        <!-- Player 1 -->
        <div class="card">
            <div class="card-inner" id="card1">
                <div class="card-front">
                    <img id="player1" src="images/back.png" width="120" alt="Player 1 card">
                </div>
                <div class="card-back">
                    <img src="images/back.png" width="120" alt="Card back">
                </div>
            </div>
        </div>

        <!-- Player 2 -->
        <div class="card">
            <div class="card-inner" id="card2">
                <div class="card-front">
                    <img id="player2" src="images/back.png" width="120" alt="Player 2 card">
                </div>
                <div class="card-back">
                    <img src="images/back.png" width="120" alt="Card back">
                </div>
            </div>
        </div>
    </div>

    <h2 id="result"></h2>

    <div id="scoreboard">
        <h3>Score</h3>
        <p>Player 1: <span id="score1"><?php echo $_SESSION['score']['player1'] ?? 0; ?></span></p>
        <p>Player 2: <span id="score2"><?php echo $_SESSION['score']['player2'] ?? 0; ?></span></p>
    </div>

    <p id="remaining">
        Cards remaining: <?php echo isset($_SESSION['deck']) ? count($_SESSION['deck']) : 52; ?>
    </p>

    <!-- Visual remaining deck -->
    <div id="deck-array" style="margin-top:10px; max-width:600px; display:flex; flex-wrap:wrap; gap:5px;"></div>

    <audio id="flip-sound" src="sounds/flip.mp3"></audio>

    <script src="game.js"></script>
</body>

</html>