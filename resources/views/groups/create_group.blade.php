@extends('layouts.app')

@section('title', 'Create Group')

@section('content')
<div class="container d-flex justify-content-center">
    <div class="col-lg-6 col-md-8 col-12">
        <h2 class="text-center mb-2">Create Group</h2>

        {{-- 支払い成功 --}}
        @if(session('payment_success'))
            <div class="alert alert-success">
                <h4>Payment Completed!</h4>
            </div>
            <a href="#" class="btn btn-primary w-100 mb-3">Go to Group Dashboard</a>
        @endif

        {{-- 支払い失敗 --}}
        @if(session('payment_failed'))
            <div class="alert alert-danger">
                <h4>Payment Failed</h4>
                <p>Please try again.</p>
            </div>
        @endif

        {{-- グループ作成フォーム --}}
        <form action="{{ route('group.store') }}" method="POST">
            @csrf

            {{-- Plan Boxes --}}
            <div class="d-flex justify-content-between mt-3 mb-1 text-center w-100">

                <label class="plan-box">
                    <img src="{{ asset('storage/images/commons/basic_plan.png') }}" class="plan-img" alt="Basic">
                    <input type="radio" name="plan" value="basic" required>
                    <div class="fw-bold mt-1">Basic</div>
                </label>

                <label class="plan-box">
                    <img src="{{ asset('storage/images/commons/standard_plan.png') }}" class="plan-img" alt="Standard">
                    <input type="radio" name="plan" value="standard">
                    <div class="fw-bold mt-1">Standard</div>
                </label>

                <label class="plan-box">
                    <img src="{{ asset('storage/images/commons/premium_plan.png') }}" class="plan-img" alt="Premium">
                    <input type="radio" name="plan" value="premium">
                    <div class="fw-bold mt-1">Premium</div>
                </label>
            </div>
            <div class="mb-4">
                <h4 class="text-center text-primary fw-bold pt-1">
                    1-month free trial, then billed automatically
                </h4>
            </div>

            {{-- Group Name --}}
            <div class="mb-2">
                <label class="form-label fw-bold">Group Name</label>
                <input type="text" name="name" maxlength="12" class="form-control"
                    placeholder="e.g. Grade 7" required>
            </div>

            {{-- Secret Word --}}
            <div class="mb-2">
                <label class="form-label fw-bold">Secret Word</label>
                <input type="text" name="secret" maxlength="12" class="form-control"
                    placeholder="Students use this word to join" required>
            </div>

            <p class="small text-muted mb-2" style="margin-top:-5px;">
                Share this word with your students. They'll need it when joining the group.
            </p>

            {{-- Memo --}}
            <div class="mb-4">
                <label class="form-label fw-bold">Notes</label>
                <textarea name="note" class="form-control" rows="3" maxlength="255" placeholder="e.g. level, goals, schedule, etc"></textarea>
            </div>

            {{-- 支払いボタン（成功後は非表示でGroupAdminのダッシュボードへ） --}}
            @if(!session('payment_success'))
                <button type="submit" class="btn btn-success w-100">
                    Proceed to Payment
                </button>
            @else
                <a href="{{ route('group.dashboard', session('created_group_id')) }}"
                class="btn btn-primary w-100 mb-3">
                    Go to Group Dashboard
                </a>
            @endif
        </form>

    </div>
</div>
@endsection