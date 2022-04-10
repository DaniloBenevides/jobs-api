<?php

namespace App\Http\Controllers;

use App\Domain\Jobs\Actions\List\ListJobs;
use App\Http\Requests\StoreJobRequest;
use App\Models\Job;
use App\Models\User;
use App\Notifications\JobCreated;
use Illuminate\Http\Request;

class JobController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request, ListJobs $listJobs)
    {
        $user_id = $request->user()->id;
        return $listJobs($user_id);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \App\Http\Requests\StoreJobRequest  $request
     * @return \Illuminate\Http\Response
     */
    public function store(StoreJobRequest $request)
    {
        $data = $request->validated();
        $user = $request->user();

        $data['user_id'] = $user->id;
        $job = Job::create($data);

        if($user->isManager()) {
            return $job;
        }

        $managers = User::whereHas('roles', fn($query) => $query->where('id', 2))->get();

        foreach($managers as $manager) {
            $manager->notify(new JobCreated($job->id));
        }

        return $job;
    }
}

