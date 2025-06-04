<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rule;

class UserController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        $query = User::query();

        // Search functionality
        if ($request->filled('search')) {
            $searchTerm = $request->search;
            $query->where(function($q) use ($searchTerm) {
                $q->where('name', 'like', '%' . $searchTerm . '%')
                    ->orWhere('email', 'like', '%' . $searchTerm . '%');
            });
        }

        // Filter by role
        if ($request->filled('role')) {
            $query->where('role', $request->role);
        }

        // Filter by status
        if ($request->filled('status')) {
            if ($request->status === 'active') {
                $query->where('is_active', true);
            } elseif ($request->status === 'inactive') {
                $query->where('is_active', false);
            }
        }

        // Sort
        $sortBy = $request->get('sort', 'latest');
        switch ($sortBy) {
            case 'name':
                $query->orderBy('name', 'asc');
                break;
            case 'email':
                $query->orderBy('email', 'asc');
                break;
            case 'oldest':
                $query->oldest();
                break;
            default: // latest
                $query->latest();
                break;
        }

        $users = $query->withCount('orders')->paginate(15)->withQueryString();

        return view('admin.users.index', compact('users'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        return view('admin.users.create');
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|string|email|max:255|unique:users',
            'password' => 'required|string|min:8|confirmed',
            'role' => 'required|in:' . implode(',', array_keys(User::getRoles())),
            'is_active' => 'nullable|boolean'
        ], [
            'name.required' => 'Tên là bắt buộc.',
            'email.required' => 'Email là bắt buộc.',
            'email.email' => 'Email không hợp lệ.',
            'email.unique' => 'Email đã tồn tại.',
            'password.required' => 'Mật khẩu là bắt buộc.',
            'password.min' => 'Mật khẩu phải có ít nhất 8 ký tự.',
            'password.confirmed' => 'Xác nhận mật khẩu không khớp.',
            'role.required' => 'Vai trò là bắt buộc.',
            'role.in' => 'Vai trò không hợp lệ.',
        ]);

        User::create([
            'name' => $request->name,
            'email' => $request->email,
            'password' => Hash::make($request->password),
            'role' => $request->role,
            'is_active' => $request->has('is_active'),
            'email_verified_at' => now(),
        ]);

        return redirect()->route('admin.users.index')
            ->with('success', 'Người dùng đã được tạo thành công!');
    }

    /**
     * Display the specified resource.
     */
    public function show(User $user)
    {
        $user->load(['orders' => function($query) {
            $query->latest()->take(10);
        }]);

        return view('admin.users.show', compact('user'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(User $user)
    {
        return view('admin.users.edit', compact('user'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, User $user)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'email' => ['required', 'string', 'email', 'max:255', Rule::unique('users')->ignore($user->id)],
            'password' => 'nullable|string|min:8|confirmed',
            'role' => 'required|in:' . implode(',', array_keys(User::getRoles())),
            'is_active' => 'nullable|boolean'
        ], [
            'name.required' => 'Tên là bắt buộc.',
            'email.required' => 'Email là bắt buộc.',
            'email.email' => 'Email không hợp lệ.',
            'email.unique' => 'Email đã tồn tại.',
            'password.min' => 'Mật khẩu phải có ít nhất 8 ký tự.',
            'password.confirmed' => 'Xác nhận mật khẩu không khớp.',
            'role.required' => 'Vai trò là bắt buộc.',
            'role.in' => 'Vai trò không hợp lệ.',
        ]);

        $updateData = [
            'name' => $request->name,
            'email' => $request->email,
            'role' => $request->role,
            'is_active' => $request->has('is_active'),
        ];

        // Only update password if provided
        if ($request->filled('password')) {
            $updateData['password'] = Hash::make($request->password);
        }

        $user->update($updateData);

        return redirect()->route('admin.users.index')
            ->with('success', 'Thông tin người dùng đã được cập nhật!');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(User $user)
    {
        // Prevent deleting current user
        if ($user->id === auth()->id()) {
            return redirect()->route('admin.users.index')
                ->with('error', 'Bạn không thể xóa tài khoản của chính mình!');
        }

        // Prevent deleting admin if it's the last admin
        if ($user->isAdmin()) {
            $adminCount = User::admins()->count();
            if ($adminCount <= 1) {
                return redirect()->route('admin.users.index')
                    ->with('error', 'Không thể xóa admin cuối cùng!');
            }
        }

        $userName = $user->name;
        $user->delete();

        return redirect()->route('admin.users.index')
            ->with('success', "Người dùng '{$userName}' đã được xóa!");
    }

    /**
     * Toggle user active status
     */
    public function toggleActive(User $user)
    {
        // Prevent deactivating current user
        if ($user->id === auth()->id()) {
            return response()->json([
                'success' => false,
                'message' => 'Bạn không thể vô hiệu hóa tài khoản của chính mình!'
            ]);
        }

        $user->update(['is_active' => !$user->is_active]);
        $status = $user->is_active ? 'kích hoạt' : 'vô hiệu hóa';

        return response()->json([
            'success' => true,
            'message' => "Đã {$status} tài khoản '{$user->name}'!",
            'is_active' => $user->is_active
        ]);
    }

    /**
     * Change user role
     */
    public function changeRole(Request $request, User $user)
    {
        $request->validate([
            'role' => 'required|in:' . implode(',', array_keys(User::getRoles()))
        ]);

        // Prevent changing own role
        if ($user->id === auth()->id()) {
            return response()->json([
                'success' => false,
                'message' => 'Bạn không thể thay đổi vai trò của chính mình!'
            ]);
        }

        // Prevent removing last admin
        if ($user->isAdmin() && $request->role !== User::ROLE_ADMIN) {
            $adminCount = User::admins()->count();
            if ($adminCount <= 1) {
                return response()->json([
                    'success' => false,
                    'message' => 'Không thể thay đổi vai trò của admin cuối cùng!'
                ]);
            }
        }

        $oldRole = $user->role_name;
        $user->update(['role' => $request->role]);
        $newRole = $user->role_name;

        return response()->json([
            'success' => true,
            'message' => "Đã thay đổi vai trò của '{$user->name}' từ {$oldRole} sang {$newRole}!",
            'role' => $user->role,
            'role_name' => $user->role_name
        ]);
    }
}
