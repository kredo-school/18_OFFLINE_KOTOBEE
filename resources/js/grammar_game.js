import { bindAgainButtonGrammer } from "./game_result_modal";

document.addEventListener("DOMContentLoaded", () => {    

    bindAgainButtonGrammer();

});

function send_result(score) {
    
    fetch('/game/grammar/save', {
        method: 'POST',
        headers: {
            "Content-Type": "application/json",
            "X-CSRF-TOKEN": document.querySelector('meta[name="csrf-token"]').content
        },
        body: JSON.stringify({
            stage_id: window.grammar_stage_id,
            play_time: score
        })
    })
    .then(res => res.json())
    .then(data => {

        console.log("保存結果：", data);

        show_result_modal(data, score);
    })
    .catch(err => {
        console.error("保存エラー：", err);
        show_result_modal({ time: score});
    });
    
}

function show_result_modal(data, score) {

    // ★ まず Phaser のゲームを完全に破棄する
    if (window.grammarGame) {
        window.grammarGame.destroy(true); // canvas もイベントも全部消える
        window.grammarGame = null;
    }

    const modal   = document.getElementById("result-modal");
    const content = document.getElementById("rank-content");

    if (!modal || !content) {
        console.error("モーダルの要素がありません");
        return;
    }

    if (!data) {
        // エラー時の表示
        content.innerHTML = `
            <p>結果を取得できませんでした。</p>
            <p>今回のタイム：${Number(score).toFixed(2)} sec</p>
        `;
        modal.style.display = "block";
        return;
    }

    // ----- 自分のベスト・順位 -----
    const bestRaw = (data.best_time !== undefined && data.best_time !== null)
        ? Number(data.best_time)
        : null;

    const myBest = (bestRaw !== null && !isNaN(bestRaw))
        ? `${bestRaw.toFixed(2)} sec`
        : "-";

    const myRank = (data.rank !== undefined && data.rank !== null)
        ? data.rank
        : "-";

    // ----- TOP3（Laravel側: top3 = GameResult::with('user:id,name')->...） -----
    const rawTop3 = Array.isArray(data.top3) ? data.top3 : [];

    const top3 = rawTop3.map((r) => {
        // user が無い場合も安全に
        const name =
            r.user && r.user.name
                ? r.user.name
                : "NoName";

        // best_time があれば優先。無ければ play_time
        const t = (r.best_time !== undefined && r.best_time !== null)
            ? Number(r.best_time)
            : (r.play_time !== undefined && r.play_time !== null
                ? Number(r.play_time)
                : null);

        const valueLabel = (t !== null && !isNaN(t))
            ? `${t.toFixed(2)} sec`
            : "-";

        return { name, valueLabel };
    });

    // ----- HTML組み立て -----
    let html = `
        <p>今回のタイム：${Number(score).toFixed(2)} sec</p>
        <p>あなたのベスト：${myBest}</p>
        <p>あなたの順位：${myRank}</p>
        <hr><h3>Top 3</h3>
    `;

    const trophyColors = ["#DAA520", "#C0C0C0", "#CD7F32"];

    top3.forEach((r, i) => {
        const color = trophyColors[i] || trophyColors[trophyColors.length - 1];
        html += `
            <p class="rank-line">
                <span class="trophy-bg" style="background:white;">
                    <i class="fa-solid fa-trophy" style="color:${color};"></i>
                </span>
                ${i + 1}. ${r.name}：${r.valueLabel}
            </p>
        `;
    });

    content.innerHTML = html;
    modal.style.display = "block";
}

function is_mobile_device() {
    const ua = navigator.userAgent;

    if (/Android|iPhone|iPod|iPad|Mobile/i.test(ua)) {
        return true;
    }
    
    return false;
}

function get_game_size() {

    const is_mobile = is_mobile_device();

    if (is_mobile) {
        return { width: window.innerWidth, height: window.innerHeight };
    } else {
        // return { width: window.innerWidth / 1.5, height: window.innerHeight / 0.60};
        return { width: window.innerWidth / 1.5, height: window.innerHeight / 1};
    }
}

// ゲームが存在するかの値
window.grammarGame = null;

// 直近の問題データ（Again で再利用する用）
window.questions = null;

window.startGrammarGame = function(questions, stage_id) {

    // ここでグローバル変数に入れておく
    window.questions = questions;
    window.grammar_stage_id = stage_id;    

    const size = get_game_size();
    const is_mobile = is_mobile_device();

    const config = {
        type: Phaser.AUTO,
        width: size.width,
        height: size.height,
        parent: 'phaser-root',
        backgroundColor: "#FFFFCE",
        scene: [MainScene],
        resolution: Math.max(window.devicePixelRatio || 1, 2),
        dom: { createContainer: true },
        scale: {
            ...(is_mobile ? {} : { autoCenter: Phaser.Scale.CENTER_BOTH})
        }        
    };

    console.log("innerWidth:", window.innerWidth);
    console.log("innerHeight:", window.innerHeight);
    console.log("size.width:", size.width);
    console.log("size.height:", size.height);

    // 初回だけ new Phaser.Game
    if (!window.grammarGame) {
        window.grammarGame = new Phaser.Game(config);
        return;
    }

    // 2回目以降はシーンだけリスタート
    const mainScene = window.grammarGame.scene.keys.main;
    if (mainScene) {
        mainScene.scene.restart();
    }

};


class MainScene extends Phaser.Scene {

    constructor() {
        super("main");
    }

    preload() {
        console.log('preload called');

        const api_questions = window.questions || [];        
        
        api_questions.forEach((q, qi) => {
            if (q.image) {
                this.load.image(`q${qi + 1}_image`, q.image);
            }

            (q.wrong_answers || []).forEach((wa, wi) => {
                if (wa.image) {
                    this.load.image(`q${qi + 1}_wrong_image_${wi + 1}`, wa.image);
                }
            });            
        });        
    }

    create() {   
        // 現在の問題番号
        this.cur_question_i = 0;  
        
        const api_questions = window.questions || [];

        this.input.enabled = true;

        let sentence = "No data";

        if (api_questions.length > 0) {
            sentence = api_questions[0].sentence;  
            // Laravel側のキー名が "sentence" じゃない場合は書き換えて！
        }



        console.log('api_questions[0]', api_questions[0].blocks[0].word);
        console.log('api_questions[0].sentence', api_questions[0].sentence);
        console.log(api_questions.length);

        this.add.text(50, 50, "最初の文章: " + sentence, {
            fontSize: "32px",
            fill: "#000"
        });

        //   const this.whole_W = this.scale.width;
        //   const this.whole_H = this.scale.height;
        this.whole_W = this.sys.game.config.width;
        this.whole_H = this.sys.game.config.height;

        // 画面中央
        this.cx = this.whole_W / 2;
        this.cy = this.whole_H / 2;

        //   console.log("config width:", this.sys.game.config.width);
        //   console.log("scale width:", this.scale.width);

        // part_of_speech: 1=noun, 2=verb, 3=adj, 4=adv, 5=particle, 6=other
        const color_map = { 1: 0x6155F5, 2: 0x0088FF, 3: 0x91FC52, 4: 0x2F48B7, 5: 0x00C8B3, 6: 0x6EE6EE};      

        // 問題
        this.questions = [];

        for (let i = 0; i < api_questions.length; i++) {
            const q = api_questions[i];
            console.log('api_questions[i]', api_questions[i]);

            this.questions.push({
                japanese: q.blocks.map(block => ({
                    word: block.word,
                    color: color_map[block.pos]
                })),

                wrong_answers: q.wrong_answers.map((wa, index) => ({
                   
                    words: wa.order,
                    image_key: `q${i + 1}_wrong_image_${index + 1}`,
                    image_url: wa.image
                })),

                english: q.sentence,
                image_key: `q${i + 1}_image`,
                image_url: q.image
            });
        }

        // ゲームの背景作成
        this.make_bg();        


        // 画像調整
        this.adjust_image_size();
        
        const q = this.questions[this.cur_question_i];
        
        console.log('q.scale', q.scale);
        // 画像を出力
        this.questionImage = this.add.image(
            this.whole_W / 3,
            this.whole_H / 6,
            q.image_key  
        ).setScale(q.scale);


        // 各containerの情報配列
        this.parameters = [];

        // 文字
        this.font_family = "sans-serif";
        this.font_size = `${this.whole_H / 50}px`;
        this.font_style = "bold";
        this.font_color = "#000000";

        // 瓶のサイズ
        this.bin_body_H = this.whole_H / 14;
        this.make_bin_body_W();
        this.bin_body_R = this.whole_H / 50;                    

        // 瓶と蓋の線の太さ
        this.bin_lid_line_weight = this.whole_W / 400;

        // 瓶の高さの最初のギャップ
        this.bin_first_space_H = this.whole_H / 3;

        // 瓶の高さのギャップ
        this.bin_space_H = this.whole_H / 12;                    

        // 瓶同士のすき間
        this.bin_space_W = this.whole_W / 20;

        // 瓶の一番左のx座標
        this.bin_start_left_x = this.whole_W / 16;

        // 瓶の一番右のx座標
        this.bin_last_right_x = this.whole_W - this.bin_start_left_x;

        // 各行の最大の瓶の幅
        this.bins_max_width = this.whole_W - (this.bin_start_left_x * 2);

        // 蓋のサイズ
        this.cap_body_H = this.whole_H / 150;
        this.cap_body_H_sum = this.cap_body_H * 2;                   
        this.cap_body_R = this.cap_body_H / 2;                    

        // 瓶と蓋のサイズ
        this.bin_cap_H_sum = this.bin_body_H + this.cap_body_H_sum;
        this.bin_cap_H_sum_half = this.bin_cap_H_sum / 2;

        // 瓶と長いパイプのボトムの幅
        this.long_pipe_bottom_w = this.whole_H / 30;

        // 瓶を作成するためのY座標(行数移動用)
        this.bin_cap_center_y = this.bin_first_space_H + this.bin_cap_H_sum_half;

        // ベルトの流れる速度（ピクセル / 秒）
        this.conveyor_speed = 0;

        this.conveyor_min_speed = 80;  // フェーズアウト開始時の最低速度（px/s）
        this.conveyor_max_speed = 500;  // フェーズアウト中の最高速度（px/s）
        this.conveyor_accel     = 300;  // 加速量（px/s^2）
        this.conveyor_decel     = 100;  // 減速量（px/s^2）
        this.conveyor_phasein_start_speed = 300; // フェーズイン時の開始スピード
        
        // 瓶のフェーズインのフラグ
        this.is_phase_in = false;

        // 瓶のフェーズアウトのフラグ
        this.is_phase_out = false;

        // フェーズアウト用のパラメータ
        this.phaseout_parameters = [];

        // 水を注ぐフラグ
        this.pouring = false;

        // 瓶の中の水の水位
        this.liquid_h = 0;

        // 波の大きさの比率(水位が高くなるほど波を弱める)
        this.wave_ratio = 0;                     
                            
        // 長いパイプ作成
        for (let i = 0; i < 3; i++) {
            const pipe_space = (this.bin_cap_H_sum + this.bin_space_H) * i;
            this.long_pipe(pipe_space);
        }
        
        // 瓶と蓋を作成
        this.create_jar_row();

        // 全てのベルトコンベアの表面の長方形を格納
        this.all_surface_rectangles = [];

        // 波用の時間とパラメータ
        this.wave_time = 0;
        this.wave_speed = 4;

        // 液体を注ぐスピード
        this.pouring_speed = 100;
        
        // 液体を減速する倍率
        this.smooth_wave_ratio = 1 - Math.pow(this.wave_ratio, 2);

        // 蛇口からの水の太さ
        this.base_stream_w = this.second_pipe_w;  
        this.min_stream_w  = 0;
        this.stream_w = this.base_stream_w;

        // ベルトコンベアを作成
        for (let i = 0; i < 3; i++) {
            const conveyor_gap_h = (this.bin_cap_H_sum + this.bin_space_H) * i;
            const recs = this.make_belt_conveyor(conveyor_gap_h);
            this.all_surface_rectangles.push(recs);                        
        } 
        
        // 右上の機械
        this.make_big_machine();

        // checkやcontinueなどのボタン
        this.check_button = this.create_button(this.cx, this.cy*2-this.cy/4, 'check', 0xFF8D28, 0.83, () => {
            // 正解判定
            this.check_sentence();
        })

        // ゲームタイトル文字(英語)
        this.add.text(this.cx, this.cy/12, 'Look at the picture and make a sentence.', {
            fontSize: `${Math.min(this.cx/16, this.cy /30)}px`,
            color: '#3C2A23',
            fontStyle: 'bold',
            padding: { top: 15, bottom: 5 },
            align: 'center'
        }).setOrigin(0.5, 0.5)
        
        /// タイマー
        // スタートタイム
        this.start_time = this.time.now;
        // タイマーが動いているかどうか
        this.is_timer_activate = true;
        // 画面にタイマー表示
        this.timer_text = this.add.text(this.whole_W * 0.71, this.whole_H / 9.1, '0.00', {
            fontSize: `${Math.min(this.cx/16, this.cy /30)}px`,
            color: '#000',
        });


        
        // ドラッグ機能
        this.input.on('dragstart', this.drag_start, this);
        this.input.on('drag', this.drag, this);
        this.input.on('dragend', this.drag_end, this);
    }    
    
    // update
    update (time, delta) {

        // msからsに変換
        this.dt = delta / 1000;

        //// コンベアを回す                                                      

        this.all_surface_rectangles.forEach(each_rect => {
            each_rect.forEach(sur_rec => {
                sur_rec.x += this.conveyor_speed * this.dt;
                // 右端を「長方形の右端」が超えたかで判定
                if (sur_rec.x > this.whole_W + (this.s_rec_space / 2)) {
                    // 長方形の右端を画面の一番左に戻す
                    sur_rec.x -= (this.whole_W + sur_rec.width);
                }
            })
        })

        // 新しい瓶を左から流して入れる
        if (this.is_phase_in) {
            this.pahse_in_moving_jar();
        }

        // 既存の瓶を右に流して消す
        if (this.is_phase_out) {
            this.phase_out_moving_jar();
        }
        
        if (this.pouring) {

            // // 液体量に応じて蛇口の水の横幅を縮める
            // const ratio_for_stream = this.wave_ratio; // 0〜1

            // this.stream_w = Phaser.Math.Linear(
            //     this.base_stream_w,
            //     this.min_stream_w,
            //     ratio_for_stream
            // ); 

            // ホースからの液体の狭め方
            const ratio_for_stream = this.wave_ratio; // 0〜1
            const target_stream_w = Phaser.Math.Linear(
                this.base_stream_w,
                this.min_stream_w,
                ratio_for_stream
            );
            const smooth_factor = 0.1; // 0.05 とか 0.02 とかでもOK
            this.stream_w += (target_stream_w - this.stream_w) * smooth_factor;


            // 蛇口からの液体描写
            this.make_jar_outside_wave();
            
            // 瓶の中の液体描写
            this.make_jar_inside_wave();                                                                   

        }
        
        if (this.is_timer_activate) {
            // 経過時間
            const elapsed_sec = (time - this.start_time) / 1000;
            // 小数第2位まで表示
            this.timer_text.setText(elapsed_sec.toFixed(2));
        }
    }

    adjust_image_size() {
        const max_w = this.cx / 2.5;
        const max_h = this.cy / 2.5;
        const max_size = Math.min(max_w, max_h);
        for (let i = 0; i < this.questions.length; i++) {
            const q = this.questions[i];
            let tmp_main = this.add.image(0, 0, q.image_key).setVisible(false);
            let scale_main = this.calcScale(tmp_main.width, tmp_main.height, max_size);
            // 保存
            q.scale = scale_main;
            tmp_main.destroy();
            for (let j = 0; j < q.wrong_answers.length; j++) {
                const wa = q.wrong_answers[j];
                let tmp_wrong = this.add.image(0, 0, wa.image_key).setVisible(false);
                let scale_wrong = this.calcScale(tmp_wrong.width, tmp_wrong.height, max_size);
                // 保存
                wa.scale = scale_wrong; 
                tmp_wrong.destroy();
            }            
        }
    }

    calcScale(img_w, img_h, max_size) {
        const shrink_scale = Math.min(max_size / img_w, max_size / img_h);
        return Math.min(shrink_scale, 1.0); // 小さすぎる画像を勝手に縮小しない
    }

    // クリア時の関数
    on_clear() {
        this.is_timer_activate = false;

        const final_time = (this.time.now - this.start_time) / 1000;  
        
        this.input.enabled = false;

        // 結果を記録する
        send_result(final_time);
    }

    // 画面の右上の機械作成
    make_big_machine() {

        const base_circle_y = (this.bin_first_space_H  - (this.long_pipe_bottom_w)) - (this.whole_H / 60);
        const base_circle_x = this.cx + this.cx / 2;
        const c_radius = (this.whole_H / 60);

        const line_weight = Math.max(
            1.25,
            this.whole_W / 500,
            this.whole_H / 900
        );

        const g = this.add.graphics();
        g.lineStyle(line_weight, 0x000000, 1);                    
        g.fillStyle(0xEEEEEE, 1);

        // 順序のため上に付与
        const bottom_rec_h = (this.whole_H / 35);
        const bottom_rec_w = (c_radius * 2) * 0.7;
        const bottom_rec_y_top = base_circle_y - bottom_rec_h;
        const middle_ellipse_h = (this.whole_H / 60);
        // 下の小さな長方形とのy座標ギャップ
        const middle_ellipse_gap = (this.whole_H / 200);
        const middle_ellipse_y = bottom_rec_y_top - middle_ellipse_h / 2 - middle_ellipse_gap;


        // ===== ハンドル形状パラメータ =====
        const right_handle_left_h = this.whole_H / 160;
        const right_handle_max_h = this.whole_H / 40;
        const right_handle_max_w = this.whole_H / 50;

        const right_handle_left_x = base_circle_x;

        // 左側の上下
        const right_handle_left_top_y = middle_ellipse_y - (right_handle_left_h / 2);
        const right_handle_left_bottom_y = middle_ellipse_y + (right_handle_left_h / 2);

        // ★ 楕円半径（横・縦）
        const radius_x = this.whole_W / 20;
        const radius_y = this.whole_H / 120;

        // ★ 楕円の中心（右端のx、中央y）
        const centerX = right_handle_left_x + right_handle_max_w;
        const centerY = middle_ellipse_y;

        // ★ 楕円の一番上の点
        const ellipseTopX = centerX;
        const ellipseTopY = centerY - radius_y;
        
        // 描写開始
        g.beginPath();

        // 左上から開始
        g.moveTo(right_handle_left_x, right_handle_left_top_y);

        // ★ 斜め線 → 楕円の最初の点に正しく接続
        g.lineTo(ellipseTopX, ellipseTopY);

        // ★ 楕円（半楕円）の描画
        const steps = 40;
        for (let i = 0; i <= steps; i++) {
            const t = -Math.PI / 2 + (Math.PI * i / steps); // -90° → +90°
            const x = centerX + radius_x * Math.cos(t);
            const y = centerY + radius_y * Math.sin(t);
            g.lineTo(x, y);
        }

        // ★ 楕円の一番下の点 → 左下へ
        g.lineTo(right_handle_left_x, right_handle_left_bottom_y);

        g.closePath();
        g.fillPath();
        g.strokePath();




        //// 左ハンドル描写
        const left_handle_right_h = right_handle_left_h;
        const left_handle_max_h = right_handle_max_h;
        const left_handle_max_w = right_handle_max_w;
        
        const left_handle_right_x = base_circle_x;

        // 右側の上下
        const left_handle_right_top_y = middle_ellipse_y - (left_handle_right_h / 2);
        const left_handle_right_bottom_y = middle_ellipse_y + (left_handle_right_h / 2);

        const left_handle_center_x = left_handle_right_x - right_handle_max_w;
        const left_handle_center_y = middle_ellipse_y;

        const left_handle_ellipse_top_x = left_handle_center_x;
        const left_handle_ellipse_top_y = left_handle_center_y - radius_y;

        g.beginPath();

        g.moveTo(left_handle_right_x, left_handle_right_top_y);

        g.lineTo(left_handle_ellipse_top_x, left_handle_ellipse_top_y);

        for (let i = 0; i <= steps; i++) {
            const t = -Math.PI / 2 + (-Math.PI * 1 * (i / steps));
            const x = left_handle_center_x + radius_x * Math.cos(t);
            const y = left_handle_center_y + radius_y * Math.sin(t);
            g.lineTo(x, y);
        }

        g.lineTo(left_handle_right_x, left_handle_right_bottom_y);
        g.closePath();
        g.fillPath();
        g.strokePath();                    





        
        // 真ん中の長方形
        const middle_rec_w = bottom_rec_w * 0.7;
        const middle_rec_h = (this.whole_H / 16);
        const middle_rec_x_left = base_circle_x - (middle_rec_w / 2);
        const middle_rec_y_top = base_circle_y - middle_rec_h;

        g.beginPath();
        g.fillRect(middle_rec_x_left, middle_rec_y_top, middle_rec_w, middle_rec_h);
        g.strokeRect(middle_rec_x_left, middle_rec_y_top, middle_rec_w, middle_rec_h);                    

        // 真ん中の楕円                    

        const middle_ellipse_w = bottom_rec_w * 0.9;                                      

        g.beginPath();
        g.fillEllipse(base_circle_x, middle_ellipse_y, middle_ellipse_w, middle_ellipse_h);
        g.strokeEllipse(base_circle_x, middle_ellipse_y, middle_ellipse_w, middle_ellipse_h);


        // 一番下のサークルの上の長方形
        // 一番下のサークルと繋がる長方形                                        
        const bottom_rec_x_left = base_circle_x - (bottom_rec_w / 2);
        g.beginPath();
        g.fillRect(bottom_rec_x_left, bottom_rec_y_top, bottom_rec_w, bottom_rec_h);
        g.strokeRect(bottom_rec_x_left, bottom_rec_y_top, bottom_rec_w, bottom_rec_h);
                            
        // 下の長方形と繋がる小さな長方形
        const bottom_2_rec_w = bottom_rec_w * 1.2;
        const bottom_2_rec_h = (this.whole_H / 150);                    
        const bottom_2_rec_x_left = bottom_rec_x_left - (bottom_2_rec_w - bottom_rec_w) / 2;
        const bottom_2_rec_y_top = base_circle_y - bottom_rec_h;
        
        g.beginPath();
        g.fillRect(bottom_2_rec_x_left, bottom_2_rec_y_top, bottom_2_rec_w, bottom_2_rec_h);
        g.strokeRect(bottom_2_rec_x_left, bottom_2_rec_y_top, bottom_2_rec_w, bottom_2_rec_h);

        // 一番下の大きいサークル
        g.beginPath();
        g.fillCircle(base_circle_x, base_circle_y, c_radius);
        g.strokeCircle(base_circle_x, base_circle_y, c_radius);
        
        // 一番下の小さなサークル
        const mini_radius = c_radius * 0.6;
        g.beginPath();
        g.fillCircle(base_circle_x, base_circle_y, mini_radius);
        g.strokeCircle(base_circle_x, base_circle_y, mini_radius);


        // 上の台形
        const trapezoid_top_w = (this.whole_W / 3.5);
        const trapezoid_h = (this.whole_H / 15);

        g.beginPath();
        // 左から右
        g.moveTo(base_circle_x - middle_rec_w / 2, middle_rec_y_top);
        g.lineTo(base_circle_x + middle_rec_w / 2, middle_rec_y_top);                    
        g.lineTo(base_circle_x + trapezoid_top_w / 2, middle_rec_y_top - trapezoid_h);
        g.lineTo(base_circle_x - trapezoid_top_w / 2, middle_rec_y_top - trapezoid_h);
        g.closePath();
        g.fillPath();
        g.strokePath();

        // 一番上の長方形
        const top_rec_w = trapezoid_top_w;
        const top_rec_h = (this.whole_H / 14);
        const top_rec_top_y = middle_rec_y_top - (trapezoid_h + top_rec_h);
        const top_rec_left_x = base_circle_x - trapezoid_top_w / 2;

        g.beginPath();
        g.fillRect(top_rec_left_x, top_rec_top_y, top_rec_w, top_rec_h);
        g.strokeRect(top_rec_left_x, top_rec_top_y, top_rec_w, top_rec_h);                                

    }
    // ゲームの背景
    make_bg() {

        const draw_rect_1 = (x, y, w, h) => {
            
            // 塗りつぶし
            g.fillStyle(fill_colors[0], 1);
            // g.fillRect(x+offset, y+offset, w-line_w, h-line_w);
            g.fillRect(x+offset, y+offset, w-line_w, h-line_w);
            
            // 上・左の明るい線
            g.lineStyle(line_w, light_stroke[0], 1);
            g.beginPath();
            // 上
            g.moveTo(x, y);
            g.lineTo(x + w, y);
            // 左
            g.moveTo(x, y);
            g.lineTo(x, y + h);
            g.strokePath();

            // 右・下の暗い線
            g.lineStyle(line_w, dark_strokes[0], 1);
            g.beginPath();
            // 右
            g.moveTo(x + w -line_w, y);
            g.lineTo(x + w -line_w, y + h);
            // 下
            g.moveTo(x, y + h -line_w);
            g.lineTo(x + w, y + h -line_w);
            g.strokePath();

        }

        const draw_rect_2 = (x, y, w, h) => {
            
            // 塗りつぶし
            g.fillStyle(fill_colors[1], 1);
            g.fillRect(x+offset, y+offset, w-line_w, h-line_w);
            
            // 上・左の明るい線
            g.lineStyle(line_w, light_stroke[1], 1);
            g.beginPath();
            // 上
            g.moveTo(x, y);
            g.lineTo(x + w, y);
            // 左
            g.moveTo(x, y);
            g.lineTo(x, y + h);
            g.strokePath();

            // 右・下の暗い線
            g.lineStyle(line_w, dark_strokes[1], 1);
            g.beginPath();
            // 右
            g.moveTo(x + w -line_w, y);
            g.lineTo(x + w -line_w, y + h);
            // 下
            g.moveTo(x, y + h -line_w);
            g.lineTo(x + w, y + h -line_w);
            g.strokePath();

        }

        // 1つの Graphics で全部描く
        const g = this.add.graphics();

        // 横の個数
        const cols = 8;
        // 縦の個数
        const rows = 18;
        // 四角形の横の長さ
        const cell_w = this.whole_W / cols;
        // 四角形の横の長さ
        const cell_h = this.whole_H / rows;

        // 色
        const fillColor   = 0xFFFFCE; // 中の色
        // FFFFCE
        const lightStroke = 0xffffff; // 左・上の線
        const darkStroke  = 0xACA99E; // 右・下の線

        // 色
        const fill_colors = [0xFFFFCE, 0xA6A398]; // 中の色
        const light_stroke = [0xffffff, 0xDEDEDE]; // 左・上の線
        const dark_strokes = [0xACA99E, 0x55544F]; // 右・下の線

        const line_w = this.whole_W / 550;
        const offset = line_w / 2;                     

        for (let row = 0; row < rows; row++) {
            for (let col = 0; col < cols; col++) {
                const x = cell_w * col +line_w*2;
                const y = cell_h * row + line_w*2;                        
                if (row === 5 || row === 6)  {
                    draw_rect_2(x, y, cell_w, cell_h);                            
                } else {
                    draw_rect_1(x, y, cell_w, cell_h);                                   
                }                          
            }                       
        }
    }

    // 波作成用のフーリエ関数
    fourier_wave_inside(x, t) {

        // 固定でwave決定
        this.waves = [
            { amp: 10, freq: 0.008, speed: 0.2 },  // 超大波
            { amp: 8,  freq: 0.015, speed: 0.5 },  // 大波
            { amp: 4,  freq: 0.030, speed: 1.3 },  // 中波
            { amp: 2,  freq: 0.060, speed: 2.0 },  // 小波
            { amp: 1,  freq: 0.120, speed: 3.0 }   // 微細波
        ];

        // // ランダムでwave決定用
        // this.waves = [];
        // const r = (Math.random() + Math.random()) / 2;  // ばらつき減少                    
        // for (let i = 0; i < 5; i++) {
        //     this.waves.push({
        //         amp: Phaser.Math.Between(1, 8),
        //         freq: 0.01 + r * 0.03,  // 緩やかにする
        //         speed: Math.random() * 3
        //     });
        // }

        let wave_sum = 0;

        // waves 配列をすべて足し合わせる
        for (const w of this.waves) {
            wave_sum += w.amp * Math.sin(w.freq * x + w.speed * t);
        }

        return (this.wave_ratio - 1) * wave_sum;

    }

    fourier_wave_outside(x, t) {

        // console.log('this.second_pipe_w / 2;', this.second_pipe_w / 2);
        // 固定でwave決定
        this.waves = [
            { amp: 3, freq: 0.008, speed: 0.2 },  // 超大波
            { amp: 2,  freq: 0.015, speed: 0.5 },  // 大波
            { amp: 1,  freq: 0.030, speed: 1.3 },  // 中波
            { amp: 0.5,  freq: 0.060, speed: 2.0 },  // 小波
            { amp: 0.1,  freq: 0.120, speed: 3.0 }   // 微細波
        ];

        // // ランダムでwave決定用
        // this.waves = [];
        // const r = (Math.random() + Math.random()) / 2;  // ばらつき減少                    
        // for (let i = 0; i < 5; i++) {
        //     this.waves.push({
        //         amp: Phaser.Math.Between(1, 8),
        //         freq: 0.01 + r * 0.03,  // 緩やかにする
        //         speed: Math.random() * 3
        //     });
        // }

        let wave_sum = 0;

        // waves 配列をすべて足し合わせる
        for (const w of this.waves) {
            wave_sum += w.amp * Math.sin(w.freq * x + w.speed * t);
        }

        return (this.wave_ratio - 1) * wave_sum;

    }

    make_jar_inside_wave() {

        this.liquid_h += this.pouring_speed * this.dt;

        // 水位が超えたら固定にする
        if (this.liquid_h >= this.bin_body_H) {
            this.liquid_h = this.bin_body_H;                        
        }

        // 波の時間
        this.wave_time += this.dt * this.wave_speed;

        this.parameters.forEach(param => {

            const g_inside_wave = param.g_inside_wave;
            const bin_w = param.bin_w;
            const bin_h = param.bin_h;

            // ★毎フレーム最初にクリア
            g_inside_wave.clear();

            // ★コンテナローカル座標での瓶の位置
            //   create_jar の g_back / g_front と同じ基準を使う
            // const halfH = this.bin_cap_H_sum_half;
            const bodyTopY = this.cap_body_H_sum - this.bin_cap_H_sum_half;   // 本体の上端（ローカルY）

            const bin_left_x = -(bin_w / 2);   // 左端（ローカルX）
            const bin_right_x = bin_w / 2;   // 左端（ローカルX）
            const bin_top_y  = bodyTopY;     // 上端（ローカルY）

            // 波の大きさの比率(水位が高くなるほど波を弱める)
            this.wave_ratio = Phaser.Math.Clamp(this.liquid_h / bin_h, 0, 1);

            // 瓶の角の半径
            const radius = this.bin_body_R;

            // 波のベースライン（液面の高さ）
            const base_wave_top_y = bin_top_y + (bin_h - this.liquid_h);                                              
            
            
            // 右上の円の中心
            const top_right_cx = bin_left_x + bin_w - radius;
            const top_right_cy = bin_top_y  + radius;

            // 左上の円の中心
            const top_left_cx  = bin_left_x + radius;
            const top_left_cy  = bin_top_y  + radius;                        

            // 右下の円の中心
            const right_bottom_cx = bin_left_x + bin_w - radius;
            const right_bottom_cy = bin_top_y + bin_h - radius;
            
            // 左下の円の中心
            const left_bottom_cx = bin_left_x + radius;
            const left_bottom_cy = bin_top_y + bin_h - radius;

            g_inside_wave.fillStyle(0xFFB728, 1);
            g_inside_wave.beginPath();
            // 左下 → 右下（底辺）
            g_inside_wave.moveTo(left_bottom_cx, bin_top_y + bin_h);
            g_inside_wave.lineTo(right_bottom_cx, right_bottom_cy + radius);
            
            
            // 波の一番上が下の radius の中心 y 座標以下の時だけ、下丸角を効かせる
            if (base_wave_top_y <= right_bottom_cy) {

                const start_angle = Math.PI / 2;
                const end_angle   = 0; 
                
                const cnt = 50;
                for (let i = 0; i <= cnt; i++) {
                    const t = start_angle + (end_angle - start_angle) * (i / cnt);
                    const x = right_bottom_cx + radius * Math.cos(t);
                    const y = right_bottom_cy + radius * Math.sin(t);
                    g_inside_wave.lineTo(x, y);
                }                                                        

                const step = 1;                      
                for (let i = 0; i <= bin_w; i += step) {
                    // 瓶の中心が基準だからbin_left_xを足す
                    const px = bin_right_x - i;
                    const wave = this.fourier_wave_inside(i, this.wave_time);                            
                    // 波の上の座標
                    let py = base_wave_top_y - wave;

                    // ---- Clamp 用の上下限を初期化 ----
                    let yMin = bin_top_y;         // 上側の限界
                    let yMax = bin_top_y + bin_h; // 下側の限界（下は矩形のまま)  
                    
                    // ==== 左上の丸角内か？ ====
                    if (px < bin_left_x + radius) {
                        const dx = px - top_left_cx;
                        if (Math.abs(dx) <= radius) {
                            const dy = Math.sqrt(radius * radius - dx * dx);
                            // 左上の円弧の内側境界（瓶の中側）: 円の「下側」のほう
                            const arcTopY = top_left_cy - dy;
                            yMin = Math.max(yMin, arcTopY);
                        }
                    }
                    // ==== 右上の丸角内か？ ====
                    else if (px > bin_left_x + bin_w - radius) {
                        const dx = px - top_right_cx;
                        if (Math.abs(dx) <= radius) {
                            const dy = Math.sqrt(radius * radius - dx * dx);
                            const arcTopY = top_right_cy - dy;
                            yMin = Math.max(yMin, arcTopY);
                        }
                    }

                    // // ---- 丸角を含めた Clamp（上だけ） ----
                    py = Phaser.Math.Clamp(py, yMin, yMax);

                    g_inside_wave.lineTo(px, py);
                }
                
                const start_angle_l = Math.PI;
                const end_angle_l   = Math.PI / 2; 
                const cx_l = left_bottom_cx;
                const cy_l = left_bottom_cy;
                
                for (let i = 0; i <= cnt; i++) {
                    const t = start_angle_l + (end_angle_l - start_angle_l) * (i / cnt);
                    const x = cx_l + radius * Math.cos(t);
                    const y = cy_l + radius * Math.sin(t);
                    g_inside_wave.lineTo(x, y);
                }

            } else {
                const cx = right_bottom_cx;
                const cy = right_bottom_cy;

                // 半径r内の半径b
                const b = radius - this.liquid_h;
                
                // 安全のため -1〜1 に Clamp（誤差で外れたとき対策）
                let s = b / radius;
                s = Phaser.Math.Clamp(s, -1, 1);

                // 下(π/2) → 左(π) の範囲にある解を取る                            
                const start_angle = Math.PI / 2; // 下
                const end_angle = Math.asin(s); // 左寄りの角度まで回す

                const cnt = 30;                            
                for (let i = 0; i <= cnt; i++) {
                    const t = start_angle + (end_angle - start_angle) * (i / cnt);
                    const x = cx + radius * Math.cos(t);
                    const y = cy + radius * Math.sin(t);
                    g_inside_wave.lineTo(x, y);
                }

                const step = 1;
                const right_x = cx + radius * Math.cos(end_angle);
                // console.log('right_x', right_x);
                const x_diff = radius - radius * Math.cos(end_angle);
                let wave_w_move = bin_w - x_diff * 2;
                wave_w_move = Math.floor(wave_w_move);

                for (let i = 0; i <= wave_w_move; i += step) {                                
                    const px = right_x - i;
                    const wave = this.fourier_wave_inside(i, this.wave_time);                            
                    // 波の上の座標
                    let py = base_wave_top_y - wave;

                    // ---- Clamp 用の上下限を初期化 ----
                    let yMin = bin_top_y;         // 上側の限界
                    let yMax = bin_top_y + bin_h; // 下側の限界（下は矩形のまま)                                                    
                                                
                    // // ---- 丸角を含めた Clamp（上だけ） ----
                    py = Phaser.Math.Clamp(py, yMin, yMax);

                    g_inside_wave.lineTo(px, py);
                }


                //// 左下のradius
                const cx_l = left_bottom_cx;
                const cy_l = left_bottom_cy;

                const start_angle_l = Math.PI - Math.asin(s);
                const end_angle_l = Math.PI / 2;

                for (let i = 0; i <= cnt; i++) {
                    const t = start_angle_l + (end_angle_l - start_angle_l) * (i / cnt);
                    const x = cx_l + radius * Math.cos(t);
                    const y = cy_l + radius * Math.sin(t);
                    g_inside_wave.lineTo(x, y);
                }                          

            }                                                                                                                                                                                        

            // 左下に戻って閉じる
            g_inside_wave.lineTo(bin_left_x + radius, bin_top_y + bin_h);
            g_inside_wave.closePath();
            g_inside_wave.fillPath();

        });
    }
    
    // make_jar_outside_wave() {

    //     this.parameters.forEach(param => {
    //         const g_wave = param.g_wave;
    //         g_wave.clear();                        
    //         const bin_w = param.bin_w;
    //         const bin_h = param.bin_h;
    //         const bin_center_x = param.bin_center_x;
    //         const bin_center_y = param.bin_center_y;


    //         const start_x = - this.second_pipe_w / 2;
    //         const start_y = - this.second_rect_bottom;
    //         const bottle_bottom_y = param.bin_h / 2;
    //         const length = bottle_bottom_y - start_y;
    //         const stream_w = this.stream_w;

    //         const x_left  = start_x - stream_w / 2;
    //         const x_right = start_x + stream_w / 2;                       

    //         // 描写
    //         g_wave.fillStyle(0x4db6ff, 1);
    //         g_wave.beginPath();
    //         // 左上から右上に直線
    //         g_wave.moveTo(x_left, start_y);
    //         g_wave.lineTo(x_right, start_y);

    //         // 上から下の波
    //         const step = 1;                        
    //         for (let i = 0; i <= length; i += step) {
    //             const py = start_y + i;
    //             const wave = this.fourier_wave(i, this.wave_time);
    //             const px = x_right - wave;
    //             g_wave.lineTo(px, py);
    //         }

    //         g_wave.lineTo(x_left, start_y + length);

    //         // 下から上の波
    //         for (let i = 0; i <= length; i += step) {
    //             const py = start_y + length - i;
    //             const wave = this.fourier_wave(i, this.wave_time);
    //             const px = x_left - wave;
    //             g_wave.lineTo(px, py);
    //         }

    //         g_wave.closePath();
    //         g_wave.fillPath();

    //     });

    // }
    
    make_jar_outside_wave() {
        this.parameters.forEach(param => {
            const g_outside_wave = param.g_outside_wave;
            g_outside_wave.clear();                        

            const bin_h = param.bin_h;

            // ▼ 小さいパイプ（下）の情報
            const second_rect = param.pipe.second_rect;

            // パイプは container 内の座標系前提
            // 上基準 origin(0.5, 0) なので「下端Y」は：
            const start_x = second_rect.x; // 中心X
            const start_y = second_rect.y + second_rect.height * second_rect.scaleY; // 下端Y

            // 瓶の底（container 原点が瓶の中心なら +bin_h/2 で底）
            const bottle_bottom_y = bin_h / 2;

            // 波の長さ（パイプの下端から瓶の底まで）
            const length = bottle_bottom_y - start_y;

            const stream_w = this.stream_w;

            const x_left  = start_x - stream_w / 2;
            const x_right = start_x + stream_w / 2;                       

            // ---- 描画 ----
            g_outside_wave.fillStyle(0xFFB728, 1);
            g_outside_wave.beginPath();

            // 上辺
            g_outside_wave.moveTo(x_left, start_y);
            g_outside_wave.lineTo(x_right, start_y);

            // 右側の波（上→下）
            const step = 1;                        
            for (let i = 0; i <= length; i += step) {
                const py = start_y + i;
                const wave = this.fourier_wave_outside(i, this.wave_time);
                const px = x_right - wave;
                g_outside_wave.lineTo(px, py);
            }

            // 下辺
            g_outside_wave.lineTo(x_left, start_y + length);

            // 左側の波（下→上）
            for (let i = 0; i <= length; i += step) {
                const py = start_y + length - i;
                const wave = this.fourier_wave_outside(i, this.wave_time);
                const px = x_left - wave;
                g_outside_wave.lineTo(px, py);
            }

            g_outside_wave.closePath();
            g_outside_wave.fillPath();
        });

    }
    
    // // 次の問題を移動させて登場
    pahse_in_moving_jar() {
        if (this.parameters.length === 0) return;

        let all_phase_in = true;

        // スピード減速
        this.conveyor_speed -= this.conveyor_decel * this.dt;

        // まだ動いている間は、あまりに0に近づきすぎないように下限を決めておく
        const MIN_PHASEIN_SPEED = 60;   // 好みで調整
        if (this.conveyor_speed < MIN_PHASEIN_SPEED) {
            this.conveyor_speed = MIN_PHASEIN_SPEED;
        }                    

        this.parameters.forEach(parameter => {
            const container = parameter.container;
            if (!container) return;

            // まだ目標位置より左なら右に動かす
            if (container.x < parameter.target_x) {
                container.x += this.conveyor_speed * this.dt;

                // 行き過ぎ防止
                if (container.x > parameter.target_x) {
                    container.x = parameter.target_x;
                }
            }

            // まだ到達してないものがあればフラグを折る
            if (container.x < parameter.target_x) {
                all_phase_in = false;
            }
        });

        // 全員 target_x に到達したらフェーズイン終了
        if (all_phase_in) {

            // 入力を有効化
            this.input.enabled = true;

            this.is_phase_in = false;

            this.conveyor_speed = 0;

            // 新しい check ボタンを出す
            this.check_button = this.create_button(
                this.cx,
                this.cy * 2 - this.cy / 4,
                'check',
                0xFF8D28,
                0.83,
                () => {
                    this.check_sentence();                                
                }
            );
        }

    }

    // 既存の問題を移動させて削除
    phase_out_moving_jar() {                    
        // console.log('this.parameters.length', this.parameters.length)
        if (!this.phaseout_parameters || this.phaseout_parameters.length === 0) {
            this.is_phase_out = false;
            return;
        }

            // ここで加速（conveyor_speed を増やす）
        this.conveyor_speed += this.conveyor_accel * this.dt;
        if (this.conveyor_speed > this.conveyor_max_speed) {
            this.conveyor_speed = this.conveyor_max_speed;
        }

        // 画面内に一つでも瓶が存在するか
        let has_inside = false;

        // 画面内に瓶が存在するか確認
        this.phaseout_parameters.forEach(parameter => {

            const container = parameter.container;

            if (!container) return;

            container.x += this.conveyor_speed * this.dt;

            // 一つでも画面内にあれば、true
            if (container.x <= this.whole_W + container.width / 2) {
                has_inside = true;
            }
        });

        if (has_inside) {
            return;
        }

        // 全て画面外にあれば、全削除
        this.phaseout_parameters.forEach(parameter => {
            const container = parameter.container;
            if (container && !container.destroyed) {
                container.destroy();
            }
        });

        this.phaseout_parameters.length = 0; // 配列を空にする
        this.is_phase_out= false;
        
        // フラグオフにして水位初期化
        this.pouring = false;
        this.liquid_h = 0;                    

        //フェーズイン開始スピード
        this.conveyor_speed = this.conveyor_phasein_start_speed;
        
        // 瓶が全てフェーズアウトしたら、フェーズインが開始
        this.setup_question_with_phase_in();
        this.is_phase_in = true;                   
                            
    }

    // ベルトコンベアを作成
    make_belt_conveyor(conveyor_gap_h) {
        // コンベアの長さ
        const belt_width = this.whole_W;

        //// コンベアの表面の長方形のパラメータ
        // コンベアの表面の長方形の高さの半分
        const s_rec_height = this.whole_H / 350;
        // 表面の長方形の数 - 1
        this.s_rec_cnt = 20;
        // 空白の数
        this.s_emp_cnt = this.s_rec_cnt + 1;
        // 空白のサイズの合計                
        const s_emp_space_sum = belt_width / 3;
        // 長方形のサイズの合計
        const s_rec_space_sum = belt_width - s_emp_space_sum;
        // 一個当たりの空白の大きさ                    
        this.s_emp_space = s_emp_space_sum / this.s_emp_cnt;
        // 一個当たりの長方形の大きさ  
        this.s_rec_space = s_rec_space_sum / this.s_rec_cnt;
        // 一個当たりの「長方形＋空白」長さ
        this.segmentLength = this.s_rec_space + this.s_emp_space;                    


        //// ベルトコンベアのパラメータ
        const line_weight = this.whole_W / 550;
        console.log('line_weight', line_weight);
        const inner_margin = this.whole_H / 350;
        const left_start_x = 0;   // 左端
        const top_start_y = this.bin_first_space_H + this.bin_cap_H_sum + (s_rec_height * 2 - line_weight) + conveyor_gap_h;   // 上端   
        const belt_height = this.whole_H / 50;
        
        
        //// コンベア内の丸い図形
        const innerY     = top_start_y + inner_margin;
        const innerHeight = belt_height - inner_margin * 2;

        this.rollerCount  = 10;
        // 円の半径
        this.rollerRadius = this.whole_H / 300;

        const spacing = belt_width / this.rollerCount; 
        
        this.innerLeft   = spacing / 2;                                                                               
    
        /// コンベアの長い直線     
        const con_line_1 = this.add.graphics();
        con_line_1.lineStyle(line_weight, 0x000000);
        con_line_1.beginPath();
        con_line_1.moveTo(left_start_x, top_start_y);
        con_line_1.lineTo(belt_width, top_start_y);
        con_line_1.strokePath();

        const con_line_2 = this.add.graphics();
        con_line_2.lineStyle(line_weight, 0x000000);
        con_line_2.beginPath();
        con_line_2.moveTo(left_start_x, top_start_y + inner_margin);
        con_line_2.lineTo(belt_width, top_start_y + inner_margin);
        con_line_2.strokePath();

        const con_line_3 = this.add.graphics();
        con_line_3.lineStyle(line_weight, 0x000000);
        con_line_3.beginPath();
        con_line_3.moveTo(left_start_x, top_start_y - inner_margin + belt_height);
        con_line_3.lineTo(belt_width, top_start_y - inner_margin + belt_height);
        con_line_3.strokePath();

        const con_line_4 = this.add.graphics();
        con_line_4.lineStyle(line_weight, 0x000000);
        con_line_4.beginPath();
        con_line_4.moveTo(left_start_x, top_start_y + belt_height);
        con_line_4.lineTo(belt_width, top_start_y + belt_height);
        con_line_4.strokePath();


        /// 長方形コンベア色指定

        // 長方形のコンベアの色
        const con_color_top = 0xC1C8CE; 
        const con_color_middle = 0xB9B2B2;
        const con_color_bottom = 0xC1C8CE; 
        
        // 一番上
        const fill_rect_top = this.add.graphics();
        fill_rect_top.fillStyle(con_color_top, 1);
        fill_rect_top.fillRect(
            left_start_x,
            top_start_y + line_weight,
            belt_width - left_start_x,
            inner_margin - line_weight
        );

        // 中間
        const fill_rect_middle = this.add.graphics();
        fill_rect_middle.fillStyle(con_color_middle, 1);
        fill_rect_middle.fillRect(
            left_start_x,
            top_start_y + inner_margin + line_weight,               
            belt_width - left_start_x,
            belt_height - inner_margin - line_weight * 4
        );

        // 一番下
        const fill_rect_bottom = this.add.graphics();
        fill_rect_bottom.fillStyle(con_color_bottom, 1);
        fill_rect_bottom.fillRect(
            left_start_x,
            top_start_y + belt_height - inner_margin ,               
            belt_width - left_start_x,
            inner_margin - line_weight       
        );


        /// コンベアの表面の四角形作成
        this.surface_rectangles = [];                    
        for (let i = 0; i < this.s_rec_cnt + 1; i++) {
            // i番目の長方形の初期位置（中心）
            const s_r_x = this.segmentLength * i
                            + this.s_emp_space / 2
                            + this.s_rec_space / 2;

            // const s_r_x = this.segmentLength * i
            //              + this.s_rec_space / 2;

            const s_r_y = top_start_y - s_rec_height;

            const s_r = this.add.rectangle(
                s_r_x,
                s_r_y,
                this.s_rec_space,
                s_rec_height * 2 - line_weight,
                0x000000,
                0.64
            );

            this.surface_rectangles.push(s_r);
        }

        /// コンベア内の丸い図形作成
        this.rollers = [];

        for (let i = 0; i <= this.rollerCount-1; i++) {
            const cx = this.innerLeft + spacing * i;
            const cy = innerY + innerHeight / 2;

            // circle を個別の GameObject として作成（動かせるようにする）
            const c = this.add.circle(
                cx,
                cy,
                this.rollerRadius,
                0xC4C3C3
            ).setStrokeStyle(line_weight, 0x000000);

            this.rollers.push(c);
        }
        
        return this.surface_rectangles;

    }

    // 問題作成
    // setup_question() {

    //     // 問題番号移動
    //     this.cur_question_i =(this.cur_question_i + 1) % this.questions.length;

    //     // // 画像差し替え
    //     // if (this.questionImage) {
    //     //     this.questionImage.setTexture(this.questions[this.cur_question_i].image_key);
    //     // }

    //     const maxW = this.cx / 2;
    //     const maxH = this.cy / 2;
    //     const maxSize = Math.min(maxW, maxH);

    //     const key = this.questions[this.cur_question_i].image_key;

    //     if (!this.questionImage) {
    //         this.questionImage = this.add.image(
    //             this.whole_W / 3,
    //             this.whole_H / 6,
    //             key
    //         ).setOrigin(0.5);
    //     } else {
    //         this.questionImage.setTexture(key);
    //     }

    //     const imgW = this.questionImage.width;
    //     const imgH = this.questionImage.height;

    //     const scale = Math.min(maxSize / imgW, maxSize / imgH);
    //     this.questionImage.setScale(scale);



    //     // チェックボタン作成                    
    //     this.check_button = this.create_button(this.cx, this.cy*2-this.cy/4, 'check', 0xFF8D28, 0.83, () => {
    //         this.check_sentence();
    //     });

    //     this.bin_cap_center_y = this.bin_first_space_H + this.bin_cap_H_sum_half;

    //     this.parameters.forEach(p => {
    //         p.container.destroy();
    //     })                    
    //     // 新しい問題用
    //     this.parameters = [];
    //     // それぞれの瓶の幅を決める
    //     this.make_bin_body_W();
    //     // 瓶と蓋を作成
    //     this.create_jar_row();
    // } 


    // フェーズイン用の問題セットアップ
    setup_question_with_phase_in() {

        // 次の問題へ
        this.cur_question_i = (this.cur_question_i + 1) % this.questions.length;

        // ===== 画像差し替え（スケール維持版） =====
        const q = this.questions[this.cur_question_i];

        if (!this.questionImage) {
            this.questionImage = this.add.image(
                this.whole_W / 3,
                this.whole_H / 6,
                q.image_key
            ).setScale(q.scale);
        } else {
            this.questionImage.setTexture(q.image_key);
            this.questionImage.setScale(q.scale);
        }

        // ===== ここから瓶のセットアップ =====
        const cur_q_words = this.questions[this.cur_question_i].japanese;

        // この問題用の瓶幅を計算
        this.make_bin_body_W();

        // Y ベースをリセット
        this.bin_cap_center_y = this.bin_first_space_H + this.bin_cap_H_sum_half;

        // 新しい問題用にリセット
        this.parameters = [];

        // 瓶の現在の左の x 座標
        let bin_current_left_x = this.bin_start_left_x;

        // 画面の左側、どれくらい外から入ってくるか（全瓶共通のオフセット量）
        const offsetX = this.whole_W + this.bin_start_left_x;

        // ★ 元の japanese をコピーしてシャッフル
        const shuffled_words = [...cur_q_words];
        Phaser.Utils.Array.Shuffle(shuffled_words);

        // 瓶を作成（ランダム順でフェーズイン）
        shuffled_words.forEach((item) => {

            // ★ この単語が「元の並びで何番目か」
            const originalIndex = cur_q_words.indexOf(item);

            // ★ 幅も元 index に対応させる
            const binWidth = this.widths[originalIndex];

            this.bin_body_W = binWidth;
            this.cap_body_W = this.bin_body_W - this.bin_body_R;

            // 折り返し判定（次の行へ）
            if (bin_current_left_x + this.bin_body_W > this.bin_last_right_x) {
                this.bin_cap_center_y += this.bin_body_H + this.cap_body_H_sum + this.bin_space_H;
                bin_current_left_x = this.bin_start_left_x;
            }

            // フェーズイン後の最終位置
            const target_center_x = bin_current_left_x + this.bin_body_W / 2;
            const target_center_y = this.bin_cap_center_y;

            // フェーズイン開始位置（全体を左に offsetX ぶんずらす）
            const start_center_x = target_center_x - offsetX;
            const start_center_y = target_center_y;

            // create_jar は this.bin_cap_center_y を使うので、一時的に差し替える
            const prev_center_y = this.bin_cap_center_y;
            this.bin_cap_center_y = start_center_y;

            const parameter = this.create_jar(start_center_x, item.word, item.color);

            // 元の Y に戻す
            this.bin_cap_center_y = prev_center_y;

            // 小さいパイプ
            parameter.pipe = this.small_pipe(parameter.container);

            // ★ 正解判定用の番号（元のインデックス）
            parameter.number = originalIndex;

            // フェーズイン完了後の目標位置
            parameter.target_x = target_center_x;
            parameter.target_y = target_center_y;

            // 幅を後から使うなら残しておく（drag_end_all_parameters_tween などで利用するなら）
            parameter.bin_w = binWidth;

            this.parameters.push(parameter);

            // 次の瓶の x へ（ここで bin_space_W 分のすき間が確保される）
            bin_current_left_x += this.bin_body_W + this.bin_space_W;
        });
    }

    // 問題の判定
    check_sentence() {
        const cur_q = this.questions[this.cur_question_i];
        // 正解の数字列                   
        const correct_sentence_num = cur_q.japanese.map((_, i) => i).join("");
        // 回答の数字列
        const user_sentence_num = this.parameters.map(item => item.number).join("");

        // コレクトアンサーを辞書にする
        const word_to_index = Object.fromEntries(
            cur_q.japanese.map((item, index) => [item.word, index])
        );       

        ///// 用意した間違い方であるかの確認
        const wrong_num_arr = cur_q.wrong_answers.map(wrong =>
            wrong.words.join("")
        );
        
        const hit_index = wrong_num_arr.findIndex(
            w => w === user_sentence_num
        );

        // チェックボタン削除
        if (this.check_button) {
            this.check_button.destroy();
            this.check_button = null;                    
        }

        // 正解、不正解の処理
        if (correct_sentence_num === user_sentence_num) {           

            if (this.cur_question_i + 1 == this.questions.length) {
                this.on_clear();
                return;
            }        

            if (this.wrong_container) {
                this.wrong_container.destroy();
                this.wrong_container = null;
            }
            
            // continueを表示
            this.continnue_container = this.create_continue_button();

            // this.input.enabled = false;
            // this.continnue_container.continue_button.setInteractive();
            
            // 注ぐための小さなパイプを出現
            this.parameters.forEach(param => {
                this.tweens.add({
                    targets: param.pipe.rect,
                    scaleY: 1,
                    duration: 600,
                    ease: 'Sine.easeInOut',
                    onComplete: () => {
                        this.tweens.add({
                            targets: param.pipe.second_rect,
                            scaleY: 1,
                            duration: 600,
                            ease: 'Sine.easeInOut',
                            onComplete: () => {
                                // 液体を注ぐ
                                this.pouring = true;                                            
                            }
                        });                                
                    }                              
                });
            });

        } else if (hit_index !== -1) {
            ////// 用意していた間違いに当てはまった時

            const wa = cur_q.wrong_answers[hit_index];

            // 間違いの画像を表示            
            this.questionImage.setTexture(wa.image_key);
            this.questionImage.setScale(wa.scale);

            // 入力無効化
            this.input.enabled = false;

            // シーン上の全オブジェクト（questionImage だけ除外）
            const allObjects = this.children.list.filter(obj => obj !== this.questionImage);

            // 揺らす
            allObjects.forEach(obj => {
                this.tweens.add({
                    targets: obj,
                    x: obj.x + 10,
                    yoyo: true,
                    repeat: 50,
                    duration: 30,
                    ease: 'Sine.easeInOut',
                    onComplete: () => {
                        // 最後のオブジェクトの tween が終わったら入力復活
                        if (obj === allObjects[allObjects.length - 1]) {
                            // 正解の画像再表示                            
                            this.questionImage.setTexture(cur_q.image_key);
                            this.questionImage.setScale(cur_q.scale);
                            this.input.enabled = true;
                        }
                    }
                });
            });


            console.log("wrong_answers の", hit_index, "番目に一致！");
            // this.handleSpecificWrong(hit_index);

            // 以前の間違え表示を削除
            if (this.wrong_container) {
                this.wrong_container.destroy()
                this.wrong_container = null;
            }

            // 間違えのコンテンツ表示
            this.wrong_container = this.create_wrong_button();

        } else {

            // 不正解シェイクアクション
            this.shake_jars()

            // 以前の間違え表示を削除
            if (this.wrong_container) {
                this.wrong_container.destroy()
                this.wrong_container = null;
            }

            // 間違えのコンテンツ表示
            this.wrong_container = this.create_wrong_button();
        }


    }

    // 間違えのコンテンツ作成
    create_wrong_button() {
        const width = this.cx * 2;
        const height = this.cy / 3;
        const radius = this.cy / 24;
        const x = this.cx;
        const y = this.cy*(2-1/6) + radius;       

        // コンテナ作成
        const container = this.add.container(x, y);
        
        // 背景作成
        const bg = this.add.graphics();
        bg.fillStyle(0x333333, 0.7);
        bg.fillRoundedRect(-width/2, -height/2, width, height, radius);
        
        // htmlでボタン内のtext作成
        const wrong_text_dom = this.add.dom(0, 0).createFromHTML(`        
            <div style="
                color: #FFFFFF;
                font-size: ${this.cy/12}px;
                font-weight: bold;
                text-align: center;            
            ">               
                <i class="fa-solid fa-xmark" style="color: #FF8D28;"></i> Try again!
            </div>
        `);

        // checkボタン作成
        const check_button = this.create_button(0, (this.cy/12 - radius), 'check', 0xFF8D28, 0.83
        , () => {
            this.check_sentence();
        });


        wrong_text_dom.setOrigin(0.5);

        wrong_text_dom.setPosition(-this.cx/3, -this.cy/12); 

        container.add([bg, wrong_text_dom, check_button]);

        return container;
        // return null;
    }                
    
    // continueを作成
    create_continue_button() {
        const width = this.whole_W;
        const height = this.cy / 3;
        const radius = this.cy / 24;
        const x = this.cx;
        const y = this.cy * (2 - 1/6) + radius;
        
        // コンテナ作成
        const container = this.add.container(x, y);

        // 背景作成
        const bg = this.add.graphics();
        bg.fillStyle(0x333333, 0.7);
        bg.fillRoundedRect(-width/2, -height/2, width, height, radius);

        // htmlでボタン内のtext作成
        const wrong_text_dom = this.add.dom(0, 0).createFromHTML(`        
            <div style="
                color: #FFFFFF;
                font-size: ${this.cy/12}px;
                font-weight: bold;
                text-align: center;            
            ">
                <i class="fa-regular fa-circle-check" style="color: #FF8D28;"></i> Great job!
            </div>
        `);

        // continueボタン作成
        const continue_button = this.create_button(0, (this.cy/12 - radius), 'continue', 0xFF8D28, 0.83, () => {
            
            // 全てのボタン削除
            if (this.continnue_container) {
                this.continnue_container.destroy();
                this.continnue_container = null;
            }
            if (this.wrong_container) {
                this.wrong_container.destroy();
                this.wrong_container = null;
            }
            if (this.check_button) {
                this.check_button.destroy();
                this.check_button = null;
            }
            
            // 入力を無効化
            this.input.enabled = false;

            // 瓶フェーズアウト
            this.phaseout_parameters = this.parameters;
            this.is_phase_out = true;                        

            // コンベア初期スピード
            this.conveyor_speed = this.conveyor_min_speed;

        })

        wrong_text_dom.setOrigin(0.5);

        wrong_text_dom.setPosition(-this.cx/3, -this.cy/12);

        container.add([bg, wrong_text_dom, continue_button]);

        return container;
        // return null;                
    }                

    /// 間違えた時のシェイク
    shake_jars() {

        // コンテナの配列
        const conts = this.parameters.map(item => item.container);
        // コンテナの元のx座標の配列
        const original_xs = conts.map(item => item.x);
        // 全ての入力を拒否
        this.input.enabled = false;

        this.tweens.add({
            targets: conts,
            x: '+=6',
            yoyo: true,
            repeat: 5,
            duration: 40,
            onComplete: () => {
                // 終わったら必ず元の位置に戻す
                conts.forEach((item, i) => {
                    item.x = original_xs[i];
                    // 全ての入力を受け入れ
                    this.input.enabled = true;
                });
            }
        });

    }

    ///// checkやcontinueなどのbutton /////
    create_button(x, y, label, color, opacity, on_click) {
        const width = this.cx;
        const height = this.cy / 10;
        const radius = 10;
        
        // コンテナ作成
        const container = this.add.container(x, y);
        
        // ボタン背景作成
        const bg = this.add.graphics();
        bg.fillStyle(color, opacity);
        bg.fillRoundedRect(-width/2, -height/2, width, height, radius);
        bg.lineStyle(this.cy/120, 0xB67A20);
        bg.strokeRoundedRect(-width/2, -height/2, width, height, radius);

        // ボタン内のテキスト作成
        const button_text = this.add.text(0, 0, label, {
            fontSize: `${this.cy/12}px`,
            color: '#f9fafb',
            fontStyle: 'bold'
        }).setOrigin(0.5, 0.5);

        button_text.setPosition(0, 0);

        // コンテナに加える
        container.add([bg, button_text]);
        container.setSize(width, height);

        // 操作可能にする
        container.setInteractive({ useHandCursor: true });
        
        
        // マウスカーソルが乗った時
        container.on('pointerover', () => {

            this.tweens.add({
                targets: container,
                scaleX: 1.05,
                scaleY: 1.05,
                duration: 120,
                ease: 'Quad.easeOut' 
            });

            bg.setFillStyle(Phaser.Display.Color.IntegerToColor(color).darken(10).color);
        })

        // マウスカーソルが外れた時
        container.on('pointerout', () => {
            
            this.tweens.add({ 
                targets: container,
                scaleX: 1,
                scaleY: 1,
                duration: 120,
                ease: 'Quad.easeOut'
            });
            
            bg.setFillStyle(color);
        })

        // クリックした時
        container.on('pointerdown', () => {

            this.tweens.add({
                targets: container,
                scaleX: 0.96,
                scaleY: 0.96,
                yoyo: true,
                duration: 120,
                ease: 'Quad.easeOut' 
            });

            // this.check_sentence()を実行
            on_click();
        });

        return container;
        // return null;
    }

    // ドラッグ開始時
    drag_start(pointer, gameObject) {
        // 前面に移動
        gameObject.setDepth(10);
        
        // ▼ drag_start 内に追加した行
        const para = gameObject.parameter;
        para.con_start_x = gameObject.x;
        para.con_start_y = gameObject.y;

        // small_pipe 非表示
        if (para.pipe) {
            para.pipe.rect.setVisible(false);
            para.pipe.second_rect.setVisible(false);
        }

        // 拡大
        this.tweens.add({
            targets: gameObject,
            scale: 1.05,
            duration: 150,
            ease: 'Quad.easeOut'
        });
    }                

    // ドラッグ中
    drag(pointer, gameObject, dragX, dragY){
        gameObject.x = dragX;
        gameObject.y = dragY;
    }

    // ドラッグ終了時
    drag_end(pointer, gameObject) {                                        

        // 手前に出していたのを戻す
        gameObject.setDepth(1);

        this.tweens.add({
            targets: gameObject,
            scale: 1.0,
            duration: 150,
            ease: 'Quad.easeOut'
        });

        // その container が持っているパラメータを取得
        const moving_para = gameObject.parameter;

        // 元の配列上の位置
        const old_index = this.parameters.indexOf(moving_para);
        if (old_index === -1) {
            return;
        }

        // ドロップ位置（＝ドラッグ終了時の中心座標）
        const dropX = gameObject.x;
        const dropY = gameObject.y;

        // 元の位置との距離を測る
        let con_start_x, con_start_y;

        if (moving_para.con_start_x !== undefined) {
            con_start_x = moving_para.con_start_x;
            con_start_y = moving_para.con_start_y;
        } else {
            con_start_x = moving_para.container.x;
            con_start_y = moving_para.container.y;
        }                    
        
        // いったん配列から自分を抜いておく
        this.parameters.splice(old_index, 1);

        const dx0 = dropX - con_start_x;
        const dy0 = dropY - con_start_y;
        const dist_base = Math.sqrt(dx0 * dx0 + dy0 * dy0);

        // 「一番近い瓶」を探すための準備
        let best_dist  = dist_base;
        let best_param = null;
        let best_i     = -1;

        for (let i = 0; i < this.parameters.length; i++) {
            const p  = this.parameters[i];
            const cx = p.container.x;
            const cy = p.container.y;

            const dx = dropX - cx;
            const dy = dropY - cy;
            const dist = Math.sqrt(dx * dx + dy * dy);

            if (dist < best_dist) {
                best_dist  = dist;
                best_param = p;
                best_i     = i;
            }
        }

        // 近くに他の瓶がひとつも無い場合
        if (!best_param) {
            // ここは parameter じゃなくて moving_para の方が正しい
            this.parameters.splice(old_index, 0, moving_para);
            this.drag_end_all_parameters_tween();
            return;
        }

        // ロジック上も、近かったやつの前に入れる
        this.parameters.splice(best_i, 0, moving_para);

        // 全体レイアウトを整える
        this.drag_end_all_parameters_tween();

        console.log('this.parameters (after drop):', this.parameters);                    

    }                                

    ///// カードを新しい位置に移動する /////
    drag_end_all_parameters_tween() {
        let sum_body_w = 0;
        const base_x = this.bin_start_left_x;  
        const base_y = this.bin_first_space_H + this.bin_cap_H_sum_half;
        let col = 0;
        let middle_w = 0;
        const row_height = this.bin_space_H + this.bin_cap_H_sum_half;

        this.parameters.forEach((parameter, i) => {

            const next_width = middle_w + parameter.bin_w;

            if (next_width > this.bins_max_width) {
                sum_body_w = 0;
                middle_w = 0;
                col += 1;
            }
            
            const pos_x = base_x + sum_body_w + parameter.bin_w / 2; 
            const pos_y = base_y + (this.bin_space_H + this.bin_cap_H_sum) * col;
            
            // 移動中は非表示する
            if (parameter.pipe) {
                parameter.pipe.rect.setVisible(false);
                parameter.pipe.second_rect.setVisible(false);
            }

            this.tweens.add({
                targets: parameter.container,
                x: pos_x,
                y: pos_y,
                duration: 300,
                ease: 'Linear',
                onComplete: () => {
                    // small_pipeを表示
                    if (parameter.pipe) {
                        parameter.pipe.rect.setVisible(true);
                        parameter.pipe.second_rect.setVisible(true);
                    }
                }                         
            })

            sum_body_w += parameter.bin_w + this.bin_space_W; 
            middle_w += parameter.bin_w + this.bin_space_W;                                      
        })
    }                

    ///// 瓶の横幅作成 /////
    make_bin_body_W() {

        const cur_q_words = this.questions[this.cur_question_i].japanese;

        // まず各単語の想定幅を計算（テキスト＋パディング）  
        const tempTextStyle = {
            fontFamily: this.font_family,
            fontSize: this.font_size,
            fontStyle: this.font_style                        
        };

        this.widths = cur_q_words.map(item => {
            const tmp = this.add.text(0, 0, item.word, tempTextStyle).setOrigin(0.5);
            const paddingX = this.whole_W / 15;
            const bin_body_W = tmp.width + paddingX;
            tmp.destroy(); // 仮テキストなので削除
            return bin_body_W;
        });
    }

    ///// 横の長いパイプを作成 /////
    long_pipe(pipe_space) {

        // パイプと瓶の蓋の距離
        const height_pipe = (this.bin_first_space_H  - (this.long_pipe_bottom_w)) + pipe_space;

        // Y座標
        const y_top = height_pipe - (this.whole_H / 60);
        const y_bottom = height_pipe;

        // X座標
        const x_left = 0;
        const x_right = this.whole_W;

        const g = this.add.graphics(); 
        
        const line_weight = this.whole_W / 550;

        // 長いパイプの描写
        // 塗りの設定
        g.fillStyle(0xD9D9D9, 1);
        // 枠線
        g.lineStyle(line_weight, 0x000000, 1);
        g.beginPath();
        g.moveTo(x_left, y_top);
        g.lineTo(x_right, y_top);
        g.lineTo(x_right, y_bottom);
        g.lineTo(x_left, y_bottom);
        g.closePath();
        g.fillPath();
        g.strokePath();

    }
    
    /////  瓶をそれぞれ参照  /////
    create_jar_row() {

        const cur_q_words = this.questions[this.cur_question_i].japanese;

        // ★ 元データを壊さないようにコピーしてからシャッフル
        const shuffled_words = [...cur_q_words];
        Phaser.Utils.Array.Shuffle(shuffled_words);

        // 瓶の現在の左のx座標                                                                                                              
        let bin_current_left_x = this.bin_start_left_x;

        // 瓶を作成
        shuffled_words.forEach((item) => {

            // ★ この単語が「元の配列の何番目か」を調べる
            const originalIndex = cur_q_words.indexOf(item);

            // 瓶とキャップの横幅
            // ※ width も「元の index」に紐づけておく
            this.bin_body_W = this.widths[originalIndex];
            this.cap_body_W = this.bin_body_W - this.bin_body_R;

            // 現在の行列
            this.row = 1;
            this.col = 1;

            // 瓶の折り返し分岐
            if (bin_current_left_x + this.bin_body_W > this.bin_last_right_x) {
                // 行を1つ下に下げる
                this.bin_cap_center_y += this.bin_body_H + this.cap_body_H_sum + this.bin_space_H;
                bin_current_left_x = this.bin_start_left_x;
                this.col++;
                this.row = 1;
            }

            // 瓶の中心のx座標
            const bin_current_center_x = bin_current_left_x + this.bin_body_W / 2;
            // 瓶の色
            const capColor = item.color;

            // 瓶と蓋を作成
            const parameter = this.create_jar(bin_current_center_x, item.word, capColor);                        

            // 小さなパイプ作成
            parameter.pipe = this.small_pipe(parameter.container);

            // ★ 正解判定用に「元の並び番号」を記録
            parameter.number = originalIndex;

            this.parameters.push(parameter);
            
            // 次のx座標に移動
            bin_current_left_x += this.bin_body_W + this.bin_space_W;
            this.row++;                        
        });

    }

    ///// それぞれの瓶の小さなパイプを作成 /////
    small_pipe(container) {                    
        // const s_pipe_top_y = this.bin_cap_center_y - (this.bin_cap_H_sum_half + this.long_pipe_bottom_w);
        const s_pipe_top_y = - (this.bin_cap_H_sum_half + this.long_pipe_bottom_w);
        const s_pipe_width = this.whole_W / 60;
        const s_pipe_height = this.whole_H / 100;
        const line_weight = this.whole_W / 550;
        
        // 小さなパイプの上の図形
        const x = 0;
        const y = s_pipe_top_y + (s_pipe_height / 2);

        const rect = this.add.rectangle(x, y - (s_pipe_height / 2), s_pipe_width, s_pipe_height, 0xD9D9D9);
        rect.setOrigin(0.5, 0);
        rect.scaleY = 0;
        rect.setStrokeStyle(line_weight, 0x000000);
        container.add(rect);


        //// 小さなパイプの下の図形
        const second_pipe_h = s_pipe_height / 2;

        this.second_pipe_w = s_pipe_width * 1.7;

        const bottom_y = rect.y + rect.height;

        const second_rect_y = bottom_y + second_pipe_h / 2;

        this.second_rect_bottom = bottom_y + second_pipe_h;

        const second_rect = this.add.rectangle(x, second_rect_y - (second_pipe_h / 2), this.second_pipe_w, second_pipe_h, 0xD9D9D9);
        second_rect.setOrigin(0.5, 0);
        second_rect.scaleY = 0;
        second_rect.setStrokeStyle(line_weight, 0x000000);                    
        container.add(second_rect);

        return {rect, second_rect};
    }

    /////  瓶と蓋を作成  /////
    create_jar(bin_current_center_x, word, capColor) {
        
        const container = this.add.container(bin_current_center_x, this.bin_cap_center_y);

        // 瓶の背面
        const g_back = this.add.graphics();
        container.add(g_back);

        // 瓶の中の液体用
        const g_inside_wave = this.add.graphics();
        container.add(g_inside_wave);

        // 瓶の外からの液体用
        const g_outside_wave = this.add.graphics();
        container.add(g_outside_wave);

        // 瓶の前面
        const g_front = this.add.graphics();
        container.add(g_front);

        // 背面
        g_back.lineStyle(this.bin_lid_line_weight, 0x000000, 1);
        g_back.fillStyle(0xffffff, 1);

        const halfH = this.bin_cap_H_sum_half;
        const bodyTopY = this.cap_body_H_sum - halfH;

        // 瓶の白い本体は背面
        g_back.fillRoundedRect(-this.bin_body_W / 2, bodyTopY, this.bin_body_W, this.bin_body_H, this.bin_body_R);


        // 蓋や枠線は前面
        g_front.lineStyle(this.bin_lid_line_weight, 0x000000, 1);
        g_front.fillStyle(capColor, 1);

        const cap2Y = -halfH;
        const cap1Y = this.cap_body_H - halfH;

        // 上の蓋
        g_front.fillRoundedRect(-this.cap_body_W / 2, cap2Y, this.cap_body_W, this.cap_body_H, this.cap_body_R);
        g_front.strokeRoundedRect(-this.cap_body_W / 2, cap2Y, this.cap_body_W, this.cap_body_H, this.cap_body_R);

        // 下の蓋
        g_front.fillRoundedRect(-this.cap_body_W / 2, cap1Y, this.cap_body_W, this.cap_body_H, this.cap_body_R);
        g_front.strokeRoundedRect(-this.cap_body_W / 2, cap1Y, this.cap_body_W, this.cap_body_H, this.cap_body_R);
        
        // 本体枠線だけ前面にする
        g_front.strokeRoundedRect(-this.bin_body_W / 2, bodyTopY, this.bin_body_W, this.bin_body_H, this.bin_body_R);

        // 瓶の中の文字
        const label = this.add.text(0, this.cap_body_H_sum / 2, word, {
            fontFamily: this.font_family,
            fontSize: this.font_size,
            fontStyle: this.font_style, 
            color: this.font_color
        }).setOrigin(0.5);


        container.add(label);

        // 現在の瓶の中心座標
        const bin_center_x = bin_current_center_x;
        const bin_center_y = this.bin_cap_center_y;
        
        // 瓶のパラメータ保管
        const parameter = {
            container: container, 
            bin_center_x: bin_center_x, 
            bin_center_y: bin_center_y, 
            bin_w: this.bin_body_W, 
            bin_h: this.bin_body_H, 
            g_inside_wave: g_inside_wave,
            g_outside_wave: g_outside_wave
        };

        // コンテナにもパラメタ保管
        container.parameter = parameter;                                                     

        // ドラッグ用
        container.setSize(this.bin_body_W, this.bin_cap_H_sum);
        container.setInteractive({ draggable: true, useHandCursor: true });
        // container.setInteractive({ useHandCursor: true });
        // this.input.setDraggable(container);  // ドラッグしたいなら有効化
        
        console.log('container.parameter', container.parameter);
        return parameter;                    
    }

}
