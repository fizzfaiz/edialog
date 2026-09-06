@extends('layouts.app')

@section('content')

<div class="row justify-content-center">
    <div class="col-md-8">

        <div class="card">
            <div class="card-header">
                <span><i class="bi bi-gear me-2"></i>Tetapan Laman</span>
            </div>
            <div class="card-body">

                <form action="{{ route('settings.update') }}" method="POST">
                    @csrf
                    @method('PUT')

                    <div class="mb-4 row">
                        <label for="font_family" class="col-md-4 col-form-label text-md-end text-start">Jenis Font</label>
                        <div class="col-md-6">
                            <select class="form-select @error('font_family') is-invalid @enderror" id="font_family" name="font_family">
                                <option value="Inter" {{ old('font_family', $settings->font_family ?? 'Inter') == 'Inter' ? 'selected' : '' }}>Inter (Moden & Bersih)</option>
                                <option value="Nunito" {{ old('font_family', $settings->font_family ?? '') == 'Nunito' ? 'selected' : '' }}>Nunito (Lembut & Santai)</option>
                                <option value="system-ui" {{ old('font_family', $settings->font_family ?? '') == 'system-ui' ? 'selected' : '' }}>System UI (Lalai Sistem)</option>
                                <option value="Arial" {{ old('font_family', $settings->font_family ?? '') == 'Arial' ? 'selected' : '' }}>Arial (Klasik)</option>
                            </select>
                            @error('font_family')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                    </div>

                    <div class="mb-4 row">
                        <label for="font_size" class="col-md-4 col-form-label text-md-end text-start">Saiz Font</label>
                        <div class="col-md-6">
                            <div class="d-flex gap-2" id="fontSizeOptions">
                                <input type="radio" class="btn-check" id="size_small" name="font_size" value="small" {{ old('font_size', $settings->font_size ?? 'medium') == 'small' ? 'checked' : '' }}>
                                <label class="btn btn-outline-secondary flex-fill" for="size_small">
                                    <i class="bi bi-type"></i> Kecil
                                </label>

                                <input type="radio" class="btn-check" id="size_medium" name="font_size" value="medium" {{ old('font_size', $settings->font_size ?? 'medium') == 'medium' ? 'checked' : '' }}>
                                <label class="btn btn-outline-secondary flex-fill" for="size_medium">
                                    <i class="bi bi-type"></i> Sederhana
                                </label>

                                <input type="radio" class="btn-check" id="size_large" name="font_size" value="large" {{ old('font_size', $settings->font_size ?? '') == 'large' ? 'checked' : '' }}>
                                <label class="btn btn-outline-secondary flex-fill" for="size_large">
                                    <i class="bi bi-type-bold"></i> Besar
                                </label>
                            </div>
                            @error('font_size')
                                <div class="text-danger mt-1 small">{{ $message }}</div>
                            @enderror
                        </div>
                    </div>

                    {{-- Preview --}}
                    <div class="mb-4 row">
                        <div class="col-md-6 offset-md-4">
                            <div class="card bg-light p-3" id="previewBox">
                                <h6 class="mb-1" id="previewHeading">Pratonton Teks</h6>
                                <p class="mb-0 text-muted" id="previewBody">Ayat contoh: Ini adalah pratonton untuk jenis dan saiz font yang dipilih.</p>
                            </div>
                        </div>
                    </div>

                    <div class="text-center">
                        <button type="submit" class="btn btn-primary btn-lg">
                            <i class="bi bi-save"></i> Simpan Tetapan
                        </button>
                    </div>

                </form>

            </div>
        </div>
    </div>
</div>

@endsection

@push('scripts')
<script>
    // Live preview when font settings change
    const fontFamilySelect = document.getElementById('font_family');
    const fontSizeRadios = document.querySelectorAll('input[name="font_size"]');
    const previewHeading = document.getElementById('previewHeading');
    const previewBody = document.getElementById('previewBody');
    const previewBox = document.getElementById('previewBox');

    const fontFamilyMap = {
        'Inter': "'Inter', 'Nunito', system-ui, -apple-system, sans-serif",
        'Nunito': "'Nunito', system-ui, -apple-system, sans-serif",
        'system-ui': "system-ui, -apple-system, 'Segoe UI', Roboto, sans-serif",
        'Arial': "Arial, Helvetica, sans-serif"
    };

    const fontSizeMap = {
        'small': { heading: '0.95rem', body: '0.8rem' },
        'medium': { heading: '1.1rem', body: '0.9rem' },
        'large': { heading: '1.3rem', body: '1.05rem' }
    };

    function updatePreview() {
        const family = fontFamilySelect.value;
        const size = document.querySelector('input[name="font_size"]:checked').value;

        previewHeading.style.fontFamily = fontFamilyMap[family] || fontFamilyMap['Inter'];
        previewBody.style.fontFamily = fontFamilyMap[family] || fontFamilyMap['Inter'];
        previewHeading.style.fontSize = fontSizeMap[size].heading;
        previewBody.style.fontSize = fontSizeMap[size].body;
    }

    fontFamilySelect.addEventListener('change', updatePreview);
    fontSizeRadios.forEach(radio => radio.addEventListener('change', updatePreview));

    // Initial preview
    updatePreview();
</script>
@endpush