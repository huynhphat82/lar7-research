<?php

namespace App\Http\Controllers;

use App\Services\ExporterService;
use App\User;
use Illuminate\Http\Request;

class TestController extends Controller
{
    private $exporterService;

    public function __construct(ExporterService $exporterService)
    {
        $this->exporterService = $exporterService;
    }

    public function __invoke($page)
    {
        $metaTitle = __('titles_'.$page);
        if ($metaTitle == 'titles_'.$page) {
            $metaTitle = null;
        }
        //var_dump($page, request()->segments(1));
        return view('pages.'.$page, ['metaTitle' => $metaTitle]);
    }

    public function update(User $user, Request $request)
    {
        $user->update($request->all());
        return redirect()->route('admin.users.index');
    }

    public function download()
    {
        return $this->exporterService->sales();
    }
}
