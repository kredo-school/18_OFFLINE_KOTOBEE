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
                // card.remove();
                // card.classList.add("card-disabled");
                card.style.visibility = 'hidden';
                
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

    // 送信：結果をサーバへ（改良版）
    async function sendResult(timeSec) {
        // timeSec を数値化（受け取ったものが文字列や null の可能性があるため）
        const timeNum = (typeof timeSec === 'number') ? timeSec : Number(timeSec);

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
                    mode: "timeattack",
                    setting_id: SETTING_ID,
                    play_time: isNaN(timeNum) ? null : timeNum  // null にできる
                })
            });

            // レスポンス JSON を試みる
            let data = null;
            try {
                data = await res.json();
            } catch (e) {
                console.warn("レスポンスを JSON にパースできませんでした:", e);
                data = null;
            }

            if (!res.ok) {
                console.error("サーバ応答エラー", res.status, data);
                // サーバがエラーを返したら data を null にしてモーダルを出す（安全）
                showResultModal(null, timeNum);
                return;
            }

            // 正常系
            showResultModal(data, timeNum);

        } catch (err) {
            console.error("Error sending result:", err);
            showResultModal(null, timeSec);
        }
    }

    // モーダル表示（改良版：data や timeSec が null / 型不一致でも安全に動作）
    function showResultModal(data, timeSec) {
        // safe conversion: number にならなければ null
        const timeNum = (typeof timeSec === 'number' && !isNaN(timeSec))
            ? timeSec
            : (typeof timeSec === 'string' && timeSec.trim() !== '' && !isNaN(Number(timeSec)))
                ? Number(timeSec)
                : null;

        // DOM 要素は実際に存在する id 名に合わせてください
        // ここでは例として result-modal / rank-content を使います（既存の blade に合わせてください）
        const modal = document.getElementById("result-modal");
        const content = document.getElementById("rank-content");

        if (!modal || !content) {
            console.error("モーダル要素が見つかりません。id を確認してください。");
            return;
        }

        if (!data) {
            // サーバ応答がない・エラー時の表示（やさしく）
            let html = `<p>結果を取得できませんでした。</p>`;
            if (timeNum !== null) {
                html += `<p>あなたの記録: ${timeNum.toFixed(2)} 秒（ローカル表示）</p>`;
            }
            content.innerHTML = html;
            modal.style.display = "block";
            return;
        }

        // data の中身を確認してから使う（安全）
        const myBest = (data.my_best !== undefined && data.my_best !== null) ? Number(data.my_best) : null;
        const myRank = (data.my_rank !== undefined && data.my_rank !== null) ? data.my_rank : '-';
        const top3 = Array.isArray(data.top3) ? data.top3 : [];

        let html = "";
        if (timeNum !== null) {
            html += `<p>今回の記録：${timeNum.toFixed(2)} 秒</p>`;
        }
        html += `<p>あなたのベスト：${myBest !== null ? myBest.toFixed(2) + " 秒" : '-'}</p>`;
        html += `<p>あなたの順位：${myRank}</p>`;
        html += `<hr><h3>Top 3</h3>`;

        const trophyColors = ["#DAA520", "#C0C0C0", "#CD7F32"];

        top3.forEach((r, i) => {
            // r は { name, value } あるいは { name, score } の可能性があるので安全に参照
            const name = r.name ?? 'NoName';
            const value = (r.value !== undefined) ? r.value : (r.score !== undefined ? r.score : null);

            // value が数値なら小数 2 桁で表示（timeattack は秒）
            const valueLabel = (value !== null && !isNaN(Number(value)))
                ? Number(value).toFixed(2)
                : (value !== null ? value : '-');

            html += `
                <p class="rank-line">
                    <span class="trophy-bg" style="background:white;">
                        <i class="fa-solid fa-trophy" style="color:${trophyColors[i]};"></i>
                    </span>
                    ${name}：${valueLabel}
                </p>
            `;
        });

        content.innerHTML = html;
        modal.style.display = "block";
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
