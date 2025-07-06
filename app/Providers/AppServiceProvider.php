<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use Inertia\Inertia;
use Illuminate\Support\Facades\File;




class AppServiceProvider extends ServiceProvider
{
    public function register()
    {
        //
    }

public function boot()
{
    Inertia::version(function () {
        // Caminho possível do arquivo do Vite (ajuste se precisar)
        $viteManifest = public_path('build/manifest.json');
        // Caminho possível do arquivo do Mix
        $mixManifest = public_path('mix-manifest.json');

        if (File::exists($viteManifest)) {
            return md5_file($viteManifest);
        } elseif (File::exists($mixManifest)) {
            return md5_file($mixManifest);
        }

        // Caso nenhum arquivo exista, retorne algo fixo ou baseado em timestamp para invalidar cache em cada request (não recomendado para produção)
        return (string) time();
    });
}
}
