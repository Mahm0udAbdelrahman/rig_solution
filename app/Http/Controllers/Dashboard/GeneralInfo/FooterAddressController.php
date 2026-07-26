<?php

namespace App\Http\Controllers\Dashboard\GeneralInfo;

use App\Http\Controllers\Controller;
use App\Models\GeneralInfo\FooterAddress;
use Illuminate\Http\Request;

class FooterAddressController extends Controller
{
    /**
     * Show the form for editing the footer address.
     *
     * @return \Illuminate\Http\Response
     */
    public function edit()
    {
        $footerAddress = FooterAddress::getFooterAddress();

        return view('layouts.organization.footerAddress.edit', [
            'model' => $footerAddress,
            'page_name' => 'Edit Footer Address'
        ]);
    }

    /**
     * Update the footer address in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request)
    {
        $data = $request->validate([
            'address_line_1' => 'nullable|string|max:255',
            'address_line_2' => 'nullable|string|max:255',
            'phone'          => 'nullable|string|max:255',
            'mobile'         => 'nullable|string|max:255',
            'email'          => 'nullable|string|max:255',
            'website'        => 'nullable|string|max:255',
        ]);

        $footerAddress = FooterAddress::first();

        if ($footerAddress) {
            $footerAddress->update($data);
        } else {
            FooterAddress::create($data);
        }

        return redirect()->route('footer-address.edit')->with('success', 'Footer Address updated successfully!');
    }
}
