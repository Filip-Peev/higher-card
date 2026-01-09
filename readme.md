# Simple Card Game

A full-stack, interactive web-based card game where two players compete for the highest card. This project demonstrates a seamless bridge between a **PHP backend API** and an **Asynchronous JavaScript frontend** with hardware-accelerated CSS animations.

---

### Key Features
* **3D Animated Gameplay**: Utilizes CSS3 `perspective` and `backface-visibility` to create realistic 180-degree card flips.
* **Asynchronous Engine**: Uses the **JavaScript Fetch API** and `async/await` to handle game logic without page reloads.
* **Persistent Scoring**: Leverages PHP `$_SESSION` to maintain scores and deck state across browser refreshes.
* **Dynamic Visuals**: 
    * **Visual Deck**: Displays remaining cards as thumbnails that update in real-time.
    * **Low Deck Alerts**: The UI dynamically changes color (dark red) when the deck is running low (10 cards or fewer).
* **Smart State Management**: Buttons are context-aware, disabling during animations or when the game is over to prevent logic conflicts.

---

### Game Logic & Tie-Breaking
Winning conditions are calculated server-side in `game.php` for security and consistency:
1.  **Rank**: Standard card ranks (2 through Ace).
2.  **Suit (The Tie-breaker)**: If ranks are identical, a winner is determined by suit strength in the following order: 
    * ♣️ Clubs < ♦️ Diamonds < ♥️ Hearts < ♠️ Spades.
3.  **Endgame**: The game tracks the 52-card deck. When the deck is empty, a final winner is declared based on the cumulative round scores.

---

### Project Structure
```text
├── images/          # Card assets (Format: suit_rank.png)
├── sounds/          # Audio assets (flip.mp3)
├── index.php        # The skeleton: PHP/HTML structure and initial state
├── game.php         # The API: Logic, Shuffling, and JSON state delivery
├── game.js          # The Engine: Animation sequencing and AJAX calls
└── style.css        # The Skin: 3D transforms, Flexbox layout, and UI alerts
```

###  Tech Stack
Frontend: HTML5, CSS3 (3D Transforms & Flexbox), JavaScript (ES6+)

Backend: PHP (REST-style JSON API, Session management)

Audio: HTML5 Audio API