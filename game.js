const drawBtn = document.getElementById("drawBtn");
const resetBtn = document.getElementById("resetBtn");
const card1 = document.getElementById("card1");
const card2 = document.getElementById("card2");
const player1 = document.getElementById("player1");
const player2 = document.getElementById("player2");
const result = document.getElementById("result");
const remainingEl = document.getElementById("remaining");
const deckContainer = document.getElementById("deck-array");
const flipSound = document.getElementById("flip-sound");

function wait(ms) {
    return new Promise(resolve => setTimeout(resolve, ms));
}

function resetCards() {
    card1.classList.remove("flip");
    card2.classList.remove("flip");
    player1.src = "images/back.png";
    player2.src = "images/back.png";
    void card1.offsetWidth;
}

async function flipCard(cardEl, imgEl, newImg, delay = 600) {
    if (delay) await wait(delay);
    cardEl.classList.remove("flip");
    void cardEl.offsetWidth;
    cardEl.classList.add("flip");
    flipSound.currentTime = 0;
    flipSound.play();
    await wait(375);
    imgEl.src = "images/" + newImg;
}

// Update remaining deck display as images
function updateDeckDisplay(cards) {
    const deckContainer = document.getElementById("deck-array");
    deckContainer.innerHTML = ""; // clear previous cards
    cards.forEach(card => {
        const img = document.createElement("img");
        img.src = "images/" + card;
        img.alt = card;
        deckContainer.appendChild(img);
    });
}

drawBtn.addEventListener("click", async () => {
    if (drawBtn.disabled) return;
    drawBtn.disabled = true;
    resetCards();
    result.textContent = "";

    try {
        const res = await fetch("game.php");
        const data = await res.json();

        await flipCard(card1, player1, data.player1);
        await flipCard(card2, player2, data.player2, 200);

        result.textContent = data.result;
        document.getElementById("score1").textContent = data.score.player1;
        document.getElementById("score2").textContent = data.score.player2;

        remainingEl.textContent = `Cards remaining: ${data.remaining}`;
        if (data.remaining <= 10) remainingEl.classList.add("low");
        else remainingEl.classList.remove("low");

        updateDeckDisplay(data.remainingCards);
        drawBtn.disabled = data.gameOver;

    } catch (err) {
        console.error(err);
        drawBtn.disabled = false;
    }
});

resetBtn.addEventListener("click", async () => {
    if (resetBtn.disabled) return;
    resetBtn.disabled = true;

    try {
        const res = await fetch("game.php?reset=1");
        const data = await res.json();

        resetCards();
        result.textContent = data.result;
        remainingEl.textContent = "Cards remaining: 52";
        remainingEl.classList.remove("low");
        deckContainer.innerHTML = "";
        document.getElementById("score1").textContent = data.score.player1;
        document.getElementById("score2").textContent = data.score.player2;

        drawBtn.disabled = false;
    } catch (err) {
        console.error(err);
    } finally {
        resetBtn.disabled = false;
    }
});

// On page load, disable Draw if deck is empty
window.addEventListener('DOMContentLoaded', () => {
    const remainingCount = parseInt(remainingEl.textContent.replace(/\D/g, ''), 10);
    if (remainingCount === 0) drawBtn.disabled = true;
});
