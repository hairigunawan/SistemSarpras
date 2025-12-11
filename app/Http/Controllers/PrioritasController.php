<?php

namespace App\Http\Controllers;

use App\Models\Prioritas;

class PrioritasController extends Controller
{
    public function ruangan()
    {
        $p = new Prioritas();
        return $p->RuanganPrioritas();
    }

    public function proyektor()
    {
        $p = new Prioritas();
        return $p->ProyektorPrioritas();
    }
}
