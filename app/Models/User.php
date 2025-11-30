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
        'telefono',
        'profile_photo_path',
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
     * The attributes that should be cast.
     *
     * @var array<string, string>
     */
    protected $casts = [
        'email_verified_at' => 'datetime',
        'password' => 'hashed',
    ];
    // app/Models/User.php


/**
 * Los roles que tiene el usuario.
 */
public function roles()
{
    // Un Usuario pertenece a muchos Roles (tabla pivote)
    return $this->belongsToMany(Rol::class, 'usuario_roles');
}

/**
 * El perfil de vendedor
 */
public function vendedor()
{
    // Un Usuario tiene un solo perfil de Vendedor
    return $this->hasOne(Vendedor::class, 'user_id');
}

/**
 * El perfil de repartidor
 */
public function repartidor()
{
    // Un Usuario tiene un solo perfil de Repartidor
    return $this->hasOne(Repartidor::class, 'user_id');
}

/**
 * Los pedidos que ha hecho como cliente
 */
public function pedidos()
{
    // Un Usuario tiene muchos Pedidos (como cliente)
    return $this->hasMany(Pedido::class, 'cliente_id');
}

/**
 * Obtener la URL de la foto de perfil.
 * Si no tiene, retorna una imagen por defecto.
 */
public function getProfilePhotoUrlAttribute()
{
    if ($this->profile_photo_path) {
        // Si la URL empieza con http, es una URL externa (ej: Google)
        if (filter_var($this->profile_photo_path, FILTER_VALIDATE_URL)) {
            // Optimizar URL de Google añadiendo parámetro de tamaño
            $url = $this->profile_photo_path;
            if (strpos($url, 'googleusercontent.com') !== false) {
                // Remover parámetros existentes y agregar tamaño específico
                $url = preg_replace('/=s\d+-c/', '', $url);
                $url = rtrim($url, '?') . '?sz=200';
            }
            return $url;
        }
        // Si no, es una ruta local
        return asset('storage/' . $this->profile_photo_path);
    }
    
    // Imagen por defecto del proyecto
    return asset('Vista/img/avatar.png');
}
}

