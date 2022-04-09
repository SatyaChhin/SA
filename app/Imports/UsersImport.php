<?php

namespace App\Imports;

use App\Models\Teacher;
use Illuminate\Support\Collection;
use Maatwebsite\Excel\Concerns\ToCollection;

class UsersImport implements ToCollection
{
    public function collection(Collection $rows)
    {
    
        foreach ($rows as $key => $row) 
        {
            if($key != 0){
                Teacher::create([
                    'code' => $row[0],
                    'name' => $row[1],
                    'gender'=>$row[2],
                    'phone' =>$row[3],
                    'address' => $row[4],
                    'created_by' => backpack_user()->id
                ]);
            }
        }
    }
}
