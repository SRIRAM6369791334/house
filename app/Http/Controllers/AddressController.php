<?php

namespace App\Http\Controllers;

use App\Models\UserAddress;
use Illuminate\Http\Request;

class AddressController extends Controller
{
    public function store(Request $request)
    {
        $data = $request->validate([
            'address_first_name' => ['required', 'string', 'max:100'],
            'address_last_name' => ['nullable', 'string', 'max:100'],
            'address_phone_number' => ['required', 'digits:10'],
            'address_line_one' => ['required', 'string', 'max:255'],
            'address_line_two' => ['nullable', 'string', 'max:255'],
            'landmark' => ['nullable', 'string', 'max:120'],
            'city' => ['required', 'string', 'max:120'],
            'state' => ['required', 'string', 'max:120'],
            'pincode' => ['required', 'digits:6'],
            'address_type_name' => ['required', 'string', 'max:50'],
            'is_default' => ['nullable', 'boolean'],
        ], [], [
            'address_first_name' => 'name',
            'address_phone_number' => 'phone number',
            'address_line_one' => 'address',
            'pincode' => 'pincode',
            'address_type_name' => 'address type',
        ]);
        UserAddress::resolveConnection()
            ->transaction(function () use ($data, $request) {
                $shouldDefault = $request->boolean('is_default') || ! UserAddress::query()
                    ->where('user_id', auth()->id())
                    ->exists();
                if ($shouldDefault) {
                    UserAddress::query()
                        ->where('user_id', auth()->id())
                        ->update(['is_default' => 0]);
                }
                $insert = array_merge($data, [
                    'user_id' => auth()->id(),
                    'address_username' => trim($data['address_first_name'].' '.($data['address_last_name'] ?? '')),
                    'phone_code' => '+91',
                    'created_at' => now(),
                    'updated_at' => now(),
                ]);
                $insert['is_default'] = $shouldDefault ? 1 : 0;
                UserAddress::query()
                    ->create($insert);
            });

        return redirect('account#address')->with('success', 'Address added successfully.');
    }

    public function update(Request $request, $id)
    {
        $address = UserAddress::query()
            ->where('id', $id)
            ->where('user_id', auth()->id())
            ->first();
        abort_unless($address, 404);
        $data = $request->validate([
            'address_first_name' => ['required', 'string', 'max:100'],
            'address_last_name' => ['nullable', 'string', 'max:100'],
            'address_phone_number' => ['required', 'digits:10'],
            'address_line_one' => ['required', 'string', 'max:255'],
            'address_line_two' => ['nullable', 'string', 'max:255'],
            'landmark' => ['nullable', 'string', 'max:120'],
            'city' => ['required', 'string', 'max:120'],
            'state' => ['required', 'string', 'max:120'],
            'pincode' => ['required', 'digits:6'],
            'address_type_name' => ['required', 'string', 'max:50'],
            'is_default' => ['nullable', 'boolean'],
        ], [], [
            'address_first_name' => 'name',
            'address_phone_number' => 'phone number',
            'address_line_one' => 'address',
            'pincode' => 'pincode',
            'address_type_name' => 'address type',
        ]);
        UserAddress::resolveConnection()
            ->transaction(function () use ($data, $request, $id) {
                if ($request->boolean('is_default')) {
                    UserAddress::query()
                        ->where('user_id', auth()->id())
                        ->update(['is_default' => 0]);
                    $data['is_default'] = 1;
                } else {
                    $data['is_default'] = 0;
                }
                UserAddress::query()
                    ->where('id', $id)
                    ->where('user_id', auth()->id())
                    ->update(array_merge($data, [
                        'address_username' => trim($data['address_first_name'].' '.($data['address_last_name'] ?? '')),
                        'phone_code' => '+91',
                        'updated_at' => now(),
                    ]));
            });

        return redirect('account#address')->with('success', 'Address updated successfully.');
    }

    public function setDefault($id)
    {
        $address = UserAddress::query()
            ->where('id', $id)
            ->where('user_id', auth()->id())
            ->first();
        abort_unless($address, 404);
        UserAddress::resolveConnection()
            ->transaction(function () use ($id) {
                UserAddress::query()
                    ->where('user_id', auth()->id())
                    ->update(['is_default' => 0]);
                UserAddress::query()
                    ->where('id', $id)
                    ->where('user_id', auth()->id())
                    ->update(['is_default' => 1, 'updated_at' => now()]);
            });

        return redirect('account#address')->with('success', 'Default address updated.');
    }

    public function destroy($id)
    {
        $address = UserAddress::query()
            ->where('id', $id)
            ->where('user_id', auth()->id())
            ->first();
        abort_unless($address, 404);
        UserAddress::resolveConnection()
            ->transaction(function () use ($address, $id) {
                UserAddress::query()
                    ->where('id', $id)
                    ->where('user_id', auth()->id())
                    ->delete();
                if ((int) ($address->is_default ?? 0) === 1) {
                    $nextAddress = UserAddress::query()
                        ->where('user_id', auth()->id())
                        ->whereRaw('LOWER(address_type_name) = ?', ['shipping'])
                        ->orderByDesc('id')
                        ->first();
                    if ($nextAddress) {
                        UserAddress::query()
                            ->where('id', $nextAddress->id)
                            ->where('user_id', auth()->id())
                            ->update(['is_default' => 1, 'updated_at' => now()]);
                    }
                }
            });

        return redirect('account#address')->with('success', 'Address deleted successfully.');
    }
}
