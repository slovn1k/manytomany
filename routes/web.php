<?php

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/

use App\User;
use App\Role;
use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return view('welcome');
});

Route::get('/insert_role/{id}/{user_role}', function($id, $user_role) {
    $user = User::findOrFail($id);

    $role = new Role(['role'=>$user_role]);

    if($user->roles()->save($role)){
        return 'Role has been created successfully';
    } else {
        return 'Error while creating a new role!!!';
    }
});

Route::get('/read/{id}', function($id) {
   $user = User::findOrFail($id);

   foreach ($user->roles as $role){
       echo $role.'<br>';

   }

});

Route::get('/update/{id}/{user_role}/{new_role}', function ($id, $user_role, $new_role){
   $user = User::findOrFail($id);

   if($user->has('roles')){
       foreach ($user->roles as $role){
           if($role->role == $user_role){
               $role->role = $new_role;
               if($role->save()){
                   return 'The role has been modified';
               } else {
                   return 'The was an error while updating the role';
               }
           }
       }
   }

});

Route::get('/delete/{id}/{role_id}', function($id, $role_id){
    $user = User::findOrFail($id);

    foreach ($user->roles as $role){
        if($role->whereId($role_id)->delete()){
            return 'Deleting successful';
        } else {
            return 'Error while deleting';
        }
    }

});

//This will attach a role to the user
Route::get('/attach/{id}/{user_role}', function($id, $user_role) {
    $user = User::findOrFail($id);

    if($user->roles()->attach($user_role)){
        return 'Attaching a new role to a new user has been successful';
    } else {
        return 'Error while attaching the role';
    }
});

//This will detach a role from a user
Route::get('/detach/{id}/{user_role}', function($id, $user_role){
   $user = User::findOrFail($id);

   $user->roles()->detach($user_role);
});


//Syncing roles with a certain user will attach this roles to the user and remove others is those one don't exist at this user
Route::get('/sync/{id}', function ($id){
    $user = User::findOrFail($id);

    $user->roles()->sync([8,9]);
});