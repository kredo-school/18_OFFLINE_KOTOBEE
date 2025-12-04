import { showResultModal, bindAgainButton } from "./game_result_modal";

document.addEventListener("DOMContentLoaded", () => {

    if (MODE !== "60s-count") return;

    let timer = 60;
    let score = 0;
    let currentQuestion = null;
    let timerInterval = null;

    const romajiDisplay = document.getElementById("romaji-display");
    const scoreDisplay = document.getElementById("score-display");
    const timerDisplay = document.getElementById("timer-display");

    const buttons = document.querySelectorAll(".kana-button");

    function pickRandomQuestion() {
        let q = QUESTIONS[Math.floor(Math.random() * QUESTIONS.length)];
        if (q.kana_char === "" || q.romaji === "") return pickRandomQuestion();
        return q;
    }

    function showNextQuestion() {
        currentQuestion = pickRandomQuestion();
        romajiDisplay.textContent = currentQuestion.romaji;
    }

    buttons.forEach(btn => {
        btn.addEventListener("click", () => {
            const userRomaji = btn.dataset.romaji;
            if (!currentQuestion) return;

            if (userRomaji === currentQuestion.romaji) {
                score++;
                scoreDisplay.textContent = score;
                showNextQuestion();
            }
        });
    });

    function startTimer() {
        timerInterval = setInterval(() => {
            timer--;
            timerDisplay.textContent = timer;

            if (timer <= 0) {
                clearInterval(timerInterval);
                endGame();
            }
        }, 1000);
    }

    function endGame() {
        clearInterval(timerInterval);
        romajiDisplay.textContent = "Time up!";

        fetch("/game/kana/save", {
            method: "POST",
            headers: {
                "Content-Type": "application/json",
                "X-CSRF-TOKEN": document.querySelector('meta[name="csrf-token"]').content
            },
            body: JSON.stringify({
                score: score,
                setting_id: SETTING_ID,
                game_id: 1,
            })
        })
        .then(res => res.json())
        .then(data => {
            console.log("保存成功:", data);
            showResultModal(data);
        })
        .catch(err => console.error("保存エラー:", err));
    }

    function startGame() {
        score = 0;
        timer = 60;

        scoreDisplay.textContent = score;
        timerDisplay.textContent = timer;

        showNextQuestion();
        startTimer();
    }

    bindAgainButton(startGame);

    startGame();

});
