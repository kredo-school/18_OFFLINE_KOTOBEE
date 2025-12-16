<div class="game-start-modal" id="modal">

    <div class="game-modal-content">

        <!-- タイトル -->
        <div class="modal-game-title">
            <h5>{{ $title }}</h5>
        </div>

        <!-- ゲーム説明 -->
        <div class="modal-game-explanation">
            <h6>{{ $description }}</h6>
        </div>
        
        <!-- ゲーム結果 -->
        <div class="modal-result-contents">

            {{-- Top 3 表示 --}}
            <div class="top3-results">
                
                <h6>Top 3 Players</h6>

                @if ($top3->isEmpty())
                    <p>No records yet</p>
                @else
                    @php
                        $trophy_colors = ["#DAA520", "#C0C0C0", "#CD7F32"];
                    @endphp

                    @foreach ($top3 as $rank => $result)
                        <li class="rank-line">
                            <span class="trophy-col">
                                <span class="trophy-bg">
                                    {{-- faでトロフィー作成 --}}
                                    <i class="fa-solid fa-trophy" 
                                        style="color: {{ $trophy_colors[$rank] ?? '#999'}}"></i>
                                </span>
                            </span>

                            <span class="rank-name
                                {{ auth()->id() === $result->user->id ? 'my-rank' : '' }}">
                                {{ $result->user->name }}
                            </span>

                            <span class="rank-time">
                                {{-- {{ number_format($result->best_time, 2) }} --}}
                                {{-- {{ number_format($result->best_value, 2) }} --}}
                                {{ rtrim(rtrim(number_format($result->best_value, 2), '0'), '.') }}
                                {{ $unit }}
                            </span>
                            
                        </li>
                    @endforeach

                @endif

            </div>

            {{-- 自分のベストタイム --}}                                
            <div class="my-best-time">
                <span class="trophy-col"></span>
                <span class="label">You</span>
            
                @if ($best_value !== null)
                    <span class="time">
                        {{-- {{ number_format($best_time, 2) }} --}}
                        {{ rtrim(rtrim(number_format($best_value, 2), '0'), '.') }}
                        {{ $unit }}
                    </span>
                @else
                    <span class="time">Not played yet</span>
                @endif
            </div>               

        </div>

        <!-- ゲームボタン -->
        <div class="modal-buttons">
            <button type="button" class="back-btn js-close-modal">Back</button>
            <a href="{{ $play_url }}" class="again-btn">Start</a>
        </div>
                    
    </div>

</div>