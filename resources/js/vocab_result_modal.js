function showVocabResult(data) {
    const modal = document.getElementById("result-modal");
    const content = document.getElementById("rank-content");

    let html = `
        <p>あなたのタイム：<strong>${data.time} sec</strong></p>
        <p>あなたの順位：${data.my_rank}</p>
        <p>あなたのベストタイム：${data.my_best} sec</p>
    `;

    

    html += `
        <hr>
        <h3>Top 3</h3>
    `;

    const trophyColors = ["#DAA520", "#C0C0C0", "#CD7F32"];
    data.top3.forEach((player, index) => {
        html += `
            <p class="rank-line">
                <span class="trophy-bg" style="background:white;">
                    <i class="fa-solid fa-trophy" style="color:${trophyColors[index]};"></i>
                </span>
                ${player.name}：${player.value} sec
            </p>
        `;
    });

     // ✅ バッジ獲得情報の表示
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

    // モーダル表示
    showResultModal();
}

export function showResultModal() {
    const modal = document.getElementById("result-modal");
    if (modal) modal.style.display = "block";

    const overlay = document.getElementById("modal-overlay");
    if (overlay) overlay.style.display = "block";
}

export function closeResultModal() {
    const modal = document.getElementById("result-modal");
    if (modal) modal.style.display = "none";

    const overlay = document.getElementById("modal-overlay");
    if (overlay) overlay.style.display = "none";
}

window.showVocabResult = showVocabResult;
