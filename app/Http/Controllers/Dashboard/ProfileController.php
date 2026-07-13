<?php

namespace App\Http\Controllers\Dashboard;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Storage;
use Illuminate\Validation\Rule;

class ProfileController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
    }

    public function edit()
    {
        $user = Auth::user()->loadMissing(['employee.departments', 'role']);

        abort_unless($user->employee, 404);

        return view('layouts.profile.index', [
            'page_name' => 'My Profile',
            'user' => $user,
            'employee' => $user->employee,
        ]);
    }

    public function update(Request $request)
    {
        $user = Auth::user()->loadMissing(['employee.departments', 'role']);
        $employee = $user->employee;

        abort_unless($employee, 404);

        $validated = $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'email' => [
                'required',
                'email',
                'max:255',
                Rule::unique('employees', 'email')->ignore($employee->id),
            ],
            'tel' => ['nullable', 'string', 'max:50'],
            'desc' => ['nullable', 'string', 'max:1000'],
            'esign' => ['nullable', 'file', 'mimes:jpg,jpeg,png,pdf', 'max:4096'],
            'avatar' => ['nullable', 'image', 'mimes:jpg,jpeg,png,webp', 'max:4096'],
            'remove_esign' => ['nullable', 'boolean'],
            'remove_avatar' => ['nullable', 'boolean'],
            'password' => ['nullable', 'string', 'min:6', 'confirmed'],
        ]);

        $esignPath = $employee->esign;
        $avatarPath = $employee->avatar;

        if ($request->boolean('remove_esign') && $esignPath) {
            $this->deleteEmployeeFileFromAllLocations('employees/' . $esignPath);
            $esignPath = null;
        }

        if ($request->boolean('remove_avatar') && $avatarPath) {
            $this->deleteEmployeeFileFromAllLocations('employees/avatars/' . $avatarPath);
            $avatarPath = null;
        }

        if ($request->hasFile('esign')) {
            if ($esignPath) {
                $this->deleteEmployeeFileFromAllLocations('employees/' . $esignPath);
            }

            $file = $request->file('esign');
            $esignPath = uniqid('esign_', true) . '.' . $file->getClientOriginalExtension();
            $this->storeUploadedFileToPublicStorage($file, 'employees', $esignPath);
        }

        if ($request->hasFile('avatar')) {
            if ($avatarPath) {
                $this->deleteEmployeeFileFromAllLocations('employees/avatars/' . $avatarPath);
            }

            $avatarFile = $request->file('avatar');
            $avatarPath = uniqid('avatar_', true) . '.' . $avatarFile->getClientOriginalExtension();
            $this->storeUploadedFileToPublicStorage($avatarFile, 'employees/avatars', $avatarPath);
        }

        $employee->update([
            'name' => $validated['name'],
            'email' => $validated['email'],
            'tel' => $validated['tel'] ?? null,
            'desc' => $validated['desc'] ?? null,
            'esign' => $esignPath,
            'avatar' => $avatarPath,
        ]);

        if (!empty($validated['password'])) {
            $user->update([
                'password' => Hash::make($validated['password']),
            ]);
        }

        return back()->with('success', 'Profile updated successfully.');
    }

    private function storeUploadedFileToPublicStorage($file, string $relativeDirectory, string $filename): void
    {
        $relativeDirectory = trim(str_replace('\\', '/', $relativeDirectory), '/');
        $targetDirectory = public_path('storage/' . $relativeDirectory);
        if (!is_dir($targetDirectory)) {
            File::makeDirectory($targetDirectory, 0755, true, true);
        }

        $file->move($targetDirectory, $filename);
    }

    private function deleteEmployeeFileFromAllLocations(?string $relativePath): void
    {
        $relativePath = trim((string) $relativePath);
        if ($relativePath === '') {
            return;
        }

        $relativePath = str_replace('\\', '/', $relativePath);
        Storage::disk('public')->delete($relativePath);

        $publicFilePath = public_path('storage/' . $relativePath);
        if (is_file($publicFilePath)) {
            @unlink($publicFilePath);
        }
    }
}
