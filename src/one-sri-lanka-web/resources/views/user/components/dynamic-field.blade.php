@switch($field['type'])
    @case('select')
        <label class="form-label">
            @if(isset($field['icon']))
                <i class="{{ $field['icon'] }} me-1"></i>
            @endif
            {{ $field['label'] }}
            @if($field['required'])
                <span class="text-danger">*</span>
            @endif
        </label>
        <select name="{{ $field['name'] }}" 
                class="form-select @error($field['name']) is-invalid @enderror"
                @if($field['required']) required @endif
                @if(isset($field['depends_on'])) data-depends-on="{{ $field['depends_on'] }}" @endif
                @if($field['readonly'] ?? false) disabled @endif>
            <option value="">{{ $field['placeholder'] ?? 'Select an option' }}</option>
            @if(isset($field['options']))
                @foreach($field['options'] as $value => $label)
                    <option value="{{ $value }}" 
                        {{ (old($field['name']) == $value || ($field['value'] ?? null) == $value) ? 'selected' : '' }}>
                        {{ $label }}
                    </option>
                @endforeach
            @endif
        </select>
        @break

    @case('textarea')
        <label class="form-label">
            @if(isset($field['icon']))
                <i class="{{ $field['icon'] }} me-1"></i>
            @endif
            {{ $field['label'] }}
            @if($field['required'])
                <span class="text-danger">*</span>
            @endif
        </label>
        <textarea name="{{ $field['name'] }}" 
                  rows="{{ $field['rows'] ?? 3 }}"
                  class="form-control @error($field['name']) is-invalid @enderror"
                  placeholder="{{ $field['placeholder'] ?? '' }}"
                  @if($field['required']) required @endif
                  @if(isset($field['maxlength'])) maxlength="{{ $field['maxlength'] }}" @endif
                  @if($field['show_counter'] ?? false) data-counter="{{ $field['name'] }}_counter" @endif>{{ old($field['name']) }}</textarea>
        @if($field['show_counter'] ?? false)
            <div class="form-text">
                <span id="{{ $field['name'] }}_counter">0</span>/{{ $field['maxlength'] }} characters
            </div>
        @endif
        @break

    @case('file')
        <label class="form-label">
            @if(isset($field['icon']))
                <i class="{{ $field['icon'] }} me-1"></i>
            @endif
            {{ $field['label'] }}
            @if($field['required'])
                <span class="text-danger">*</span>
            @endif
        </label>
        <input type="file" 
               name="{{ $field['name'] }}" 
               class="form-control @error($field['name']) is-invalid @enderror"
               @if($field['required']) required @endif
               @if(isset($field['accept'])) accept="{{ $field['accept'] }}" @endif
               @if($field['multiple'] ?? false) multiple @endif>
        @if(isset($field['max_size']))
            <div class="form-text">Maximum file size: {{ $field['max_size'] }}</div>
        @endif
        @break

    @case('datetime-local')
        <label class="form-label">
            @if(isset($field['icon']))
                <i class="{{ $field['icon'] }} me-1"></i>
            @endif
            {{ $field['label'] }}
            @if($field['required'])
                <span class="text-danger">*</span>
            @endif
        </label>
        <input type="datetime-local" 
               name="{{ $field['name'] }}" 
               value="{{ old($field['name']) }}"
               class="form-control @error($field['name']) is-invalid @enderror"
               @if($field['required']) required @endif
               @if(isset($field['min'])) min="{{ $field['min'] }}" @endif
               @if(isset($field['max'])) max="{{ $field['max'] }}" @endif>
        @break

    @default
        <label class="form-label">
            @if(isset($field['icon']))
                <i class="{{ $field['icon'] }} me-1"></i>
            @endif
            {{ $field['label'] }}
            @if($field['required'])
                <span class="text-danger">*</span>
            @endif
        </label>
        <input type="{{ $field['type'] }}" 
               name="{{ $field['name'] }}" 
               value="{{ old($field['name']) ?? ($field['value'] ?? '') }}"
               class="form-control @error($field['name']) is-invalid @enderror"
               placeholder="{{ $field['placeholder'] ?? '' }}"
               @if($field['required']) required @endif
               @if(isset($field['maxlength'])) maxlength="{{ $field['maxlength'] }}" @endif
               @if($field['show_counter'] ?? false) data-counter="{{ $field['name'] }}_counter" @endif
               @if($field['readonly'] ?? false) readonly @endif>
        @if($field['show_counter'] ?? false)
            <div class="form-text">
                <span id="{{ $field['name'] }}_counter">0</span>/{{ $field['maxlength'] }} characters
            </div>
        @endif
@endswitch

@if(isset($field['help_text']))
    <div class="form-text">{{ $field['help_text'] }}</div>
@endif

@error($field['name'])
    <div class="invalid-feedback">{{ $message }}</div>
@enderror