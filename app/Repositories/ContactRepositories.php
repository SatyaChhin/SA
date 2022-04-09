<?php 
namespace App\Repositories;

use App\Models\Contact;
use Dotenv\Repository\RepositoryInterface;

class ContactRepositories 
{
    // Get all instances of model
    public function model() {

        return Contact::class;
    }

    // create a new record in the database
    public function createRepo($entry, $request)
    {
        Contact::create([
            'name_teacher' =>$entry->name,
            'phone' => $request->phone,
            'email' =>$request->email,
            'facebook' => $request->facebook,
            'whatApp' => $request->whatApp,
            'messenger' => $request->messenger,
            'website' => $request->Website,
            'twitter' =>$request->Twitter,
            'telegram' =>$request->telegram,
        ]);
    }

   
}