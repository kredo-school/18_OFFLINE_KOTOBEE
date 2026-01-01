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

    // ============================
    //   終了処理 (保存 & モーダル)
    // ============================
    function endGame() {
        clearInterval(timerInterval);
        romajiDisplay.textContent = "Time up!";

        sendResult(score);
    }

    async function sendResult(score) {
        try {
            const res = await fetch("/game/kana/save", {
                method: "POST",
                headers: {
                    "Content-Type": "application/json",
                    "X-CSRF-TOKEN": document
                        .querySelector('meta[name="csrf-token"]')
                        .getAttribute("content")
                },
                body: JSON.stringify({
                    mode: "60s-count",
                    setting_id: SETTING_ID,
                    score: score
                })
            });

            let data = null;
            try {
                data = await res.json();
            } catch (e) {
                console.warn("レスポンス JSON パース失敗:", e);
            }

            // エラー時も安全にモーダルを表示
            if (!res.ok) {
                console.error("サーバエラー:", res.status, data);
                showResultModal60s(null, score);
                return;
            }

            showResultModal60s(data, score);

        } catch (err) {
            console.error("送信失敗:", err);
            showResultModal60s(null, score);
        }
    }

    // ============================
    //     モーダル表示（60s）
    // ============================
    function showResultModal60s(data, score) {
        const modal = document.getElementById("result-modal");
        const content = document.getElementById("rank-content");

        if (!modal || !content) {
            console.error("モーダルの要素がありません");
            return;
        }

        if (!data) {
            // エラー時の表示
            content.innerHTML = `
                <p>結果を取得できませんでした。</p>
                <p>今回のスコア：${score}</p>
            `;
            modal.style.display = "block";
            return;
        }

        // 安全に値を取り出す
        const myBest = (data.my_best !== undefined && data.my_best !== null) 
            ? data.my_best 
            : "-";

        const myRank = (data.my_rank !== undefined && data.my_rank !== null)
            ? data.my_rank 
            : "-";

        const top3 = Array.isArray(data.top3) ? data.top3 : [];

        let html = `
            <p>今回のスコア：${score}</p>
            <p>あなたのベスト：${myBest}</p>
            <p>あなたの順位：${myRank}</p>
            <hr><h3>Top 3</h3>
        `;

        const trophyColors = ["#DAA520", "#C0C0C0", "#CD7F32"];

        top3.forEach((r, i) => {
            const name = r.name ?? "NoName";

            // value または score のどちらかを安全に取得
            const value = (r.value !== undefined)
                ? r.value
                : (r.score !== undefined ? r.score : null);

            // 数値ならそのまま表示（60s は秒ではないので小数点処理はしない）
            const valueLabel = (value !== null && !isNaN(Number(value)))
                ? value
                : "-";

            html += `
                <p class="rank-line">
                    <span class="trophy-bg" style="background:white;">
                        <i class="fa-solid fa-trophy" style="color:${trophyColors[i]};"></i>
                    </span>
                    ${name}：${valueLabel}
                </p>
            `;
        });

        // バッジ獲得情報の表示
        if (data.badge) {
            html += `
                <hr>
                <div class="badge-win">
                    <p>${data.badge.name_hiragana} をゲットしました！🎉</p>
                    <img src="/storage/images/badges_modal/${data.badge.file_name}" class="badge-spin">
                </div>
            `;
        }

        content.innerHTML = html;
        modal.style.display = "block";
    }

    // ============================
    //       スタート処理
    // ============================
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
