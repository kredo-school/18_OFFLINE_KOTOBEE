document.addEventListener("DOMContentLoaded", () => {

    if (MODE !== "60s-count") return; // 60s-count のときだけ起動
    
    let timer = 60;
    let score = 0;
    let currentQuestion = null;
    let timerInterval = null;

    const romajiDisplay = document.getElementById("romaji-display");
    const scoreDisplay = document.getElementById("score-display");
    const timerDisplay = document.getElementById("timer-display");

    const buttons = document.querySelectorAll(".kana-button"); 
    // ↑ Blade側では kana_char のボタンに class="kana-button" を付ける

    // -------------------------------------------
    // ランダムで問題を選ぶ（空白はスキップ）
    // -------------------------------------------
    function pickRandomQuestion() {
        let q = QUESTIONS[Math.floor(Math.random() * QUESTIONS.length)];
        
        // 空白だったら再取得
        if (q.kana_char === "" || q.romaji === "") {
            return pickRandomQuestion();
        }
        return q;
    }

    // -------------------------------------------
    // 新しい問題を表示
    // -------------------------------------------
    function showNextQuestion() {
        currentQuestion = pickRandomQuestion();
        romajiDisplay.textContent = currentQuestion.romaji;
    }

    // -------------------------------------------
    // ボタンを押したときの判定
    // -------------------------------------------
    buttons.forEach(btn => {
        btn.addEventListener("click", () => {
            const userKana = btn.dataset.kana; 
            const userRomaji = btn.dataset.romaji; 

            if (!currentQuestion) return;

            if (userRomaji === currentQuestion.romaji) {
                score++;
                scoreDisplay.textContent = score;
                showNextQuestion();
            }
        });
    });

    // -------------------------------------------
    // タイマーの開始
    // -------------------------------------------
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

    // -------------------------------------------
    // 終了処理
    // -------------------------------------------
    function endGame() {
        clearInterval(timerInterval);

        romajiDisplay.textContent = "Time up!";

        // ----------- ① DBへ保存 -----------
        fetch("/game/kana/save", {
            method: "POST",
            headers: {
                "Content-Type": "application/json",
                "X-CSRF-TOKEN": document.querySelector('meta[name="csrf-token"]').content
            },
            body: JSON.stringify({
                score: score,
                setting_id: SETTING_ID, // blade から埋め込む
                game_id: 1,             // ひらがなゲーム固定
            })
        })
        .then(res => res.json())
        .then(data => {
            console.log("保存成功:", data);

            // ----------- ② モーダル表示 -----------
            showResultModal(data);
        })
        .catch(err => {
            console.error("保存エラー:", err);
        });
    }

    function showResultModal(data) {
        const modal = document.getElementById("result-modal");
        const content = document.getElementById("rank-content");

        let html = `
            <p>Your best score：${data.my_score}</p>
            <p>Your rank：${data.my_rank}</p>
            <hr>
            <h3>Top 3</h3>
        `;

        const trophyColors = ["#DAA520", "#C0C0C0", "#CD7F32"];

        data.top3.forEach((r, i) => {
            html += `
                <p class="rank-line">
                    <span class="trophy-bg" style="background:white;">
                        <i class="fa-solid fa-trophy" style="color:${trophyColors[i]};"></i>
                    </span>
                    ${r.name}：${r.score}
                </p>
            `;
        });

        content.innerHTML = html;
        modal.style.display = "block";
    }

    function closeModal() {
        document.getElementById("result-modal").style.display = "none";
    }
    
    // モーダルの再挑戦ボタン
    document.getElementById("again-btn").addEventListener("click", () => {
        // モーダル閉じる
        closeModal();

        // ゲーム再スタート（既存の startGame 呼び出し）
        startGame();
    });

    // -------------------------------------------
    // ゲーム開始
    // -------------------------------------------
    function startGame() {
        score = 0;
        timer = 60;

        scoreDisplay.textContent = score;
        timerDisplay.textContent = timer;

        showNextQuestion();
        startTimer();
    }

    // ページ読み込みでゲーム開始
    startGame();

});
