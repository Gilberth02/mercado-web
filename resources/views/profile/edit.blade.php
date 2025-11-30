@extends('layouts.principal')

@section('titulo', 'Mi Perfil')

@section('estilos')
    <style>
        .perfil-container { 
            max-width: 800px; 
            margin: 2rem auto; 
            padding: 0 1rem;
        }
        .perfil-titulo {
            font-family: 'Playfair Display', serif;
            font-size: 2rem;
            font-weight: 700;
            margin-bottom: 2rem;
            color: #1f2937;
        }
        .estadisticas-section {
            margin-bottom: 2rem;
        }
        .estadisticas-titulo {
            font-size: 1.125rem;
            font-weight: 600;
            margin-bottom: 0.5rem;
            color: #1f2937;
        }
        .estadisticas-subtitulo {
            font-size: 0.875rem;
            color: #6b7280;
            margin-bottom: 1rem;
        }
        .estadisticas-grid {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(150px, 1fr));
            gap: 1rem;
        }
        .stat-card {
            background: #059669;
            color: white;
            padding: 1.5rem 1rem;
            border-radius: 8px;
            text-align: center;
        }
        .stat-number {
            font-size: 2.5rem;
            font-weight: 700;
            margin-bottom: 0.25rem;
        }
        .stat-label {
            font-size: 0.875rem;
            opacity: 0.95;
        }
        .perfil-section {
            background: white;
            padding: 2rem;
            border-radius: 8px;
            box-shadow: 0 1px 3px rgba(0,0,0,0.1);
            margin-bottom: 2rem;
        }
        .section-titulo {
            font-size: 1.25rem;
            font-weight: 600;
            margin-bottom: 0.5rem;
            color: #1f2937;
        }
        .section-subtitulo {
            font-size: 0.875rem;
            color: #6b7280;
            margin-bottom: 1.5rem;
        }
        .avatar-container {
            display: flex;
            flex-direction: column;
            align-items: center;
            margin-bottom: 1.5rem;
        }
        .avatar-img {
            width: 100px;
            height: 100px;
            border-radius: 50%;
            object-fit: cover;
            border: 4px solid #059669;
            margin-bottom: 1rem;
        }
        .btn-cambiar-foto {
            background: #059669;
            color: white;
            border: none;
            padding: 0.5rem 1.5rem;
            border-radius: 6px;
            font-size: 0.875rem;
            cursor: pointer;
            transition: background 0.2s;
        }
        .btn-cambiar-foto:hover {
            background: #047857;
        }
        .form-row {
            display: grid;
            grid-template-columns: 1fr 1fr;
            gap: 1rem;
            margin-bottom: 1rem;
        }
        .form-group {
            margin-bottom: 1rem;
        }
        .form-group.full-width {
            grid-column: 1 / -1;
        }
        .form-label {
            display: block;
            font-size: 0.875rem;
            font-weight: 600;
            color: #374151;
            margin-bottom: 0.5rem;
        }
        .form-input {
            width: 100%;
            padding: 0.625rem;
            border: 1px solid #d1d5db;
            border-radius: 6px;
            font-size: 0.875rem;
            transition: border-color 0.2s;
        }
        .form-input:focus {
            outline: none;
            border-color: #059669;
            box-shadow: 0 0 0 3px rgba(5, 150, 105, 0.1);
        }
        .btn-guardar {
            background: #059669;
            color: white;
            border: none;
            padding: 0.625rem 1.5rem;
            border-radius: 6px;
            font-size: 0.875rem;
            font-weight: 500;
            cursor: pointer;
            transition: background 0.2s;
        }
        .btn-guardar:hover {
            background: #047857;
        }
        .btn-eliminar {
            background: #dc2626;
            color: white;
            border: none;
            padding: 0.625rem 1.5rem;
            border-radius: 6px;
            font-size: 0.875rem;
            font-weight: 500;
            cursor: pointer;
            transition: background 0.2s;
        }
        .btn-eliminar:hover {
            background: #b91c1c;
        }
        .error-message {
            color: #dc2626;
            font-size: 0.75rem;
            margin-top: 0.25rem;
        }
        @media (max-width: 640px) {
            .form-row {
                grid-template-columns: 1fr;
            }
            .estadisticas-grid {
                grid-template-columns: 1fr;
            }
        }
    </style>
@endsection

@section('scripts')
    <script>
        function previewImage(event) {
            const file = event.target.files[0];
            if (file) {
                const reader = new FileReader();
                reader.onload = function(e) {
                    document.getElementById('preview-avatar').src = e.target.result;
                }
                reader.readAsDataURL(file);
            }
        }

        function removePhoto() {
            if (confirm('¿Estás seguro de eliminar tu foto de perfil?')) {
                document.getElementById('remove_photo').value = '1';
                document.getElementById('profile-form').submit();
            }
        }

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
                        confirmButtonText: 'Sí, eliminar',
                        cancelButtonText: 'Cancelar'
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
    <div class="perfil-container">
        <h1 class="perfil-titulo">Mi Perfil</h1>

        {{-- Estadísticas --}}
        @if(isset($estadisticas) && count($estadisticas) > 0)
        <div class="estadisticas-section">
            <h2 class="estadisticas-titulo">Estadísticas</h2>
            <p class="estadisticas-subtitulo">Tu actividad en la plataforma</p>
            <div class="estadisticas-grid">
                @if(in_array('vendedor', $roles) && isset($estadisticas['productos_publicados']))
                <div class="stat-card">
                    <div class="stat-number">{{ $estadisticas['productos_publicados'] }}</div>
                    <div class="stat-label">Productos Publicados</div>
                </div>
                @endif

                @if(in_array('vendedor', $roles) && isset($estadisticas['productos_vendidos']))
                <div class="stat-card">
                    <div class="stat-number">{{ $estadisticas['productos_vendidos'] }}</div>
                    <div class="stat-label">Productos Vendidos</div>
                </div>
                @endif

                @if(in_array('repartidor', $roles) && isset($estadisticas['pedidos_entregados']))
                <div class="stat-card">
                    <div class="stat-number">{{ $estadisticas['pedidos_entregados'] }}</div>
                    <div class="stat-label">Pedidos Entregados</div>
                </div>
                @endif

                @if(in_array('cliente', $roles) && isset($estadisticas['pedidos_comprador']))
                <div class="stat-card">
                    <div class="stat-number">{{ $estadisticas['pedidos_comprador'] }}</div>
                    <div class="stat-label">Pedidos Realizados</div>
                </div>
                @endif

                @if(in_array('cliente', $roles) && isset($estadisticas['pedidos_entregados_comprador']))
                <div class="stat-card">
                    <div class="stat-number">{{ $estadisticas['pedidos_entregados_comprador'] }}</div>
                    <div class="stat-label">Pedidos Completados</div>
                </div>
                @endif
            </div>
        </div>
        @endif

        {{-- Información de perfil --}}
        <div class="perfil-section">
            <h2 class="section-titulo">Información de perfil</h2>
            <p class="section-subtitulo">Actualiza tu nombre y dirección de correo.</p>

            <form method="post" action="{{ route('profile.update') }}" id="profile-form" enctype="multipart/form-data">
                @csrf
                @method('patch')

                {{-- Avatar --}}
                <div class="avatar-container">
                    <img src="{{ $user->profile_photo_url }}" alt="{{ $user->name }}" id="preview-avatar" class="avatar-img">
                    <input type="file" name="profile_photo" id="profile_photo" accept="image/*" style="display:none;" onchange="previewImage(event)">
                    <label for="profile_photo" class="btn-cambiar-foto">Cambiar foto</label>
                </div>

                <div class="form-row">
                    <div class="form-group">
                        <label class="form-label">Nombre</label>
                        <input type="text" name="name" value="{{ old('name', $user->name) }}" class="form-input" required>
                        @error('name')
                            <div class="error-message">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="form-group">
                        <label class="form-label">Teléfono</label>
                        <input type="text" name="telefono" value="{{ old('telefono', $user->telefono) }}" class="form-input" placeholder="936003594">
                        @error('telefono')
                            <div class="error-message">{{ $message }}</div>
                        @enderror
                    </div>
                </div>

                <div class="form-group full-width">
                    <label class="form-label">Email</label>
                    <input type="email" name="email" value="{{ old('email', $user->email) }}" class="form-input" required>
                    @error('email')
                        <div class="error-message">{{ $message }}</div>
                    @enderror
                </div>

                <input type="hidden" name="remove_photo" id="remove_photo" value="0">

                <button type="submit" class="btn-guardar">Guardar</button>
            </form>
        </div>

        {{-- Cambiar contraseña --}}
        <div class="perfil-section">
            <h2 class="section-titulo">Cambiar contraseña</h2>
            <p class="section-subtitulo">Asegúrate de usar una contraseña segura.</p>

            <form method="post" action="{{ route('password.update') }}">
                @csrf
                @method('put')

                <div class="form-group">
                    <label class="form-label">Contraseña actual</label>
                    <input type="password" name="current_password" class="form-input" autocomplete="current-password">
                    @error('current_password', 'updatePassword')
                        <div class="error-message">{{ $message }}</div>
                    @enderror
                </div>

                <div class="form-group">
                    <label class="form-label">Nueva contraseña</label>
                    <input type="password" name="password" class="form-input" autocomplete="new-password">
                    @error('password', 'updatePassword')
                        <div class="error-message">{{ $message }}</div>
                    @enderror
                </div>

                <div class="form-group">
                    <label class="form-label">Confirmar contraseña</label>
                    <input type="password" name="password_confirmation" class="form-input" autocomplete="new-password">
                    @error('password_confirmation', 'updatePassword')
                        <div class="error-message">{{ $message }}</div>
                    @enderror
                </div>

                <button type="submit" class="btn-guardar">Guardar</button>
            </form>
        </div>

        {{-- Eliminar cuenta --}}
        <div class="perfil-section">
            <h2 class="section-titulo">Eliminar cuenta</h2>
            <p class="section-subtitulo">Eliminar tu cuenta es permanente. Se te pedirá tu contraseña para confirmar.</p>

            <form method="post" action="{{ route('profile.destroy') }}" id="delete-account-form">
                @csrf
                @method('delete')

                <div class="form-group">
                    <label class="form-label">Contraseña (confirmación)</label>
                    <input type="password" name="password" class="form-input" placeholder="Ingresa tu contraseña para confirmar">
                    @error('password', 'userDeletion')
                        <div class="error-message">{{ $message }}</div>
                    @enderror
                </div>

                <button type="submit" class="btn-eliminar">Eliminar cuenta</button>
            </form>
        </div>
    </div>
@endsection
