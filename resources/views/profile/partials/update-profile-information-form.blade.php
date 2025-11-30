<section>
    <header>
        <h3>Información de perfil</h3>
        <p class="small">Actualiza tu foto de perfil, nombre y correo asociado a tu cuenta.</p>
    </header>

    <form id="send-verification" method="post" action="{{ route('verification.send') }}">
        @csrf
    </form>

    <form method="post" action="{{ route('profile.update') }}" class="perfil-form" style="margin-top:12px;" enctype="multipart/form-data">
        @csrf
        @method('patch')

        <!-- Foto de perfil -->
        <div style="margin-bottom:20px;">
            <label class="label">Foto de perfil</label>
            <div style="display:flex;align-items:center;gap:16px;margin-top:8px;">
                <img src="{{ $user->profile_photo_url }}" alt="{{ $user->name }}" id="preview-avatar" style="width:80px;height:80px;border-radius:50%;object-fit:cover;border:3px solid #1ca69a;" onerror="this.onerror=null; this.src='{{ asset('Vista/img/avatar.png') }}'">
                <div>
                    <input type="file" name="profile_photo" id="profile_photo" accept="image/*" style="display:none;" onchange="previewImage(event)">
                    <label for="profile_photo" class="boton_borde" style="cursor:pointer;display:inline-block;padding:8px 16px;">Cambiar foto</label>
                    @if($user->profile_photo_path && !filter_var($user->profile_photo_path, FILTER_VALIDATE_URL))
                        <button type="button" onclick="removePhoto()" class="boton_borde" style="background:#dc3545;color:white;border-color:#dc3545;padding:8px 16px;">Eliminar</button>
                    @endif
                    <p class="small" style="margin-top:4px;color:#666;">JPG, PNG o GIF. Máximo 2MB.</p>
                </div>
            </div>
            @if($errors->has('profile_photo'))
                <div class="small" style="color:#c0392b;margin-top:4px;">{{ $errors->first('profile_photo') }}</div>
            @endif
        </div>

        <div style="margin-bottom:10px;">
            <label class="label">Nombre</label>
            <input type="text" name="name" value="{{ old('name', $user->name) }}" required autofocus />
            @if($errors->has('name'))
                <div class="small" style="color:#c0392b">{{ $errors->first('name') }}</div>
            @endif
        </div>

        <div style="margin-bottom:10px;">
            <label class="label">Teléfono</label>
            <input type="text" name="telefono" value="{{ old('telefono', $user->telefono) }}" placeholder="Ej: +51 9xxxxxxxx" />
            @if($errors->has('telefono'))
                <div class="small" style="color:#c0392b">{{ $errors->first('telefono') }}</div>
            @endif
        </div>

        <div style="margin-bottom:10px;">
            <label class="label">Email</label>
            <input type="email" name="email" value="{{ old('email', $user->email) }}" required />
            @if($errors->has('email'))
                <div class="small" style="color:#c0392b">{{ $errors->first('email') }}</div>
            @endif

            @if ($user instanceof \Illuminate\Contracts\Auth\MustVerifyEmail && ! $user->hasVerifiedEmail())
                <div style="margin-top:8px;">
                    <p class="small">Tu correo no está verificado.
                        <button form="send-verification" class="boton_borde" style="margin-left:8px;">Reenviar correo</button>
                    </p>

                    @if (session('status') === 'verification-link-sent')
                        <p class="small" style="color:green;margin-top:6px;">Se envió un nuevo enlace de verificación.</p>
                    @endif
                </div>
            @endif
        </div>

        <input type="hidden" name="remove_photo" id="remove_photo" value="0">

        <div class="perfil-actions">
            <button class="boton" type="submit">Guardar</button>

            @if (session('status') === 'profile-updated')
                <span class="small" style="color:#2d8659;">✓ Perfil actualizado correctamente.</span>
            @endif
        </div>
    </form>
</section>

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
        // Submit form
        document.querySelector('.perfil-form').submit();
    }
}
</script>
