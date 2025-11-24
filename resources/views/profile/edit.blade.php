@extends('layouts.principal')

@section('titulo', 'Perfil')

@section('estilos')
    <style>
        .perfil-panel { padding: 2rem 0; }
        .perfil-card { background: #fff; padding: 1.25rem; border-radius: 8px; box-shadow: 0 2px 6px rgba(0,0,0,0.06); margin-bottom: 1rem; }
        .perfil-header { margin-bottom: 1rem; }
        .perfil-h2 { font-family: 'Playfair Display', serif; font-size: 1.25rem; margin: 0 0 0.25rem; }
        .perfil-p { color: #555; margin: 0 0 0.75rem; }
        .perfil-form .label { display:block; font-weight:600; margin-bottom:0.25rem; }
        .perfil-form input[type="text"], .perfil-form input[type="email"], .perfil-form input[type="password"] { width:100%; padding:0.5rem; border:1px solid #ddd; border-radius:4px; }
        .perfil-actions { display:flex; gap:0.5rem; align-items:center; }
        .boton-primario { background:#1f2937; color:#fff; padding:0.5rem 0.75rem; border-radius:4px; text-decoration:none; }
    </style>
@endsection

@section('scripts')
    <script>
        document.addEventListener('DOMContentLoaded', function(){
            const delForm = document.getElementById('delete-account-form');
            if (delForm) {
                delForm.addEventListener('submit', function(e){
                    e.preventDefault();
                    Swal.fire({
                        title: '¿Estás seguro?',
                        text: 'Eliminar tu cuenta es irreversible.',
                        icon: 'warning',
                        showCancelButton: true,
                        confirmButtonColor: '#d33',
                        cancelButtonColor: '#3085d6',
                        confirmButtonText: 'Sí, eliminar'
                    }).then((result) => {
                        if (result.isConfirmed) {
                            delForm.submit();
                        }
                    });
                });
            }
        });
    </script>
@endsection

@section('contenido')
    <div class="contenedor perfil-panel">
        <h1 class="perfil-header">Mi Perfil</h1>

        <div class="perfil-card">
            <h2 class="perfil-h2">Información de perfil</h2>
            <p class="perfil-p">Actualiza tu nombre y dirección de correo.</p>
            <div class="perfil-form">
                @include('profile.partials.update-profile-information-form')
            </div>
        </div>

        <div class="perfil-card">
            <h2 class="perfil-h2">Cambiar contraseña</h2>
            <p class="perfil-p">Asegúrate de usar una contraseña segura.</p>
            <div class="perfil-form">
                @include('profile.partials.update-password-form')
            </div>
        </div>

        <div class="perfil-card">
            <h2 class="perfil-h2">Eliminar cuenta</h2>
            <p class="perfil-p">Eliminar tu cuenta es permanente. Se te pedirá tu contraseña para confirmar.</p>
            <div class="perfil-form">
                @include('profile.partials.delete-user-form')
            </div>
        </div>
        
        
    </div>
@endsection
