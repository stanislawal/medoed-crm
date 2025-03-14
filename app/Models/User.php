<?php

namespace App\Models;

use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Laravel\Sanctum\HasApiTokens;
use Spatie\Permission\Models\Role;
use Spatie\Permission\Traits\HasRoles;

class User extends Authenticatable
{
    use HasApiTokens, HasFactory, Notifiable, HasRoles;

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'full_name',
        'password',
        'visual_password',
        'login',
        'birthday',
        'contact_info',
        'manager_salary',
        'duty',
        'link_author',
        'is_work',
        'payment', //реквизиты оплаты
        'bank_id',
        'working_day',

        // поля с иформацией для генерации документа
        'fio_for_doc',
        'inn_for_doc',
        'contract_number_for_doc',
        'date_contract_for_doc',
        'email_for_doc'
    ];

    /**
     * The attributes that should be hidden for serialization.
     *
     * @var array<int, string>
     */
    protected $hidden = [
        'password',
        'remember_token',
    ];

    /**
     * The attributes that should be cast.
     *
     * @var array<string, string>
     */
    protected $casts = [
        'email_verified_at' => 'datetime',
    ];

    public function bank()
    {
        return $this->belongsTo(Bank::class, 'bank_id');
    }

    /*
     *  Минималькое имя
     */
    public function getMinNameAttribute()
    {
        // Получаем полное имя
        $fullName = $this->full_name; // Замените 'full_name' на ваше поле с ФИО

        // Разбиваем строку на части
        $parts = explode(' ', $fullName);

        $minName = '';

        foreach ($parts as $key => $item) {
            if ($key == 0) {
                $minName .= $item;
            } else {
                $minName .= ' ' . mb_substr($item, 0, 1) . '.';
            }
        }

        return $minName;
    }
}
