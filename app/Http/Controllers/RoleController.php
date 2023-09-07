<?php

namespace App\Http\Controllers;

use App\Http\Libraries\Response;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Spatie\Permission\Models\Permission;
use Spatie\Permission\Models\Role;

class RoleController extends Controller
{

    function __construct()
    {
        $this->middleware('permission:regras|cadastro-regras|ver-regras|editar-regras|deletar-regras',
                            ['only' => ['index','update','store', 'edit', 'create', 'show']]
                         );
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        $roles = Role::orderBy('id','ASC')->paginate(50);
        return view('adm.roles.index',compact('roles'))->with('i', ($request->input('page', 1) - 1) * 50);
    }

    public function update(Request $request, $id)
    {
        $this->validate($request, [
            'name' => 'required',
            'permission' => 'required',
        ]);

        $role = Role::find($id);
        $role->name = $request->input('name');
        $role->save();
        $role->syncPermissions($request->input('permission'));

        echo (new Response())->success('Registro editado com Sucesso')->action('redirect','/roles')->json();
        // return redirect()->route('roles.index');
    }

    public function edit($id)
    {

        $role = Role::find($id);
        $permission = Permission::get();
        $rolePermissions = DB::table("role_has_permissions")->where("role_has_permissions.role_id",$id)
            ->pluck('role_has_permissions.permission_id','role_has_permissions.permission_id')
            ->all();

        return view('adm.roles.edit',compact('role','permission','rolePermissions'));

        // return view('adm.roles.edit', compact('role', 'permissions'));
    }


         /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */

    public function store(Request $request)
    {

        $this->validate($request, [
            'name' => 'required|unique:roles,name',
            'permission' => 'required',
        ]);

        $role = Role::create(['name' => strtoupper($request->input('name'))]);

        $role->syncPermissions($request->input('permission'));

        return view('adm.roles.createRoles',compact(['permission']))->with('success','Role created successfully');
        // $permission = Permission::get();
        // return view('adm.roles.createRoles',compact(['permission']));

        // $role = Role::create(['name' => $request->name]);

        // return redirect()->route('roles.index');
    }

    public function create(Request $request)
    {
        $permission = Permission::get();
        return view('adm.roles.create',compact('permission'));
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        $role = Role::find($id);
        $rolePermissions = Permission::join("role_has_permissions","role_has_permissions.permission_id","=","permissions.id")
        ->where("role_has_permissions.role_id",$id)->get();

        return view('adm.roles.show',compact('role','rolePermissions'));
    }

      /**
    * Remove the specified resource from storage.
    *
    * @param  int  $id
    * @return \Illuminate\Http\Response
    */
   public function destroy($id)
   {
    dd("asd");
       DB::table("roles")->where('id',$id)->delete();
       echo (new Response())->success('Registro deletado com Sucesso')->action('redirect','/roles')->json();
   }

}
