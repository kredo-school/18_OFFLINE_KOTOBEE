/**
 * Kana Game - Time Attack Mode
 */

document.addEventListener("DOMContentLoaded", () => {

    if (MODE !== "timeattack") return;

    let startTime = null;
    let timerInterval = null;
    let romajiToKanaMap = {};

    const romajiDisplay = document.getElementById("romaji-display");
    const scoreDisplay = document.getElementById("score-display"); // ← 使用しない
    const timerDisplay = document.getElementById("timer-display");

    const cards = Array.from(document.querySelectorAll(".kana-button"));

    // ▼ romaji → kana の辞書作成
    cards.forEach(card => {
        romajiToKanaMap[card.dataset.romaji] = card.dataset.kana;
    });

    let usedRomaji = null;

    function pickNewQuestion() {
        const romajiList = Object.keys(romajiToKanaMap);

        // 全部消えた → ゴール
        if (romajiList.length === 0) {
            endGame();
            return;
        }

        let romaji;
        do {
            romaji = romajiList[Math.floor(Math.random() * romajiList.length)];
        } while (romajiList.length > 1 && romaji === usedRomaji);

        usedRomaji = romaji;
        romajiDisplay.textContent = romaji;
    }

    // ▼ カードクリック処理
    cards.forEach(card => {
        card.addEventListener("click", () => {
            const kana = card.dataset.kana;
            const nowRomaji = romajiDisplay.textContent;
            const correctKana = romajiToKanaMap[nowRomaji];

            if (kana === correctKana) {
                // 正解 → カード削除
                delete romajiToKanaMap[nowRomaji];
                card.remove();

                pickNewQuestion();
            } else {
                // 不正解
                card.classList.add("shake");
                setTimeout(() => card.classList.remove("shake"), 400);
            }
        });
    });

    // ▼ 終了
    function endGame() {
        clearInterval(timerInterval);

        const time = (performance.now() - startTime) / 1000;
        const timeSec = time.toFixed(2);

        timerDisplay.textContent = timeSec;

        sendResult(timeSec);
    }

    // ▼ 結果送信
    function sendResult(timeSec) {

        fetch("/game/kana/result", {
            method: "POST",
            headers: {
                "Content-Type": "application/json",
                "X-CSRF-TOKEN": document
                    .querySelector('meta[name="csrf-token"]')
                    .getAttribute("content")
            },
            body: JSON.stringify({
                mode: "timeattack",
                setting_id: SETTING_ID,
                time: timeSec
            })
        })
            .then(res => res.json())
            .then(data => {
                showResultModal(data.result_id, timeSec);
            })
            .catch(err => {
                console.error("Error sending result:", err);
                showResultModal(null, timeSec);
            });
    }

    // ▼ モーダル表示
    function showResultModal(resultId, timeSec) {
        const modal = document.getElementById("result-modal");
        const scoreLabel = document.getElementById("result-score");
        const resultIdField = document.getElementById("result-id-field");

        scoreLabel.textContent = timeSec + " 秒";
        resultIdField.value = resultId ?? "";

        modal.style.display = "flex";
    }

    // ▼ Start Game
    function startGame() {
        startTime = performance.now();

        timerInterval = setInterval(() => {
            const now = (performance.now() - startTime) / 1000;
            timerDisplay.textContent = now.toFixed(2);
        }, 50);

        pickNewQuestion();
    }

    startGame();
});
