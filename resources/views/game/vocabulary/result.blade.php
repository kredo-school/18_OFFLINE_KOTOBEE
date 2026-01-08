<!-- モーダルの背景オーバーレイ -->
<div id="modal-overlay" class="modal" style="display:none;"></div>

<!-- モーダル本体 -->
<div id="result-modal" style="display:none;">
    <h2>Result</h2>
    <div id="rank-content"></div>
    <div class="modal-buttons">
        <button id="again-btn" class="kb-btn-again">Again</button>
        <a href="{{ route('game.select') }}" class="kb-btn-back">Back</a>
    </div>
</div>
