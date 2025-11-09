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
            'email_verified_at' => 'datetime',
            'password' => 'hashed',
        ];
    }
    // app/Models/User.php

// ... (dentro de la clase User)

/**
 * Los roles que tiene el usuario.
 */
public function roles()
{
    // Un Usuario pertenece a muchos Roles (tabla pivote 'usuario_roles')
    return $this->belongsToMany(Rol::class, 'usuario_roles');
}

/**
 * El perfil de vendedor (si lo tiene).
 */
public function vendedor()
{
    // Un Usuario tiene un solo perfil de Vendedor
    return $this->hasOne(Vendedor::class, 'user_id');
}

/**
 * El perfil de repartidor (si lo tiene).
 */
public function repartidor()
{
    // Un Usuario tiene un solo perfil de Repartidor
    return $this->hasOne(Repartidor::class, 'user_id');
}

/**
 * Los pedidos que ha hecho como cliente.
 */
public function pedidos()
{
    // Un Usuario tiene muchos Pedidos (como cliente)
    return $this->hasMany(Pedido::class, 'cliente_id');
}
}

