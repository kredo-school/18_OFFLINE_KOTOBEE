const container = document.getElementById("circle");
const info = document.getElementById("stage-info");


const count = 6;
const gap = 5;

const h = 86;
const w = 100;
const w_theta = w / 2 + w / 4 + gap;
const h_theta = 2 * h + h / 2 + 2 * gap + gap / 2;
const r = Math.sqrt( w_theta**2 + h_theta**2 );
const theta = Math.acos(w_theta / r); // ラジアン
const theta_deg = 90 - (theta * (180 / Math.PI)); // 度数に変換            
console.log('theta', theta);

// 中心に移動するための配列
const main_centers = [];

// 共通の六角形作成のSVGパス
// const HEX_PATH_D = "M90.6973 33.5427C94.0734 39.3867 94.1801 46.5455 91.018 52.4733L90.7027 53.0427L77.2561 76.3473C73.7745 82.3815 67.3378 86.101 60.3711 86.103L33.4649 86.1105C26.4983 86.1124 20.0595 82.3965 16.5745 76.3642L3.11483 53.0672C-0.26126 47.2233 -0.368008 40.0644 2.79412 34.1366L3.10939 33.5672L16.556 10.2627C20.0376 4.22842 26.4743 0.509948 33.441 0.507956L60.3472 0.500441C67.3138 0.498542 73.7526 4.21257 77.2376 10.2447L90.6973 33.5427Z";


const HEX_SVG_TEMPLATE = `
<svg
  class="hex-svg"
  width="150"
  height="150"
  viewBox="0 0 101 101"
  fill="none"
  xmlns="http://www.w3.org/2000/svg"
>
  <path
    d="M92.073 41.2522C95.2006 46.6658 95.2025 53.3368 92.0779 58.7522L78.6313 82.0577C75.5067 87.4729 69.7304 90.8102 63.4785 90.812L36.5722 90.8195C30.3203 90.8212 24.5421 87.4871 21.4145 82.0737L7.95488 58.7757C4.82733 53.3621 4.82546 46.6911 7.95 41.2757L21.3966 17.9702C24.5212 12.5551 30.2975 9.21873 36.5494 9.21694L63.4557 9.20943C69.7076 9.20773 75.4858 12.5408 78.6134 17.9542L92.073 41.2522Z"
    fill="#F09813"
    stroke="#AF6607"
    stroke-width="5"
  />
  <path
    d="M22.5806 52.5984C21.6096 51.0022 21.6096 48.9978 22.5806 47.4016L34.2892 28.1529C35.1963 26.6616 36.8155 25.7513 38.561 25.7513H62.439C64.1845 25.7513 65.8037 26.6616 66.7108 28.1529L78.4194 47.4016C79.3904 48.9978 79.3904 51.0022 78.4194 52.5984L66.7108 71.8471C65.8037 73.3384 64.1845 74.2487 62.439 74.2487H38.561C36.8155 74.2487 35.1963 73.3384 34.2892 71.8471L22.5806 52.5984Z"
    fill="#FFB54E"
    fill-opacity="0.37"
  />
</svg>
`;


// 各ステージのurlの配列controls
const hrefs = window.stage_urls || [];
let stage_id = 0;

// 次のurlを取り出す
function next_href(stage_id) {
    // URL があれば使う、なければ "#"
    return hrefs[stage_id] || "#";
}

// そのstageをplay済みかそうでないか
function played_stage_check(hex, stage_id) {

    const is_played = stage_id === 0 || played_stage_ids.has(stage_id);

    // すでにplay済みか判定
    if (is_played) {
        hex.classList.add("played");
    } else {
        hex.classList.add("unplayed");

        // クリックを不可にする
        hex.removeAttribute("href");

        // 念の為JS側も止める
        hex.addEventListener("click", (e) => {
            e.preventDefault();
        });
    }
}

// でかい背景
function fit_background_to_hexes() {
    const bg = container.querySelector(".hex-bg");
    if (!bg) return;
  
    const hexes = container.querySelectorAll(".hex");
    if (!hexes.length) return;
  
    const containerRect = container.getBoundingClientRect();
  
    let minX = Infinity, minY = Infinity, maxX = -Infinity, maxY = -Infinity;
  
    hexes.forEach((el) => {
      const r = el.getBoundingClientRect();
  
      // container左上を(0,0)にした相対座標に変換
      const x1 = r.left - containerRect.left;
      const y1 = r.top - containerRect.top;
      const x2 = r.right - containerRect.left;
      const y2 = r.bottom - containerRect.top;
  
      minX = Math.min(minX, x1);
      minY = Math.min(minY, y1);
      maxX = Math.max(maxX, x2);
      maxY = Math.max(maxY, y2);
    });
  
    const pad = 30; // 余白（大きめ推奨）
    const left = minX - pad;
    const top = minY - pad;
    const width = (maxX - minX) + pad * 2;
    const height = (maxY - minY) + pad * 2;
  
    bg.style.transform = `translate(${left}px, ${top}px)`;
    bg.style.width = `${width}px`;
    bg.style.height = `${height}px`;
}
  


// 各ステージのidセット
const played_stage_ids = new Set(window.played_stage_ids);

// console.log("played_stage_ids", played_stage_ids);

// // 仮で数字を入れる
// const num_to_add = [3, 4, 5, 6, 7, 8, 9, 10, 11, 12, 13, 14, 21];

// num_to_add.forEach(num => {
//     played_stage_ids.add(num);
// });



function create_parent_hex(p_index, p_label, stage_id, x, y) {

    // url呼び出し
    const href = next_href(stage_id);


    const hex = create_hex_element(p_label, href);
    hex.style.transform = `translate(${x}px, ${y}px)`; 
    
    // プレイ済みか
    played_stage_check(hex, stage_id);

    container.appendChild(hex);

    // 周りの六角形の中心座標とhexとその周りの子六角形のhexを格納
    main_centers[p_index] = {x: x, y: y, pre_hex: hex, children: []};

    stage_id++;

    return stage_id;

}

function create_child_hex(p_index, c_label, stage_id, child_x, child_y) {

    const child_href = next_href(stage_id);

    const child_hex = create_hex_element(c_label, child_href);
    child_hex.classList.add("child");
    child_hex.style.transform = `translate(${child_x}px, ${child_y}px)`;
    
    // プレイ済みか
    played_stage_check(child_hex, stage_id);

    container.appendChild(child_hex);

    // child_hexを格納
    main_centers[p_index].children.push(child_hex);

    stage_id++;

    return stage_id;

}

function create_hex_element(label_text, href="#") {
    const a = document.createElement("a");
    a.className = "hex";
    a.href = href;
  
    a.innerHTML = `
        ${HEX_SVG_TEMPLATE}
        <span class="hex-label">${label_text}</span>
    `;
    return a;
  }
  

for (let i = 0; i < count; i++) {
    const start_angle = -(90 + theta_deg);
    const angle = (360 / count) * i + start_angle;
    const rad = angle * (Math.PI / 180); // ラジアン変換

    const x = r * Math.cos(rad);
    const y = r * Math.sin(rad);
    
    // 表示ラベル
    const p_label = `${i + 1}-1`;

    // 中心hex
    stage_id = create_parent_hex(i, p_label, stage_id, x, y);

    const child_count = 6;
    const child_r = 86 + gap;

    for (let j = 0; j < child_count; j++) {
        const start_angle = -90;
        const child_angle = (360 / child_count) * j + start_angle;
        const child_rad = child_angle * (Math.PI / 180);

        const child_x = x + child_r * Math.cos(child_rad);
        const child_y = y + child_r * Math.sin(child_rad);

        // 表示ラベル
        const c_label = `${i + 1}-${j + 2}`;
        
        // 中心hexの周りの子hex
        stage_id = create_child_hex(i, c_label, stage_id, child_x, child_y);

    }

}

// 中心hex
stage_id = create_parent_hex(count, `${count + 1}-${1}`, stage_id, 0, 0);

const child_count = 6;
const child_r = 86 + gap;

for (let j = 0; j < child_count; j++) {
    const start_angle = -90;
    const child_angle = (360 / child_count) * j + start_angle;
    const child_rad = child_angle * (Math.PI / 180);

    const child_x = 0 + child_r * Math.cos(child_rad);
    const child_y = 0 + child_r * Math.sin(child_rad);    
    
     // 中心hexの周りの子hex
    stage_id = create_child_hex(count, `${count + 1}-${j + 2}`, stage_id, child_x, child_y);

}

fit_background_to_hexes();


function update_focus(focus_index) {

    const data = main_centers[focus_index];

    if (!data) return;

    // 全ての親から.focusedと.clickableを外す
    main_centers.forEach(item => {

        if (!item) return;

        if (item.pre_hex) {
            item.pre_hex.classList.remove("focused", "clickable");
        }

        if (item.children) {
            item.children.forEach(child => child.classList.remove("clickable"));
        }

    });

    // 親に.focusedと.clickableを付与
    if (data.pre_hex) {
        data.pre_hex.classList.add("focused", "clickable");
    }
    // フォーカス中の親の子に .clickable を付与
    if (data.children) {
        data.children.forEach(child => child.classList.add("clickable"));
    }

    // 移動処理
    container.style.transform = `translate(${ -data.x }px, ${ -data.y }px)`;

    // テキスト内容変更
    info.textContent = `Stage: ${focus_index + 1}`;
}
  


// 現在中心としている中心六角形
const max_value = Math.max(0, ...played_stage_ids);
console.log('max_value', max_value);
let focus_index = Math.floor(max_value / 7);
console.log('focus_index', focus_index);
const max_index = main_centers.length - 1;

// 初期フォーカス
update_focus(focus_index)

// 次に移動
function go_next() {
    focus_index = (focus_index + 1) % (max_index + 1);
    update_focus(focus_index);
}
// 前に移動
function go_prev() {
    focus_index = (focus_index - 1 + (max_index + 1)) % (max_index + 1);
    update_focus(focus_index);
}

// ボタン操作(次に移動)
document.getElementById("next").addEventListener("click", () => {
    go_next();
})

// ボタン操作(前に移動)
document.getElementById("prev").addEventListener("click", () => {
    go_prev();
})



//// スワイプ機能
let touch_start_x = 0;
let touch_start_y = 0;
let touch_start_time = 0;

const SWIPE_MIN_X = 40;      // 横移動の最小px（好みで調整）
const SWIPE_MAX_Y = 60;      // 縦ブレ許容（縦スクロールと区別）
const SWIPE_MAX_TIME = 800;  // 速すぎ/遅すぎ対策

// スワイプを取る対象：全体か、circle-container だけにするか選べる
const swipe_target = document.getElementById("circle"); // ここを document にすると全画面で反応

// スワイプ開始時
swipe_target.addEventListener("touchstart", (e) => {    
    // 指が一本でない場合は拒否
    if (e.touches.length !== 1) return;

    const t = e.touches[0];
    touch_start_x = t.clientX;
    touch_start_y = t.clientY;
    touch_start_time = Date.now();
}, { passive: true });

// スワイプ終了時
swipe_target.addEventListener("touchend", (e) => {
    // 差分を取る
    const dt = Date.now() - touch_start_time;
    if (dt > SWIPE_MAX_TIME) return;

    const t = e.changedTouches[0];
    const dx = t.clientX - touch_start_x;
    const dy = t.clientY - touch_start_y;

    // 縦スクロールを優先（縦ブレが大きい時は無視）
    if (Math.abs(dy) > SWIPE_MAX_Y) return;

    // 横移動が短いなら無視
    if (Math.abs(dx) < SWIPE_MIN_X) return;

    // 左スワイプ（dx < 0）→ prev、右スワイプ（dx > 0）→ next
    if (dx < 0) {
        go_prev();
    } else {
        go_next();
    }
}, { passive: true });