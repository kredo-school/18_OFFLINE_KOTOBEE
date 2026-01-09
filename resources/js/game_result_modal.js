export function showResultModal(data) {
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

export function closeResultModal() {
    const modal = document.getElementById("result-modal");
    if (modal) modal.style.display = "none";
}

export function bindAgainButton(startCallback) {
    const btn = document.getElementById("again-btn");
    if (!btn) return;

    btn.addEventListener("click", () => {
        closeResultModal();
        if (startCallback) startCallback();
    });
}

//// grammarゲーム用開始ボタン /////
export function bindAgainButtonGrammer() {

    
    const btn = document.getElementById("again-btn");
    if (!btn) return;

    btn.addEventListener("click", () => {
        
        // modalを非表示
        const modal = document.getElementById('result-modal');
        if (modal) {
            modal.style.display = 'none';
        }

        // すでに取得済みの問題データでゲームを再スタート
        if (window.questions) {
            window.startGrammarGame(window.questions, window.grammar_stage_id);
        }

    });
}
