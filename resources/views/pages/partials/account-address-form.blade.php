@php
    $isEdit = ($formMode ?? 'add') === 'edit';
    $addressId = $isEdit && $address ? $address->id : null;
    $formClass = $isEdit ? 'js-address-edit-form d-none' : 'js-address-add-form d-none';
    $isDefault = $isEdit && $address && property_exists($address, 'is_default') && (int) $address->is_default === 1;
    $types = ['Home', 'Office', 'Billing', 'Shipping', 'Other'];
    $fieldValue = function ($field, $default = '') use ($address, $isEdit) {
        return old($field, $isEdit && $address ? ($address->{$field} ?? $default) : $default);
    };
@endphp

<form
    action="{{ $isEdit ? route('account.address.update', $addressId) : route('account.address.store') }}"
    method="POST"
    class="{{ $formClass }}"
    @if($isEdit) data-address-form-id="{{ $addressId }}" @endif
    style="margin-top: 28px;"
>
    @csrf
    <div class="account-section-head">
        <h2>{{ $isEdit ? 'Edit Address' : 'Add New Address' }}</h2>
    </div>

    <div class="account-field-grid">
        <div class="account-field">
            <label>Name</label>
            <input type="text" name="address_first_name" value="{{ $fieldValue('address_first_name', $user->name ?? '') }}" required>
        </div>
        <div class="account-field">
            <label>Phone</label>
            <input type="text" name="address_phone_number" value="{{ $fieldValue('address_phone_number', $user->phone ?? '') }}" maxlength="10" required>
        </div>
        <div class="account-field full">
            <label>Address line 1</label>
            <textarea name="address_line_one" required>{{ $fieldValue('address_line_one') }}</textarea>
        </div>
        <div class="account-field full">
            <label>Address line 2</label>
            <textarea name="address_line_two">{{ $fieldValue('address_line_two') }}</textarea>
        </div>
        <div class="account-field">
            <label>Landmark</label>
            <input type="text" name="landmark" value="{{ $fieldValue('landmark') }}">
        </div>
        <div class="account-field">
            <label>City</label>
            <input type="text" name="city" value="{{ $fieldValue('city') }}" required>
        </div>
        <div class="account-field">
            <label>State</label>
            <input type="text" name="state" value="{{ $fieldValue('state') }}" required>
        </div>
        <div class="account-field">
            <label>Pincode</label>
            <input type="text" name="pincode" value="{{ $fieldValue('pincode') }}" maxlength="6" required>
        </div>
        <div class="account-field">
            <label>Address type</label>
            <select name="address_type_name" required>
                @foreach($types as $type)
                    <option value="{{ $type }}" {{ $fieldValue('address_type_name', 'Home') === $type ? 'selected' : '' }}>{{ $type }}</option>
                @endforeach
            </select>
        </div>
    </div>

    <label class="account-check">
        <input type="checkbox" name="is_default" value="1" {{ old('is_default', $isDefault) ? 'checked' : '' }}>
        Set as default address
    </label>

    <div class="account-actions" style="justify-content:flex-end;">
        <button type="button" class="account-muted-btn {{ $isEdit ? 'js-cancel-address-edit' : 'js-cancel-address-add' }}">Cancel</button>
        <button type="submit" class="account-save-btn">{{ $isEdit ? 'Update Address' : 'Save Address' }}</button>
    </div>
</form>
