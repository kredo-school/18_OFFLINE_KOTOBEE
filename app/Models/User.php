<?php

namespace App\Models;

// use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;

class User extends Authenticatable
{
    /** @use HasFactory<\Database\Factories\UserFactory> */
    use HasFactory, Notifiable;

    /**
     * The attributes that are mass assignable.
     *
     * @var list<string>
     */
    protected $fillable = [
        'name',
        'email',
        'password',
        'avatar_url',
        'role',
        'group_id',
        'prefecture_id',
        'acquired_at',
    ];

    /**
     * The attributes that should be hidden for serialization.
     *
     * @var list<string>
     */
    protected $hidden = [
        'password',
        'remember_token',
    ];

    /**
     * Get the attributes that should be cast.
     *
     * @return array<string, string>
     */
    protected function casts(): array
    {
        return [
            'acquired_at' => 'datetime',
            'password' => 'hashed',
        ];
    }
    
    // Relations. ユーザのグループidからグループ名などを取得する
    public function group()
    {
        return $this->belongsTo(Group::class);
    }

    // ユーザが管理者である場合、保持しているグループ一覧
    public function my_groups()
    {
        return $this->hasMany(Group::class, 'owner_id');
    }

    // ユーザがプレイしたゲーム結果一覧
    public function game_results()
    {
        return $this->hasMany(GameResult::class, 'user_id');
    }

    // User.php
    public function gameResults()
    {
        return $this->hasMany(GameResult::class, 'user_id');
    }

    //ユーザーがアカウント削除した場合
    protected static function booted()
    {
        static::deleting(function ($user) {
            $user->gameResults()->delete(); // 関連データも削除
        });
    }

    // グループに承認されているか
    public function approved_group()
    {
        return $this->belongsToMany(Group::class, 'group_members')
            ->wherePivot('status', 2)
            ->withPivot('status')
            ->first();
    }

    // 表示用のグループを返す
    public function display_group()
    {
        $approved = $this->approved_group();
        
        if ($approved) return $approved;

        // ownerとして作成したグループがあるなら最初の1件
        return $this->my_groups()->orderBy('id')->first();
    }


}
