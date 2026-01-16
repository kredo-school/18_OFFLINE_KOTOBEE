
// npm run build

import { defineConfig } from 'vite';
import laravel from 'laravel-vite-plugin';

export default defineConfig({
    plugins: [
        laravel({
            input: [
                'resources/sass/app.scss',

                'resources/js/app.js',
                'resources/js/bootstrap.js',
                'resources/js/game_result_modal.js',
                'resources/js/game_stages.js',
                'resources/js/game_start_modal.js',
                'resources/js/grammar_game.js',
                'resources/js/kana_game_60s.js',
                'resources/js/kana_game_timeattack.js',
                'resources/js/streak.js',
                'resources/js/vocab_result_modal.js',   
                'resources/css/style.css',
                'resources/css/style_kana.css',      

                'resources/css/app.css',
                'resources/css/common.css',
                'resources/css/game_stages.css',
                'resources/css/game_start_modal.css',
                'resources/css/grammar_game.css',
                'resources/css/grammar.css',
                'resources/css/group_student_join.css',
                'resources/css/group_student_search.css',
                'resources/css/kana_game.css',
                'resources/css/profile_edit.css',
                'resources/css/profile.css',
                'resources/css/vocabulary.css',
                'resources/css/dashboard/playcount-cards.css',

            ],
            refresh: true,
        }),
    ],   
})


// import { defineConfig } from 'vite';
// import laravel from 'laravel-vite-plugin';

// export default defineConfig({
//     plugins: [
//         laravel({
//             input: [
//                 'resources/sass/app.scss',
//                 'resources/js/app.js',
//             ],
//             refresh: true,
//         }),
//     ],
// })      

