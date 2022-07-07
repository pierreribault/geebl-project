<?php

namespace App\Http\Controllers;

use App\Data\CompanyData;
use App\Models\Company;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Redirect;
use Inertia\Inertia;

class CompanyController
{
    /**
     * Display a listing of the resource.
     *
     * @return Response
     */
    public function index()
    {
        //return CompanyData::collection(Company::all());
        return Inertia::render('Company', [
            'models' => CompanyData::collection(Company::paginate())
        ]);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return Response
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param CompanyData $data
     * @return Response
     */
    public function store(Request $request)
    {
        $data = CompanyData::from([
            'name' => $request->get('name'),
            'crn' => $request->get('crn'),
            'location' => $request->get('location'),
        ]);

        $company = Company::create($data->all());

        $user = Auth::user();
        $user->is_owner = true;
        $user->save();

        $company->users()->save($user);

        return Inertia::location(route('nova.login'));
    }

    /**
     * Display the specified resource.
     *
     * @param  Company  $company
     * @return Response
     */
    public function show(Company $company)
    {
        return CompanyData::from($company);
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  Company  $company
     * @return Response
     */
    public function edit(Company $company)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  CompanyData $data
     * @param  Company  $company
     * @return Response
     */
    public function update(CompanyData $data, Company $company)
    {
        $company->update($data->all());

        redirect()->back();
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  Company  $company
     * @return Response
     */
    public function destroy(Company $company)
    {
        //
    }
}
