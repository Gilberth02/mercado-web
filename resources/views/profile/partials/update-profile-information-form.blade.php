<section>
    <header>
        <h3>Información de perfil</h3>
        <p class="small">Actualiza el nombre y correo asociado a tu cuenta.</p>
    </header>

    <form id="send-verification" method="post" action="{{ route('verification.send') }}">
        @csrf
    </form>

    <form method="post" action="{{ route('profile.update') }}" class="perfil-form" style="margin-top:12px;">
        @csrf
        @method('patch')

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

        <div class="perfil-actions">
            <button class="boton" type="submit">Guardar</button>

            @if (session('status') === 'profile-updated')
                <span class="small" style="color:#2d8659;">Guardado.</span>
            @endif
        </div>
    </form>
</section>

<script>
    // ensure telefono value exists and basic numeric filter could be applied client-side if desired
</script>
