<!-- resources/views/courts/edit.blade.php -->
@extends('layouts.app')

@section('content')
<div class="container-fluid">
    <!-- Page Heading -->
    <div class="d-sm-flex align-items-center justify-content-between mb-4">
        <h1 class="h3 mb-0 text-gray-800">
            <i class="fas fa-edit me-2"></i>Editar Cancha: {{ $court->name }}
        </h1>
        <nav aria-label="breadcrumb">
            <ol class="breadcrumb">
                <li class="breadcrumb-item"><a href="{{ route('dashboard') }}">Dashboard</a></li>
                <li class="breadcrumb-item"><a href="{{ route('courts.index') }}">Canchas</a></li>
                <li class="breadcrumb-item"><a href="{{ route('courts.show', $court) }}">{{ $court->name }}</a></li>
                <li class="breadcrumb-item active">Editar</li>
            </ol>
        </nav>
    </div>

    <div class="row">
        <div class="col-lg-8">
            <div class="card shadow mb-4">
                <div class="card-header py-3">
                    <h6 class="m-0 font-weight-bold text-primary">
                        <i class="fas fa-info-circle me-2"></i>Información de la Cancha
                    </h6>
                </div>
                <div class="card-body">
                    <form method="POST" action="{{ route('courts.update', $court) }}" enctype="multipart/form-data">
                        @csrf
                        @method('PUT')

                        <!-- Basic Information -->
                        <div class="row">
                            <div class="col-md-8">
                                <div class="form-group">
                                    <label for="name" class="form-label font-weight-bold">
                                        <i class="fas fa-tennis-ball me-1"></i>Nombre de la Cancha *
                                    </label>
                                    <input type="text"
                                           class="form-control form-control-lg @error('name') is-invalid @enderror"
                                           id="name" name="name" value="{{ old('name', $court->name) }}"
                                           required placeholder="Ej: Cancha Central, Pista Roland Garros..."
                                           maxlength="255">
                                    @error('name')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>

                            <div class="col-md-4">
                                <div class="form-group">
                                    <label for="surface_type" class="form-label font-weight-bold">
                                        <i class="fas fa-layer-group me-1"></i>Tipo de Superficie *
                                    </label>
                                    <select class="form-control form-control-lg @error('surface_type') is-invalid @enderror"
                                            id="surface_type" name="surface_type" required>
                                        <option value="">Seleccionar tipo...</option>
                                        <option value="clay" {{ old('surface_type', $court->surface_type) == 'clay' ? 'selected' : '' }}>
                                            🟤 Arcilla (Clay)
                                        </option>
                                        <option value="hard" {{ old('surface_type', $court->surface_type) == 'hard' ? 'selected' : '' }}>
                                            🔵 Dura (Hard Court)
                                        </option>
                                        <option value="grass" {{ old('surface_type', $court->surface_type) == 'grass' ? 'selected' : '' }}>
                                            🟢 Césped (Grass)
                                        </option>
                                        <option value="synthetic" {{ old('surface_type', $court->surface_type) == 'synthetic' ? 'selected' : '' }}>
                                            🟡 Sintética (Synthetic)
                                        </option>
                                    </select>
                                    @error('surface_type')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>
                        </div>

                        <!-- Description -->
                        <div class="form-group">
                            <label for="description" class="form-label font-weight-bold">
                                <i class="fas fa-align-left me-1"></i>Descripción
                            </label>
                            <textarea class="form-control @error('description') is-invalid @enderror"
                                      id="description" name="description" rows="4"
                                      placeholder="Descripción detallada de la cancha...">{{ old('description', $court->description) }}</textarea>
                            @error('description')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <!-- Pricing and Status -->
                        <div class="row">
                            <div class="col-md-4">
                                <div class="form-group">
                                    <label for="price_per_hour" class="form-label font-weight-bold">
                                        <i class="fas fa-dollar-sign me-1"></i>Precio por Hora *
                                    </label>
                                    <input type="text"
                                           class="form-control form-control-lg @error('price_per_hour') is-invalid @enderror"
                                           id="price_per_hour" name="price_per_hour" value="{{ old('price_per_hour', $court->price_per_hour) }}"
                                           required placeholder="Ej: 1000" maxlength="10">
                                    @error('price_per_hour')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>

        const methodInput = document.createElement('input');
        methodInput.type = 'hidden';
        methodInput.name = '_method';
        methodInput.value = 'DELETE';

        form.appendChild(csrfInput);
        form.appendChild(methodInput);
        document.body.appendChild(form);
        form.submit();
    }
}
</script>
@endsection
