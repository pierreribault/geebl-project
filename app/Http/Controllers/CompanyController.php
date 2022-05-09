<?php

namespace App\Http\Controllers;

use App\Data\CompanyData;
use App\Models\Company;
use Illuminate\Http\Response;
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
        return Inertia::render(
            'Company',
            [
                'title' => 'Homepage',
            ]
        );
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
    public function store(CompanyData $data)
    {
        Company::create($data->all());

        return redirect()->back()->with('success', 'Company created successfully');
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
