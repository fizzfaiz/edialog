<?php

namespace App\Http\Controllers;

use App\Models\User;
use App\Http\Requests\StoreUserRequest;
use App\Http\Requests\UpdateUserRequest;
use Illuminate\View\View;
use Illuminate\Http\RedirectResponse;
use Spatie\Permission\Models\Role;
use App\Models\PejabatPendidikan;
use Illuminate\Support\Facades\Hash;
use App\Http\Controllers\Traits\ScopesUserRegistration;

class UserController extends Controller
{
    use ScopesUserRegistration;

    /**
     * Instantiate a new UserController instance.
     */
    public function __construct()
    {
        $this->middleware('auth');
        $this->middleware('permission:create-user|edit-user|delete-user', ['only' => ['index','show']]);
        $this->middleware('permission:create-user', ['only' => ['create','store']]);
        $this->middleware('permission:edit-user', ['only' => ['edit','update']]);
        $this->middleware('permission:delete-user', ['only' => ['destroy']]);
    }

    /**
     * Display a listing of the resource.
     */
    public function index(): View
    {
        $users = User::orderBy('name')->get();

        $sections = [];
        $handledIds = [];

        // Hierarki: JPN -> PPD (anak)
        $jpnList = PejabatPendidikan::jenis('jpn')
            ->with(['anak' => fn ($q) => $q->orderBy('nama')])
            ->orderBy('nama')
            ->get();

        foreach ($jpnList as $jpn) {
            $handledIds[] = $jpn->id;

            $jpnUsers = $users->where('pejabat_pendidikan_id', $jpn->id);
            if ($jpnUsers->isNotEmpty()) {
                $sections[] = ['title' => $jpn->nama, 'users' => $jpnUsers->values()];
            }

            foreach ($jpn->anak as $ppd) {
                $handledIds[] = $ppd->id;

                $ppdUsers = $users->where('pejabat_pendidikan_id', $ppd->id);
                if ($ppdUsers->isNotEmpty()) {
                    $sections[] = ['title' => $ppd->nama, 'users' => $ppdUsers->values()];
                }
            }
        }

        // Pengguna tanpa pejabat (cth. Super Admin)
        $unassigned = $users->whereNull('pejabat_pendidikan_id');
        if ($unassigned->isNotEmpty()) {
            $sections[] = ['title' => 'Tanpa Pejabat', 'users' => $unassigned->values()];
        }

        // Pejabat lain di luar hierarki JPN->PPD (cth. KPM)
        $others = $users
            ->whereNotNull('pejabat_pendidikan_id')
            ->reject(fn ($u) => in_array($u->pejabat_pendidikan_id, $handledIds));

        if ($others->isNotEmpty()) {
            $sections[] = ['title' => 'Lain-lain', 'users' => $others->values()];
        }

        return view('users.index', compact('sections'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create(): View
    {
        $scope = $this->registrationScope();

        return view('users.create', [
            'roles' => $this->availableRoles($scope),
            'pejabats' => $this->availablePejabats($scope),
        ]);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(StoreUserRequest $request): RedirectResponse
    {
        $scope = $this->registrationScope();
        $this->authorizeRegistrationScope($scope, $request);

        $input = $request->validated();
        $input['password'] = Hash::make($request->password);

        $user = User::create($input);
        $user->assignRole($request->roles);

        return redirect()->route('users.index')
                ->withSuccess('New user is added successfully.');
    }

    /**
     * Display the specified resource.
     */
    public function show(User $user): RedirectResponse
    {
        return redirect()->route('users.index');
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(User $user): View
    {
        // Check Only Super Admin can update his own Profile
        if ($user->hasRole('Super Admin')){
            if($user->id != auth()->user()->id){
                abort(403, 'USER DOES NOT HAVE THE RIGHT PERMISSIONS');
            }
        }

        $scope = $this->registrationScope();
        if (!$this->userInScope($scope, $user)) {
            abort(403, 'Pengguna di luar skop anda.');
        }

        return view('users.edit', [
            'user' => $user,
            'roles' => $this->availableRoles($scope),
            'userRoles' => $user->roles->pluck('name')->all(),
            'pejabats' => $this->availablePejabats($scope),
        ]);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(UpdateUserRequest $request, User $user): RedirectResponse
    {
        $scope = $this->registrationScope();
        if (!$this->userInScope($scope, $user)) {
            abort(403, 'Pengguna di luar skop anda.');
        }
        $this->authorizeRegistrationScope($scope, $request);

        $input = $request->validated();
 
        if(!empty($request->password)){
            $input['password'] = Hash::make($request->password);
        }else{
            $input = $request->except('password');
        }
        
        $user->update($input);

        $user->syncRoles($request->roles);

        return redirect()->back()
                ->withSuccess('User is updated successfully.');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(User $user): RedirectResponse
    {
        // About if user is Super Admin or User ID belongs to Auth User
        if ($user->hasRole('Super Admin') || $user->id == auth()->user()->id)
        {
            abort(403, 'USER DOES NOT HAVE THE RIGHT PERMISSIONS');
        }

        $user->syncRoles([]);
        $user->delete();
        return redirect()->route('users.index')
                ->withSuccess('User is deleted successfully.');
    }
}