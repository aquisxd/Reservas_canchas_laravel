<!-- resources/views/courts/create.blade.php -->
@extends('layouts.app')

@section('content')
    <div class="container-fluid">
        <!-- Page Heading -->
        <div class="d-sm-flex align-items-center justify-content-between mb-4">
            <h1 class="h3 mb-0 text-gray-800">
                <i class="fas fa-plus-circle me-2"></i>Crear Nueva Cancha
            </h1>
            <nav aria-label="breadcrumb">
                <ol class="breadcrumb">
                    <li class="breadcrumb-item"><a href="{{ route('dashboard') }}">Dashboard</a></li>
                    <li class="breadcrumb-item"><a href="{{ route('courts.index') }}">Canchas</a></li>
                    <li class="breadcrumb-item active">Crear</li>
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
                        <form method="POST" action="{{ route('courts.store') }}" enctype="multipart/form-data">
                            @csrf

                            <!-- Basic Information -->
                            <div class="row">
                                <div class="col-md-8">
                                    <div class="form-group">
                                        <label for="name" class="form-label font-weight-bold">
                                            <i class="fas fa-tennis-ball me-1"></i>Nombre de la Cancha *
                                        </label>
                                        <input type="text"
                                            class="form-control form-control-lg @error('name') is-invalid @enderror"
                                            id="name" name="name" value="{{ old('name') }}" required
                                            placeholder="Ej: Cancha Central, Pista Roland Garros..." maxlength="255">
                                        @error('name')
                                            <div class="invalid-feedback">{{ $message }}</div>
                                        @enderror
                                        <small class="form-text text-muted">Nombre distintivo y atractivo para la
                                            cancha</small>
                                    </div>
                                </div>

                                <div class="col-md-4">
                                    <div class="form-group">
                                        <label for="surface_type" class="form-label font-weight-bold">
                                            <i class="fas fa-layer-group me-1"></i>Tipo de Superficie *
                                        </label>
                                        <select
                                            class="form-control form-control-lg @error('surface_type') is-invalid @enderror"
                                            id="surface_type" name="surface_type" required>
                                            <option value="">Seleccionar tipo...</option>
                                            <option value="clay" {{ old('surface_type') == 'clay' ? 'selected' : '' }}>
                                                🟤 Arcilla (Clay)
                                            </option>
                                            <option value="hard" {{ old('surface_type') == 'hard' ? 'selected' : '' }}>
                                                🔵 Dura (Hard Court)
                                            </option>
                                            <option value="grass" {{ old('surface_type') == 'grass' ? 'selected' : '' }}>
                                                🟢 Césped (Grass)
                                            </option>
                                            <option value="synthetic" {{ old('surface_type') == 'synthetic' ? 'selected' : '' }}>
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
                                <textarea class="form-control @error('description') is-invalid @enderror" id="description"
                                    name="description" rows="4"
                                    placeholder="Descripción detallada de la cancha: características especiales, ubicación, servicios adicionales...">{{ old('description') }}</textarea>
                                @error('description')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                                <small class="form-text text-muted">Describe las características únicas de esta
                                    cancha</small>
                            </div>

                            <!-- Pricing and Status -->
                            <div class="row">
                                <div class="col-md-4">
                                    <div class="form-group">
                                        <label for="price_per_hour" class="form-label font-weight-bold">
                                            <i class="fas fa-dollar-sign me-1"></i>Precio por Hora *
                                        </label>
                                        <div class="input-group input-group-lg">
                                            <span class="input-group-text">$</span>
                                            <input type="number"
                                                class="form-control @error('price_per_hour') is-invalid @enderror"
                                                id="price_per_hour" name="price_per_hour"
                                                value="{{ old('price_per_hour') }}" min="0" step="1" required
                                                placeholder="0" onchange="updatePricePreview()">
                                            <span class="input-group-text">.00</span>
                                        </div>
                                        @error('price_per_hour')
                                            <div class="invalid-feedback">{{ $message }}</div>
                                        @enderror
                                        <small class="form-text text-muted">Precio en pesos por hora de uso</small>
                                    </div>
                                </div>

                                <div class="col-md-4">
                                    <div class="form-group">
                                        <label class="form-label font-weight-bold">
                                            <i class="fas fa-toggle-on me-1"></i>Estado de la Cancha
                                        </label>
                                        <div class="custom-control custom-switch custom-control-lg">
                                            <input type="checkbox" class="custom-control-input" id="is_active"
                                                name="is_active" value="1" {{ old('is_active', true) ? 'checked' : '' }}>
                                            <label class="custom-control-label" for="is_active">
                                                <span class="switch-text">Cancha Activa</span>
                                            </label>
                                        </div>
                                        <small class="form-text text-muted">Las canchas inactivas no aparecerán para
                                            reserva</small>
                                    </div>
                                </div>

                                <div class="col-md-4">
                                    <div class="form-group">
                                        <label class="form-label font-weight-bold">Vista Previa de Precio</label>
                                        <div class="card bg-light">
                                            <div class="card-body text-center py-3">
                                                <h4 class="text-primary mb-0" id="price-preview">$0 / hora</h4>
                                                <small class="text-muted">Precio que verán los clientes</small>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <!-- Image Upload -->
                            <div class="form-group">
                                <label for="image" class="form-label font-weight-bold">
                                    <i class="fas fa-camera me-1"></i>Imagen de la Cancha
                                </label>
                                <div class="custom-file">
                                    <input type="file" class="custom-file-input @error('image') is-invalid @enderror"
                                        id="image" name="image" accept="image/*" onchange="previewImage(this)">
                                    <label class="custom-file-label" for="image">Seleccionar imagen...</label>
                                </div>
                                @error('image')
                                    <div class="invalid-feedback d-block">{{ $message }}</div>
                                @enderror
                                <small class="form-text text-muted">
                                    <i class="fas fa-info-circle me-1"></i>
                                    Formatos permitidos: JPG, PNG, GIF. Tamaño máximo: 2MB. Resolución recomendada:
                                    800x600px
                                </small>

                                <!-- Image Preview -->
                                <div id="image-preview" class="mt-3" style="display: none;">
                                    <div class="card" style="max-width: 300px;">
                                        <img id="preview-img" src="" class="card-img-top" alt="Vista previa">
                                        <div class="card-body text-center">
                                            <small class="text-muted">Vista previa de la imagen</small>
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <!-- Action Buttons -->
                            <hr class="my-4">
                            <div class="d-flex justify-content-between">
                                <a href="{{ route('courts.index') }}" class="btn btn-secondary btn-lg">
                                    <i class="fas fa-arrow-left me-2"></i>Cancelar
                                </a>
                                <button type="submit" class="btn btn-primary btn-lg">
                                    <i class="fas fa-save me-2"></i>Crear Cancha
                                </button>
                            </div>
                        </form>
                    </div>
                </div>
            </div>

            <!-- Help Sidebar -->
            <div class="col-lg-4">
                <!-- Quick Tips -->
                <div class="card shadow mb-4">
                    <div class="card-header py-3">
                        <h6 class="m-0 font-weight-bold text-primary">
                            <i class="fas fa-lightbulb me-2"></i>Consejos para Crear Canchas
                        </h6>
                    </div>
                    <div class="card-body">
                        <div class="mb-3">
                            <h6 class="font-weight-bold text-success">
                                <i class="fas fa-check-circle me-1"></i>Nombre Atractivo
                            </h6>
                            <small class="text-muted">Usa nombres descriptivos como "Cancha Central", "Pista Philippe
                                Chatrier", etc.</small>
                        </div>

                        <div class="mb-3">
                            <h6 class="font-weight-bold text-info">
                                <i class="fas fa-dollar-sign me-1"></i>Precio Competitivo
                            </h6>
                            <small class="text-muted">Investiga precios del mercado local. Considera la calidad de la
                                superficie.</small>
                        </div>

                        <div class="mb-3">
                            <h6 class="font-weight-bold text-warning">
                                <i class="fas fa-image me-1"></i>Imagen de Calidad
                            </h6>
                            <small class="text-muted">Una buena foto aumenta las reservas. Toma la imagen en horario
                                diurno.</small>
                        </div>
                    </div>
                </div>

                <!-- Surface Types Info -->
                <div class="card shadow mb-4">
                    <div class="card-header py-3">
                        <h6 class="m-0 font-weight-bold text-primary">
                            <i class="fas fa-info-circle me-2"></i>Tipos de Superficie
                        </h6>
                    </div>
                    <div class="card-body">
                        <div class="mb-3">
                            <div class="d-flex align-items-center mb-2">
                                <span class="mr-2">🟤</span>
                                <strong>Arcilla (Clay)</strong>
                            </div>
                            <small class="text-muted">Superficie más lenta, ideal para jugadores que prefieren rallies
                                largos.</small>
                        </div>

                        <div class="mb-3">
                            <div class="d-flex align-items-center mb-2">
                                <span class="mr-2">🔵</span>
                                <strong>Dura (Hard Court)</strong>
                            </div>
                            <small class="text-muted">Superficie rápida y versátil, la más común en torneos
                                profesionales.</small>
                        </div>

                        <div class="mb-3">
                            <div class="d-flex align-items-center mb-2">
                                <span class="mr-2">🟢</span>
                                <strong>Césped (Grass)</strong>
                            </div>
                            <small class="text-muted">Superficie muy rápida, característica de Wimbledon.</small>
                        </div>

                        <div>
                            <div class="d-flex align-items-center mb-2">
                                <span class="mr-2">🟡</span>
                                <strong>Sintética</strong>
                            </div>
                            <small class="text-muted">Superficie económica y duradera, fácil mantenimiento.</small>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <style>
        .custom-control-lg .custom-control-label::before {
            top: 0.25rem;
            left: -2.25rem;
            width: 1.75rem;
            height: 1.75rem;
        }

        .custom-control-lg .custom-control-label::after {
            top: 0.25rem;
            left: -2.25rem;
            width: 1.75rem;
            height: 1.75rem;
        }

        .switch-text {
            font-weight: 600;
            margin-left: 0.5rem;
        }
    </style>

    <script>
        function updatePricePreview() {
            const priceInput = document.getElementById('price_per_hour');
            const preview = document.getElementById('price-preview');
            const value = priceInput.value || 0;
            preview.textContent = `${value} / hora`;
        }

        function previewImage(input) {
            const preview = document.getElementById('image-preview');
            const previewImg = document.getElementById('preview-img');
            const label = document.querySelector('.custom-file-label');

            if (input.files && input.files[0]) {
                const reader = new FileReader();

                reader.onload = function (e) {
                    previewImg.src = e.target.result;
                    preview.style.display = 'block';
                }

                reader.readAsDataURL(input.files[0]);
                label.textContent = input.files[0].name;
            } else {
                preview.style.display = 'none';
                label.textContent = 'Seleccionar imagen...';
            }
        }

        // Initialize price preview
        document.addEventListener('DOMContentLoaded', function () {
            updatePricePreview();
        });
    </script>
@endsection