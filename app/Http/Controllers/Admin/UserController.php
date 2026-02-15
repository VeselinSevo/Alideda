<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\Request;
use App\Queries\UsersQuery;

class UserController extends Controller
{

    public function index(Request $request)
    {
        $base = User::query();

        $users = UsersQuery::for($base)
            ->apply($request)
            ->paginate(15)
            ->withQueryString();

        return view('admin.users.index', compact('users'));
    }

    public function ban(User $user)
    {
        // don't ban admins
        if ($user->is_admin)
            abort(403);

        $user->update(['banned_at' => now()]);
        return back()->with('success', 'User banned.');
    }

    public function unban(User $user)
    {
        $user->update(['banned_at' => null]);
        return back()->with('success', 'User unbanned.');
    }

    public function destroy(User $user)
    {
        if ($user->is_admin)
            abort(403);

        $user->delete();
        return back()->with('success', 'User deleted.');
    }
}
