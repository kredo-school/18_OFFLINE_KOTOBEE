// モーダルを開く
async function open_stage_modal(url) {
    if (!url || url === "#") return;

    const res = await fetch(url, {
        headers: { "X-Requested-With": "XMLHttpRequest" }, 
    });

    const html = await res.text();
    const root = document.getElementById("start-modal-root");
    root.innerHTML = html;

    // 背景クリックで閉じる（#modal がオーバーレイ想定）
    const modal = document.getElementById("modal");
    modal?.addEventListener("click", (e) => {
        if (e.target.id === "modal") close_modal();
    });

    // Backボタンで閉じる（start_modal 側に class="js-close-modal" が必要）
    document.querySelector(".js-close-modal")?.addEventListener("click", close_modal);

    // ESCで閉じる（任意）
    document.addEventListener("keydown", esc_close_once);

    // 任意：背景スクロール停止
    document.body.style.overflow = "hidden";
}

function close_modal() {
    const root = document.getElementById("start-modal-root");
    root.innerHTML = "";
    document.removeEventListener("keydown", esc_close_once);
    document.body.style.overflow = "";
}

function esc_close_once(e) {
    if (e.key === "Escape") close_modal();
}

// 六角形クリックで遷移せずにモーダルを出す（イベントデリゲーション）
document.addEventListener("DOMContentLoaded", () => {
    const container = document.getElementById("circle");
    if (!container) return;

    container.addEventListener("click", (e) => {
        const a = e.target.closest("a.hex");
        if (!a) return;

        const url = a.getAttribute("href");

        // unplayed は href を消してるので、hrefが無い / "#" の場合は何もしない
        if (!url || url === "#") {
            e.preventDefault();
            return;
        }

        // 画面遷移を止める
        e.preventDefault();

        // 同じ画面でモーダル表示
        open_stage_modal(url);
    });
});

// カナゲーム用
document.addEventListener("click", (e) => {
    const a = e.target.closest("a.js-open-start-modal");
    if (!a) return;
  
    e.preventDefault();
    open_stage_modal(a.href);
  });
  
