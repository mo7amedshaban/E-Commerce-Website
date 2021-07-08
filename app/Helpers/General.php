<?php
use Illuminate\Support\Facades\Config;

# not forget composer.json  look down of this page

function get_languages(){

    return \App\Models\Language::active() -> Selection() -> get();
}

function get_default_lang(){
  return   Config::get('app.locale');
}


function uploadImage($folder, $image)
{
    $image->store('/', $folder);
    $filename = $image->hashName();
    $path = 'images/' . $folder . '/' . $filename;
    return $path;
}












# not forget composer.json   because this functions allawed to call any page when autoload this programm
# add file in  composer.json     general.php
/*
"autoload": {
        "psr-4": {
            "App\\": "app/"
        },
        "classmap": [
            "database/seeds",
            "database/factories"
        ],"files" : [
            "App/Helpers/General.php"
        ] */
